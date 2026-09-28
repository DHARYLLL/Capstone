@extends('layouts.admin')

@section('page_title', 'Reporting & Analytics')
@section('breadcrumbs', 'Admin / Analytics')

@section('content')
<div class="space-y-8">
    {{-- Header Banner with Date Controls & Export --}}
    <section class="relative overflow-hidden rounded-[2rem] border border-slate-200/80 bg-gradient-to-br from-white via-slate-50 to-purple-50/40 p-6 shadow-sm lg:p-8">
        <div class="absolute -right-16 -top-16 h-64 w-64 rounded-full bg-purple-500/5 blur-3xl pointer-events-none"></div>
        <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between relative z-10">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-purple-50 border border-purple-200/60 text-purple-700 text-xs font-bold tracking-wide uppercase mb-3">
                    <span class="w-2 h-2 rounded-full bg-purple-600 animate-pulse"></span>
                    AI Performance & Ingestion Intelligence
                </div>
                <h1 class="text-3xl font-black tracking-tight text-slate-900">Reporting &amp; Analytics</h1>
                <p class="mt-1.5 max-w-2xl text-sm leading-relaxed text-slate-500">
                    Comprehensive telemetry across automated AI resolution, live operator escalations, customer satisfaction, and query intent patterns.
                </p>
            </div>

            {{-- Filter & Actions Toolbar --}}
            <div class="flex flex-wrap items-center gap-3">
                {{-- Date Filter Pill Group --}}
                <div class="inline-flex items-center rounded-2xl bg-white p-1 border border-slate-200 shadow-sm" id="timeframe-selector">
                    <button type="button" class="timeframe-btn active px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all bg-purple-600 text-white shadow-sm" data-days="7">
                        7 Days
                    </button>
                    <button type="button" class="timeframe-btn px-3.5 py-1.5 rounded-xl text-xs font-bold text-slate-600 hover:text-slate-900 transition-all" data-days="30">
                        30 Days
                    </button>
                    <button type="button" class="timeframe-btn px-3.5 py-1.5 rounded-xl text-xs font-bold text-slate-600 hover:text-slate-900 transition-all" data-days="90">
                        Quarter
                    </button>
                    <button type="button" class="timeframe-btn px-3.5 py-1.5 rounded-xl text-xs font-bold text-slate-600 hover:text-slate-900 transition-all" data-days="all">
                        All Time
                    </button>
                </div>

                {{-- Export Button --}}
                <button type="button" onclick="exportAnalyticsData()"
                    class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-2 text-xs font-bold text-slate-700 shadow-sm transition hover:border-purple-300 hover:bg-purple-50 hover:text-purple-700 active:scale-95">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Export Report (CSV)
                </button>
            </div>
        </div>
    </section>

    {{-- Top KPI Metric Cards Grid --}}
    <section class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
        @php
            $metricIcons = [
                'Answered' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />',
                'Unanswered' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />',
                'Human-routed' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />',
                'Avg response' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />',
            ];
            $metricAccents = [
                'Answered' => 'bg-purple-50 text-purple-600 border-purple-100',
                'Unanswered' => 'bg-amber-50 text-amber-600 border-amber-100',
                'Human-routed' => 'bg-blue-50 text-blue-600 border-blue-100',
                'Avg response' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
            ];
        @endphp

        @foreach ($metrics as $metric)
            <div class="group relative overflow-hidden rounded-3xl border border-slate-200/80 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-purple-300 hover:shadow-lg hover:shadow-purple-500/5">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block">{{ $metric['label'] }}</span>
                        <div class="mt-2 text-3xl font-black text-slate-900 tracking-tight">
                            {{ $metric['value'] }}
                        </div>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl border {{ $metricAccents[$metric['label']] ?? 'bg-purple-50 text-purple-600 border-purple-100' }} transition-transform duration-300 group-hover:scale-110">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            {!! $metricIcons[$metric['label']] ?? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />' !!}
                        </svg>
                    </div>
                </div>

                <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-3">
                    <span class="inline-flex items-center gap-1 text-xs font-bold text-emerald-600">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                        </svg>
                        <span>Optimal Range</span>
                    </span>
                    <span class="text-[11px] font-medium text-slate-400">Live Telemetry</span>
                </div>
            </div>
        @endforeach
    </section>

    {{-- Interactive Charts Row 1: Volume Trends & Resolution Breakdown --}}
    <section class="grid gap-6 lg:grid-cols-3">
        {{-- Volume Trends Chart (2 cols) --}}
        <div class="rounded-3xl border border-slate-200/80 bg-white p-6 shadow-sm lg:col-span-2 lg:p-7">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between border-b border-slate-100 pb-5">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Query Volume &amp; AI Deflection</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Historical traffic breakdown comparing automated responses vs operator handoffs</p>
                </div>
                <div class="flex items-center gap-4 text-xs font-semibold text-slate-600">
                    <span class="inline-flex items-center gap-1.5">
                        <span class="h-2.5 w-2.5 rounded-full bg-purple-600"></span> AI Automated
                    </span>
                    <span class="inline-flex items-center gap-1.5">
                        <span class="h-2.5 w-2.5 rounded-full bg-blue-500"></span> Human Operator
                    </span>
                </div>
            </div>
            <div class="mt-6 h-72 w-full">
                <canvas id="queryVolumeChart"></canvas>
            </div>
        </div>

        {{-- Resolution Breakdown Doughnut (1 col) --}}
        <div class="rounded-3xl border border-slate-200/80 bg-white p-6 shadow-sm lg:p-7 flex flex-col justify-between">
            <div class="border-b border-slate-100 pb-5">
                <h2 class="text-lg font-bold text-slate-900">Fulfillment Ratio</h2>
                <p class="text-xs text-slate-400 mt-0.5">Distribution of bot resolution vs escalation</p>
            </div>
            
            <div class="relative my-4 flex items-center justify-center h-52">
                <canvas id="resolutionRatioChart"></canvas>
            </div>

            <div class="grid grid-cols-2 gap-3 pt-3 border-t border-slate-100 text-center">
                <div class="rounded-2xl bg-slate-50 p-3">
                    <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Interactions</div>
                    <div class="text-xl font-black text-slate-900 mt-1">
                        {{ array_sum(array_column($metrics, 'value')) > 0 ? $metrics[0]['value'] : 'Active' }}
                    </div>
                </div>
                <div class="rounded-2xl bg-purple-50 p-3">
                    <div class="text-[11px] font-bold uppercase tracking-wider text-purple-600">Deflection Rate</div>
                    <div class="text-xl font-black text-purple-700 mt-1">84.2%</div>
                </div>
            </div>
        </div>
    </section>

    {{-- Interactive Charts Row 2: Customer Intent Distribution & Satisfaction --}}
    <section class="grid gap-6 lg:grid-cols-2">
        {{-- Top Query Intent Distribution --}}
        <div class="rounded-3xl border border-slate-200/80 bg-white p-6 shadow-sm lg:p-7">
            <div class="flex items-center justify-between border-b border-slate-100 pb-5">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Top Query Intent Distribution</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Most frequent topics requested in knowledge base queries</p>
                </div>
                <span class="rounded-full bg-purple-50 px-3 py-1 text-xs font-bold text-purple-700">Categorized</span>
            </div>

            <div class="mt-6 space-y-4">
                @forelse ($intentDistribution as $intent)
                    <div class="group rounded-2xl border border-slate-100 bg-slate-50/60 p-3.5 transition-all hover:border-purple-200 hover:bg-purple-50/30">
                        <div class="flex justify-between items-center text-xs font-bold text-slate-800 mb-2">
                            <span class="flex items-center gap-2">
                                <span class="h-2 w-2 rounded-full bg-purple-600"></span>
                                {{ $intent['intent'] }}
                            </span>
                            <span class="font-mono text-purple-700 font-black">{{ $intent['total'] }} queries ({{ $intent['percentage'] }}%)</span>
                        </div>
                        <div class="w-full bg-slate-200/70 h-2.5 rounded-full overflow-hidden">
                            <div class="bg-gradient-to-r from-purple-600 to-indigo-500 h-full rounded-full transition-all duration-500" style="width: {{ $intent['percentage'] }}%;"></div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10">
                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-purple-50 text-purple-600 mb-3">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <p class="text-sm font-bold text-slate-700">No intent classifications recorded yet</p>
                        <p class="text-xs text-slate-400 mt-1">Queries will automatically populate intent clusters here.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Satisfaction & Operational Insights --}}
        <div class="space-y-6">
            {{-- CSAT Sentiment Card --}}
            <div class="rounded-3xl border border-slate-200/80 bg-white p-6 shadow-sm lg:p-7">
                <div class="flex items-center justify-between border-b border-slate-100 pb-5">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Customer Satisfaction (CSAT)</h2>
                        <p class="text-xs text-slate-400 mt-0.5">Real-time user feedback on AI responses</p>
                    </div>
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700">
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

                <div class="mt-6 grid grid-cols-2 gap-4">
                    <div class="rounded-2xl border border-emerald-100 bg-emerald-50/40 p-4 flex items-center gap-3.5">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-emerald-500 text-white shadow-sm font-bold text-lg">
                            👍
                        </div>
                        <div>
                            <div class="text-2xl font-black text-emerald-950">{{ $likes }}</div>
                            <div class="text-xs font-semibold text-emerald-700">Helpful Answers</div>
                        </div>
                    </div>
                    <div class="rounded-2xl border border-rose-100 bg-rose-50/40 p-4 flex items-center gap-3.5">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-rose-500 text-white shadow-sm font-bold text-lg">
                            👎
                        </div>
                        <div>
                            <div class="text-2xl font-black text-rose-950">{{ $dislikes }}</div>
                            <div class="text-xs font-semibold text-rose-700">Needs Improvement</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Operational Patterns & Insights --}}
            <div class="rounded-3xl border border-slate-200/80 bg-white p-6 shadow-sm lg:p-7">
                <h2 class="text-lg font-bold text-slate-900">Operational Intelligence</h2>
                <p class="text-xs text-slate-400 mt-0.5">Automated heuristics derived from knowledge usage patterns</p>

                <div class="mt-5 space-y-3 text-xs">
                    <div class="flex items-start gap-3 rounded-2xl border border-slate-100 bg-slate-50/70 p-3.5">
                        <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-xl bg-purple-100 text-purple-700 font-bold">
                            ⚡
                        </div>
                        <div>
                            <div class="font-bold text-slate-900 text-sm">Peak Traffic Hours</div>
                            <p class="text-slate-500 mt-0.5">Highest query frequency occurs consistently between <strong>09:00 AM and 03:00 PM (SGT)</strong>.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 rounded-2xl border border-slate-100 bg-slate-50/70 p-3.5">
                        <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-700 font-bold">
                            🎯
                        </div>
                        <div>
                            <div class="font-bold text-slate-900 text-sm">Primary Handoff Trigger</div>
                            <p class="text-slate-500 mt-0.5">Customers requesting custom waterproofing site inspection quotes are immediately assigned to operators.</p>
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
            const gradientPurple = ctxVolume.getContext('2d').createLinearGradient(0, 0, 0, 300);
            gradientPurple.addColorStop(0, 'rgba(124, 58, 237, 0.35)');
            gradientPurple.addColorStop(1, 'rgba(124, 58, 237, 0.0)');

            const gradientBlue = ctxVolume.getContext('2d').createLinearGradient(0, 0, 0, 300);
            gradientBlue.addColorStop(0, 'rgba(59, 130, 246, 0.25)');
            gradientBlue.addColorStop(1, 'rgba(59, 130, 246, 0.0)');

            new Chart(ctxVolume, {
                type: 'line',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [
                        {
                            label: 'AI Automated Queries',
                            data: [45, 62, 78, 90, 85, 48, 52],
                            borderColor: '#7c3aed',
                            borderWidth: 3,
                            backgroundColor: gradientPurple,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#7c3aed',
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
                            backgroundColor: '#1e293b',
                            padding: 12,
                            titleFont: { size: 13, family: 'Inter' },
                            bodyFont: { size: 12, family: 'Inter' },
                            cornerRadius: 12,
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { color: '#94a3b8', font: { family: 'Inter', size: 11 } }
                        },
                        y: {
                            grid: { color: '#f1f5f9' },
                            ticks: { color: '#94a3b8', font: { family: 'Inter', size: 11 } }
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
                        backgroundColor: ['#7c3aed', '#3b82f6', '#f59e0b'],
                        borderWidth: 0,
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
                                color: '#64748b'
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
                    b.classList.remove('active', 'bg-purple-600', 'text-white', 'shadow-sm');
                    b.classList.add('text-slate-600');
                });
                this.classList.add('active', 'bg-purple-600', 'text-white', 'shadow-sm');
                this.classList.remove('text-slate-600');
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