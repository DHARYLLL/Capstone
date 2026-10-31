<?php

namespace App\Services;

use App\Models\BusinessKnowledge;
use App\Models\Company;
use App\Models\StagedKnowledgeDocument;
use Illuminate\Validation\ValidationException;
use Closure;
use Illuminate\Support\Facades\DB;

class OrganizationQuotaService
{
    public function assertCanAcceptUpload(int $companyId, int $newFileSize): void
    {
        $this->checkUploadQuota($companyId, $newFileSize);
    }

    /**
     * Run a storage-mutating operation while holding the company quota lock.
     */
    public function withinUploadQuota(int $companyId, int $newFileSize, Closure $operation): mixed
    {
        return DB::transaction(function () use ($companyId, $newFileSize, $operation): mixed {
            $this->checkUploadQuota($companyId, $newFileSize, true);

            return $operation();
        });
    }

    /**
     * Run a vector-mutating operation while holding the company quota lock.
     */
    public function withinVectorQuota(
        int $companyId,
        int $newChunkCount,
        ?int $businessUnitId,
        bool $overwrite,
        Closure $operation,
    ): mixed {
        return DB::transaction(function () use (
            $companyId,
            $newChunkCount,
            $businessUnitId,
            $overwrite,
            $operation,
        ): mixed {
            $this->checkChunkQuota($companyId, $newChunkCount, $businessUnitId, $overwrite, true);

            return $operation();
        });
    }

    private function checkUploadQuota(int $companyId, int $newFileSize, bool $locked = false): void
    {
        $companyQuery = Company::query()->whereKey($companyId);
        $company = ($locked ? $companyQuery->lockForUpdate() : $companyQuery)->firstOrFail();
        $usage = $this->usage($companyId);

        if ($usage['storage_bytes'] + $newFileSize > (int) $company->storage_quota_bytes) {
            throw ValidationException::withMessages([
                'file' => 'Organization storage quota has been reached.',
            ]);
        }

        if ($usage['vector_chunks'] >= (int) $company->vector_chunk_quota) {
            throw ValidationException::withMessages([
                'file' => 'Organization vector chunk quota has been reached.',
            ]);
        }
    }

    public function assertCanInsertChunks(
        int $companyId,
        int $newChunkCount,
        ?int $businessUnitId = null,
        bool $overwrite = false,
    ): void {
        $this->checkChunkQuota($companyId, $newChunkCount, $businessUnitId, $overwrite);
    }

    private function checkChunkQuota(
        int $companyId,
        int $newChunkCount,
        ?int $businessUnitId,
        bool $overwrite,
        bool $locked = false,
    ): void {
        $companyQuery = Company::query()->whereKey($companyId);
        $company = ($locked ? $companyQuery->lockForUpdate() : $companyQuery)->firstOrFail();
        $usage = $this->usage($companyId);
        $existingChunksBeingReplaced = 0;

        if ($overwrite && $businessUnitId !== null) {
            $existingChunksBeingReplaced = BusinessKnowledge::query()
                ->where('company_id', $companyId)
                ->where('business_unit_id', $businessUnitId)
                ->count();
        }

        $projectedChunks = $usage['vector_chunks'] - $existingChunksBeingReplaced + $newChunkCount;

        if ($projectedChunks > (int) $company->vector_chunk_quota) {
            throw ValidationException::withMessages([
                'file' => 'Organization vector chunk quota has been reached.',
            ]);
        }
    }

    /**
     * @return array{storage_bytes: int, vector_chunks: int}
     */
    public function usage(int $companyId): array
    {
        $storageBytes = StagedKnowledgeDocument::query()
            ->whereIn('status', ['staged', 'queued', 'processing'])
            ->whereHas('businessUnit', fn ($query) => $query->where('company_id', $companyId))
            ->sum('file_size');

        $vectorChunks = BusinessKnowledge::query()
            ->where('company_id', $companyId)
            ->count();

        return [
            'storage_bytes' => (int) $storageBytes,
            'vector_chunks' => (int) $vectorChunks,
        ];
    }
}