<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\ChatMessage;
use App\Models\ChatSession;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class AdminChatController extends Controller
{
    public function index(): View
    {
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

    public function pendingCount(): JsonResponse
    {
        return response()->json([
            'count' => $this->pendingSessionsQuery()->count(),
        ]);
    }

    public function messages(ChatSession $session): JsonResponse
    {
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
        $user = Auth::user();

        try {
            abort_unless($this->canAccessSession($session, $user), 404);

            [$claimedSession, $wasAssigned] = DB::transaction(function () use ($session, $user): array {
                $lockedSession = ChatSession::query()->lockForUpdate()->findOrFail($session->id);
                abort_unless($this->canAccessSession($lockedSession, $user), 404);
                abort_unless($this->isActiveSession($lockedSession), 422, 'This chat is no longer active.');
                $wasAssigned = filled($lockedSession->assigned_user_id);

                $lockedSession->update([
                    'status' => 'human_active',
                    'assigned_user_id' => $user->id,
                    'handed_off_at' => $lockedSession->handed_off_at ?? now(),
                ]);

                return [$lockedSession->fresh(['lastMessage']), $wasAssigned];
            });

            ActivityLog::record(
                $user->id,
                $wasAssigned ? 'Admin took over chat from Operator' : 'Human handoff accepted',
                'Done',
                null,
                'DARIV',
                'auth',
            );

            return response()->json(['success' => true, 'data' => $this->sessionPayload($claimedSession)]);
        } catch (Throwable $exception) {
            Log::error('Chat claim failed', [
                'error' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
                'session_id' => $session->id,
                'user_id' => $user?->id,
                'user_role' => $user?->role,
                'user_company_id' => $user?->company_id,
                'session_company_id' => $session->company_id,
                'session_business_unit_id' => $session->business_unit_id,
            ]);

            throw $exception;
        }
    }

    public function reply(Request $request, ChatSession $session): JsonResponse
    {
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

    private function canAccessSession(ChatSession $session, ?\App\Models\User $user): bool
    {
        $companyMatches = $user?->company_id !== null
            && $session->company_id !== null
            && (int) $session->company_id === (int) $user->company_id;

        $businessUnitMatches = $user?->business_unit_id === null
            || ($session->business_unit_id !== null
                && (int) $session->business_unit_id === (int) $user->business_unit_id);

        if (! $companyMatches || ! $businessUnitMatches) {
            Log::warning('Chat claim tenant mismatch', [
                'session_id' => $session->id,
                'session_company_id' => $session->company_id,
                'session_business_unit_id' => $session->business_unit_id,
                'user_id' => $user?->id,
                'user_company_id' => $user?->company_id,
                'user_business_unit_id' => $user?->business_unit_id,
            ]);
        }

        return $companyMatches && $businessUnitMatches;
    }

    private function pendingSessionsQuery()
    {
        $query = ChatSession::query()->where('status', 'waiting');
        $user = Auth::user();

        if ($user?->company_id) {
            $query->where('company_id', $user->company_id);
        }

        return $query;
    }

}