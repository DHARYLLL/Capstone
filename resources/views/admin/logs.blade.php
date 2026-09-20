@extends('layouts.admin')

@section('page_title', 'Activity Logs')
@section('breadcrumbs', 'Admin / Activity Logs')

@section('content')
<div class="space-y-8">

    {{-- Page header --}}
    <section class="rounded-[2rem] border border-[#e2e8f0] bg-white p-6 shadow-sm lg:p-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                    <h1 class="text-3xl font-black tracking-tight text-gray-900">Activity Logs</h1>
                <p class="mt-2 max-w-3xl text-sm leading-6 text-gray-500">
                    A record of all upload events, processing steps, indexing outcomes, and approval actions for the waterproofing company.
                </p>
            </div>
        </div>
    </section>

    {{-- Stats strip --}}
    <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ([
            ['label' => 'Total uploads',    'value' => $totalUploads,  'sub' => 'All time'],
            ['label' => 'Indexed today',    'value' => $indexedToday,  'sub' => 'Last 24 h'],
            ['label' => 'Failed / Rejected','value' => $failedCount,   'sub' => 'Needs review'],
            ['label' => 'Pending approval', 'value' => $pendingCount,  'sub' => 'Staged files'],
        ] as $stat)
        <div class="card bg-base-100 shadow-sm">
            <div class="card-body p-5">
                <div class="text-sm font-medium text-gray-500">{{ $stat['label'] }}</div>
                <div class="mt-2 text-4xl font-black text-gray-900">{{ $stat['value'] }}</div>
                <p class="mt-2 text-sm text-gray-400">{{ $stat['sub'] }}</p>
            </div>
        </div>
        @endforeach
    </section>

    {{-- Log table --}}
    <section class="card bg-base-100 shadow-sm">
        <div class="card-body p-6 lg:p-8">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Activity Log</h2>
                    <p class="mt-1 text-sm text-gray-500">All events are recorded automatically on upload, review, and indexing.</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    @foreach (['All' => null, 'Indexed' => 'indexed', 'Staged' => 'staged', 'Failed' => 'failed', 'Deleted' => 'deleted'] as $label => $filterKey)
                    <a href="{{ route('admin.logs', $filterKey ? ['filter' => $filterKey] : []) }}"
                        class="btn btn-sm rounded-full {{ $filter === $filterKey || ($filterKey === null && $filter === '') ? 'border-0 bg-[#1e293b] text-white' : 'btn-outline border-gray-300 text-gray-600' }}">
                        {{ $label }}
                    </a>
                    @endforeach
                </div>
            </div>

            <div class="mt-6 overflow-x-auto">
                <table class="table table-zebra text-sm">
                    <thead>
                        <tr class="text-xs font-semibold uppercase tracking-wider text-gray-400">
                            <th>Timestamp</th>
                            <th>File</th>
                            <th>Division</th>
                            <th>Event</th>
                            <th>Status</th>
                            <th>User</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logs as $log)
                        <tr>
                            <td class="whitespace-nowrap text-gray-400">{{ $log->created_at->isToday() ? 'Today '.$log->created_at->format('g:i A') : $log->created_at->diffForHumans() }}</td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <svg viewBox="0 0 24 24" class="h-4 w-4 shrink-0 fill-gray-400"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zm-1 1.5L18.5 9H13V3.5zM6 20V4h5v7h7v9H6z"/></svg>
                                    <span class="font-medium text-gray-800">{{ $log->file_name ?? '—' }}</span>
                                </div>
                            </td>
                            <td class="text-gray-600">{{ $log->division }}</td>
                            <td class="text-gray-700">{{ $log->event }}</td>
                            <td>
                                @if ($log->status === 'Done')
                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-200">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Done
                                    </span>
                                @elseif ($log->status === 'Pending')
                                    <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700 ring-1 ring-amber-200">
                                        <span class="h-1.5 w-1.5 rounded-full bg-amber-400"></span> Pending
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-full bg-rose-50 px-2.5 py-1 text-xs font-semibold text-rose-700 ring-1 ring-rose-200">
                                        <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span> Failed
                                    </span>
                                @endif
                            </td>
                            <td class="text-gray-500">{{ $log->user?->name ?? 'System' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="py-8 text-center text-sm text-gray-400">No activity logs found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4 flex flex-col gap-3 border-t border-gray-100 pt-4 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-xs text-gray-500">Showing <strong>{{ $logs->count() }}</strong> of <strong>{{ $logs->total() }}</strong> entries</p>
                {{ $logs->appends(request()->query())->links() }}
            </div>
        </div>
    </section>

</div>
@endsection
