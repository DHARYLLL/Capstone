<?php

namespace App\Jobs;

use App\Exceptions\NonRetryableIngestionException;
use App\Models\BusinessKnowledge;
use App\Models\BusinessUnit;
use App\Models\ActivityLog;
use App\Models\StagedKnowledgeDocument;
use App\Services\TextChunker;
use App\Services\EmbeddingService;
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
use Generator;

class ProcessPdfIngestion implements ShouldQueue
{
    private const EMBEDDING_BATCH_SIZE = 75;

    private const MINIMUM_CHUNK_LENGTH = 50;

    private int $extractedTextLength = 0;

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
        EmbeddingService $embeddingService,
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

            $totalPages = 0;
            $segments = $this->extractTextSegments($totalPages);
            $processedChunks = 0;
            $hasInsertedRows = false;
            $batch = [];

            foreach ($segments as $segmentData) {
                $page = $segmentData['page'];
                $segment = $segmentData['text'];
                $this->assertTextLimit($segment, $page);
                $this->updateProgress($page + 1, $totalPages, $processedChunks);

                foreach (TextChunker::chunkSegments([$segment]) as $chunk) {
                    if (mb_strlen(trim($chunk)) < self::MINIMUM_CHUNK_LENGTH) {
                        continue;
                    }

                    $batch[] = $chunk;

                    if (count($batch) < self::EMBEDDING_BATCH_SIZE) {
                        continue;
                    }

                    $processedChunks += $this->insertChunkBatch(
                        $batch,
                        $businessUnit,
                        $quotaService,
                        $embeddingService,
                        ! $hasInsertedRows,
                    );
                    $hasInsertedRows = true;
                    $batch = [];
                    $this->updateProgress($page + 1, $totalPages, $processedChunks);
                }
            }

            if ($batch !== []) {
                $processedChunks += $this->insertChunkBatch(
                    $batch,
                    $businessUnit,
                    $quotaService,
                    $embeddingService,
                    ! $hasInsertedRows,
                );
            }

            if ($processedChunks === 0) {
                throw new NonRetryableIngestionException('No embeddings were generated.');
            }

            Storage::disk($this->storageDisk)->delete($this->storedPath);
            $this->updateProgress($totalPages, $totalPages, $processedChunks, 'indexed');
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
     * @return Generator<int, array{page: int, text: string}>
     */
    private function extractTextSegments(int &$totalPages): Generator
    {
        $this->extractedTextLength = 0;

        if ($this->editedContent !== null && trim($this->editedContent) !== '') {
            $totalPages = 1;
            foreach ($this->boundedSegments($this->editedContent) as $segment) {
                yield ['page' => 0, 'text' => $segment];
            }

            return;
        }

        if (str_contains((string) $this->mimeType, 'csv')) {
            $totalPages = 1;
            foreach ($this->boundedSegments(Storage::disk($this->storageDisk)->get($this->storedPath)) as $segment) {
                yield ['page' => 0, 'text' => $segment];
            }

            return;
        }

        $pdf = (new Parser)->parseFile(Storage::disk($this->storageDisk)->path($this->storedPath));
        $pages = $pdf->getPages();
        $totalPages = count($pages);

        foreach ($pages as $pageNumber => $page) {
            foreach ($this->boundedSegments((string) $page->getText()) as $segment) {
                yield ['page' => $pageNumber, 'text' => $segment];
            }
        }
    }

    /**
     * @return Generator<int, string>
     */
    private function boundedSegments(string $text): Generator
    {
        $segmentSize = (int) config('ingestion.segment_size', 100000);
        $length = mb_strlen($text);

        for ($offset = 0; $offset < $length; $offset += $segmentSize) {
            yield mb_substr($text, $offset, $segmentSize);
        }
    }

    private function assertTextLimit(string $segment, int $page): void
    {
        $this->extractedTextLength += mb_strlen($segment);

        if ($this->extractedTextLength > (int) config('ingestion.max_extracted_text_length', 5000000)) {
            throw new NonRetryableIngestionException(
                "Extracted text exceeded the configured maximum at page {$page}.",
            );
        }
    }

    private function updateProgress(
        int $currentPage,
        int $totalPages,
        int $processedChunks,
        ?string $status = null,
    ): void {
        StagedKnowledgeDocument::query()
            ->whereKey($this->stagedDocumentId)
            ->update(array_filter([
                'current_page' => $currentPage,
                'total_pages' => $totalPages,
                'processed_chunks' => $processedChunks,
                'status' => $status,
            ], static fn (mixed $value): bool => $value !== null));

        Log::info('PDF ingestion progress.', [
            'business_unit_id' => $this->businessUnitId,
            'staged_document_id' => $this->stagedDocumentId,
            'current_page' => $currentPage,
            'total_pages' => $totalPages,
            'processed_chunks' => $processedChunks,
        ]);
    }

    private function insertChunkBatch(
        array $chunks,
        BusinessUnit $businessUnit,
        OrganizationQuotaService $quotaService,
        EmbeddingService $embeddingService,
        bool $isFirstBatch,
    ): int {
        $quotaService->assertCanInsertChunks(
            (int) $businessUnit->company_id,
            count($chunks),
            $businessUnit->id,
            $isFirstBatch && $this->ingestionMode === 'overwrite',
        );

        $embeddings = $this->batchEmbedChunks($chunks, $embeddingService);
        $rows = [];

        foreach ($chunks as $index => $chunk) {
            $rows[] = [
                'company_id' => $businessUnit->company_id,
                'business_unit_id' => $businessUnit->id,
                'content' => $chunk,
                'embedding' => $embeddings[$index],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::transaction(function () use ($businessUnit, $rows, $isFirstBatch): void {
            if ($isFirstBatch && $this->ingestionMode === 'overwrite') {
                BusinessKnowledge::query()
                    ->where('company_id', $businessUnit->company_id)
                    ->where('business_unit_id', $businessUnit->id)
                    ->delete();
            }

            BusinessKnowledge::query()->insert($rows);
        });

        return count($rows);
    }

    /**
     * @param  array<int, string>  $chunks
     * @return array<int, string>
     */
    private function batchEmbedChunks(array $chunks, EmbeddingService $embeddingService): array
    {
        return $embeddingService->embedDocuments($chunks, [
            'business_unit_id' => $this->businessUnitId,
            'stored_path' => $this->storedPath,
            'source' => 'pdf',
        ]);
    }
}
