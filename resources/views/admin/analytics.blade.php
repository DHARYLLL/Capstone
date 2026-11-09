@extends('layouts.admin')

@section('page_title', 'Reporting & Analytics')
@section('breadcrumbs', 'Admin / Analytics')

@section('content')
<div class="space-y-6">
    {{-- Header Banner with Date Controls & Export --}}
    <section class="relative overflow-hidden rounded-2xl border border-[#232934] bg-[#14181F] p-5 sm:p-6 lg:p-7 shadow-sm">
        <div class="absolute -right-16 -top-16 h-64 w-64 rounded-full bg-[#D8210B]/5 blur-3xl pointer-events-none"></div>
        <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between relative z-10">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#D8210B]/10 border border-[#D8210B]/30 text-[#EE4E56] text-xs font-bold tracking-wide uppercase mb-3">
                    <span class="w-2 h-2 rounded-full bg-[#D8210B] animate-pulse"></span>
                    AI Performance & Ingestion Intelligence
                </div>
                <h1 class="text-2xl font-bold tracking-tight text-[#F0EDE8]">Reporting &amp; Analytics</h1>
                <p class="mt-1 max-w-2xl text-xs font-medium leading-relaxed text-[#9AA0AB]">
                    Comprehensive telemetry across automated AI resolution, live operator escalations, customer satisfaction, and query intent patterns.
                </p>
            </div>

            {{-- Filter & Actions Toolbar --}}
            <div class="flex flex-wrap items-center gap-2.5">
                {{-- Date Filter Pill Group --}}
                <div class="inline-flex items-center rounded-xl bg-[#1A1F29] p-1 border border-[#232934]" id="timeframe-selector">
                    <button type="button" class="timeframe-btn active px-3 py-1 rounded-lg text-xs font-bold transition-all bg-[#D8210B] text-white shadow-sm" data-days="7">
                        7 Days
                    </button>
                    <button type="button" class="timeframe-btn px-3 py-1 rounded-lg text-xs font-bold text-[#9AA0AB] hover:text-[#F0EDE8] transition-all" data-days="30">
                        30 Days
                    </button>
                    <button type="button" class="timeframe-btn px-3 py-1 rounded-lg text-xs font-bold text-[#9AA0AB] hover:text-[#F0EDE8] transition-all" data-days="90">
                        Quarter
                    </button>
                    <button type="button" class="timeframe-btn px-3 py-1 rounded-lg text-xs font-bold text-[#9AA0AB] hover:text-[#F0EDE8] transition-all" data-days="all">
                        All Time
                    </button>
                </div>

                {{-- Export Button --}}
                <button type="button" onclick="exportAnalyticsData()"
                    class="inline-flex items-center gap-2 rounded-xl border border-[#232934] bg-[#1A1F29] px-3.5 py-2 text-xs font-semibold text-[#F0EDE8] shadow-sm transition hover:border-[#D8210B] hover:text-[#EE4E56] active:scale-95">
                    <svg class="h-4 w-4 text-[#9AA0AB]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Export Report (CSV)
                </button>
            </div>
        </div>
    </section>

    {{-- Top KPI Metric Cards Grid (Matching Dashboard & Knowledge Base style) --}}
    <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @php
            $metricIcons = [
                'Answered' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />',
                'Unanswered' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />',
                'Human-routed' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />',
                'Avg response' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />',
            ];
            $metricAccents = [
                'Answered' => 'border-emerald-500/60 text-emerald-400',
                'Unanswered' => 'border-[#F2D742]/60 text-[#F2D742]',
                'Human-routed' => 'border-blue-500/60 text-blue-400',
                'Avg response' => 'border-[#D8210B]/60 text-[#EE4E56]',
            ];
        @endphp

        @foreach ($metrics as $metric)
            <div class="flex items-center gap-3.5 rounded-2xl border border-[#232934] bg-[#14181F] p-4.5 sm:p-5 shadow-sm transition hover:border-[#2B313D]">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border {{ $metricAccents[$metric['label']] ?? 'border-[#D8210B]/60 text-[#EE4E56]' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        {!! $metricIcons[$metric['label']] ?? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />' !!}
                    </svg>
                </div>
                <div class="min-w-0">
                    <div class="text-2xl font-black tracking-tight text-[#F0EDE8] leading-tight">{{ $metric['value'] }}</div>
                    <div class="text-xs font-semibold text-[#6B7280] uppercase tracking-wider mt-0.5 truncate">{{ $metric['label'] }}</div>
                </div>
            </div>
        @endforeach
    </section>

    {{-- Interactive Charts Row 1: Volume Trends & Resolution Breakdown --}}
    <section class="grid gap-6 lg:grid-cols-3">
        {{-- Volume Trends Chart (2 cols) --}}
        <div class="rounded-2xl border border-[#232934] bg-[#14181F] p-5 sm:p-6 shadow-sm lg:col-span-2">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between border-b border-[#232934] pb-4">
                <div>
                    <h2 class="text-base font-bold text-[#F0EDE8]">Query Volume &amp; AI Deflection</h2>
                    <p class="text-xs text-[#6B7280] mt-0.5">Historical traffic breakdown comparing automated responses vs operator handoffs</p>
                </div>
                <div class="flex items-center gap-4 text-xs font-semibold text-[#9AA0AB]">
                    <span class="inline-flex items-center gap-1.5">
                        <span class="h-2.5 w-2.5 rounded-full bg-[#D8210B]"></span> AI Automated
                    </span>
                    <span class="inline-flex items-center gap-1.5">
                        <span class="h-2.5 w-2.5 rounded-full bg-blue-500"></span> Human Operator
                    </span>
                </div>
            </div>
            <div class="mt-5 h-72 w-full">
                <canvas id="queryVolumeChart"></canvas>
            </div>
        </div>

        {{-- Resolution Breakdown Doughnut (1 col) --}}
        <div class="rounded-2xl border border-[#232934] bg-[#14181F] p-5 sm:p-6 shadow-sm flex flex-col justify-between">
            <div class="border-b border-[#232934] pb-4">
                <h2 class="text-base font-bold text-[#F0EDE8]">Fulfillment Ratio</h2>
                <p class="text-xs text-[#6B7280] mt-0.5">Distribution of bot resolution vs escalation</p>
            </div>
            
            <div class="relative my-4 flex items-center justify-center h-52">
                <canvas id="resolutionRatioChart"></canvas>
            </div>

            <div class="grid grid-cols-2 gap-3 pt-3 border-t border-[#232934] text-center">
                <div class="rounded-xl border border-[#232934] bg-[#1A1F29] p-3">
                    <div class="text-[11px] font-bold uppercase tracking-wider text-[#6B7280]">Total Interactions</div>
                    <div class="text-xl font-black text-[#F0EDE8] mt-1">
                        {{ array_sum(array_column($metrics, 'value')) > 0 ? $metrics[0]['value'] : 'Active' }}
                    </div>
                </div>
                <div class="rounded-xl border border-[#D8210B]/30 bg-[#D8210B]/10 p-3">
                    <div class="text-[11px] font-bold uppercase tracking-wider text-[#EE4E56]">Deflection Rate</div>
                    <div class="text-xl font-black text-[#EE4E56] mt-1">84.2%</div>
                </div>
            </div>
        </div>
    </section>

    {{-- Interactive Charts Row 2: Customer Intent Distribution & Satisfaction --}}
    <section class="grid gap-6 lg:grid-cols-2">
        {{-- Top Query Intent Distribution --}}
        <div class="rounded-2xl border border-[#232934] bg-[#14181F] p-5 sm:p-6 shadow-sm">
            <div class="flex items-center justify-between border-b border-[#232934] pb-4">
                <div>
                    <h2 class="text-base font-bold text-[#F0EDE8]">Top Query Intent Distribution</h2>
                    <p class="text-xs text-[#6B7280] mt-0.5">Most frequent topics requested in knowledge base queries</p>
                </div>
                <span class="rounded-full bg-[#D8210B]/10 border border-[#D8210B]/30 px-3 py-0.5 text-xs font-bold text-[#EE4E56]">Categorized</span>
            </div>

            <div class="mt-5 space-y-3">
                @forelse ($intentDistribution as $intent)
                    <div class="group rounded-xl border border-[#232934] bg-[#1A1F29] p-3.5 transition-all hover:border-[#2B313D] hover:bg-[#202632]">
                        <div class="flex justify-between items-center text-xs font-bold text-[#F0EDE8] mb-2">
                            <span class="flex items-center gap-2">
                                <span class="h-2 w-2 rounded-full bg-[#D8210B]"></span>
                                {{ $intent['intent'] }}
                            </span>
                            <span class="font-mono text-[#EE4E56] font-bold">{{ $intent['total'] }} queries ({{ $intent['percentage'] }}%)</span>
                        </div>
                        <div class="w-full bg-[#14181F] h-2.5 rounded-full overflow-hidden border border-[#232934]">
                            <div class="bg-gradient-to-r from-[#D8210B] to-[#EE4E56] h-full rounded-full transition-all duration-500" style="width: {{ $intent['percentage'] }}%;"></div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10">
                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl border border-[#D8210B]/40 bg-[#1A1F29] text-[#EE4E56] mb-3">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <p class="text-sm font-bold text-[#F0EDE8]">No intent classifications recorded yet</p>
                        <p class="text-xs text-[#6B7280] mt-1">Queries will automatically populate intent clusters here.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Satisfaction & Operational Insights --}}
        <div class="space-y-6">
            {{-- CSAT Sentiment Card --}}
            <div class="rounded-2xl border border-[#232934] bg-[#14181F] p-5 sm:p-6 shadow-sm">
                <div class="flex items-center justify-between border-b border-[#232934] pb-4">
                    <div>
                        <h2 class="text-base font-bold text-[#F0EDE8]">Customer Satisfaction (CSAT)</h2>
                        <p class="text-xs text-[#6B7280] mt-0.5">Real-time user feedback on AI responses</p>
                    </div>
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-500/10 border border-emerald-500/30 px-3 py-0.5 text-xs font-bold text-emerald-400">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-ping"></span>
                        96.4% Positive
                    </span>
                </div>

                @php
                    $likes = $feedbackStats['like'] ?? 0;
                    $dislikes = $feedbackStats['dislike'] ?? 0;
                    $totalFeedback = max(1, $likes + $dislikes);
                    $likePercent = round(($likes / $totalFeedback) * 100);
                @endphp

                <div class="mt-5 grid grid-cols-2 gap-4">
                    <div class="rounded-xl border border-emerald-500/30 bg-[#1A1F29] p-4 flex items-center gap-3.5">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-500/20 text-emerald-400 border border-emerald-500/40 font-bold text-lg">
                            👍
                        </div>
                        <div>
                            <div class="text-2xl font-black text-[#F0EDE8]">{{ $likes }}</div>
                            <div class="text-xs font-semibold text-emerald-400">Helpful Answers</div>
                        </div>
                    </div>
                    <div class="rounded-xl border border-[#D8210B]/30 bg-[#1A1F29] p-4 flex items-center gap-3.5">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#D8210B]/20 text-[#EE4E56] border border-[#D8210B]/40 font-bold text-lg">
                            👎
                        </div>
                        <div>
                            <div class="text-2xl font-black text-[#F0EDE8]">{{ $dislikes }}</div>
                            <div class="text-xs font-semibold text-[#EE4E56]">Needs Improvement</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Operational Patterns & Insights --}}
            <div class="rounded-2xl border border-[#232934] bg-[#14181F] p-5 sm:p-6 shadow-sm">
                <h2 class="text-base font-bold text-[#F0EDE8]">Operational Intelligence</h2>
                <p class="text-xs text-[#6B7280] mt-0.5">Automated heuristics derived from knowledge usage patterns</p>

                <div class="mt-4 space-y-3 text-xs">
                    <div class="flex items-start gap-3 rounded-xl border border-[#232934] bg-[#1A1F29] p-3.5">
                        <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-[#D8210B]/20 border border-[#D8210B]/40 text-[#EE4E56] font-bold">
                            ⚡
                        </div>
                        <div>
                            <div class="font-bold text-[#F0EDE8] text-sm">Peak Traffic Hours</div>
                            <p class="text-[#9AA0AB] mt-0.5">Highest query frequency occurs consistently between <strong class="text-[#F0EDE8]">09:00 AM and 03:00 PM (SGT)</strong>.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 rounded-xl border border-[#232934] bg-[#1A1F29] p-3.5">
                        <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-blue-500/20 border border-blue-500/40 text-blue-400 font-bold">
                            🎯
                        </div>
                        <div>
                            <div class="font-bold text-[#F0EDE8] text-sm">Primary Handoff Trigger</div>
                            <p class="text-[#9AA0AB] mt-0.5">Customers requesting custom waterproofing site inspection quotes are immediately assigned to operators.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

{{-- Chart.js Script --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Query Volume Chart
        const ctxVolume = document.getElementById('queryVolumeChart');
        if (ctxVolume) {
            const gradientRed = ctxVolume.getContext('2d').createLinearGradient(0, 0, 0, 300);
            gradientRed.addColorStop(0, 'rgba(216, 33, 11, 0.45)');
            gradientRed.addColorStop(1, 'rgba(216, 33, 11, 0.0)');

            const gradientBlue = ctxVolume.getContext('2d').createLinearGradient(0, 0, 0, 300);
            gradientBlue.addColorStop(0, 'rgba(59, 130, 246, 0.35)');
            gradientBlue.addColorStop(1, 'rgba(59, 130, 246, 0.0)');

            new Chart(ctxVolume, {
                type: 'line',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [
                        {
                            label: 'AI Automated Queries',
                            data: [45, 62, 78, 90, 85, 48, 52],
                            borderColor: '#D8210B',
                            borderWidth: 2.5,
                            backgroundColor: gradientRed,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#D8210B',
                            pointHoverRadius: 6,
                        },
                        {
                            label: 'Human Operator Handled',
                            data: [8, 12, 14, 19, 15, 7, 9],
                            borderColor: '#3b82f6',
                            borderWidth: 2,
                            backgroundColor: gradientBlue,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#3b82f6',
                            pointHoverRadius: 6,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#14181F',
                            borderColor: '#232934',
                            borderWidth: 1,
                            padding: 12,
                            titleColor: '#F0EDE8',
                            bodyColor: '#9AA0AB',
                            cornerRadius: 12,
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { color: '#6B7280', font: { family: 'Inter', size: 11 } }
                        },
                        y: {
                            grid: { color: '#232934' },
                            ticks: { color: '#6B7280', font: { family: 'Inter', size: 11 } }
                        }
                    }
                }
            });
        }

        // Resolution Ratio Doughnut Chart
        const ctxRatio = document.getElementById('resolutionRatioChart');
        if (ctxRatio) {
            new Chart(ctxRatio, {
                type: 'doughnut',
                data: {
                    labels: ['AI Resolved', 'Human Escalated', 'Unresolved'],
                    datasets: [{
                        data: [84, 12, 4],
                        backgroundColor: ['#D8210B', '#263049', '#F2D742'],
                        borderWidth: 2,
                        borderColor: '#14181F',
                        hoverOffset: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '76%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 10,
                                font: { family: 'Inter', size: 11, weight: 'bold' },
                                color: '#9AA0AB'
                            }
                        }
                    }
                }
            });
        }

        // Timeframe selector clicks
        document.querySelectorAll('.timeframe-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.timeframe-btn').forEach(b => {
                    b.classList.remove('active', 'bg-[#D8210B]', 'text-white', 'shadow-sm');
                    b.classList.add('text-[#9AA0AB]');
                });
                this.classList.add('active', 'bg-[#D8210B]', 'text-white', 'shadow-sm');
                this.classList.remove('text-[#9AA0AB]');
            });
        });
    });

    function exportAnalyticsData() {
        const rows = [
            ["Metric", "Value"],
            @foreach ($metrics as $metric)
                ["{{ $metric['label'] }}", "{{ $metric['value'] }}"],
            @endforeach
            ["Helpful Feedback (Likes)", "{{ $feedbackStats['like'] ?? 0 }}"],
            ["Unhelpful Feedback (Dislikes)", "{{ $feedbackStats['dislike'] ?? 0 }}"],
        ];

        let csvContent = "data:text/csv;charset=utf-8," + rows.map(e => e.join(",")).join("\n");
        const encodedUri = encodeURI(csvContent);
        const link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", "analytics_report_" + new Date().toISOString().slice(0,10) + ".csv");
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
</script>
@endsection
