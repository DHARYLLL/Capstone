@php $isManager = isset($slug); @endphp
@if($isManager)
    @php
        if ($slug === 'villa-carmelita') { $unitName = 'Villa Carmelita'; $unitType = 'Accommodation / Hotel'; $unitIcon = '🏨'; $sidebarExtras = ['Room Availability', 'Rate Configuration', 'Guest Inquiries']; }
        elseif ($slug === 'monclaire-pool') { $unitName = 'Monclaire Pool'; $unitType = 'Facility / Pool'; $unitIcon = '🏊'; $sidebarExtras = ['Pool Schedule', 'Pass & Rental Rates', 'Guest Inquiries']; }
        else { $unitName = 'Dakong Balay'; $unitType = 'Food & Restaurant'; $unitIcon = '🍽️'; $sidebarExtras = ['Menu Management', 'Dining Availability', 'Guest Inquiries']; }
    @endphp
@endif

@extends($isManager ? 'layouts.manager' : 'layouts.admin')

@section('page_title', 'Reporting & Analytics')
@section('page_description', 'Track answered, unanswered, and human-routed query volumes, then export CSV logs or formatted PDF summaries.')
@section('breadcrumbs', ($isManager ? $unitName . ' / ' : 'Admin / Businesses / ') . 'Analytics')
@section('unit-type', $isManager ? $unitType : '')

@if($isManager)
@section('manager-sidebar')
    @include('partials.manager-sidebar')
@endsection
@endif

@section('content')
    @php
        $metrics = [
            ['label' => 'Answered', 'value' => '1,284', 'trend' => '+18%'],
            ['label' => 'Unanswered', 'value' => '34', 'trend' => '-6%'],
            ['label' => 'Human-routed', 'value' => '126', 'trend' => '+11%'],
            ['label' => 'Avg response', 'value' => '8.2s', 'trend' => '-1.1s'],
        ];
    @endphp

    <div class="space-y-8">
        <section class="rounded-[2rem] border border-[#e2e8f0] bg-white p-6 shadow-sm lg:p-8">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <h1 class="text-3xl font-black tracking-tight text-gray-900">Reporting & Analytics</h1>
                    <p class="mt-2 max-w-3xl text-sm leading-6 text-gray-500">Track answered, unanswered, and human-routed query volumes, then export CSV logs or formatted PDF summaries.</p>
                </div>
                <div class="flex gap-3">
                    <button class="btn btn-outline rounded-full border-gray-300 text-gray-700 hover:bg-[#f5f3ff]">Download CSV</button>
                    <button class="btn rounded-full border-0 bg-brand-primary text-white hover:bg-[#6d28d9]">Generate PDF report</button>
                </div>
            </div>
        </section>

        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ($metrics as $metric)
                <div class="card bg-base-100 shadow-sm">
                    <div class="card-body p-6">
                        <div class="text-sm text-gray-500">{{ $metric['label'] }}</div>
                        <div class="mt-2 text-4xl font-black text-gray-900">{{ $metric['value'] }}</div>
                        <p class="mt-2 text-sm text-emerald-600">{{ $metric['trend'] }} vs prior period</p>
                    </div>
                </div>
            @endforeach
        </section>

        <section class="grid gap-6 lg:grid-cols-[2fr_1fr]">
            <div class="card bg-base-100 shadow-sm">
                <div class="card-body p-6 lg:p-8">
                    <h2 class="text-xl font-bold text-gray-900">Query volume trend</h2>
                    <div class="mt-6 h-80 rounded-[2rem] bg-[#ffffff] p-6">
                        <div class="flex h-full items-end gap-3">
                            <div class="h-[30%] flex-1 rounded-t-2xl bg-[#D8C3A7]"></div>
                            <div class="h-[45%] flex-1 rounded-t-2xl bg-[#C8B091]"></div>
                            <div class="h-[38%] flex-1 rounded-t-2xl bg-[#B79A79]"></div>
                            <div class="h-[62%] flex-1 rounded-t-2xl bg-[#8E6B4E]"></div>
                            <div class="h-[55%] flex-1 rounded-t-2xl bg-brand-primary"></div>
                            <div class="h-[72%] flex-1 rounded-t-2xl bg-[#7A5841]"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card bg-base-100 shadow-sm">
                <div class="card-body p-6 lg:p-8">
                    <h2 class="text-xl font-bold text-gray-900">Insights</h2>
                    <div class="mt-4 space-y-3 text-sm text-gray-600">
                        <div class="rounded-2xl bg-[#f8fafc] p-4">Top intent: room rates and availability</div>
                        <div class="rounded-2xl bg-[#f8fafc] p-4">Peak traffic: 6 PM to 9 PM</div>
                        <div class="rounded-2xl bg-[#f8fafc] p-4">Escalation reason: booking confirmation</div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection