<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('business_knowledges', function (Blueprint $table): void {
            $table->foreignId('company_id')
                ->nullable()
                ->after('id')
                ->constrained('companies')
                ->cascadeOnDelete();
        });

        DB::table('business_knowledges')
            ->whereNull('company_id')
            ->orderBy('id')
            ->chunkById(1000, function ($knowledgeRows): void {
                foreach ($knowledgeRows as $knowledge) {
                    $companyId = DB::table('business_units')
                        ->where('id', $knowledge->business_unit_id)
                        ->value('company_id');

                    DB::table('business_knowledges')
                        ->where('id', $knowledge->id)
                        ->update(['company_id' => $companyId]);
                }
            });

        Schema::table('business_knowledges', function (Blueprint $table): void {
            $table->index(['company_id', 'business_unit_id']);
        });
    }

    public function down(): void
    {
        Schema::table('business_knowledges', function (Blueprint $table): void {
            $table->dropIndex('business_knowledges_company_id_business_unit_id_index');
            $table->dropConstrainedForeignId('company_id');
        });
    }
};