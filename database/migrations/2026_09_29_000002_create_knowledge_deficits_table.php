<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('knowledge_deficits', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('chat_feedback_id')
                ->unique()
                ->constrained('bot_feedbacks')
                ->cascadeOnDelete();
            $table->foreignId('company_id')
                ->nullable()
                ->constrained('companies')
                ->nullOnDelete();
            $table->foreignId('business_unit_id')
                ->nullable()
                ->constrained('business_units')
                ->nullOnDelete();
            $table->foreignId('chat_session_id')
                ->nullable()
                ->constrained('chat_sessions')
                ->nullOnDelete();
            $table->foreignId('chat_message_id')
                ->constrained('chat_messages')
                ->cascadeOnDelete();
            $table->text('customer_query');
            $table->text('missing_context_details')->nullable();
            $table->string('status', 30)->default('open')->index();
            $table->timestamp('flagged_at');
            $table->timestamps();

            $table->index(['company_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('knowledge_deficits');
    }
};
