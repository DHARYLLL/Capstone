<?php

namespace App\Jobs;

use App\Exceptions\NonRetryableIngestionException;
use App\Models\BusinessKnowledge;
use App\Models\BusinessUnit;
use App\Models\ActivityLog;
use App\Models\StagedKnowledgeDocument;
use App\Services\TextChunker;
use App\Services\GeminiEmbeddingService;
use App\Services\OrganizationQuotaService;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Smalot\PdfParser\Parser;

class ProcessPdfIngestion implements ShouldQueue
{
    use Dispatchable;
    use Queueable;
    use InteractsWithQueue;

    public int $timeout = 300;

    public int $tries = 3;

    public function backoff(): array
    {
        return [10, 30, 60];
    }

    /**
     * Create a new job instance.
     */
    public function __construct(
        public int $businessUnitId,
        public string $storageDisk,
        public string $storedPath,
        public string $ingestionMode = 'append',
        public ?int $stagedDocumentId = null,
        public ?string $editedContent = null,
        public ?string $mimeType = null,
        public ?string $fileName = null,
        public string $division = 'DARIV',
        public ?int $userId = null,
        public bool $preserveForensics = false,
    ) {
        $this->onQueue('imports');
    }

    /**
     * Execute the job.
     */
    public function handle(
        OrganizationQuotaService $quotaService,
        GeminiEmbeddingService $embeddingService,
    ): void
    {
        set_time_limit(300);
        ini_set('memory_limit', '512M');

        $stagedDocument = $this->stagedDocumentId
            ? StagedKnowledgeDocument::query()->find($this->stagedDocumentId)
            : null;
        $businessUnit = BusinessUnit::query()->find($this->businessUnitId);

        if (! $businessUnit) {
            Log::warning('Skipping PDF ingestion because the business unit no longer exists.', [
                'business_unit_id' => $this->businessUnitId,
                'stored_path' => $this->storedPath,
            ]);
            $this->markFailed('Business unit no longer exists.');

            return;
        }

        try {
            $quotaService->assertCanAcceptUpload(
                (int) $businessUnit->company_id,
                $stagedDocument ? 0 : (int) Storage::disk($this->storageDisk)->size($this->storedPath),
            );

            $extractedText = $this->editedContent;

            if ($extractedText === null || trim($extractedText) === '') {
                if (str_contains((string) $this->mimeType, 'csv')) {
                    $extractedText = Storage::disk($this->storageDisk)->get($this->storedPath);
                } else {
                    $pdfPath = Storage::disk($this->storageDisk)->path($this->storedPath);
                    $parser = new Parser;
                    $pdf = $parser->parseFile($pdfPath);
                    $extractedText = (string) $pdf->getText();
                }
            }

            $extractedText = TextChunker::cleanExtractedText((string) $extractedText);

            if ($extractedText === '') {
                $this->markFailed('No readable text remained after extraction and cleaning.');

                return;
            }

            $chunks = TextChunker::chunk($extractedText);
            $chunks = array_values(array_filter($chunks, static fn (string $chunk): bool => mb_strlen(trim($chunk)) >= 50));

            if ($chunks === []) {
                $this->markFailed('No chunks met the minimum embedding length.');

                return;
            }

            $quotaService->assertCanInsertChunks(
                (int) $businessUnit->company_id,
                count($chunks),
                $businessUnit->id,
                $this->ingestionMode === 'overwrite',
            );

            $rows = [];

            foreach (array_chunk($chunks, 75) as $chunkBatch) {
                $embeddings = $this->batchEmbedChunks($chunkBatch, $embeddingService);

                foreach ($chunkBatch as $index => $chunk) {
                    $embedding = $embeddings[$index] ?? null;

                    if ($embedding === null) {
                        throw new NonRetryableIngestionException(
                            "Gemini returned no embedding for chunk {$index}.",
                        );
                    }

                    $rows[] = [
                        'company_id' => $businessUnit->company_id,
                        'business_unit_id' => $businessUnit->id,
                        'content' => $chunk,
                        'embedding' => $embedding,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            if ($rows === []) {
                throw new NonRetryableIngestionException('No embeddings were generated.');
            }

            // Replace only when explicitly requested; append preserves existing knowledge.
            DB::transaction(function () use ($businessUnit, $rows): void {
                if ($this->ingestionMode === 'overwrite') {
                    BusinessKnowledge::query()
                        ->where('company_id', $businessUnit->company_id)
                        ->where('business_unit_id', $businessUnit->id)
                        ->delete();
                }

                BusinessKnowledge::query()->insert($rows);
            });

            Storage::disk($this->storageDisk)->delete($this->storedPath);
            $stagedDocument?->update(['status' => 'indexed']);
            ActivityLog::record($this->userId, 'Indexed', 'Done', $this->fileName, $this->division);
        } catch (NonRetryableIngestionException|ValidationException $exception) {
            $this->markFailed($exception->getMessage(), $exception);
        }
    }

    public function failed(\Throwable $exception): void
    {
        $this->markFailed('Queue retries exhausted.', $exception);
    }

    private function markFailed(string $reason, ?\Throwable $exception = null): void
    {
        StagedKnowledgeDocument::query()
            ->whereKey($this->stagedDocumentId)
            ->update(['status' => 'failed']);

        if (! $this->preserveForensics) {
            Storage::disk($this->storageDisk)->delete($this->storedPath);
        }

        Log::error('PDF ingestion failed.', [
            'reason' => $reason,
            'exception' => $exception?->getMessage(),
            'trace' => $exception?->getTraceAsString(),
            'business_unit_id' => $this->businessUnitId,
            'staged_document_id' => $this->stagedDocumentId,
            'stored_path' => $this->storedPath,
            'file_name' => $this->fileName,
            'attempts' => method_exists($this, 'attempts') ? $this->attempts() : null,
        ]);
        ActivityLog::record($this->userId, 'Upload failed', 'Failed', $this->fileName, $this->division);
    }

    /**
     * @param  array<int, string>  $chunks
     * @return array<int, string>
     */
    private function batchEmbedChunks(array $chunks, GeminiEmbeddingService $embeddingService): array
    {
        return $embeddingService->embedDocuments($chunks, [
            'business_unit_id' => $this->businessUnitId,
            'stored_path' => $this->storedPath,
            'source' => 'pdf',
        ]);
    }
}
