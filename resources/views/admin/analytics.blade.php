@extends('layouts.admin')

@section('page_title', 'Reporting & Analytics')
@section('breadcrumbs', 'Admin / Analytics')

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
            </div>
        </section>

        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ($metrics as $metric)
                <div class="card bg-base-100 shadow-sm border border-gray-100/60 rounded-3xl">
                    <div class="card-body p-6 flex flex-row items-center justify-between gap-4">
                        <div class="text-left space-y-1">
                            <span class="text-xs font-semibold uppercase tracking-wider text-gray-400 block">{{ $metric['label'] }}</span>
                            <span class="text-xs text-emerald-600 font-bold block">{{ $metric['trend'] }} vs prior period</span>
                        </div>
                        <div class="text-right text-3xl font-black text-gray-900 tracking-tight shrink-0">
                            {{ $metric['value'] }}
                        </div>
                    </div>
                </div>
            @endforeach
        </section>

        <section class="grid gap-6 lg:grid-cols-2">
            <!-- System Insights -->
            <div class="card bg-base-100 shadow-sm">
                <div class="card-body p-6 lg:p-8">
                    <h2 class="text-xl font-bold text-gray-900">Operational Insights</h2>
                    <p class="text-xs text-gray-400 mt-1">Automatic patterns detected from customer assistant queries.</p>
                    
                    <div class="mt-6 space-y-4 text-sm text-gray-600">
                        <div class="rounded-2xl bg-[#f8fafc] border border-gray-100 p-4">
                            <span class="font-bold text-gray-900 block text-xs uppercase tracking-wide">Top Customer Intent</span>
                            <p class="mt-1 text-sm text-gray-500">Waterproofing cost calculations and product warranty details.</p>
                        </div>
                        <div class="rounded-2xl bg-[#f8fafc] border border-gray-100 p-4">
                            <span class="font-bold text-gray-900 block text-xs uppercase tracking-wide">Peak Traffic Hour</span>
                            <p class="mt-1 text-sm text-gray-500">Highest volume recorded between 9:00 AM and 3:00 PM.</p>
                        </div>
                        <div class="rounded-2xl bg-[#f8fafc] border border-gray-100 p-4">
                            <span class="font-bold text-gray-900 block text-xs uppercase tracking-wide">Escalation Trigger</span>
                            <p class="mt-1 text-sm text-gray-500">Clients requesting custom site-inspection quotes are instantly routed to operators.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Search Keywords -->
            <div class="card bg-base-100 shadow-sm">
                <div class="card-body p-6 lg:p-8">
                    <h2 class="text-xl font-bold text-gray-900">Top Query Intent Distribution</h2>
                    <p class="text-xs text-gray-400 mt-1">Most frequent customer topics requested inside the widget.</p>
                    
                    <div class="mt-6 space-y-4">
                        <div>
                            <div class="flex justify-between text-xs font-bold text-gray-700 mb-1.5">
                                <span>Roof & Balcony Cost Estimates</span>
                                <span>48%</span>
                            </div>
                            <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                <div class="bg-indigo-600 h-full rounded-full" style="width: 48%;"></div>
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between text-xs font-bold text-gray-700 mb-1.5">
                                <span>Warranty & Guarantee Inquiries</span>
                                <span>32%</span>
                            </div>
                            <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                <div class="bg-indigo-600 h-full rounded-full" style="width: 32%;"></div>
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between text-xs font-bold text-gray-700 mb-1.5">
                                <span>Site Inspections & Bookings</span>
                                <span>20%</span>
                            </div>
                            <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                <div class="bg-indigo-600 h-full rounded-full" style="width: 20%;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection