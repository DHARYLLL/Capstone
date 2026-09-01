<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\ChatSession;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OperatorChatController extends Controller
{
    public function getActiveSessions(): JsonResponse
    {
        $sessions = ChatSession::query()
            ->with(['assignedUser:id,name,email', 'businessUnit:id,name'])
            ->whereIn('status', ['handoff_requested', 'human_active'])
            ->orderByDesc('updated_at')
            ->get();

        return response()->json([
            'data' => $sessions->map(function (ChatSession $session): array {
                return [
                    'id' => $session->id,
                    'business_unit_id' => $session->business_unit_id,
                    'status' => $session->status,
                    'user_identifier' => $session->user_identifier,
                    'assigned_user' => $session->assignedUser ? [
                        'id' => $session->assignedUser->id,
                        'name' => $session->assignedUser->name,
                        'email' => $session->assignedUser->email,
                    ] : null,
                    'updated_at' => $session->updated_at,
                    'message_count' => $session->chatMessages()->count(),
                ];
            })->values(),
        ]);
    }

    public function getMessages(ChatSession $session): JsonResponse
    {
        $messages = $session->chatMessages()->orderBy('created_at')->get()->map(function (ChatMessage $message): array {
            return [
                'id' => $message->id,
                'sender_type' => $message->sender_type,
                'message_text' => $message->message_text,
                'created_at' => $message->created_at,
            ];
        });

        return response()->json([
            'data' => [
                'session' => [
                    'id' => $session->id,
                    'status' => $session->status,
                    'user_identifier' => $session->user_identifier,
                ],
                'messages' => $messages,
            ],
        ]);
    }

    public function reply(Request $request, ChatSession $session): JsonResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $messageText = trim($validated['message']);

        ChatMessage::query()->create([
            'chat_session_id' => $session->id,
            'sender_type' => 'operator',
            'message_text' => $messageText,
        ]);

        $user = Auth::user();

        $session->update([
            'status' => 'human_active',
            'handed_off_at' => $session->handed_off_at ?? now(),
            'assigned_user_id' => $user?->id ?? $session->assigned_user_id,
        ]);

        return response()->json([
            'status' => 'human_active',
            'message' => 'Operator reply saved.',
            'session_id' => $session->id,
        ]);
    }
}
