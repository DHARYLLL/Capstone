<?php

namespace App\Jobs;

use App\Models\ChatFeedback;
use App\Models\ChatMessage;
use App\Models\KnowledgeDeficit;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessChatFeedbackJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 3;

    public function __construct(
        public int $chatMessageId,
        public string $rating,
        public ?string $comment = null,
    ) {
        $this->onQueue('telemetry');
    }

    public function handle(): void
    {
        $botMessage = ChatMessage::query()
            ->with('chatSession.businessUnit')
            ->where('sender_type', 'bot')
            ->find($this->chatMessageId);

        if (! $botMessage) {
            return;
        }

        $feedback = ChatFeedback::updateOrCreate(
            ['chat_message_id' => $botMessage->id],
            [
                'rating' => $this->rating,
                'comment' => $this->comment,
            ],
        );

        if ($this->rating !== 'dislike') {
            return;
        }

        $customerMessage = ChatMessage::query()
            ->where('chat_session_id', $botMessage->chat_session_id)
            ->where('sender_type', 'customer')
            ->where('id', '<', $botMessage->id)
            ->latest('id')
            ->first();

        $session = $botMessage->chatSession;
        $details = "Customer marked the bot response as unhelpful.\nBot response:\n{$botMessage->message_text}";

        if ($this->comment !== null && trim($this->comment) !== '') {
            $details = "Customer comment:\n{$this->comment}\n\n{$details}";
        }

        KnowledgeDeficit::updateOrCreate(
            ['chat_message_id' => $botMessage->id],
            [
                'chat_feedback_id' => $feedback->id,
                'company_id' => $session?->company_id,
                'business_unit_id' => $session?->business_unit_id,
                'chat_session_id' => $session?->id,
                'customer_query' => $customerMessage?->message_text ?? 'Customer query unavailable.',
                'missing_context_details' => $details,
                'status' => 'open',
                'flagged_at' => now(),
            ],
        );
    }
}
