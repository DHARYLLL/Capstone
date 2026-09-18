<?php

namespace App\Http\Controllers;

use App\Models\ChatFeedback;
use App\Models\ChatMessage;
use App\Models\ChatSession;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    public function index(): View|RedirectResponse
    {
        if (! session()->has('user_role')) {
            return redirect()->route('login');
        }

        if (session('user_role') !== 'Administrator') {
            abort(403, 'Unauthorized. This section is restricted to Administrators only.');
        }

        $customerMessages = ChatMessage::query()
            ->where('sender_type', 'customer');

        $answered = (clone $customerMessages)
            ->whereExists(function ($query): void {
                $query->selectRaw('1')
                    ->from('chat_messages as bot')
                    ->whereColumn('bot.chat_session_id', 'chat_messages.chat_session_id')
                    ->where('bot.sender_type', 'bot')
                    ->whereColumn('bot.id', '>', 'chat_messages.id')
                    ->whereNotExists(function ($nestedQuery): void {
                        $nestedQuery->selectRaw('1')
                            ->from('chat_messages as intermediate')
                            ->whereColumn(
                                'intermediate.chat_session_id',
                                'chat_messages.chat_session_id'
                            )
                            ->whereColumn('intermediate.id', '>', 'chat_messages.id')
                            ->whereColumn('intermediate.id', '<', 'bot.id');
                    });
            })
            ->count();

        $totalCustomerQueries = (clone $customerMessages)->count();
        $unanswered = max(0, $totalCustomerQueries - $answered);

        $humanRouted = ChatSession::query()
            ->whereNotNull('handed_off_at')
            ->count();

        $feedbackStats = ChatFeedback::query()
            ->select('rating', DB::raw('COUNT(*) as total'))
            ->groupBy('rating')
            ->pluck('total', 'rating');

        return view('admin.analytics', [
            'metrics' => [
                ['label' => 'Answered', 'value' => number_format($answered), 'trend' => null],
                ['label' => 'Unanswered', 'value' => number_format($unanswered), 'trend' => null],
                ['label' => 'Human-routed', 'value' => number_format($humanRouted), 'trend' => null],
                [
                    'label' => 'Avg response',
                    'value' => ($averageResponseSeconds = $this->averageResponseSeconds()) === null
                        ? 'N/A'
                        : round($averageResponseSeconds, 1) . 's',
                    'trend' => null,
                ],
            ],
            'feedbackStats' => [
                'like' => (int) ($feedbackStats['like'] ?? 0),
                'dislike' => (int) ($feedbackStats['dislike'] ?? 0),
            ],
            'intentDistribution' => $this->intentDistribution(),
        ]);
    }

    private function averageResponseSeconds(): ?float
    {
        $average = DB::query()
            ->fromSub(
                DB::table('chat_messages')
                    ->select([
                        'chat_session_id',
                        'id',
                        'created_at',
                        DB::raw('LEAD(sender_type) OVER (PARTITION BY chat_session_id ORDER BY id) AS next_sender_type'),
                        DB::raw('LEAD(created_at) OVER (PARTITION BY chat_session_id ORDER BY id) AS next_created_at'),
                    ])
                    ->where('sender_type', 'customer'),
                'message_pairs'
            )
            ->where('next_sender_type', 'bot')
            ->selectRaw('AVG(EXTRACT(EPOCH FROM (next_created_at - created_at))) AS average_seconds')
            ->value('average_seconds');

        return $average === null ? null : (float) $average;
    }

    private function intentDistribution()
    {
        if (! Schema::hasColumn('chat_messages', 'intent')) {
            return collect();
        }

        $total = ChatMessage::query()
            ->where('sender_type', 'customer')
            ->whereNotNull('intent')
            ->count();

        if ($total === 0) {
            return collect();
        }

        return ChatMessage::query()
            ->select('intent', DB::raw('COUNT(*) as total'))
            ->where('sender_type', 'customer')
            ->whereNotNull('intent')
            ->groupBy('intent')
            ->orderByDesc('total')
            ->get()
            ->map(fn ($row): array => [
                'intent' => $row->intent,
                'total' => (int) $row->total,
                'percentage' => round(((int) $row->total / $total) * 100, 1),
            ]);
    }
}