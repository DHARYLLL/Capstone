@php
    $slug = $slug ?? 'dakong-balay';

    if ($slug === 'villa-carmelita') {
        $unitName    = 'Villa Carmelita';
        $unitType    = 'Accommodation / Hotel';
        $unitIcon    = '🏨';
        $unitBadgeBg = 'bg-[#F3ECE4] text-[#6B4226]';
        $kpis = [
            ['label' => 'Rooms occupied',     'value' => '4',  'sub' => '21 total · 17 available', 'up' => false],
            ['label' => 'Check-ins today',    'value' => '3',  'sub' => '+1 vs yesterday',          'up' => true],
            ['label' => 'AI booking queries', 'value' => '28', 'sub' => 'Last 24 hours',            'up' => true],
            ['label' => 'Maintenance flags',  'value' => '2',  'sub' => 'RM 304 · RM 207',         'up' => false],
        ];
        $sidebarExtras = ['Room Availability', 'Rate Configuration', 'Guest Inquiries'];
        $aiTitle = 'Room & Policy Knowledge Files';
        $aiDesc  = 'Upload room rate sheets, house rules, and accommodation policy PDFs. CSV pricing tables are also accepted.';
        $aiFiles = ['Room rate table (CSV)', 'Accommodation policy (PDF)', 'House rules (PDF)', 'Amenity listing (CSV)'];
        $aiCount = '14 files indexed';
        $chatCtx = 'Scoped to /villa-carmelita — only conversations from this subpath appear here.';
        $chatTags = ['Room inquiry', 'Booking confirmation', 'Rate query', 'Maintenance report', 'Check-in/out'];
        $queue = [
            ['name' => 'Maria D.', 'status' => 'Waiting',      'topic' => 'Room availability query',    'time' => '2m',  'high' => false],
            ['name' => 'John P.',  'status' => 'Escalated',     'topic' => 'Payment confirmation issue', 'time' => '8m',  'high' => true],
            ['name' => 'Aya R.',   'status' => 'Live operator', 'topic' => 'Junior Suite pricing',       'time' => '14m', 'high' => false],
        ];
    } elseif ($slug === 'monclaire-pool') {
        $unitName    = 'Monclaire Pool';
        $unitType    = 'Facility / Pool';
        $unitIcon    = '🏊';
        $unitBadgeBg = 'bg-[#E4EEF3] text-[#2A5F7A]';
        $kpis = [
            ['label' => 'Day passes sold',     'value' => '31', 'sub' => 'Today · adults + children', 'up' => true],
            ['label' => 'Gazebo rentals',      'value' => '3',  'sub' => '2 active · 1 reserved',     'up' => false],
            ['label' => 'AI facility queries', 'value' => '19', 'sub' => 'Last 24 hours',              'up' => true],
            ['label' => 'Events this week',    'value' => '1',  'sub' => 'Pool party · Saturday',     'up' => false],
        ];
        $sidebarExtras = ['Pool Schedule', 'Pass & Rental Rates', 'Guest Inquiries'];
        $aiTitle = 'Facility & Rate Knowledge Files';
        $aiDesc  = 'Upload pass pricing sheets, pool operating rules, and event package PDFs. CSV rate tables are supported.';
        $aiFiles = ['Day pass pricing (CSV)', 'Facility rules (PDF)', 'Event packages (PDF)', 'Gazebo rates (CSV)'];
        $aiCount = '8 files indexed';
        $chatCtx = 'Scoped to /monclaire-pool — only conversations from this subpath appear here.';
        $chatTags = ['Pass pricing', 'Gazebo booking', 'Pool hours', 'Event inquiry', 'Group booking'];
        $queue = [
            ['name' => 'Renz B.', 'status' => 'Waiting',   'topic' => 'Pool hours this weekend', 'time' => '1m', 'high' => false],
            ['name' => 'Lena M.', 'status' => 'Escalated', 'topic' => 'Event package pricing',   'time' => '5m', 'high' => true],
        ];
    } else {
        $unitName    = 'Dakong Balay';
        $unitType    = 'Food & Restaurant';
        $unitIcon    = '🍽️';
        $unitBadgeBg = 'bg-[#F4EBE0] text-[#7A4A2B]';
        $kpis = [
            ['label' => 'Menu items active',  'value' => '12', 'sub' => '4 featured · 8 standard', 'up' => false],
            ['label' => 'Reservations today', 'value' => '7',  'sub' => 'Via AI assistant',         'up' => true],
            ['label' => 'AI menu queries',    'value' => '44', 'sub' => 'Last 24 hours',            'up' => true],
            ['label' => 'Low-stock alerts',   'value' => '1',  'sub' => 'Pork Sisig · limited',    'up' => false],
        ];
        $sidebarExtras = ['Menu Management', 'Dining Availability', 'Guest Inquiries'];
        $aiTitle = 'Menu & Dining Knowledge Files';
        $aiDesc  = 'Upload menu PDFs, allergen sheets, and CSV price lists. The AI uses these to answer food and reservation questions.';
        $aiFiles = ['Full menu (PDF)', 'Daily specials (CSV)', 'Allergen guide (PDF)', 'Pricing table (CSV)'];
        $aiCount = '11 files indexed';
        $chatCtx = 'Scoped to /dakong-balay — only conversations from this subpath appear here.';
        $chatTags = ['Menu query', 'Reservation request', 'Allergy question', 'Group dining', 'Special offers'];
        $queue = [
            ['name' => 'Carlo S.', 'status' => 'Waiting',      'topic' => 'Table for 8 tonight',      'time' => '3m',  'high' => false],
            ['name' => 'Diana V.', 'status' => 'Escalated',     'topic' => 'Allergen info for sisig',  'time' => '9m',  'high' => true],
            ['name' => 'Mike T.',  'status' => 'Live operator', 'topic' => 'Family set menu pricing', 'time' => '18m', 'high' => false],
        ];
    }
@endphp

@extends('layouts.manager')

@section('page_title', $unitName . ' — Manager Dashboard')
@section('page_description', 'Operational command view for ' . $unitType . ' · ' . $unitName)
@section('breadcrumbs', 'Manager / ' . $unitName . ' / Dashboard')
@section('unit-type', $unitType)

@section('manager-sidebar')
<aside class="hidden w-72 shrink-0 flex-col border-r border-gray-200 bg-base-100 md:flex">

    {{-- Brand + unit identity --}}
    <div class="border-b border-gray-200 px-6 py-5">
        <a href="{{ route('admin.businesses.manager-dashboard', $slug) }}" class="flex items-center gap-3">
            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[#1e293b] text-xl text-white shadow-sm">
                {{ $unitIcon }}
            </div>
            <div>
                <div class="text-base font-extrabold tracking-tight text-gray-900">{{ $unitName }}</div>
                <div class="text-xs text-gray-500">Manager Portal</div>
            </div>
        </a>

        <div class="mt-4 rounded-2xl border border-[#e2e8f0] bg-[#ffffff] px-4 py-3">
            <div class="text-xs font-semibold uppercase tracking-wider text-gray-400">Your assigned unit</div>
            <div class="mt-1 text-sm font-bold text-gray-900">{{ $unitName }}</div>
            <div class="mt-1 flex items-center gap-2 text-xs text-gray-500">
                <span class="inline-flex h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                {{ $unitType }} · Live
            </div>
        </div>
    </div>

    <nav class="flex-1 px-4 py-5">
        @php
            $mgrNav = [
                [
                    'label'  => 'Overview',
                    'href'   => route('admin.businesses.manager-dashboard', $slug),
                    'icon'   => 'M4 13h7V4H4v9zm0 7h7v-5H4v5zm9 0h7V11h-7v9zm0-16v5h7V4h-7z',
                    'active' => request()->routeIs('admin.businesses.manager-dashboard'),
                ],
                [
                    'label'  => 'Knowledge Base',
                    'href'   => route('admin.businesses.manager-knowledge-base', $slug),
                    'icon'   => 'M12 2a7 7 0 0 0-7 7v13h14V9a7 7 0 0 0-7-7zm-2 8h4v2h-4v-2zm0 4h4v2h-4v-2z',
                    'active' => request()->routeIs('admin.businesses.manager-knowledge-base'),
                ],
                [
                    'label'  => 'Live Chat & Handoff',
                    'href'   => route('admin.businesses.manager-chat', $slug),
                    'icon'   => 'M4 4h16v12H7l-3 3V4zm4 5h8v2H8V9zm0 4h6v2H8v-2z',
                    'active' => request()->routeIs('admin.businesses.manager-chat'),
                ],
                [
                    'label'  => 'Analytics',
                    'href'   => route('admin.businesses.manager-analytics', $slug),
                    'icon'   => 'M12 3C7.03 3 3 6.58 3 11c0 2.47 1.22 4.7 3.22 6.29L5 21l3.9-1.96c.97.25 2 .38 3.1.38 4.97 0 9-3.58 9-8s-4.03-8-9-8zm-3 9H7v-2h2v2zm4 0h-2v-2h2v2zm4 0h-2v-2h2v2z',
                    'active' => request()->routeIs('admin.businesses.manager-analytics'),
                ],
                [
                    'label'  => 'Edit Business Profile',
                    'href'   => route('admin.businesses.edit', $slug),
                    'icon'   => 'M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zm18-10.5a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z',
                    'active' => request()->routeIs('admin.businesses.edit'),
                ],
                [
                    'label'  => 'Staff & Roles',
                    'href'   => route('admin.businesses.manager-staff', $slug),
                    'icon'   => 'M12 12a4 4 0 1 0-4-4 4 4 0 0 0 4 4zm0 2c-4.4 0-8 2.2-8 5v1h16v-1c0-2.8-3.6-5-8-5z',
                    'active' => request()->routeIs('admin.businesses.manager-staff'),
                ],
                [
                    'label'  => 'Logs',
                    'href'   => route('admin.businesses.manager-logs', $slug),
                    'icon'   => 'M4 6h16v2H4zm0 5h16v2H4zm0 5h16v2H4z',
                    'active' => request()->routeIs('admin.businesses.manager-logs'),
                ],
            ];
        @endphp

        <div class="mb-2 px-3 text-[11px] font-semibold uppercase tracking-[0.2em] text-gray-400">
            {{ $unitName }} Workspace
        </div>
        <ul class="space-y-1">
            @foreach ($mgrNav as $item)
                <li>
                    <a href="{{ $item['href'] }}"
                       class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium transition-colors
                              {{ $item['active']
                                  ? 'bg-[#1e293b] font-semibold text-white shadow-sm'
                                  : 'text-gray-700 hover:bg-[#f5f3ff] hover:text-[#1e293b]' }}">
                        <svg viewBox="0 0 24 24" class="h-5 w-5 shrink-0 fill-current" aria-hidden="true">
                            <path d="{{ $item['icon'] }}"/>
                        </svg>
                        {{ $item['label'] }}
                    </a>
                </li>
            @endforeach
        </ul>
    </nav>

    <div class="mt-auto border-t border-gray-200 p-4">
        <button class="btn w-full justify-start rounded-2xl border-0 bg-[#f5f3ff] text-gray-800 hover:bg-[#f5f3ff]">
            <svg viewBox="0 0 24 24" class="h-5 w-5 fill-current" aria-hidden="true">
                <path d="M10 17l1.4-1.4L8.8 13H20v-2H8.8l2.6-2.6L10 7l-5 5 5 5zM4 4h7v2H6v12h5v2H4V4z"/>
            </svg>
            Logout
        </button>
    </div>
</aside>
@endsection

@section('content')
<div class="space-y-8">

    {{-- HERO --}}
    <section class="rounded-[2rem] border border-[#e2e8f0] bg-white p-6 shadow-sm lg:p-8">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div class="space-y-3">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-xs font-semibold ring-1 ring-black/5 {{ $unitBadgeBg }}">
                        {{ $unitIcon }} {{ $unitName }} · {{ $unitType }}
                    </span>
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-200">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        Live
                    </span>
                </div>
                <h1 class="text-3xl font-black tracking-tight text-gray-900 md:text-4xl">Manager Dashboard</h1>
                <p class="max-w-2xl text-sm leading-6 text-gray-500">
                    All metrics, knowledge files, and chat tickets are scoped exclusively to
                    <strong class="text-gray-800">{{ $unitName }}</strong>.
                </p>
            </div>
        </div>
    </section>

    {{-- KPI STRIP --}}
    <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ($kpis as $kpi)
            <div class="card bg-base-100 shadow-sm">
                <div class="card-body p-5">
                    <div class="text-sm font-medium text-gray-500">{{ $kpi['label'] }}</div>
                    <div class="mt-2 text-4xl font-black text-gray-900">{{ $kpi['value'] }}</div>
                    <p class="mt-2 flex items-center gap-1 text-sm {{ $kpi['up'] ? 'text-emerald-600' : 'text-gray-400' }}">
                        @if ($kpi['up'])
                            <svg viewBox="0 0 24 24" class="h-3.5 w-3.5 fill-current"><path d="M7 14l5-5 5 5z"/></svg>
                        @endif
                        {{ $kpi['sub'] }}
                    </p>
                </div>
            </div>
        @endforeach
    </section>

    {{-- MAIN GRID --}}
    <section class="grid gap-6 xl:grid-cols-[1fr_1.5fr]">

        {{-- AI RESOURCE + QUICK LINKS --}}
        <div class="space-y-6">
            <div class="card bg-base-100 shadow-sm">
                <div class="card-body p-6 lg:p-8">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">{{ $aiTitle }}</h2>
                            <p class="mt-1 text-sm leading-6 text-gray-500">{{ $aiDesc }}</p>
                        </div>
                        <span class="inline-flex shrink-0 items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-200">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                            {{ $aiCount }}
                        </span>
                    </div>
                    <div class="mt-5 space-y-2">
                        @foreach ($aiFiles as $f)
                            <div class="flex items-center gap-3 rounded-2xl border border-[#e2e8f0] bg-[#ffffff] px-4 py-3 text-sm text-gray-700">
                                <svg viewBox="0 0 24 24" class="h-4 w-4 shrink-0 fill-[#1e293b]"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zm-1 1.5L18.5 9H13V3.5zM6 20V4h5v7h7v9H6z"/></svg>
                                {{ $f }}
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-5 flex gap-3">
                        <a href="{{ route('admin.businesses.knowledge-base') }}"
                           class="btn btn-sm rounded-full border-0 bg-[#1e293b] text-white hover:bg-[#3a2f2e]">
                            Upload new file
                        </a>
                        <a href="{{ route('admin.businesses.knowledge-base') }}"
                           class="btn btn-sm rounded-full border border-gray-300 bg-white text-gray-700 hover:bg-[#f5f3ff] hover:text-gray-700">
                            View index
                        </a>
                    </div>
                </div>
            </div>

            <div class="card bg-base-100 shadow-sm">
                <div class="card-body p-6">
                    <h2 class="text-lg font-bold text-gray-900">{{ $unitType }} quick links</h2>
                    <ul class="mt-4 space-y-2">
                        @foreach ($sidebarExtras as $extra)
                            <li>
                                <button type="button"
                                        class="w-full rounded-2xl bg-[#ffffff] px-4 py-3 text-left text-sm font-medium text-gray-700 transition hover:bg-[#F0E8DE] hover:text-[#1e293b]">
                                    {{ $extra }}
                                </button>
                            </li>
                        @endforeach
                        <li>
                            <a href="{{ route('admin.businesses.knowledge-base') }}"
                               class="flex items-center gap-2 rounded-2xl bg-[#ffffff] px-4 py-3 text-sm font-medium text-gray-700 transition hover:bg-[#F0E8DE] hover:text-[#1e293b]">
                                <span class="h-2 w-2 rounded-full bg-[#1e293b]"></span>
                                Knowledge Base Ingestion
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.businesses.chat') }}"
                               class="flex items-center gap-2 rounded-2xl bg-[#ffffff] px-4 py-3 text-sm font-medium text-gray-700 transition hover:bg-[#F0E8DE] hover:text-[#1e293b]">
                                <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                Live Chat & Handoff Terminal
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- LIVE CHAT & HANDOFF --}}
        <div class="card bg-base-100 shadow-sm">
            <div class="card-body p-6 lg:p-8">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Live Chat & Handoff</h2>
                        <p class="mt-1 text-sm text-gray-500">{{ $chatCtx }}</p>
                    </div>
                    <span class="inline-flex shrink-0 items-center gap-1.5 rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700 ring-1 ring-amber-200">
                        <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-amber-500"></span>
                        {{ count($queue) }} in queue
                    </span>
                </div>

                <div class="mt-4 flex flex-wrap gap-1.5">
                    @foreach ($chatTags as $tag)
                        <span class="rounded-full border border-[#e2e8f0] bg-[#ffffff] px-3 py-1 text-xs font-medium text-gray-600">{{ $tag }}</span>
                    @endforeach
                </div>

                <div class="mt-5 grid gap-4 md:grid-cols-2">
                    {{-- AI queue --}}
                    <div class="space-y-3">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400">AI-Handled Queue</h3>
                        @foreach ($queue as $item)
                            <div class="rounded-2xl border p-4 {{ $item['high'] ? 'border-rose-200 bg-rose-50' : 'border-[#e2e8f0] bg-[#ffffff]' }}">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="font-semibold text-gray-800 text-sm">{{ $item['name'] }}</span>
                                    <span class="text-xs text-gray-400">{{ $item['time'] }} ago</span>
                                </div>
                                <p class="mt-0.5 text-xs text-gray-600">{{ $item['topic'] }}</p>
                                <div class="mt-2 inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-bold
                                    {{ $item['status'] === 'Escalated' ? 'bg-rose-100 text-rose-700' :
                                       ($item['status'] === 'Live operator' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600') }}">
                                    {{ $item['status'] }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Operator controls --}}
                    <div class="space-y-3">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400">Operator Controls</h3>
                        <div class="rounded-2xl border border-[#e2e8f0] bg-white p-4 space-y-2">
                            <div class="h-20 rounded-2xl bg-gray-50 border border-gray-100 flex items-center justify-center">
                                <span class="text-xs text-gray-400">Live reply area</span>
                            </div>
                            @foreach (['Assign to self', 'Transfer to operator', 'Mark resolved', 'Send canned response'] as $action)
                                <button type="button"
                                        class="w-full rounded-xl bg-[#ffffff] px-4 py-2.5 text-left text-sm text-gray-700 transition hover:bg-[#F0E8DE]">
                                    {{ $action }}
                                </button>
                            @endforeach
                            <a href="{{ route('admin.businesses.chat') }}"
                               class="btn btn-sm mt-1 w-full rounded-full border-0 bg-[#1e293b] text-white hover:bg-[#3a2f2e]">
                                Open full terminal
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </section>

</div>
@endsection
