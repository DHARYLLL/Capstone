<?php

namespace App\Services;

use App\Models\BusinessUnit;
use InvalidArgumentException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\LazyCollection;
use League\Csv\Reader;

class CsvIngestionService
{
    private const EMBEDDING_BATCH_SIZE = 75;

    private const MINIMUM_ROW_LENGTH = 1;

    public function __construct(
        private readonly GeminiEmbeddingService $embeddingService,
    ) {
    }

    /**
     * Parse a CSV file, format each row for embeddings, and prepare Supabase-ready payloads.
     *
     * @return array<int, array{
     *     company_id: int,
     *     business_unit_id: int,
     *     document_id: string,
     *     content: string,
     *     embedding: string,
     *     created_at: Carbon,
     *     updated_at: Carbon,
     * }>
     */
    public function ingest(
        int $companyId,
        int $businessUnitId,
        string $documentId,
        string $storageDisk,
        string $storedPath,
    ): array {
        $businessUnit = BusinessUnit::query()->findOrFail($businessUnitId);

        if ((int) $businessUnit->company_id !== $companyId) {
            throw new InvalidArgumentException('The business unit does not belong to the specified company.');
        }

        $companyId = (int) $businessUnit->company_id;
        $csvPath = Storage::disk($storageDisk)->path($storedPath);
        $reader = Reader::createFromPath($csvPath, 'r');
        $reader->setHeaderOffset(0);

        $formattedRows = LazyCollection::make(function () use ($reader): \Generator {
            foreach ($reader->getRecords() as $record) {
                if (! is_array($record)) {
                    continue;
                }

                yield $record;
            }
        })
            ->map(fn (array $record): string => $this->formatRowForEmbedding($record))
            ->filter(fn (string $row): bool => mb_strlen(trim($row)) >= self::MINIMUM_ROW_LENGTH)
            ->values();

        if ($formattedRows->isEmpty()) {
            Log::warning('CSV ingestion produced no rows large enough for embedding.', [
                'company_id' => $companyId,
                'business_unit_id' => $businessUnitId,
                'document_id' => $documentId,
                'stored_path' => $storedPath,
            ]);

            return [];
        }

        $rows = [];

        $formattedRows
            ->chunk(self::EMBEDDING_BATCH_SIZE)
            ->each(function (LazyCollection $chunk) use (&$rows, $companyId, $businessUnitId, $documentId): void {
                $chunkRows = $chunk->values()->all();
                $embeddings = $this->batchEmbedChunks($chunkRows);

                foreach ($chunkRows as $index => $content) {
                    $embedding = $embeddings[$index] ?? null;

                    if ($embedding === null) {
                        continue;
                    }

                    $rows[] = [
                        'company_id' => $companyId,
                        'business_unit_id' => $businessUnitId,
                        'document_id' => $documentId,
                        'content' => $content,
                        'embedding' => $embedding,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            });

        if ($rows === []) {
            Log::warning('CSV ingestion completed but no embeddings were generated.', [
                'company_id' => $companyId,
                'business_unit_id' => $businessUnitId,
                'document_id' => $documentId,
                'stored_path' => $storedPath,
            ]);

            return [];
        }

        return $rows;
    }

    /**
     * Format a CSV row into a single string for chunking and embedding.
     *
     * @param  array<string, mixed>  $record
     */
    public function formatRowForEmbedding(array $record): string
    {
        $questionKey = $this->findHeaderKey($record, 'question');
        $answerKey = $this->findHeaderKey($record, 'answer');

        if ($questionKey !== null && $answerKey !== null) {
            $question = $this->normalizeCellValue($record[$questionKey] ?? '');
            $answer = $this->normalizeCellValue($record[$answerKey] ?? '');

            return trim(sprintf('Q: %s A: %s', $question, $answer));
        }

        $segments = [];

        foreach ($record as $header => $value) {
            $normalizedHeader = $this->normalizeHeader((string) $header);
            $normalizedValue = $this->normalizeCellValue($value);

            if ($normalizedHeader === '' || $normalizedValue === '') {
                continue;
            }

            $segments[] = $normalizedHeader.': '.$normalizedValue;
        }

        return trim(implode(' | ', $segments));
    }

    /**
     * Batch Gemini embeddings so we make far fewer API calls for large CSV files.
     *
     * @param  array<int, string>  $chunks
     * @return array<int, string>
     */
    private function batchEmbedChunks(array $chunks): array
    {
        return $this->embeddingService->embedDocuments($chunks, [
            'source' => 'csv',
        ]);
    }

    private function findHeaderKey(array $record, string $expectedHeader): ?string
    {
        foreach (array_keys($record) as $header) {
            if (mb_strtolower(trim($this->normalizeHeader((string) $header))) === $expectedHeader) {
                return (string) $header;
            }
        }

        return null;
    }

    private function normalizeHeader(string $header): string
    {
        $header = preg_replace('/^\xEF\xBB\xBF/u', '', $header) ?? $header;

        return trim($header);
    }

    private function normalizeCellValue(mixed $value): string
    {
        if (is_array($value)) {
            $value = implode(', ', array_map(static fn (mixed $item): string => trim((string) $item), $value));
        }

        return trim((string) preg_replace('/\s+/u', ' ', (string) $value));
    }

}
