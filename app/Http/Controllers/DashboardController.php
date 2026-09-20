<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\BusinessKnowledge;
use App\Models\ChatMessage;
use App\Models\ChatSession;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        abort_unless(session('user_role') === 'Administrator', 403);

        $now = now();
        $currentPeriod = [$now->copy()->subDays(30), $now->copy()];
        $previousPeriod = [$now->copy()->subDays(60), $now->copy()->subDays(30)];

        $answeredCount = $this->answeredQueries($currentPeriod);
        $previousAnsweredCount = $this->answeredQueries($previousPeriod);
        $currentCustomerQueries = $this->customerQueries($currentPeriod)->count();
        $previousCustomerQueries = $this->customerQueries($previousPeriod)->count();
        $unansweredCount = max(0, $currentCustomerQueries - $answeredCount);
        $previousUnansweredCount = max(0, $previousCustomerQueries - $previousAnsweredCount);
        $humanRoutedCount = $this->humanRoutedQueries($currentPeriod);
        $previousHumanRoutedCount = $this->humanRoutedQueries($previousPeriod);
        $knowledgeFilesCount = BusinessKnowledge::query()->count();
        $previousKnowledgeFilesCount = BusinessKnowledge::query()
            ->whereBetween('created_at', $previousPeriod)
            ->count();
        $currentKnowledgeFilesCount = BusinessKnowledge::query()
            ->whereBetween('created_at', $currentPeriod)
            ->count();
        $answeredDiffFormatted = $this->formatChange($answeredCount, $previousAnsweredCount);
        $unansweredDiffFormatted = $this->formatChange($unansweredCount, $previousUnansweredCount);
        $humanRoutedDiffFormatted = $this->formatChange($humanRoutedCount, $previousHumanRoutedCount);
        $knowledgeFilesDiffFormatted = $this->formatChange($currentKnowledgeFilesCount, $previousKnowledgeFilesCount, true);

        $kpis = [
            [
                'label' => 'Answered queries',
                'value' => number_format($answeredCount),
                'delta' => $answeredDiffFormatted,
                'deltaClass' => $this->changeClass($answeredCount, $previousAnsweredCount),
            ],
            [
                'label' => 'Unanswered queries',
                'value' => number_format($unansweredCount),
                'delta' => $unansweredDiffFormatted,
                'deltaClass' => $this->changeClass($unansweredCount, $previousUnansweredCount),
            ],
            [
                'label' => 'Human-routed queries',
                'value' => number_format($humanRoutedCount),
                'delta' => $humanRoutedDiffFormatted,
                'deltaClass' => $this->changeClass($humanRoutedCount, $previousHumanRoutedCount),
            ],
            [
                'label' => 'Knowledge files',
                'value' => number_format($knowledgeFilesCount),
                'delta' => $knowledgeFilesDiffFormatted,
                'deltaClass' => $this->changeClass($currentKnowledgeFilesCount, $previousKnowledgeFilesCount),
            ],
        ];

        $recentActivities = ActivityLog::query()
            ->with('user')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('kpis', 'recentActivities'));
    }

    private function customerQueries(array $period): Builder
    {
        return ChatMessage::query()
            ->where('sender_type', 'customer')
            ->whereBetween('created_at', $period);
    }

    private function answeredQueries(array $period): int
    {
        return $this->customerQueries($period)
            ->whereExists(function ($query): void {
                $query->selectRaw('1')
                    ->from('chat_messages as bot')
                    ->whereColumn('bot.chat_session_id', 'chat_messages.chat_session_id')
                    ->where('bot.sender_type', 'bot')
                    ->whereColumn('bot.id', '>', 'chat_messages.id');
            })
            ->count();
    }

    private function humanRoutedQueries(array $period): int
    {
        return ChatSession::query()
            ->where(function (Builder $query) use ($period): void {
                $query->whereBetween('handed_off_at', $period)
                    ->orWhere(function (Builder $query) use ($period): void {
                        $query->whereIn('status', ['handoff_requested', 'human_active'])
                            ->whereBetween('created_at', $period);
                    });
            })
            ->count();
    }

    private function formatChange(int $current, int $previous, bool $absoluteForFiles = false): string
    {
        if ($absoluteForFiles) {
            $difference = $current - $previous;

            return ($difference >= 0 ? '+' : '').$difference;
        }

        if ($previous === 0) {
            return $current === 0 ? '0%' : '+100%';
        }

        $change = (($current - $previous) / $previous) * 100;

        return sprintf('%s%s%%', $change >= 0 ? '+' : '', rtrim(rtrim(number_format($change, 1), '0'), '.'));
    }

    private function changeClass(int $current, int $previous): string
    {
        return $current >= $previous ? 'text-emerald-600 bg-emerald-50' : 'text-rose-600 bg-rose-50';
    }
}