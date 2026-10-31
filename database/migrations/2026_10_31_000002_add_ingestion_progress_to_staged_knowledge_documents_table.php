<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('staged_knowledge_documents', function (Blueprint $table): void {
            $table->unsignedInteger('current_page')->nullable()->after('status');
            $table->unsignedInteger('total_pages')->nullable()->after('current_page');
            $table->unsignedInteger('processed_chunks')->default(0)->after('total_pages');
        });
    }

    public function down(): void
    {
        Schema::table('staged_knowledge_documents', function (Blueprint $table): void {
            $table->dropColumn(['current_page', 'total_pages', 'processed_chunks']);
        });
    }
};
