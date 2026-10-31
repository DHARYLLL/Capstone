<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const INDEX_NAME = 'business_knowledges_embedding_hnsw_idx';

    public function up(): void
    {
        if (DB::getDriverName() !== 'pgsql') {
            return;
        }

        DB::statement('CREATE INDEX IF NOT EXISTS '.$this->quoted(self::INDEX_NAME)
            .' ON business_knowledges USING hnsw (embedding extensions.vector_cosine_ops)');
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('DROP INDEX IF EXISTS '.$this->quoted(self::INDEX_NAME));
        }
    }

    private function quoted(string $identifier): string
    {
        return '"'.str_replace('"', '""', $identifier).'"';
    }
};
