<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\ChatSession;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StaffChatController extends Controller
{
    public function index(): View
    {
        $this->touchLastSeen();
        return view('staff.chat');
    }

    public function sessions(): JsonResponse
    {
        $this->touchLastSeen();
        $sessions = ChatSession::query()
            ->with(['assignedUser:id,name', 'businessUnit:id,name'])
            ->where('company_id', Auth::user()->company_id)
            ->whereIn('status', ['waiting', 'human_active'])
            ->latest('updated_at')
            ->get();

        return response()->json(['data' => $sessions]);
    }

    public function pendingCount(): JsonResponse
    {
        $this->touchLastSeen();
        $companyId = Auth::user()?->company_id;

        abort_unless($companyId, 403);

        return response()->json([
            'count' => ChatSession::query()
                ->where('company_id', $companyId)
                ->where('status', 'waiting')
                ->count(),
        ]);
    }

    public function messages(ChatSession $session): JsonResponse
    {
        $this->touchLastSeen();
        $this->ensureCompanySession($session);

        return response()->json([
            'data' => $session->chatMessages()->oldest('created_at')->get(),
        ]);
    }

    public function sendMessage(Request $request, ChatSession $session): JsonResponse
    {
        $this->touchLastSeen();
        $this->ensureCompanySession($session);
        abort_unless($session->status === 'human_active' && $session->assigned_user_id === Auth::id(), 403);

        $validated = $request->validate([
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $message = $session->chatMessages()->create([
            'sender_type' => 'agent',
            'message_text' => trim($validated['message']),
        ]);

        return response()->json(['data' => $message], 201);
    }

    public function claimSession(ChatSession $session): JsonResponse
    {
        $this->touchLastSeen();
        $this->ensureCompanySession($session);

        $claimedSession = DB::transaction(function () use ($session): ChatSession {
            $lockedSession = ChatSession::query()->lockForUpdate()->findOrFail($session->id);

            abort_unless(
                $lockedSession->company_id === Auth::user()->company_id,
                404,
            );
            abort_unless(
                in_array($lockedSession->status, ['waiting', 'pending', 'human_active'], true),
                422,
                'This chat is no longer available for claiming.',
            );
            abort_unless(
                $lockedSession->assigned_user_id === null
                    || $lockedSession->assigned_user_id === Auth::id(),
                409,
                'This chat has already been claimed by another operator.',
            );

            $lockedSession->update([
                'status' => 'human_active',
                'handed_off_at' => $lockedSession->handed_off_at ?? now(),
                'assigned_user_id' => Auth::id(),
            ]);

            return $lockedSession->fresh(['assignedUser:id,name']);
        });

        return response()->json(['success' => true, 'data' => $claimedSession]);
    }

    public function resolveSession(ChatSession $session): JsonResponse
    {
        $this->touchLastSeen();
        $this->ensureCompanySession($session);
        abort_unless($session->assigned_user_id === Auth::id(), 403);

        $session->status = 'bot_active';
        $session->assigned_user_id = null;
        $session->save();

        return response()->json(['status' => 'bot_active']);
    }

    private function ensureCompanySession(ChatSession $session): void
    {
        abort_unless($session->company_id === Auth::user()->company_id, 404);
    }

    private function touchLastSeen(): void
    {
        $user = Auth::user();

        if ($user instanceof User) {
            $user->update(['last_seen_at' => now()]);
        }
    }
}
