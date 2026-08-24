<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\ChatSession;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class StaffChatController extends Controller
{
    public function index(): View
    {
        return view('staff.chat');
    }

    public function sessions(): JsonResponse
    {
        $sessions = ChatSession::query()
            ->with(['assignedUser:id,name', 'businessUnit:id,name'])
            ->where('company_id', Auth::user()->company_id)
            ->whereIn('status', ['pending', 'human_active'])
            ->latest('updated_at')
            ->get();

        return response()->json(['data' => $sessions]);
    }

    public function messages(ChatSession $session): JsonResponse
    {
        $this->ensureCompanySession($session);

        return response()->json([
            'data' => $session->chatMessages()->oldest('created_at')->get(),
        ]);
    }

    public function sendMessage(Request $request, ChatSession $session): JsonResponse
    {
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
        $this->ensureCompanySession($session);
        abort_unless(in_array($session->status, ['pending', 'human_active'], true), 422);

        $session->status = 'human_active';
        $session->assigned_user_id = Auth::id();
        $session->save();

        return response()->json(['data' => $session->fresh(['assignedUser:id,name'])]);
    }

    public function resolveSession(ChatSession $session): JsonResponse
    {
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
}
