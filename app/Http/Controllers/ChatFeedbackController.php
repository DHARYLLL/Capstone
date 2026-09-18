<?php

namespace App\Http\Controllers;

use App\Models\ChatFeedback;
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
            'rating' => ['required', 'string', 'in:like,dislike'],
            'comment' => ['nullable', 'string', 'max:5000'],
        ]);

        ChatFeedback::updateOrCreate(
            ['chat_message_id' => $validated['chat_message_id']],
            [
                'rating' => $validated['rating'],
                'comment' => $validated['comment'] ?? null,
            ],
        );

        return response()->json([
            'success' => true,
            'message' => 'Feedback recorded',
        ]);
    }
}