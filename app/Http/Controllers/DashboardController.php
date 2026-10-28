<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\BusinessKnowledge;
use App\Models\ChatMessage;
use App\Models\ChatSession;
use App\Models\Company;
use App\Models\StagedKnowledgeDocument;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless(session('user_role') === 'Administrator', 403);

        $user = $request->user() ?? Auth::user();
        $companyId = $user?->company_id ?? $request->integer('company_id');
        
        if (! $companyId) {
            $company = Company::with('businessUnits')->first();
            $companyId = $company?->id;
        } else {
            $company = Company::with('businessUnits')->find($companyId);
        }

        $now = now();
        $currentPeriod = [$now->copy()->subDays(30), $now->copy()];
        $previousPeriod = [$now->copy()->subDays(60), $now->copy()->subDays(30)];

        $answeredCount = $this->answeredQueries($companyId, $currentPeriod);
        $previousAnsweredCount = $this->answeredQueries($companyId, $previousPeriod);
        $currentCustomerQueries = $this->customerQueries($companyId, $currentPeriod)->count();
        $previousCustomerQueries = $this->customerQueries($companyId, $previousPeriod)->count();
        $unansweredCount = max(0, $currentCustomerQueries - $answeredCount);
        $previousUnansweredCount = max(0, $previousCustomerQueries - $previousAnsweredCount);
        $humanRoutedCount = $this->humanRoutedQueries($companyId, $currentPeriod);
        $previousHumanRoutedCount = $this->humanRoutedQueries($companyId, $previousPeriod);

        $knowledgeFilesCount = BusinessKnowledge::query()
            ->when($companyId, fn ($q) => $q->where('company_id', $companyId))
            ->count();
        $previousKnowledgeFilesCount = BusinessKnowledge::query()
            ->when($companyId, fn ($q) => $q->where('company_id', $companyId))
            ->whereBetween('created_at', $previousPeriod)
            ->count();
        $currentKnowledgeFilesCount = BusinessKnowledge::query()
            ->when($companyId, fn ($q) => $q->where('company_id', $companyId))
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

        // Scoped recent activities
        $recentActivities = ActivityLog::query()
            ->when($companyId, function ($query) use ($companyId): void {
                $query->where(function ($sub) use ($companyId): void {
                    $sub->whereHas('user', fn ($q) => $q->where('company_id', $companyId))
                        ->orWhereNull('user_id');
                });
            })
            ->with('user')
            ->latest()
            ->take(5)
            ->get();

        // Real-time system status and health checks
        $isGeminiConfigured = ! empty(config('gemini.api_key'));
        $pendingStagedCount = StagedKnowledgeDocument::query()
            ->when($companyId, function ($query) use ($companyId): void {
                $query->whereHas('businessUnit', fn ($q) => $q->where('company_id', $companyId));
            })
            ->where('status', 'staged')
            ->count();

        $onlineStaffCount = User::query()
            ->when($companyId, fn ($q) => $q->where('company_id', $companyId))
            ->where('last_seen_at', '>=', now()->subMinutes(5))
            ->count();

        $avgResponseFormatted = $this->calculateAvgResponseTime($companyId);

        $systemHealth = [
            'ai_engine' => $isGeminiConfigured ? 'Online · RAG Active' : 'Offline · Missing API Key',
            'ai_engine_ok' => $isGeminiConfigured,
            'ingestion' => $pendingStagedCount > 0 ? "{$pendingStagedCount} Awaiting Review" : 'Synchronized',
            'ingestion_badge' => $pendingStagedCount > 0 ? 'text-amber-600' : 'text-slate-800',
            'avg_response' => $avgResponseFormatted,
            'online_staff' => $onlineStaffCount,
        ];

        $tenantName = $company?->name ?? 'Dariv Platform';
        $buNames = $company?->businessUnits?->pluck('name')->filter()->join(', ');
        $tenantType = ! empty($buNames) ? $buNames : 'Customer Support & AI Operations';

        return view('admin.dashboard', compact(
            'kpis',
            'recentActivities',
            'systemHealth',
            'tenantName',
            'tenantType',
            'company'
        ));
    }

    private function customerQueries(?int $companyId, array $period): Builder
    {
        return ChatMessage::query()
            ->when($companyId, function (Builder $query) use ($companyId): void {
                $query->whereHas('chatSession', fn (Builder $q) => $q->where('company_id', $companyId));
            })
            ->where('sender_type', 'customer')
            ->whereBetween('created_at', $period);
    }

    private function answeredQueries(?int $companyId, array $period): int
    {
        return $this->customerQueries($companyId, $period)
            ->whereExists(function ($query): void {
                $query->selectRaw('1')
                    ->from('chat_messages as bot')
                    ->whereColumn('bot.chat_session_id', 'chat_messages.chat_session_id')
                    ->where('bot.sender_type', 'bot')
                    ->whereColumn('bot.id', '>', 'chat_messages.id');
            })
            ->count();
    }

    private function humanRoutedQueries(?int $companyId, array $period): int
    {
        return ChatSession::query()
            ->when($companyId, fn (Builder $q) => $q->where('company_id', $companyId))
            ->where(function (Builder $query) use ($period): void {
                $query->whereBetween('handed_off_at', $period)
                    ->orWhere(function (Builder $query) use ($period): void {
                        $query->whereIn('status', ['handoff_requested', 'human_active', 'waiting', 'pending', 'handed_off'])
                            ->whereBetween('created_at', $period);
                    });
            })
            ->count();
    }

    private function calculateAvgResponseTime(?int $companyId): string
    {
        $botMessages = ChatMessage::query()
            ->when($companyId, function (Builder $query) use ($companyId): void {
                $query->whereHas('chatSession', fn (Builder $q) => $q->where('company_id', $companyId));
            })
            ->where('sender_type', 'bot')
            ->latest('id')
            ->take(15)
            ->get();

        $durations = [];
        foreach ($botMessages as $botMsg) {
            $prev = ChatMessage::query()
                ->where('chat_session_id', $botMsg->chat_session_id)
                ->where('sender_type', 'customer')
                ->where('id', '<', $botMsg->id)
                ->latest('id')
                ->first();

            if ($prev && $botMsg->created_at && $prev->created_at) {
                $diff = $botMsg->created_at->diffInSeconds($prev->created_at);
                if ($diff >= 0 && $diff <= 120) {
                    $durations[] = $diff;
                }
            }
        }

        if (count($durations) > 0) {
            $avg = array_sum($durations) / count($durations);
            return number_format($avg, 1) . 's';
        }

        return '< 1.5s';
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
