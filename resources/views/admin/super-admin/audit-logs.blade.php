@extends('layouts.super-admin')

@section('page_title', 'Audit Logs & Ecosystem Health')
@section('page_description', 'Review errors, webhook flags, and fallback routing alerts from the entire platform.')
@section('breadcrumbs', 'Super Admin / Audit & Health')

@section('content')
    <div class="space-y-8">
        <section class="rounded-[2rem] border border-[#E8DFD2] bg-white p-6 shadow-sm lg:p-8">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <h2 class="text-2xl font-black tracking-tight text-gray-900">Master audit terminal</h2>
                    <p class="mt-2 max-w-3xl text-sm leading-6 text-gray-500">Centralized monitoring for errors, webhook delivery, database events, and fallback routing anomalies.</p>
                </div>
                <div class="flex gap-3">
                    <button class="btn btn-outline rounded-full border-[#D8C3A7] text-gray-700 hover:bg-[#F4EEDF]">Filter by severity</button>
                    <button class="btn rounded-full border-0 bg-[#5A3E2B] text-white hover:bg-[#453020]">Retry webhook</button>
                </div>
            </div>
        </section>

        <section class="grid gap-6 lg:grid-cols-[1.4fr_0.9fr]">
            <div class="card bg-base-100 shadow-sm">
                <div class="card-body p-6 lg:p-8">
                    <h3 class="text-xl font-bold text-gray-900">System-wide event stream</h3>
                    <div class="mt-4 space-y-3">
                        <div class="rounded-2xl bg-[#FAF8F4] p-4">Critical: webhook delivery failed for tenant Monclaire Pool</div>
                        <div class="rounded-2xl bg-[#FAF8F4] p-4">Warning: elevated fallback routing detected for Dakong Balay</div>
                        <div class="rounded-2xl bg-[#FAF8F4] p-4">Info: Supabase storage sync completed successfully</div>
                        <div class="rounded-2xl bg-[#FAF8F4] p-4">Info: tenant Villa Carmelita knowledge sync finished</div>
                    </div>
                </div>
            </div>

            <div class="card bg-base-100 shadow-sm">
                <div class="card-body p-6 lg:p-8">
                    <h3 class="text-xl font-bold text-gray-900">Ecosystem health</h3>
                    <div class="mt-4 space-y-3 text-sm text-gray-600">
                        <div class="rounded-2xl bg-emerald-50 p-4">Database: healthy</div>
                        <div class="rounded-2xl bg-emerald-50 p-4">Supabase webhooks: 99.4% success</div>
                        <div class="rounded-2xl bg-amber-50 p-4">Fallback alerts: 1 branch elevated</div>
                        <div class="rounded-2xl bg-rose-50 p-4">Error reporting: 2 open incidents</div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection