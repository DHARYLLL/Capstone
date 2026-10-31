<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessChatFeedbackJob;
use App\Models\ChatMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ChatFeedbackController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'chat_message_id' => [
                'required',
                'integer',
                Rule::exists('chat_messages', 'id')->where(fn ($query) => $query->where('sender_type', 'bot')),
            ],
            'session_token' => ['required', 'string', 'size:64', 'regex:/^[A-Za-z0-9]+$/'],
            'rating' => ['required', 'string', 'in:like,dislike'],
            'comment' => ['nullable', 'string', 'max:5000'],
        ]);

        abort_unless(
            ChatMessage::query()
                ->whereKey($validated['chat_message_id'])
                ->where('sender_type', 'bot')
                ->whereHas('chatSession', fn ($query) => $query->where('token', $validated['session_token']))
                ->exists(),
            404,
        );

        ProcessChatFeedbackJob::dispatch(
            chatMessageId: (int) $validated['chat_message_id'],
            rating: $validated['rating'],
            comment: $validated['comment'] ?? null,
        );

        return response()->json([
            'success' => true,
            'message' => 'Feedback queued for processing',
        ], 202);
    }
}