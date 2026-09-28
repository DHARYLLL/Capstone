@extends('layouts.admin')

@section('page_title', 'Activity & Ingestion Logs')
@section('breadcrumbs', 'Admin / Activity Logs')

@section('content')
<div class="space-y-8">

    {{-- Page Header Banner --}}
    <section class="relative overflow-hidden rounded-[2rem] border border-slate-200/80 bg-gradient-to-br from-white via-slate-50 to-purple-50/40 p-6 shadow-sm lg:p-8">
        <div class="absolute -right-16 -top-16 h-64 w-64 rounded-full bg-purple-500/5 blur-3xl pointer-events-none"></div>
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between relative z-10">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-purple-50 border border-purple-200/60 text-purple-700 text-xs font-bold tracking-wide uppercase mb-3">
                    <span class="w-2 h-2 rounded-full bg-purple-600 animate-pulse"></span>
                    Audit Trail &amp; Pipeline Telemetry
                </div>
                <h1 class="text-3xl font-black tracking-tight text-slate-900">Activity &amp; Ingestion Logs</h1>
                <p class="mt-1.5 max-w-2xl text-sm leading-relaxed text-slate-500">
                    Chronological audit ledger of document staging, vector embedding generations, knowledge index updates, and operator interactions.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3.5 py-1.5 text-xs font-bold text-emerald-700 ring-1 ring-emerald-200">
                    <span class="h-2 w-2 rounded-full bg-emerald-500 animate-ping"></span>
                    Audit Stream Live
                </span>
            </div>
        </div>
    </section>

    {{-- Stats Cards Strip --}}
    <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @php
            $statsCards = [
                [
                    'label' => 'Total Uploads',
                    'value' => $totalUploads,
                    'sub' => 'All time files logged',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />',
                    'accent' => 'bg-purple-50 text-purple-600 border-purple-100',
                ],
                [
                    'label' => 'Indexed Today',
                    'value' => $indexedToday,
                    'sub' => 'Active vector embeddings',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />',
                    'accent' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                ],
                [
                    'label' => 'Pending Staged',
                    'value' => $pendingCount,
                    'sub' => 'Awaiting approval/training',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />',
                    'accent' => 'bg-amber-50 text-amber-600 border-amber-100',
                ],
                [
                    'label' => 'Failed / Rejected',
                    'value' => $failedCount,
                    'sub' => 'Ingestion review required',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />',
                    'accent' => 'bg-rose-50 text-rose-600 border-rose-100',
                ],
            ];
        @endphp

        @foreach ($statsCards as $stat)
            <div class="group relative overflow-hidden rounded-3xl border border-slate-200/80 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-purple-300 hover:shadow-lg hover:shadow-purple-500/5">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block">{{ $stat['label'] }}</span>
                        <div class="mt-2 text-3xl font-black text-slate-900 tracking-tight">{{ $stat['value'] }}</div>
                        <p class="mt-1 text-xs font-medium text-slate-500">{{ $stat['sub'] }}</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl border {{ $stat['accent'] }} transition-transform duration-300 group-hover:scale-110">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            {!! $stat['icon'] !!}
                        </svg>
                    </div>
                </div>
            </div>
        @endforeach
    </section>

    {{-- Log Table Section --}}
    <section class="rounded-3xl border border-slate-200/80 bg-white shadow-sm overflow-hidden">
        {{-- Table Toolbar --}}
        <div class="p-6 lg:p-7 border-b border-slate-100">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Event Audit Trail</h2>
                    <p class="mt-0.5 text-xs text-slate-400">Filter records by processing status or search specific file names.</p>
                </div>

                {{-- Status Filter Chips & Search Bar --}}
                <div class="flex flex-wrap items-center gap-3">
                    {{-- Search Input --}}
                    <div class="relative min-w-[220px]">
                        <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" id="log-search-input" placeholder="Search file or division..."
                            class="w-full rounded-full border border-slate-200 bg-slate-50/50 py-1.5 pl-9 pr-4 text-xs text-slate-800 placeholder-slate-400 transition focus:border-purple-600 focus:bg-white focus:outline-none" />
                    </div>

                    {{-- Filter Tabs --}}
                    <div class="inline-flex rounded-full bg-slate-100 p-1">
                        @foreach (['All' => null, 'Indexed' => 'indexed', 'Staged' => 'staged', 'Failed' => 'failed', 'Deleted' => 'deleted'] as $label => $filterKey)
                            <a href="{{ route('admin.logs', $filterKey ? ['filter' => $filterKey] : []) }}"
                                class="px-3 py-1 text-xs font-bold rounded-full transition-all {{ $filter === $filterKey || ($filterKey === null && $filter === '') ? 'bg-purple-600 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                                {{ $label }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Table Element --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm" id="logs-table">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/70 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                        <th class="px-6 py-3.5">Timestamp</th>
                        <th class="px-6 py-3.5">Target Document</th>
                        <th class="px-6 py-3.5">Business Unit / Division</th>
                        <th class="px-6 py-3.5">Action Event</th>
                        <th class="px-6 py-3.5">Status</th>
                        <th class="px-6 py-3.5">Actor</th>
                        <th class="px-6 py-3.5 text-right">Details</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($logs as $log)
                        <tr class="log-row group transition-colors hover:bg-purple-50/30"
                            data-log-id="{{ $log->id }}"
                            data-filename="{{ $log->file_name ?? '—' }}"
                            data-division="{{ $log->division }}"
                            data-event="{{ $log->event }}"
                            data-status="{{ $log->status }}"
                            data-user="{{ $log->user?->name ?? 'System Automated' }}"
                            data-time="{{ $log->created_at->format('M d, Y · g:i:s A') }}"
                            data-diff="{{ $log->created_at->diffForHumans() }}">
                            
                            <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-500 font-medium">
                                <div class="font-semibold text-slate-800">{{ $log->created_at->isToday() ? 'Today at '.$log->created_at->format('g:i A') : $log->created_at->format('M d, Y') }}</div>
                                <div class="text-[11px] text-slate-400">{{ $log->created_at->diffForHumans() }}</div>
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2.5">
                                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-purple-50 text-purple-600">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <span class="font-bold text-slate-900 line-clamp-1 max-w-[240px] text-xs">
                                        {{ $log->file_name ?? 'Direct Manual Knowledge Entry' }}
                                    </span>
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700">
                                    {{ $log->division ?: 'General' }}
                                </span>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-800">
                                    @if(str_contains(strtolower($log->event), 'indexed'))
                                        <span class="h-2 w-2 rounded-full bg-purple-600"></span>
                                    @elseif(str_contains(strtolower($log->event), 'upload'))
                                        <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                                    @elseif(str_contains(strtolower($log->event), 'delete'))
                                        <span class="h-2 w-2 rounded-full bg-rose-500"></span>
                                    @else
                                        <span class="h-2 w-2 rounded-full bg-amber-500"></span>
                                    @endif
                                    {{ $log->event }}
                                </span>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($log->status === 'Done')
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-700 ring-1 ring-emerald-200">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                        Completed
                                    </span>
                                @elseif ($log->status === 'Pending')
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-2.5 py-1 text-xs font-bold text-amber-700 ring-1 ring-amber-200">
                                        <span class="h-1.5 w-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                                        Processing
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-rose-50 px-2.5 py-1 text-xs font-bold text-rose-700 ring-1 ring-rose-200">
                                        <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span>
                                        {{ $log->status }}
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-600 font-medium">
                                <div class="flex items-center gap-1.5">
                                    <div class="h-5 w-5 rounded-full bg-purple-100 text-purple-700 font-black text-[10px] flex items-center justify-center">
                                        {{ strtoupper(substr($log->user?->name ?? 'S', 0, 1)) }}
                                    </div>
                                    <span>{{ $log->user?->name ?? 'System AI' }}</span>
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <button type="button" onclick="openLogModal(this)"
                                    class="inline-flex items-center gap-1 rounded-full border border-slate-200 bg-white px-3 py-1 text-xs font-bold text-slate-700 shadow-sm transition hover:border-purple-300 hover:bg-purple-50 hover:text-purple-700">
                                    <span>Inspect</span>
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-sm text-slate-400">
                                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-400 mb-3">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <p class="font-bold text-slate-700">No activity logs found for this filter criteria.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($logs->hasPages())
            <div class="border-t border-slate-100 p-4 sm:px-6">
                {{ $logs->links() }}
            </div>
        @endif
    </section>
</div>

{{-- Log Detail Inspection Modal --}}
<div id="log-detail-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeLogModal()"></div>
    
    <div class="relative w-full max-w-lg overflow-hidden rounded-3xl bg-white shadow-2xl ring-1 ring-slate-900/10">
        <div class="border-b border-slate-100 bg-slate-50/70 px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-purple-50 text-purple-600 font-bold">
                    📋
                </div>
                <h3 class="text-base font-bold text-slate-900">Log Entry Details</h3>
            </div>
            <button type="button" onclick="closeLogModal()" class="rounded-full p-1.5 text-slate-400 hover:bg-slate-200/60 hover:text-slate-600">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="p-6 space-y-4 text-xs">
            <div class="grid grid-cols-2 gap-3">
                <div class="rounded-2xl border border-slate-100 bg-slate-50/60 p-3">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Log Reference ID</span>
                    <span id="modal-log-id" class="mt-1 font-mono font-bold text-slate-800 text-sm block">#0</span>
                </div>
                <div class="rounded-2xl border border-slate-100 bg-slate-50/60 p-3">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Current Status</span>
                    <span id="modal-log-status" class="mt-1 font-bold text-slate-800 text-sm block">Done</span>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-100 bg-slate-50/60 p-3.5 space-y-2.5">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Document Name</span>
                    <span id="modal-log-file" class="font-semibold text-slate-900 text-sm block mt-0.5 break-all">—</span>
                </div>
                <div class="grid grid-cols-2 gap-2 pt-2 border-t border-slate-200/60">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Division / Scope</span>
                        <span id="modal-log-division" class="font-semibold text-slate-800 block mt-0.5">—</span>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Initiated By</span>
                        <span id="modal-log-user" class="font-semibold text-slate-800 block mt-0.5">—</span>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-100 bg-slate-50/60 p-3.5">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Pipeline Lifecycle &amp; Timestamp</span>
                <div class="flex items-center justify-between text-slate-700">
                    <span id="modal-log-event" class="font-bold text-purple-700">Event</span>
                    <span id="modal-log-time" class="text-slate-500 font-mono text-[11px]">Timestamp</span>
                </div>
            </div>
        </div>

        <div class="border-t border-slate-100 bg-slate-50/50 px-6 py-4 flex justify-end">
            <button type="button" onclick="closeLogModal()"
                class="rounded-full bg-purple-600 px-5 py-2 text-xs font-bold text-white shadow-sm transition hover:bg-purple-700">
                Close Inspection
            </button>
        </div>
    </div>
</div>

<script>
    // Live Search Filter for Logs Table
    document.getElementById('log-search-input')?.addEventListener('input', function(e) {
        const query = e.target.value.toLowerCase().trim();
        const rows = document.querySelectorAll('.log-row');
        
        rows.forEach(row => {
            const filename = (row.dataset.filename || '').toLowerCase();
            const division = (row.dataset.division || '').toLowerCase();
            const event = (row.dataset.event || '').toLowerCase();
            const user = (row.dataset.user || '').toLowerCase();

            if (filename.includes(query) || division.includes(query) || event.includes(query) || user.includes(query)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    function openLogModal(btn) {
        const row = btn.closest('.log-row');
        if (!row) return;

        document.getElementById('modal-log-id').innerText = '#' + row.dataset.logId;
        document.getElementById('modal-log-status').innerText = row.dataset.status;
        document.getElementById('modal-log-file').innerText = row.dataset.filename;
        document.getElementById('modal-log-division').innerText = row.dataset.division || 'General';
        document.getElementById('modal-log-user').innerText = row.dataset.user;
        document.getElementById('modal-log-event').innerText = row.dataset.event;
        document.getElementById('modal-log-time').innerText = row.dataset.time;

        const modal = document.getElementById('log-detail-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeLogModal() {
        const modal = document.getElementById('log-detail-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>
@endsection
