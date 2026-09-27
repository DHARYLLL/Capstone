<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('staged_knowledge_documents', function (Blueprint $table): void {
            $table->string('ingestion_mode', 20)->default('append')->after('edited_content');
        });
    }

    public function down(): void
    {
        Schema::table('staged_knowledge_documents', function (Blueprint $table): void {
            $table->dropColumn('ingestion_mode');
        });
    }
};