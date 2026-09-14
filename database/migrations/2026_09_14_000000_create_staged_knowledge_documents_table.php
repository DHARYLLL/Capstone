<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staged_knowledge_documents', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('business_unit_id')->constrained('business_units')->cascadeOnDelete();
            $table->string('branch')->nullable();
            $table->string('original_name');
            $table->string('mime_type', 100);
            $table->unsignedBigInteger('file_size');
            $table->string('stored_path');
            $table->longText('edited_content')->nullable();
            $table->string('status', 30)->default('staged')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staged_knowledge_documents');
    }
};