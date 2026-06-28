@extends('layouts.super-admin')

@section('page_title', 'Subscription & Resource Configurator')
@section('page_description', 'Set upload ceilings, CSV limits, and AI token budgets across all tenant accounts.')
@section('breadcrumbs', 'Super Admin / Resources')

@section('content')
    <div class="space-y-8">
        <section class="rounded-[2rem] border border-[#e2e8f0] bg-white p-6 shadow-sm lg:p-8">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <h2 class="text-2xl font-black tracking-tight text-gray-900">Global operating limits</h2>
                    <p class="mt-2 max-w-3xl text-sm leading-6 text-gray-500">Adjust platform-wide limits for files, CSV ingestion, token consumption, and per-tenant overrides.</p>
                </div>
                <span class="badge badge-outline border-brand-border text-brand-primary-dark">Changes staged</span>
            </div>
        </section>

        <section class="grid gap-6 xl:grid-cols-[1.3fr_0.9fr]">
            <div class="card bg-base-100 shadow-sm">
                <div class="card-body p-6 lg:p-8 space-y-6">
                    <div class="grid gap-4 md:grid-cols-2">
                        <label class="form-control"><div class="label"><span class="label-text font-medium">Maximum PDF upload size (MB)</span></div><input type="range" min="5" max="100" value="25" class="range range-neutral"><div class="mt-1 text-sm text-gray-500">25 MB</div></label>
                        <label class="form-control"><div class="label"><span class="label-text font-medium">Maximum CSV rows</span></div><input type="range" min="500" max="100000" value="50000" class="range range-neutral"><div class="mt-1 text-sm text-gray-500">50,000 rows</div></label>
                        <label class="form-control"><div class="label"><span class="label-text font-medium">Monthly AI tokens per business</span></div><input type="range" min="10000" max="500000" value="100000" class="range range-neutral"><div class="mt-1 text-sm text-gray-500">100,000 tokens</div></label>
                        <label class="form-control"><div class="label"><span class="label-text font-medium">Daily AI requests per business</span></div><input type="range" min="100" max="50000" value="5000" class="range range-neutral"><div class="mt-1 text-sm text-gray-500">5,000 requests</div></label>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <label class="form-control"><div class="label"><span class="label-text font-medium">PDF boundary</span></div><input class="input input-bordered bg-base-100" value="25 MB hard cap"></label>
                        <label class="form-control"><div class="label"><span class="label-text font-medium">CSV row policy</span></div><input class="input input-bordered bg-base-100" value="Rows above 50,000 rejected"></label>
                    </div>
                </div>
            </div>

            <div class="card bg-base-100 shadow-sm">
                <div class="card-body p-6 lg:p-8">
                    <h3 class="text-xl font-bold text-gray-900">Tenant override preview</h3>
                    <div class="mt-4 space-y-3 text-sm text-gray-600">
                        <div class="rounded-2xl bg-[#ffffff] p-4">Villa Carmelita override: +50,000 AI tokens</div>
                        <div class="rounded-2xl bg-[#ffffff] p-4">Dakong Balay override: PDF cap unchanged</div>
                        <div class="rounded-2xl bg-[#ffffff] p-4">Monclaire Pool override: CSV cap unchanged</div>
                    </div>
                    <div class="mt-6 rounded-[1.5rem] bg-[#f8fafc] p-4 text-sm text-gray-600">All overrides require an audit note and approval before publishing.</div>
                </div>
            </div>
        </section>
    </div>
@endsection