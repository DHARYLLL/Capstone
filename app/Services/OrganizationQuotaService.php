<?php

namespace App\Services;

use App\Models\BusinessKnowledge;
use App\Models\Company;
use App\Models\StagedKnowledgeDocument;
use Illuminate\Validation\ValidationException;

class OrganizationQuotaService
{
    public function assertCanAcceptUpload(int $companyId, int $newFileSize): void
    {
        $company = Company::query()->findOrFail($companyId);
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
        $company = Company::query()->findOrFail($companyId);
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