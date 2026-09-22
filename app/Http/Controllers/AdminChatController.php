<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\ChatMessage;
use App\Models\ChatSession;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminChatController extends Controller
{
    public function index(): View
    {
        $this->authorizeAdmin();

        $sessions = $this->activeSessions()->get();
        $waitingCount = ChatSession::query()->whereIn('status', ['waiting', 'pending', 'queued'])->count();
        $activeCount = ChatSession::query()
            ->whereIn('status', ['handed_off', 'active', 'in_progress'])
            ->whereNotNull('assigned_user_id')
            ->count();

        return view('admin.chat', compact('sessions', 'waitingCount', 'activeCount'));
    }

    public function sessions(): JsonResponse
    {
        $this->authorizeAdmin();

        $sessions = $this->activeSessions()->get()->map(fn (ChatSession $session): array => $this->sessionPayload($session));

        return response()->json([
            'data' => $sessions,
            'waiting_count' => ChatSession::query()->whereIn('status', ['waiting', 'pending', 'queued'])->count(),
            'active_count' => ChatSession::query()
                ->whereIn('status', ['handed_off', 'active', 'in_progress'])
                ->whereNotNull('assigned_user_id')
                ->count(),
        ]);
    }

    public function messages(ChatSession $session): JsonResponse
    {
        $this->authorizeAdmin();

        return response()->json([
            'data' => $session->chatMessages()->oldest('created_at')->get()->map(static fn (ChatMessage $message): array => [
                'id' => $message->id,
                'sender_type' => $message->sender_type,
                'message_text' => $message->message_text,
                'created_at' => $message->created_at?->toIso8601String(),
            ]),
        ]);
    }

    public function claim(ChatSession $session): JsonResponse
    {
        $this->authorizeAdmin();
        abort_unless($this->isActiveSession($session), 422, 'This chat is no longer active.');
        $wasAssigned = filled($session->assigned_user_id);

        $session->update([
            'status' => 'handed_off',
            'assigned_user_id' => Auth::id(),
            'handed_off_at' => $session->handed_off_at ?? now(),
        ]);

        ActivityLog::record(
            Auth::id(),
            $wasAssigned ? 'Admin took over chat from Operator' : 'Human handoff accepted',
            'Done',
            null,
            'DARIV',
            'auth',
        );

        return response()->json(['data' => $this->sessionPayload($session->fresh(['lastMessage']))]);
    }

    public function reply(Request $request, ChatSession $session): JsonResponse
    {
        $this->authorizeAdmin();
        abort_unless($session->assigned_user_id === Auth::id(), 403);

        $validated = $request->validate(['message' => ['required', 'string', 'max:5000']]);
        $session->chatMessages()->create([
            'sender_type' => 'operator',
            'message_text' => trim($validated['message']),
        ]);
        $session->update(['status' => 'human_active']);

        return response()->json(['success' => true]);
    }

    public function resolve(ChatSession $session): JsonResponse
    {
        $this->authorizeAdmin();
        abort_unless($session->assigned_user_id === Auth::id(), 403);

        $session->update(['status' => 'bot_active', 'assigned_user_id' => null]);

        return response()->json(['success' => true]);
    }

    private function activeSessions()
    {
        return ChatSession::query()
            ->with(['lastMessage', 'assignedUser:id,name'])
            ->where(function ($query): void {
                $query->whereIn('status', ['waiting', 'pending', 'queued'])
                    ->orWhere(function ($query): void {
                        $query->whereIn('status', ['handed_off', 'active', 'in_progress', 'human_active']);
                    });
            })
            ->latest('updated_at');
    }

    private function sessionPayload(ChatSession $session): array
    {
        return [
            'id' => $session->id,
            'customer_name' => $session->user_identifier ?: 'Guest Customer',
            'latest_message' => $session->lastMessage?->message_text ?? 'No messages yet.',
            'status' => $this->isWaiting($session) ? 'waiting' : 'active',
            'raw_status' => $session->status,
            'assigned_user_id' => $session->assigned_user_id,
            'assigned_user_name' => $session->assignedUser?->name,
            'updated_at' => $session->updated_at?->toIso8601String(),
        ];
    }

    private function isWaiting(ChatSession $session): bool
    {
        return in_array($session->status, ['waiting', 'pending', 'queued'], true);
    }

    private function isActiveSession(ChatSession $session): bool
    {
        return in_array($session->status, [
            'waiting', 'pending', 'queued', 'handed_off', 'active', 'in_progress',
        ], true);
    }

    private function authorizeAdmin(): void
    {
        abort_unless(session('user_role') === 'Administrator', 403);
    }
}