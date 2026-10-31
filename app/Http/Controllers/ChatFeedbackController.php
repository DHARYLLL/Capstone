<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessChatFeedbackJob;
use App\Models\ChatFeedback;
use App\Models\ChatMessage;
use App\Models\ChatSession;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChatFeedbackController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'chat_message_id' => ['required', 'integer'],
            'session_token' => ['required', 'string', 'size:64', 'regex:/^[A-Za-z0-9]+$/'],
            'rating' => ['required', 'string', 'in:like,dislike'],
            'comment' => ['nullable', 'string', 'max:5000'],
        ]);

        $session = ChatSession::query()
            ->where('token', $validated['session_token'])
            ->firstOrFail();

        $botMessage = ChatMessage::query()
            ->whereKey($validated['chat_message_id'])
            ->where('chat_session_id', $session->id)
            ->where('sender_type', 'bot')
            ->firstOrFail();

        ChatFeedback::query()->updateOrCreate(
            ['chat_message_id' => $botMessage->id],
            [
                'rating' => $validated['rating'],
                'comment' => $validated['comment'] ?? null,
            ],
        );

        ProcessChatFeedbackJob::dispatch(
            chatMessageId: (int) $botMessage->id,
            rating: $validated['rating'],
            comment: $validated['comment'] ?? null,
        );

        return response()->json([
            'success' => true,
            'message' => 'Feedback saved.',
            'feedback' => [
                'rating' => $validated['rating'],
                'comment' => $validated['comment'] ?? null,
            ],
        ]);
    }
}