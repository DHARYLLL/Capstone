{{-- filepath: c:\Users\dhary\Desktop\Capstone\capstone1\resources\views\admin\dashboard.blade.php --}}
@extends('layouts.admin')

@section('page_title', 'Tenant Portal Overview')
{{-- @section('page_description', 'Configure operations, manage knowledge files, monitor assisted conversations, and keep the tenant experience aligned.') --}}
@section('breadcrumbs', 'Admin / Businesses / Overview')

@section('content')
    @php
        $tenantName = 'DARIV Waterproofing';
        $tenantType = 'Residential & Roof Sealing';
        $tenantStatus = 'Live';

        $kpis = [
            ['label' => 'Answered queries', 'value' => '1,284', 'delta' => '+18%'],
            ['label' => 'Unanswered queries', 'value' => '34', 'delta' => '-6%'],
            ['label' => 'Human-routed queries', 'value' => '126', 'delta' => '+11%'],
            ['label' => 'Knowledge files', 'value' => '18', 'delta' => '+3'],
        ];

        $modules = [
            ['title' => 'Knowledge Base Upload', 'desc' => 'Drop PDFs and CSVs, inspect ingestion results, and approve data.', 'href' => route('admin.knowledge-base')],
            ['title' => 'Reporting & Analytics', 'desc' => 'Review answered, unanswered, and human-routed query volume.', 'href' => route('admin.analytics')],
            ['title' => 'Widget Embed Playground', 'desc' => 'Generate and test copy-paste HTML chat widget integration code.', 'href' => route('chat.demo')],
            ['title' => 'Chat & Handoff', 'desc' => 'Monitor queue, assign operators, and resolve escalations.', 'href' => route('admin.chat')],
            ['title' => 'Staff & Roles', 'desc' => 'Manage operators, shifts, permissions, and routing rules.', 'href' => route('admin.staff')],
        ];

        $activity = [
            ['label' => 'Knowledge base indexed', 'meta' => '18 minutes ago · 4 files processed'],
            ['label' => 'Human handoff accepted', 'meta' => '42 minutes ago · Operator: Mae'],
        ];
    @endphp

    <div class="space-y-8">
        <section class="rounded-[2rem] border border-[#e2e8f0] bg-white p-6 shadow-sm lg:p-8">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                <div class="space-y-3">
                    <div
                        class="flex flex-wrap items-center gap-2 text-xs font-semibold uppercase tracking-[0.18em] text-gray-400">
                        <span>{{ $tenantName }}</span>
                        <span class="h-1 w-1 rounded-full bg-gray-300"></span>
                        <span>{{ $tenantType }}</span>
                        <span class="h-1 w-1 rounded-full bg-gray-300"></span>
                        <span class="text-emerald-600">Active</span>
                    </div>
                    <h1 class="text-3xl font-black tracking-tight text-gray-900 md:text-5xl">Waterproofing Console</h1>
                    <p class="max-w-3xl text-sm leading-6 text-gray-500 md:text-base">
                        Configure operations, manage knowledge files, monitor assisted conversations, and keep the tenant
                        experience aligned across every public touchpoint.
                    </p>
                </div>
            </div>
        </section>

        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ($kpis as $kpi)
                <div class="card bg-base-100 shadow-sm border border-gray-100/60 rounded-3xl">
                    <div class="card-body p-6 flex flex-row items-center justify-between gap-4">
                        <div class="text-left space-y-1">
                            <span class="text-xs font-semibold uppercase tracking-wider text-gray-400 block">{{ $kpi['label'] }}</span>
                            <span class="text-xs text-emerald-600 font-bold block">{{ $kpi['delta'] }} vs last period</span>
                        </div>
                        <div class="text-right text-3xl font-black text-gray-800 tracking-tight shrink-0">
                            {{ $kpi['value'] }}
                        </div>
                    </div>
                </div>
            @endforeach
        </section>

        <section class="grid gap-6 xl:grid-cols-[1.5fr_1fr]">
            <div class="card bg-base-100 shadow-sm">
                <div class="card-body p-6 lg:p-8">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">Operational Modules</h2>
                            <p class="mt-1 text-sm text-gray-500">Jump directly into the major workspaces used by tenant
                                operators.</p>
                        </div>
                        <span
                            class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-200">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                            All systems ready
                        </span>
                    </div>

                    <div class="mt-6 grid gap-4 md:grid-cols-2">
                        @foreach ($modules as $module)
                            <a href="{{ $module['href'] }}"
                                class="group rounded-[1.5rem] border border-[#e2e8f0] bg-[#ffffff] p-5 transition hover:-translate-y-0.5 hover:shadow-md">
                                <div class="flex items-center justify-between gap-4">
                                    <h3 class="text-lg font-bold text-gray-900">{{ $module['title'] }}</h3>
                                    <span class="text-brand-primary transition group-hover:translate-x-1">→</span>
                                </div>
                                <p class="mt-2 text-sm leading-6 text-gray-500">{{ $module['desc'] }}</p>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="card bg-base-100 shadow-sm">
                <div class="card-body p-6 lg:p-8">
                    <h2 class="text-xl font-bold text-gray-900">Recent activity</h2>
                    <p class="mt-1 text-sm text-gray-500">Latest portal updates and tenant actions.</p>

                    <div class="mt-5 space-y-3">
                        @foreach ($activity as $item)
                            <div class="rounded-2xl bg-[#f8fafc] p-4">
                                <div class="font-semibold text-gray-800">{{ $item['label'] }}</div>
                                <p class="mt-1 text-sm text-gray-500">{{ $item['meta'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection