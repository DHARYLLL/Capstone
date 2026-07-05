{{-- filepath: resources/views/admin/businesses/logs.blade.php --}}
@php $isManager = isset($slug); @endphp
@if($isManager)
    @php
        if ($slug === 'villa-carmelita') {
            $unitName = 'Villa Carmelita';
            $unitType = 'Accommodation / Hotel';
            $unitIcon = '🏨';
        } elseif ($slug === 'monclaire-pool') {
            $unitName = 'Monclaire Pool';
            $unitType = 'Facility / Pool';
            $unitIcon = '🏊';
        } else {
            $unitName = 'Dakong Balay';
            $unitType = 'Food & Restaurant';
            $unitIcon = '🍽️';
        }
    @endphp
@endif

@extends($isManager ? 'layouts.manager' : 'layouts.admin')

@section('page_title', 'Knowledge Base Logs')
@section('page_description', 'View upload history, processing events, and indexing results for all knowledge base files.')
@section('breadcrumbs', ($isManager ? ($unitName ?? 'Manager') . ' / ' : 'Admin / Businesses / ') . 'Logs')
@section('unit-type', $isManager ? ($unitType ?? '') : '')

@if($isManager)
    @section('manager-sidebar')
        @include('partials.manager-sidebar')
    @endsection
@endif

@section('content')
<div class="space-y-8">

    {{-- Page header --}}
    <section class="rounded-[2rem] border border-[#e2e8f0] bg-white p-6 shadow-sm lg:p-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <h1 class="text-3xl font-black tracking-tight text-gray-900">Knowledge Base Logs</h1>
                <p class="mt-2 max-w-3xl text-sm leading-6 text-gray-500">
                    A record of all upload events, processing steps, indexing outcomes, and approval actions
                    {{ $isManager ? 'for <strong class="text-gray-800">' . $unitName . '</strong>' : 'across all business units' }}.
                </p>
            </div>
            <div class="flex flex-wrap gap-3">
                <button type="button"
                    class="btn rounded-full border border-gray-300 bg-white text-gray-700 hover:bg-[#f5f3ff] hover:text-gray-700">
                    Export CSV
                </button>
                <button type="button"
                    class="btn rounded-full border border-gray-300 bg-white text-gray-700 hover:bg-[#f5f3ff] hover:text-gray-700">
                    Clear filters
                </button>
            </div>
        </div>
    </section>

    {{-- Stats strip --}}
    <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ([
            ['label' => 'Total uploads',    'value' => '47', 'sub' => 'All time'],
            ['label' => 'Indexed today',    'value' => '3',  'sub' => 'Last 24 h'],
            ['label' => 'Failed / Rejected','value' => '2',  'sub' => 'Needs review'],
            ['label' => 'Pending approval', 'value' => '5',  'sub' => 'Staged files'],
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
                    @foreach (['All', 'Indexed', 'Staged', 'Failed', 'Deleted'] as $filter)
                    <button type="button"
                        class="btn btn-sm rounded-full {{ $filter === 'All' ? 'border-0 bg-[#1e293b] text-white' : 'btn-outline border-gray-300 text-gray-600' }}">
                        {{ $filter }}
                    </button>
                    @endforeach
                </div>
            </div>

            <div class="mt-6 overflow-x-auto">
                <table class="table table-zebra text-sm">
                    <thead>
                        <tr class="text-xs font-semibold uppercase tracking-wider text-gray-400">
                            <th>Timestamp</th>
                            <th>File</th>
                            @if (!$isManager)<th>Business Unit</th>@endif
                            <th>Event</th>
                            <th>Status</th>
                            <th>User</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $logRows = [
                                ['time' => 'Today 11:42 AM', 'file' => 'menu-updated.csv',              'unit' => 'Dakong Balay',     'event' => 'Indexed',         'status' => 'success', 'user' => 'Admin'],
                                ['time' => 'Today 10:15 AM', 'file' => 'villa-policies.pdf',            'unit' => 'Villa Carmelita',  'event' => 'Approved',        'status' => 'success', 'user' => 'Manager'],
                                ['time' => 'Today 09:03 AM', 'file' => 'pool-rates-summer.pdf',         'unit' => 'Monclaire Pool',   'event' => 'Staged',          'status' => 'pending', 'user' => 'Manager'],
                                ['time' => 'Yesterday 4:50 PM','file' => 'allergen-guide.pdf',          'unit' => 'Dakong Balay',     'event' => 'Upload failed',   'status' => 'error',   'user' => 'Admin'],
                                ['time' => 'Yesterday 2:11 PM','file' => 'room-rate-table.csv',         'unit' => 'Villa Carmelita',  'event' => 'Indexed',         'status' => 'success', 'user' => 'Admin'],
                                ['time' => 'Yesterday 1:00 PM','file' => 'house-rules.pdf',             'unit' => 'Villa Carmelita',  'event' => 'Rejected',        'status' => 'error',   'user' => 'Manager'],
                                ['time' => '2 days ago 3:30 PM','file' => 'daily-specials.csv',         'unit' => 'Dakong Balay',     'event' => 'Indexed',         'status' => 'success', 'user' => 'Admin'],
                                ['time' => '2 days ago 9:10 AM','file' => 'gazebo-rates.csv',           'unit' => 'Monclaire Pool',   'event' => 'Indexed',         'status' => 'success', 'user' => 'Admin'],
                            ];
                        @endphp
                        @foreach ($logRows as $row)
                        <tr>
                            <td class="whitespace-nowrap text-gray-400">{{ $row['time'] }}</td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <svg viewBox="0 0 24 24" class="h-4 w-4 shrink-0 fill-gray-400"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zm-1 1.5L18.5 9H13V3.5zM6 20V4h5v7h7v9H6z"/></svg>
                                    <span class="font-medium text-gray-800">{{ $row['file'] }}</span>
                                </div>
                            </td>
                            @if (!$isManager)
                            <td class="text-gray-600">{{ $row['unit'] }}</td>
                            @endif
                            <td class="text-gray-700">{{ $row['event'] }}</td>
                            <td>
                                @if ($row['status'] === 'success')
                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-200">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Done
                                    </span>
                                @elseif ($row['status'] === 'pending')
                                    <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700 ring-1 ring-amber-200">
                                        <span class="h-1.5 w-1.5 rounded-full bg-amber-400"></span> Pending
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-full bg-rose-50 px-2.5 py-1 text-xs font-semibold text-rose-700 ring-1 ring-rose-200">
                                        <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span> Failed
                                    </span>
                                @endif
                            </td>
                            <td class="text-gray-500">{{ $row['user'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4 flex flex-col gap-3 border-t border-gray-100 pt-4 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-xs text-gray-500">Showing <strong>8</strong> of <strong>47</strong> entries</p>
                <div class="join">
                    <button class="join-item btn btn-xs btn-outline border-gray-300 text-gray-600" disabled>«</button>
                    <button class="join-item btn btn-xs btn-active border-0 bg-[#1e293b] text-white">1</button>
                    <button class="join-item btn btn-xs btn-outline border-gray-300 text-gray-600">2</button>
                    <button class="join-item btn btn-xs btn-outline border-gray-300 text-gray-600">3</button>
                    <button class="join-item btn btn-xs btn-outline border-gray-300 text-gray-600">»</button>
                </div>
            </div>
        </div>
    </section>

</div>
@endsection
