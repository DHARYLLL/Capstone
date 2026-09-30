{{-- filepath: c:\Users\dhary\Desktop\Capstone\capstone1\resources\views\admin\dashboard.blade.php --}}
@extends('layouts.admin')

@section('page_title', 'Tenant Dashboard')
@section('breadcrumbs', 'Admin / Overview')

@section('content')
    @php
        $tenantName = 'DARIV Waterproofing';
        $tenantType = 'Residential & Commercial';
        
        $answeredNum = (int) str_replace(',', '', $kpis[0]['value'] ?? '0');
        $unansweredNum = (int) str_replace(',', '', $kpis[1]['value'] ?? '0');
        $humanRoutedNum = (int) str_replace(',', '', $kpis[2]['value'] ?? '0');
        $totalQueries = $answeredNum + $unansweredNum + $humanRoutedNum;
        $aiRate = $totalQueries > 0 ? round(($answeredNum / $totalQueries) * 100, 1) : 100;
        $humanRate = $totalQueries > 0 ? round(($humanRoutedNum / $totalQueries) * 100, 1) : 0;
        $unansweredRate = $totalQueries > 0 ? max(0, 100 - $aiRate - $humanRate) : 0;

        $modules = [
            [
                'title' => 'Knowledge Base',
                'subtitle' => 'Docs, ingestion & sync',
                'badge' => 'Files Ready',
                'badgeColor' => 'bg-violet-50 text-violet-700 ring-violet-200',
                'iconBg' => 'bg-violet-500 text-white',
                'icon' => 'kb',
                'href' => route('admin.knowledge-base'),
            ],
            [
                'title' => 'Live Chat & Queue',
                'subtitle' => 'Operator escalations',
                'badge' => 'Active',
                'badgeColor' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
                'iconBg' => 'bg-emerald-500 text-white',
                'icon' => 'chat',
                'href' => route('admin.chat'),
            ],
            [
                'title' => 'Analytics & Trends',
                'subtitle' => 'Query volumes & CSAT',
                'badge' => 'Insights',
                'badgeColor' => 'bg-blue-50 text-blue-700 ring-blue-200',
                'iconBg' => 'bg-blue-500 text-white',
                'icon' => 'analytics',
                'href' => route('admin.analytics'),
            ],
            [
                'title' => 'Widget Playground',
                'subtitle' => 'Test & embed chat',
                'badge' => 'HTML Ready',
                'badgeColor' => 'bg-amber-50 text-amber-700 ring-amber-200',
                'iconBg' => 'bg-amber-500 text-white',
                'icon' => 'widget',
                'href' => route('chat.demo'),
                'external' => true,
            ],
            [
                'title' => 'Staff & Permissions',
                'subtitle' => 'Agents, shifts & roles',
                'badge' => 'Team',
                'badgeColor' => 'bg-slate-100 text-slate-700 ring-slate-200',
                'iconBg' => 'bg-slate-700 text-white',
                'icon' => 'staff',
                'href' => route('admin.staff'),
            ],
        ];
    @endphp

    <div class="space-y-6">
        <!-- Minimal Top Control Header -->
        <div class="flex flex-col gap-4 rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-violet-600 to-indigo-700 text-lg font-black text-white shadow-xs">
                    {{ strtoupper(substr($tenantName, 0, 2)) }}
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-xl font-bold tracking-tight text-slate-900">{{ $tenantName }}</h1>
                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2 py-0.5 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-600/20">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            Live
                        </span>
                    </div>
                    <p class="text-xs font-medium text-slate-400">{{ $tenantType }} · Production Console</p>
                </div>
            </div>

            <!-- Quick Action Buttons -->
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('admin.knowledge-base') }}" class="inline-flex items-center gap-2 rounded-xl bg-violet-600 px-3.5 py-2 text-xs font-semibold text-white shadow-xs hover:bg-violet-700 transition">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Upload Document
                </a>
                <a href="{{ route('admin.chat') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-100 transition">
                    <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a.75.75 0 0 1-.974-.94 4.09 4.09 0 0 0 .546-2.127C3.308 16.326 2 14.307 2 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z" />
                    </svg>
                    Chat Queue
                </a>
                <a href="{{ route('chat.demo') }}" target="_blank" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition">
                    <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                    </svg>
                    Playground
                </a>
            </div>
        </div>

        <!-- 4 Visual KPI Metric Cards -->
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <!-- 1. Answered Queries -->
            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs transition hover:border-slate-300">
                <div class="flex items-center justify-between">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </div>
                    <span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-xs font-semibold {{ $kpis[0]['deltaClass'] }}">
                        {{ $kpis[0]['delta'] }}
                    </span>
                </div>
                <div class="mt-4">
                    <div class="text-2xl font-black tracking-tight text-slate-900">{{ $kpis[0]['value'] }}</div>
                    <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider mt-0.5">{{ $kpis[0]['label'] }}</div>
                </div>
            </div>

            <!-- 2. Unanswered Queries -->
            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs transition hover:border-slate-300">
                <div class="flex items-center justify-between">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                        </svg>
                    </div>
                    <span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-xs font-semibold {{ $kpis[1]['deltaClass'] }}">
                        {{ $kpis[1]['delta'] }}
                    </span>
                </div>
                <div class="mt-4">
                    <div class="text-2xl font-black tracking-tight text-slate-900">{{ $kpis[1]['value'] }}</div>
                    <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider mt-0.5">{{ $kpis[1]['label'] }}</div>
                </div>
            </div>

            <!-- 3. Human Routed Queries -->
            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs transition hover:border-slate-300">
                <div class="flex items-center justify-between">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                        </svg>
                    </div>
                    <span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-xs font-semibold {{ $kpis[2]['deltaClass'] }}">
                        {{ $kpis[2]['delta'] }}
                    </span>
                </div>
                <div class="mt-4">
                    <div class="text-2xl font-black tracking-tight text-slate-900">{{ $kpis[2]['value'] }}</div>
                    <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider mt-0.5">{{ $kpis[2]['label'] }}</div>
                </div>
            </div>

            <!-- 4. Knowledge Base Files -->
            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs transition hover:border-slate-300">
                <div class="flex items-center justify-between">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-50 text-violet-600">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                        </svg>
                    </div>
                    <span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-xs font-semibold {{ $kpis[3]['deltaClass'] }}">
                        {{ $kpis[3]['delta'] }}
                    </span>
                </div>
                <div class="mt-4">
                    <div class="text-2xl font-black tracking-tight text-slate-900">{{ $kpis[3]['value'] }}</div>
                    <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider mt-0.5">{{ $kpis[3]['label'] }}</div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid gap-6 xl:grid-cols-[1.6fr_1fr]">
            
            <!-- Left Column: Visual AI Distribution & Action Hub -->
            <div class="space-y-6">
                
                <!-- AI Resolution & Routing Distribution Bar -->
                <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-xs">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-base font-bold text-slate-900">Query Resolution Breakdown</h2>
                            <p class="text-xs text-slate-400 mt-0.5">Automated AI vs human operator routing</p>
                        </div>
                        <span class="inline-flex items-center gap-1 rounded-xl bg-violet-50 px-2.5 py-1 text-xs font-bold text-violet-700">
                            {{ $aiRate }}% AI Automated
                        </span>
                    </div>

                    <!-- Progress Distribution Segment -->
                    <div class="mt-5 h-3.5 w-full overflow-hidden rounded-full bg-slate-100 flex">
                        <div style="width: {{ $aiRate }}%" class="h-full bg-emerald-500 transition-all duration-500" title="AI Answered: {{ $aiRate }}%"></div>
                        <div style="width: {{ $humanRate }}%" class="h-full bg-blue-500 transition-all duration-500" title="Human Routed: {{ $humanRate }}%"></div>
                        <div style="width: {{ $unansweredRate }}%" class="h-full bg-amber-400 transition-all duration-500" title="Unanswered: {{ $unansweredRate }}%"></div>
                    </div>

                    <!-- Legend Stats -->
                    <div class="mt-4 grid grid-cols-3 gap-2 pt-2 border-t border-slate-100 text-center sm:text-left">
                        <div class="flex items-center gap-2">
                            <span class="h-2.5 w-2.5 shrink-0 rounded-full bg-emerald-500"></span>
                            <div>
                                <span class="text-xs font-bold text-slate-800">{{ $answeredNum }}</span>
                                <span class="block text-[10px] font-semibold text-slate-400 uppercase">AI Answered</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="h-2.5 w-2.5 shrink-0 rounded-full bg-blue-500"></span>
                            <div>
                                <span class="text-xs font-bold text-slate-800">{{ $humanRoutedNum }}</span>
                                <span class="block text-[10px] font-semibold text-slate-400 uppercase">Human Routed</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="h-2.5 w-2.5 shrink-0 rounded-full bg-amber-400"></span>
                            <div>
                                <span class="text-xs font-bold text-slate-800">{{ $unansweredNum }}</span>
                                <span class="block text-[10px] font-semibold text-slate-400 uppercase">Unanswered</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fast Action Modules Grid (Icon & Badge Driven) -->
                <div>
                    <div class="flex items-center justify-between mb-3 px-1">
                        <h2 class="text-sm font-bold uppercase tracking-wider text-slate-400">Operational Modules</h2>
                        <span class="text-xs font-medium text-slate-400">5 Modules Ready</span>
                    </div>

                    <div class="grid gap-3 sm:grid-cols-2">
                        @foreach ($modules as $module)
                            <a href="{{ $module['href'] }}"
                                @if (!empty($module['external'])) target="_blank" rel="noopener noreferrer" @endif
                                class="group flex items-center justify-between rounded-2xl border border-slate-200/80 bg-white p-4 shadow-xs transition hover:-translate-y-0.5 hover:border-violet-300 hover:shadow-md">
                                <div class="flex items-center gap-3.5">
                                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl {{ $module['iconBg'] }} shadow-xs transition group-hover:scale-105">
                                        @if ($module['icon'] === 'kb')
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                                            </svg>
                                        @elseif ($module['icon'] === 'chat')
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                                            </svg>
                                        @elseif ($module['icon'] === 'analytics')
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                                            </svg>
                                        @elseif ($module['icon'] === 'widget')
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75 22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3-4.5 16.5" />
                                            </svg>
                                        @else
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                                            </svg>
                                        @endif
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-bold text-slate-900 group-hover:text-violet-600 transition">{{ $module['title'] }}</h3>
                                        <p class="text-xs text-slate-400">{{ $module['subtitle'] }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="hidden sm:inline-flex rounded-full px-2 py-0.5 text-[10px] font-semibold ring-1 {{ $module['badgeColor'] }}">
                                        {{ $module['badge'] }}
                                    </span>
                                    @if (!empty($module['external']))
                                        <svg class="h-4 w-4 text-slate-300 transition group-hover:scale-110 group-hover:text-violet-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                                        </svg>
                                    @else
                                        <svg class="h-4 w-4 text-slate-300 transition group-hover:translate-x-0.5 group-hover:text-violet-600" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                        </svg>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right Column: Live Timeline & System Health -->
            <div class="space-y-6">
                
                <!-- System Health Indicator Widget -->
                <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">System Status</span>
                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-600">
                            <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                            All Systems Optimal
                        </span>
                    </div>

                    <div class="mt-4 space-y-2.5 text-xs">
                        <div class="flex items-center justify-between py-1 border-b border-slate-100">
                            <span class="text-slate-500">AI Assistant Engine</span>
                            <span class="font-semibold text-slate-800">Online · RAG Active</span>
                        </div>
                        <div class="flex items-center justify-between py-1 border-b border-slate-100">
                            <span class="text-slate-500">Knowledge Ingestion</span>
                            <span class="font-semibold text-slate-800">Synchronized</span>
                        </div>
                        <div class="flex items-center justify-between py-1">
                            <span class="text-slate-500">Avg Response Time</span>
                            <span class="font-semibold text-emerald-600">&lt; 1.2s</span>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity Timeline -->
                <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-base font-bold text-slate-900">Recent Activity</h2>
                        <span class="text-xs font-medium text-slate-400">Realtime</span>
                    </div>

                    <div class="space-y-3">
                        @forelse ($recentActivities as $activity)
                            <div class="flex items-start gap-3 rounded-xl bg-slate-50/70 p-3 transition hover:bg-slate-50">
                                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-violet-100 text-violet-700 text-xs font-bold">
                                    {{ strtoupper(substr($activity->user?->name ?? 'SYS', 0, 2)) }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="text-xs font-bold text-slate-800 truncate">{{ $activity->event }}</div>
                                    <div class="mt-0.5 text-[11px] text-slate-400 truncate">
                                        {{ $activity->file_name ? 'File: '.$activity->file_name : 'By: '.($activity->user?->name ?? 'System') }}
                                    </div>
                                </div>
                                <span class="text-[10px] font-medium text-slate-400 shrink-0">
                                    {{ $activity->created_at->diffForHumans(null, true, true) }}
                                </span>
                            </div>
                        @empty
                            <div class="text-center py-6 text-slate-400">
                                <svg class="mx-auto h-8 w-8 text-slate-300 mb-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                <p class="text-xs font-semibold">No recent activity</p>
                                <p class="text-[11px] text-slate-400 mt-0.5">Logged actions will appear here.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection