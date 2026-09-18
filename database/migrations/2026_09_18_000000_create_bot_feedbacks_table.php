<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bot_feedbacks', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('chat_message_id')
                ->constrained('chat_messages')
                ->cascadeOnDelete();
            $table->string('rating', 10);
            $table->text('comment')->nullable();
            $table->timestamps();
            $table->unique('chat_message_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bot_feedbacks');
    }
};