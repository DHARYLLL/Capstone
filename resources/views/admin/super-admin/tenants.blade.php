@extends('layouts.super-admin')

@section('page_title', 'Tenant Management Ledger')
@section('page_description', 'Review every tenant, provision paths, configure tiers, and temporarily suspend access.')
@section('breadcrumbs', 'Super Admin / Tenants')

@section('content')
    <div class="space-y-8">
        <section class="rounded-[2rem] border border-[#E8DFD2] bg-white p-6 shadow-sm lg:p-8">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <h2 class="text-2xl font-black tracking-tight text-gray-900">Comprehensive tenant ledger</h2>
                    <p class="mt-2 max-w-3xl text-sm leading-6 text-gray-500">Master list of businesses with path status, usage tiers, registration dates, and quick action tools.</p>
                </div>
                <div class="flex gap-3">
                    <button class="btn rounded-full border-0 bg-[#5A3E2B] text-white hover:bg-[#453020]">Provision new tenant</button>
                    <button class="btn btn-outline rounded-full border-[#D8C3A7] text-gray-700 hover:bg-[#F4EEDF]">Export ledger</button>
                </div>
            </div>
        </section>

        <section class="overflow-hidden rounded-[2rem] bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="table">
                    <thead class="bg-[#FAF8F4]">
                        <tr class="text-gray-500">
                            <th>Tenant</th>
                            <th>Category</th>
                            <th>Registered</th>
                            <th>Subpath</th>
                            <th>Tier</th>
                            <th>Usage</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $tenants = [
                                ['name' => 'Villa Carmelita', 'category' => 'Hotel / Villa', 'date' => '2026-02-11', 'path' => 'provisioned', 'tier' => 'Enterprise', 'usage' => '72%'],
                                ['name' => 'Dakong Balay', 'category' => 'Restaurant', 'date' => '2026-03-04', 'path' => 'provisioned', 'tier' => 'Growth', 'usage' => '38%'],
                                ['name' => 'Monclaire Pool', 'category' => 'Pool', 'date' => '2026-03-18', 'path' => 'pending', 'tier' => 'Starter', 'usage' => '15%'],
                            ];
                        @endphp

                        @foreach ($tenants as $tenant)
                            <tr>
                                <td>
                                    <div class="font-semibold text-gray-900">{{ $tenant['name'] }}</div>
                                    <div class="text-xs text-gray-500">/{{ str()->slug($tenant['name']) }}</div>
                                </td>
                                <td>{{ $tenant['category'] }}</td>
                                <td>{{ $tenant['date'] }}</td>
                                <td><span class="badge badge-outline">{{ ucfirst($tenant['path']) }}</span></td>
                                <td>{{ $tenant['tier'] }}</td>
                                <td>{{ $tenant['usage'] }}</td>
                                <td>
                                    <div class="flex flex-wrap gap-2">
                                        <button class="btn btn-xs rounded-full border-[#D8C3A7]">Provision path</button>
                                        <button class="btn btn-xs rounded-full border-[#D8C3A7]">Set tier</button>
                                        <button class="btn btn-xs rounded-full border-rose-300 text-rose-700">Suspend</button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection