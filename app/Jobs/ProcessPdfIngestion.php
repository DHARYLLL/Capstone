<?php

namespace App\Jobs;

use App\Models\BusinessKnowledge;
use App\Models\BusinessUnit;
use App\Services\TextChunker;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser;

class ProcessPdfIngestion implements ShouldQueue
{
    use Dispatchable;
    use Queueable;

    public int $timeout = 300;

    public int $tries = 3;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public int $businessUnitId,
        public string $storageDisk,
        public string $storedPath,
    ) {
        $this->onQueue('imports');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $businessUnit = BusinessUnit::query()->find($this->businessUnitId);

        if (! $businessUnit) {
            Log::warning('Skipping PDF ingestion because the business unit no longer exists.', [
                'business_unit_id' => $this->businessUnitId,
                'stored_path' => $this->storedPath,
            ]);

            return;
        }

        $pdfPath = Storage::disk($this->storageDisk)->path($this->storedPath);
        $parser = new Parser;
        $pdf = $parser->parseFile($pdfPath);
        $extractedText = TextChunker::cleanExtractedText((string) $pdf->getText());

        if ($extractedText === '') {
            Log::warning('PDF ingestion produced no readable text after cleaning.', [
                'business_unit_id' => $businessUnit->id,
                'stored_path' => $this->storedPath,
            ]);

            return;
        }

        $chunks = TextChunker::chunk($extractedText);
        $chunks = array_values(array_filter($chunks, static fn (string $chunk): bool => mb_strlen(trim($chunk)) >= 50));

        if ($chunks === []) {
            Log::warning('PDF ingestion produced no chunks large enough for embedding.', [
                'business_unit_id' => $businessUnit->id,
                'stored_path' => $this->storedPath,
            ]);

            return;
        }

        $rows = [];

        foreach (array_chunk($chunks, 75) as $chunkBatch) {
            $embeddings = $this->batchEmbedChunks($chunkBatch);

            foreach ($chunkBatch as $index => $chunk) {
                $embedding = $embeddings[$index] ?? null;

                if ($embedding === null) {
                    continue;
                }

                $rows[] = [
                    'business_unit_id' => $businessUnit->id,
                    'content' => $chunk,
                    'embedding' => $embedding,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        if ($rows === []) {
            Log::warning('PDF ingestion completed but no embeddings were generated.', [
                'business_unit_id' => $businessUnit->id,
                'stored_path' => $this->storedPath,
            ]);

            return;
        }

        // Delete and replace in one transaction so the knowledge base never ends up half-written.
        DB::transaction(function () use ($businessUnit, $rows): void {
            BusinessKnowledge::query()
                ->where('business_unit_id', $businessUnit->id)
                ->delete();

            BusinessKnowledge::query()->insert($rows);
        });

        Storage::disk($this->storageDisk)->delete($this->storedPath);
    }

    /**
     * Batch Gemini embeddings so we make far fewer API calls for large PDFs.
     *
     * @param  array<int, string>  $chunks
     * @return array<int, string>
     */
    // private function batchEmbedChunks(array $chunks): array
    // {
    //     $response = Http::baseUrl($this->geminiBaseUrl())
    //         ->acceptJson()
    //         ->timeout((int) config('gemini.request_timeout', 30))
    //         ->retry(2, 1000)
    //         ->withQueryParameters(['key' => (string) config('gemini.api_key')])
    //         ->post('models/text-embedding-004:batchEmbedContents', [
    //             'requests' => array_map(
    //                 static fn (string $chunk): array => [
    //                     'content' => [
    //                         'parts' => [
    //                             ['text' => $chunk],
    //                         ],
    //                     ],
    //                     'task_type' => 'RETRIEVAL_DOCUMENT',
    //                     'output_dimensionality' => 768,
    //                 ],
    //                 $chunks
    //             ),
    //         ]);

    //     if (! $response->successful()) {
    //         Log::warning('Gemini batch embedding request failed.', [
    //             'business_unit_id' => $this->businessUnitId,
    //             'stored_path' => $this->storedPath,
    //             'status' => $response->status(),
    //             'body' => $response->body(),
    //         ]);

    //         return [];
    //     }

    //     $payload = $response->json();
    //     $embeddingRows = $payload['embeddings'] ?? [];

    //     if (! is_array($embeddingRows) || $embeddingRows === []) {
    //         return [];
    //     }

    //     $vectors = [];

    //     foreach ($embeddingRows as $index => $embeddingRow) {
    //         $values = $embeddingRow['values'] ?? $embeddingRow['embedding']['values'] ?? null;

    //         if (! is_array($values) || $values === []) {
    //             continue;
    //         }

    //         $vectors[$index] = $this->formatVectorLiteral($values);
    //     }

    //     return $vectors;
    // }

// private function batchEmbedChunks(array $chunks): array
// {
//     $baseUrl = $this->geminiBaseUrl(); // e.g. https://generativelanguage.googleapis.com/v1beta
//     $apiKey = (string) config('gemini.api_key');
//     $vectors = [];

//     foreach ($chunks as $index => $chunk) {
//         $response = Http::baseUrl($baseUrl)
//             ->acceptJson()
//             ->timeout(15)
//             ->withQueryParameters(['key' => $apiKey])
//             ->post('models/text-embedding-004:embedContent', [
//                 'model' => 'models/text-embedding-004',
//                 'content' => [
//                     'parts' => [
//                         ['text' => $chunk],
//                     ],
//                 ],
//                 'taskType' => 'RETRIEVAL_DOCUMENT',
//                 'outputDimensionality' => 768,
//             ]);

//         if ($response->successful()) {
//             $values = $response->json('embedding.values');
//             if (is_array($values) && count($values) > 0) {
//                 // Convert array [0.1, 0.2, ...] to PGvector array string "[0.1,0.2,...]"
//                 $vectors[$index] = '[' . implode(',', $values) . ']';
//             }
//         } else {
//             Log::error('Gemini Embed Content Failed', [
//                 'status' => $response->status(),
//                 'body' => $response->body(),
//                 'chunk_index' => $index,
//             ]);
//         }
//     }

//     return $vectors;
// }
private function batchEmbedChunks(array $chunks): array
{
    $baseUrl = $this->geminiBaseUrl(); // e.g. https://generativelanguage.googleapis.com/v1beta
    $apiKey = (string) config('gemini.api_key');
    $vectors = [];

    foreach ($chunks as $index => $chunk) {
        $response = Http::baseUrl($baseUrl)
            ->acceptJson()
            ->timeout(15)
            ->withQueryParameters(['key' => $apiKey])
            ->post('models/gemini-embedding-001:embedContent', [ // 👈 Updated model name
                'model' => 'models/gemini-embedding-001',       // 👈 Updated model name
                'content' => [
                    'parts' => [
                        ['text' => $chunk],
                    ],
                ],
                'taskType' => 'RETRIEVAL_DOCUMENT',
                'outputDimensionality' => 768,
            ]);

        if ($response->successful()) {
            $values = $response->json('embedding.values');
            if (is_array($values) && count($values) > 0) {
                // Convert array [0.1, 0.2, ...] to PGvector string "[0.1,0.2,...]"
                $vectors[$index] = '[' . implode(',', $values) . ']';
            }
        } else {
            Log::error('Gemini Embed Content Failed', [
                'status' => $response->status(),
                'body' => $response->body(),
                'chunk_index' => $index,
            ]);
        }
    }

    return $vectors;
}

    private function geminiBaseUrl(): string
    {
        return rtrim((string) (config('gemini.base_url') ?: 'https://generativelanguage.googleapis.com/v1beta'), '/');
    }

    /**
     * @param  array<int, float|int|string>  $values
     */
    private function formatVectorLiteral(array $values): string
    {
        $normalizedValues = array_map(static fn (float|int|string $value): string => (string) $value, $values);

        return '['.implode(',', $normalizedValues).']';
    }
}
