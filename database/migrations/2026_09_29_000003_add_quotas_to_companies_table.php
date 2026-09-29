<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table): void {
            $table->unsignedBigInteger('storage_quota_bytes')->default(5368709120)->after('api_key');
            $table->unsignedInteger('vector_chunk_quota')->default(10000)->after('storage_quota_bytes');
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table): void {
            $table->dropColumn(['storage_quota_bytes', 'vector_chunk_quota']);
        });
    }
};