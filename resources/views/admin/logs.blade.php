@extends('layouts.admin')

@section('page_title', 'Activity & Ingestion Logs')
@section('breadcrumbs', 'Admin / Activity Logs')

@section('content')
<div class="space-y-6">

    {{-- Page Header Banner --}}
    <section class="relative overflow-hidden rounded-2xl border border-[#232934] bg-[#14181F] p-5 sm:p-6 lg:p-7 shadow-sm">
        <div class="absolute -right-16 -top-16 h-64 w-64 rounded-full bg-[#D8210B]/5 blur-3xl pointer-events-none"></div>
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between relative z-10">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#D8210B]/10 border border-[#D8210B]/30 text-[#EE4E56] text-xs font-bold tracking-wide uppercase mb-3">
                    <span class="w-2 h-2 rounded-full bg-[#D8210B] animate-pulse"></span>
                    Audit Trail &amp; Pipeline Telemetry
                </div>
                <h1 class="text-2xl font-bold tracking-tight text-[#F0EDE8]">Activity &amp; Ingestion Logs</h1>
                <p class="mt-1 max-w-2xl text-xs font-medium leading-relaxed text-[#9AA0AB]">
                    Chronological audit ledger of document staging, vector embedding generations, knowledge index updates, and operator interactions.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-500/10 border border-emerald-500/30 px-3.5 py-1.5 text-xs font-bold text-emerald-400">
                    <span class="h-2 w-2 rounded-full bg-emerald-500 animate-ping"></span>
                    Audit Stream Live
                </span>
            </div>
        </div>
    </section>

    {{-- Stats Cards Strip (Consistent horizontal KPI media-object layout) --}}
    <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @php
            $statsCards = [
                [
                    'label' => 'Total Uploads',
                    'value' => $totalUploads,
                    'sub' => 'All time files logged',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />',
                    'borderClass' => 'border-[#D8210B]/60 text-[#EE4E56]',
                ],
                [
                    'label' => 'Indexed Today',
                    'value' => $indexedToday,
                    'sub' => 'Active vector embeddings',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />',
                    'borderClass' => 'border-emerald-500/60 text-emerald-400',
                ],
                [
                    'label' => 'Pending Staged',
                    'value' => $pendingCount,
                    'sub' => 'Awaiting approval/training',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />',
                    'borderClass' => 'border-[#F2D742]/60 text-[#F2D742]',
                ],
                [
                    'label' => 'Failed / Rejected',
                    'value' => $failedCount,
                    'sub' => 'Ingestion review required',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />',
                    'borderClass' => 'border-[#EE4E56]/60 text-[#EE4E56]',
                ],
            ];
        @endphp

        @foreach ($statsCards as $stat)
            <div class="flex items-center gap-3.5 rounded-2xl border border-[#232934] bg-[#14181F] p-4.5 sm:p-5 shadow-sm transition hover:border-[#2B313D]">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border {{ $stat['borderClass'] }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        {!! $stat['icon'] !!}
                    </svg>
                </div>
                <div class="min-w-0">
                    <div class="text-2xl font-black tracking-tight text-[#F0EDE8] leading-tight">{{ $stat['value'] }}</div>
                    <div class="text-xs font-semibold text-[#6B7280] uppercase tracking-wider mt-0.5 truncate">{{ $stat['label'] }}</div>
                </div>
            </div>
        @endforeach
    </section>

    {{-- Log Table Section --}}
    <section class="rounded-2xl border border-[#232934] bg-[#14181F] shadow-sm overflow-hidden">
        {{-- Table Toolbar --}}
        <div class="p-5 sm:p-6 border-b border-[#232934]">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-base font-bold text-[#F0EDE8]">Event Audit Trail</h2>
                    <p class="mt-0.5 text-xs text-[#6B7280]">Filter records by processing status or search specific file names.</p>
                </div>

                {{-- Status Filter Chips & Search Bar --}}
                <div class="flex flex-wrap items-center gap-2.5">
                    {{-- Search Input --}}
                    <div class="relative min-w-[220px]">
                        <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 h-4 w-4 text-[#6B7280]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" id="log-search-input" placeholder="Search file or division..."
                            class="w-full rounded-xl border border-[#232934] bg-[#1A1F29] py-2 pl-9 pr-4 text-xs text-[#F0EDE8] placeholder-[#6B7280] transition focus:border-[#D8210B] focus:outline-none" />
                    </div>

                    {{-- Filter Tabs --}}
                    <div class="inline-flex rounded-xl bg-[#1A1F29] border border-[#232934] p-1">
                        @foreach (['All' => null, 'Indexed' => 'indexed', 'Staged' => 'staged', 'Failed' => 'failed', 'Deleted' => 'deleted'] as $label => $filterKey)
                            <a href="{{ route('admin.logs', $filterKey ? ['filter' => $filterKey] : []) }}"
                                class="px-3 py-1 text-xs font-bold rounded-lg transition-all {{ $filter === $filterKey || ($filterKey === null && $filter === '') ? 'bg-[#D8210B] text-white shadow-sm' : 'text-[#9AA0AB] hover:text-[#F0EDE8]' }}">
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
                    <tr class="border-b border-[#232934] bg-[#1A1F29]/60 text-[11px] font-bold uppercase tracking-wider text-[#6B7280]">
                        <th class="px-6 py-3.5">Timestamp</th>
                        <th class="px-6 py-3.5">Target Document</th>
                        <th class="px-6 py-3.5">Business Unit / Division</th>
                        <th class="px-6 py-3.5">Action Event</th>
                        <th class="px-6 py-3.5">Status</th>
                        <th class="px-6 py-3.5">Actor</th>
                        <th class="px-6 py-3.5 text-right">Details</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#232934]">
                    @forelse ($logs as $log)
                        <tr class="log-row group transition-colors hover:bg-[#1A1F29]/70"
                            data-log-id="{{ $log->id }}"
                            data-filename="{{ $log->file_name ?? '—' }}"
                            data-division="{{ $log->division }}"
                            data-event="{{ $log->event }}"
                            data-status="{{ $log->status }}"
                            data-user="{{ $log->user?->name ?? 'System Automated' }}"
                            data-time="{{ $log->created_at->format('M d, Y · g:i:s A') }}"
                            data-diff="{{ $log->created_at->diffForHumans() }}">
                            
                            <td class="px-6 py-4 whitespace-nowrap text-xs text-[#9AA0AB] font-medium">
                                <div class="font-semibold text-[#F0EDE8]">{{ $log->created_at->isToday() ? 'Today at '.$log->created_at->format('g:i A') : $log->created_at->format('M d, Y') }}</div>
                                <div class="text-[11px] text-[#6B7280]">{{ $log->created_at->diffForHumans() }}</div>
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2.5">
                                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-[#D8210B]/20 border border-[#D8210B]/40 text-[#EE4E56]">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <span class="font-bold text-[#F0EDE8] line-clamp-1 max-w-[240px] text-xs">
                                        {{ $log->file_name ?? 'Direct Manual Knowledge Entry' }}
                                    </span>
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-[#1A1F29] border border-[#232934] px-2.5 py-1 text-xs font-semibold text-[#9AA0AB]">
                                    {{ $log->division ?: 'General' }}
                                </span>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 text-xs font-bold text-[#F0EDE8]">
                                    @if(str_contains(strtolower($log->event), 'indexed'))
                                        <span class="h-2 w-2 rounded-full bg-[#D8210B]"></span>
                                    @elseif(str_contains(strtolower($log->event), 'upload'))
                                        <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                                    @elseif(str_contains(strtolower($log->event), 'delete'))
                                        <span class="h-2 w-2 rounded-full bg-rose-500"></span>
                                    @else
                                        <span class="h-2 w-2 rounded-full bg-[#F2D742]"></span>
                                    @endif
                                    {{ $log->event }}
                                </span>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($log->status === 'Done')
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-500/10 border border-emerald-500/30 px-2.5 py-1 text-xs font-bold text-emerald-400">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                        Completed
                                    </span>
                                @elseif ($log->status === 'Pending')
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-[#F2D742]/10 border border-[#F2D742]/30 px-2.5 py-1 text-xs font-bold text-[#F2D742]">
                                        <span class="h-1.5 w-1.5 rounded-full bg-[#F2D742] animate-pulse"></span>
                                        Processing
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-[#D8210B]/10 border border-[#D8210B]/30 px-2.5 py-1 text-xs font-bold text-[#EE4E56]">
                                        <span class="h-1.5 w-1.5 rounded-full bg-[#D8210B]"></span>
                                        {{ $log->status }}
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-xs text-[#9AA0AB] font-medium">
                                <div class="flex items-center gap-1.5">
                                    <div class="h-5 w-5 rounded-full bg-[#D8210B]/20 border border-[#D8210B]/40 text-[#EE4E56] font-black text-[10px] flex items-center justify-center">
                                        {{ strtoupper(substr($log->user?->name ?? 'S', 0, 1)) }}
                                    </div>
                                    <span class="text-[#F0EDE8]">{{ $log->user?->name ?? 'System AI' }}</span>
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <button type="button" onclick="openLogModal(this)"
                                    class="inline-flex items-center gap-1 rounded-xl border border-[#232934] bg-[#1A1F29] px-3 py-1.5 text-xs font-bold text-[#F0EDE8] shadow-sm transition hover:border-[#D8210B] hover:text-[#EE4E56]">
                                    <span>Inspect</span>
                                    <svg class="h-3.5 w-3.5 text-[#6B7280]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-sm text-[#6B7280]">
                                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-[#1A1F29] border border-[#232934] text-[#6B7280] mb-3">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <p class="font-bold text-[#F0EDE8]">No activity logs found for this filter criteria.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($logs->hasPages())
            <div class="border-t border-[#232934] p-4 sm:px-6">
                {{ $logs->links() }}
            </div>
        @endif
    </section>
</div>

{{-- Log Detail Inspection Modal --}}
<div id="log-detail-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-black/70 backdrop-blur-xs transition-opacity" onclick="closeLogModal()"></div>
    
    <div class="relative w-full max-w-lg overflow-hidden rounded-2xl border border-[#232934] bg-[#14181F] shadow-2xl">
        <div class="border-b border-[#232934] bg-[#14181F] px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-[#D8210B]/20 border border-[#D8210B]/40 text-[#EE4E56] font-bold">
                    📋
                </div>
                <h3 class="text-base font-bold text-[#F0EDE8]">Log Entry Details</h3>
            </div>
            <button type="button" onclick="closeLogModal()" class="rounded-xl p-1.5 text-[#6B7280] hover:bg-[#1A1F29] hover:text-[#F0EDE8] transition">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="p-6 space-y-4 text-xs">
            <div class="grid grid-cols-2 gap-3">
                <div class="rounded-xl border border-[#232934] bg-[#1A1F29] p-3">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-[#6B7280] block">Log Reference ID</span>
                    <span id="modal-log-id" class="mt-1 font-mono font-bold text-[#F0EDE8] text-sm block">#0</span>
                </div>
                <div class="rounded-xl border border-[#232934] bg-[#1A1F29] p-3">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-[#6B7280] block">Current Status</span>
                    <span id="modal-log-status" class="mt-1 font-bold text-[#F0EDE8] text-sm block">Done</span>
                </div>
            </div>

            <div class="rounded-xl border border-[#232934] bg-[#1A1F29] p-3.5 space-y-2.5">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-[#6B7280] block">Document Name</span>
                    <span id="modal-log-file" class="font-semibold text-[#F0EDE8] text-sm block mt-0.5 break-all">—</span>
                </div>
                <div class="grid grid-cols-2 gap-2 pt-2 border-t border-[#232934]">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-[#6B7280] block">Division / Scope</span>
                        <span id="modal-log-division" class="font-semibold text-[#F0EDE8] block mt-0.5">—</span>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-[#6B7280] block">Initiated By</span>
                        <span id="modal-log-user" class="font-semibold text-[#F0EDE8] block mt-0.5">—</span>
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-[#232934] bg-[#1A1F29] p-3.5">
                <span class="text-[10px] font-bold uppercase tracking-wider text-[#6B7280] block mb-1">Pipeline Lifecycle &amp; Timestamp</span>
                <div class="flex items-center justify-between text-[#9AA0AB]">
                    <span id="modal-log-event" class="font-bold text-[#EE4E56]">Event</span>
                    <span id="modal-log-time" class="text-[#6B7280] font-mono text-[11px]">Timestamp</span>
                </div>
            </div>
        </div>

        <div class="border-t border-[#232934] bg-[#14181F] px-6 py-4 flex justify-end">
            <button type="button" onclick="closeLogModal()"
                class="rounded-xl bg-[#D8210B] px-5 py-2 text-xs font-bold text-white shadow-sm transition hover:bg-[#EE4E56]">
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
