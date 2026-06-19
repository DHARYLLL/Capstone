@extends('layouts.super-admin')

@section('page_title', 'Global Platform Overview')
@section('page_description', 'Monitor tenants, AI connections, cloud resource consumption, and system activity at a glance.')
@section('breadcrumbs', 'Super Admin / Overview')

@section('content')
    @php
        $kpis = [
            ['label' => 'Total tenants', 'value' => '3', 'delta' => '+1 this month'],
            ['label' => 'Active AI connections', 'value' => '3', 'delta' => '100% online'],
            ['label' => 'Supabase storage', 'value' => '72%', 'delta' => '18 GB of 25 GB'],
            ['label' => 'Database load', 'value' => '41%', 'delta' => 'Stable'],
        ];

        $timeline = [
            ['time' => '09:10', 'label' => 'Tenant Villa Carmelita synced knowledge base'],
            ['time' => '10:24', 'label' => 'Webhook delivery retry succeeded for Monclaire Pool'],
            ['time' => '11:03', 'label' => 'Human handoff rate spike detected for Dakong Balay'],
        ];
    @endphp

    <div class="space-y-8">
        <section class="rounded-[2rem] border border-[#E8DFD2] bg-white p-6 shadow-sm lg:p-8">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                <div class="space-y-3">
                    <div class="badge border-0 bg-[#F1E4D2] px-4 py-2 text-xs font-semibold uppercase tracking-[0.22em] text-[#5A3E2B]">Ecosystem command center</div>
                    <h2 class="text-3xl font-black tracking-tight text-gray-900 md:text-5xl">Master visibility across all tenants</h2>
                    <p class="max-w-3xl text-sm leading-6 text-gray-600 md:text-base">Track platform usage, cloud health, AI activity, and ecosystem risk from one operator-grade control room.</p>
                </div>
                <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
                    @foreach ($kpis as $kpi)
                        <div class="rounded-[1.5rem] border border-[#E8DFD2] bg-[#FBF8F2] p-4 shadow-sm">
                            <div class="text-xs font-semibold uppercase tracking-[0.18em] text-gray-400">{{ $kpi['label'] }}</div>
                            <div class="mt-2 text-3xl font-black text-gray-900">{{ $kpi['value'] }}</div>
                            <div class="mt-1 text-xs text-[#5A3E2B]">{{ $kpi['delta'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="grid gap-6 lg:grid-cols-[2fr_1fr]">
            <div class="card bg-base-100 shadow-sm">
                <div class="card-body p-6 lg:p-8">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Global activity timeline</h3>
                            <p class="mt-1 text-sm text-gray-500">Line metric tracking total queries across the platform.</p>
                        </div>
                        <span class="badge badge-success badge-outline">Live</span>
                    </div>

                    <div class="mt-6 h-72 rounded-[2rem] bg-[#FAF8F4] p-6">
                        <div class="flex h-full items-end gap-3">
                            <div class="h-[30%] flex-1 rounded-t-2xl bg-[#EED9C4]"></div>
                            <div class="h-[42%] flex-1 rounded-t-2xl bg-[#D8C3A7]"></div>
                            <div class="h-[38%] flex-1 rounded-t-2xl bg-[#C8B091]"></div>
                            <div class="h-[60%] flex-1 rounded-t-2xl bg-[#B79A79]"></div>
                            <div class="h-[55%] flex-1 rounded-t-2xl bg-[#8E6B4E]"></div>
                            <div class="h-[72%] flex-1 rounded-t-2xl bg-[#5A3E2B]"></div>
                            <div class="h-[64%] flex-1 rounded-t-2xl bg-[#7A5841]"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card bg-base-100 shadow-sm">
                <div class="card-body p-6 lg:p-8">
                    <h3 class="text-xl font-bold text-gray-900">Platform health</h3>
                    <div class="mt-4 space-y-3 text-sm text-gray-600">
                        <div class="rounded-2xl bg-[#FAF8F4] p-4">Supabase storage: 72% used</div>
                        <div class="rounded-2xl bg-[#FAF8F4] p-4">Database load: 41% / stable</div>
                        <div class="rounded-2xl bg-[#FAF8F4] p-4">Webhook delivery: 99.4% success</div>
                        <div class="rounded-2xl bg-[#FAF8F4] p-4">Fallback routing: 1 branch flagged</div>
                    </div>
                </div>
            </div>
        </section>

        <section class="grid gap-6 lg:grid-cols-[1.2fr_0.8fr]">
            <div class="card bg-base-100 shadow-sm">
                <div class="card-body p-6 lg:p-8">
                    <h3 class="text-xl font-bold text-gray-900">Ecosystem timeline</h3>
                    <div class="mt-5 space-y-3">
                        @foreach ($timeline as $event)
                            <div class="flex gap-4 rounded-2xl border border-[#E9E2D6] bg-[#FAF8F4] p-4">
                                <div class="w-16 shrink-0 text-sm font-semibold text-[#5A3E2B]">{{ $event['time'] }}</div>
                                <div class="text-sm text-gray-600">{{ $event['label'] }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="card bg-base-100 shadow-sm">
                <div class="card-body p-6 lg:p-8">
                    <h3 class="text-xl font-bold text-gray-900">Platform alerts</h3>
                    <div class="mt-4 space-y-3 text-sm text-gray-600">
                        <div class="rounded-2xl bg-[#F8F1E7] p-4">1 tenant showing elevated human handoff rate</div>
                        <div class="rounded-2xl bg-[#F8F1E7] p-4">Supabase webhook retry observed for one delivery</div>
                        <div class="rounded-2xl bg-[#F8F1E7] p-4">All AI links connected and responsive</div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection