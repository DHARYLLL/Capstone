@extends('layouts.admin')

@php
    $pageTitle = 'Waterproofing Services Catalog';
    $pageDesc = 'Manage service offerings, catalog pricing, and estimated durations for clients.';
    $businessName = 'AquaShield Waterproofing Console';

    $products = [
        // AquaShield Residential
        ['name' => 'Roof Deck Waterproofing', 'note' => 'Residential best seller', 'description' => 'Multi-layer polyurethane membranes with primer and UV protective top coats.', 'category' => 'Residential', 'price' => 'PHP 450 / sqm', 'availability' => '3-5 days project'],
        ['name' => 'Balcony & Terrace Sealing', 'note' => 'Standard residential', 'description' => 'Flexible acrylic membranes designed to tolerate structural expansion.', 'category' => 'Residential', 'price' => 'PHP 500 / sqm', 'availability' => '2-3 days project'],
        ['name' => 'Gutter Leak Repair', 'note' => 'Quick repair', 'description' => 'High-performance elastomeric sealant application to metal or concrete gutters.', 'category' => 'Residential', 'price' => 'Custom Quote', 'availability' => '1-2 days project'],
        ['name' => 'Polyurethane Roof Coating', 'note' => 'Premium service', 'description' => 'Heavy-duty PU liquid coating with reinforcing mesh for lifetime seal.', 'category' => 'Residential', 'price' => 'PHP 650 / sqm', 'availability' => '4-6 days project'],

        // HydroGuard Commercial
        ['name' => 'Basement Pressure Grouting', 'note' => 'Heavy commercial', 'description' => 'Polyurethane crack injection to block active water leaks under hydrostatic pressure.', 'category' => 'Commercial', 'price' => 'Custom Quote', 'availability' => 'Requires audit'],
        ['name' => 'Elevator Pit Sealing', 'note' => 'Safety critical', 'description' => 'Negative-side crystalline waterproofing and active waterstop installation.', 'category' => 'Commercial', 'price' => 'Custom Quote', 'availability' => 'Under 2 days'],
        ['name' => 'Crystalline Foundation Barrier', 'note' => 'Standard commercial', 'description' => 'Crystalline chemical application forming concrete-pore sealants deep inside walls.', 'category' => 'Commercial', 'price' => 'PHP 550 / sqm', 'availability' => '3-5 days project'],
        ['name' => 'Retaining Wall Coating', 'note' => 'Exterior seal', 'description' => 'Bentonite sheets and bitumen protection membranes for earth-retaining structures.', 'category' => 'Commercial', 'price' => 'Custom Quote', 'availability' => 'Blueprints required'],

        // DryMax Interior
        ['name' => 'Tile-Over Bathroom Seal', 'note' => 'Interior best seller', 'description' => 'No-hacking bathroom floor waterproofing system using high-penetration sealants.', 'category' => 'Interior', 'price' => 'PHP 8,500 / toilet', 'availability' => '1 day completion'],
        ['name' => 'Epoxy Grouting & Seal', 'note' => 'Leak preventative', 'description' => 'Removal of old grout and replacement with waterproof, chemical-resistant epoxy grout.', 'category' => 'Interior', 'price' => 'PHP 1,500 / toilet', 'availability' => 'Under 4 hours'],
        ['name' => 'Window Frame Sealing', 'note' => 'Minor repair', 'description' => 'Polyurethane caulking sealant to frame joint borders to prevent rain ingress.', 'category' => 'Interior', 'price' => 'PHP 150 / m', 'availability' => '1-2 hours project'],
        ['name' => 'Under-Sink Leak Repair', 'note' => 'Emergency sealing', 'description' => 'Rapid set hydraulic cement and flexible pipe sealing compound repairs.', 'category' => 'Interior', 'price' => 'PHP 2,500 / leak', 'availability' => 'Under 3 hours'],
    ];
@endphp

@section('page_title', $pageTitle)
@section('breadcrumbs', 'Admin / Services Catalog')

@section('content')
    <div class="space-y-8 bg-base-200 px-4 py-8">

        {{-- Page heading --}}
        <section class="space-y-2">
            <h1 class="text-3xl font-black tracking-tight text-gray-900 sm:text-4xl">{{ $pageTitle }}</h1>
            <p class="max-w-2xl text-sm leading-6 text-gray-500 sm:text-base">{{ $pageDesc }}</p>
        </section>

        {{-- Header bar --}}
        <section class="card bg-base-100 shadow-sm">
            <div class="card-body gap-6 p-6 lg:p-8">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                    <div class="flex items-center gap-3">
                        <h2 class="text-2xl font-bold text-gray-900">{{ $businessName }}</h2>
                        <span class="badge badge-success badge-outline">Active</span>
                    </div>
                    <div class="flex flex-wrap sm:flex-nowrap items-center gap-2 shrink-0">
                        <a href="{{ route('admin.dashboard') }}"
                            class="btn btn-outline rounded-full border-gray-300 text-gray-700 hover:border-brand-border hover:bg-[#f5f3ff] hover:text-gray-900 whitespace-nowrap">
                            <svg viewBox="0 0 24 24" class="h-4 w-4 fill-current" aria-hidden="true">
                                <path d="M19 11H8.41l4.3-4.29L11.29 5 5 11.29l6.29 6.29 1.42-1.42-4.3-4.3H19v-2z" />
                            </svg>
                            Back to dashboard
                        </a>
                    </div>
                </div>
            </div>
        </section>

        {{-- Main content: Products table --}}
        <section class="card bg-base-100 shadow-sm">
            <div class="card-body gap-6 p-6 lg:p-8">
                {{-- Products & Services Panel --}}
                <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Services Catalog</h2>
                        <p class="mt-1 text-sm leading-6 text-gray-500">Manage published items, archive older entries, and keep customer-facing availability up to date.</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-3 lg:justify-end">
                        <button type="button"
                            class="btn rounded-full border-0 bg-brand-primary whitespace-nowrap text-white hover:bg-[#6d28d9]">Active items</button>
                        <button type="button"
                            class="btn btn-outline rounded-full border-gray-300 whitespace-nowrap text-gray-700 hover:bg-[#f5f3ff]">Archived items</button>
                        <button type="button"
                            class="btn rounded-full border-0 bg-brand-primary whitespace-nowrap text-white hover:bg-[#6d28d9]">
                            <svg viewBox="0 0 24 24" class="h-4 w-4 fill-current" aria-hidden="true">
                                <path d="M19 11H13V5h-2v6H5v2h6v6h2v-6h6z" />
                            </svg>
                            Add service
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="table table-zebra">
                        <thead>
                            <tr class="text-gray-500">
                                <th>Service Item</th>
                                <th>Description</th>
                                <th>Classification</th>
                                <th>Price Range</th>
                                <th>Project Duration</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr>
                                    <td>
                                        <div class="space-y-1">
                                            <div class="font-semibold text-gray-900">{{ $product['name'] }}</div>
                                            <div class="text-sm text-gray-500">{{ $product['note'] }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="max-w-[320px] truncate text-sm leading-6 text-gray-600">
                                            {{ $product['description'] }}</div>
                                    </td>
                                    <td class="whitespace-nowrap text-sm text-gray-600">
                                        <span class="badge badge-outline border-slate-300 text-slate-700">{{ $product['category'] }}</span>
                                    </td>
                                    <td class="whitespace-nowrap text-sm font-medium text-gray-900">{{ $product['price'] }}</td>
                                    <td>
                                        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold border border-brand-border bg-[#f8fafc] text-brand-primary-dark whitespace-nowrap">
                                            {{ $product['availability'] }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                                            <button type="button"
                                                class="btn btn-outline btn-sm rounded-full border-gray-300 whitespace-nowrap text-gray-700 hover:bg-[#f5f3ff] product-edit-btn">Edit</button>
                                            <button type="button"
                                                class="btn btn-outline btn-sm rounded-full border-gray-300 whitespace-nowrap text-gray-700 hover:bg-[#f5f3ff] product-toggle-btn">Mark unavailable</button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 border-t border-gray-150 pt-4 mt-4">
                    <div class="text-xs text-gray-500">Showing <strong>1</strong> to <strong>{{ count($products) }}</strong> of <strong>{{ count($products) }}</strong> services</div>
                    <div class="join">
                        <button type="button" class="join-item btn btn-xs btn-outline border-gray-300 text-gray-700 hover:bg-[#f5f3ff]" disabled>«</button>
                        <button type="button" class="join-item btn btn-xs btn-active border-0 bg-brand-primary text-white hover:bg-[#6d28d9]">1</button>
                        <button type="button" class="join-item btn btn-xs btn-outline border-gray-300 text-gray-700 hover:bg-[#f5f3ff]" disabled>»</button>
                    </div>
                </div>
            </div>
        </section>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const showToast = (message, title = 'Success!') => {
                const toast = document.createElement('div');
                toast.className = 'fixed bottom-4 right-4 z-50';
                toast.innerHTML = `<div class="alert alert-success bg-brand-primary text-white border-0 shadow-2xl rounded-2xl p-4 flex items-center gap-3"><svg class="h-6 w-6 shrink-0 stroke-current text-white" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><div><span class="font-bold">${title}</span><div class="text-xs text-white/80">${message}</div></div></div>`;
                document.body.appendChild(toast);
                setTimeout(() => { toast.classList.add('opacity-0', 'transition-opacity', 'duration-500'); setTimeout(() => toast.remove(), 500); }, 3000);
            };

            document.querySelectorAll('.product-edit-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const itemName = e.target.closest('tr').querySelector('.font-semibold').textContent;
                    showToast(`Editing panel for "${itemName}" simulated.`, 'Service Edit');
                });
            });

            document.querySelectorAll('.product-toggle-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const row = e.target.closest('tr');
                    const itemName = row.querySelector('.font-semibold').textContent;
                    const badge = row.querySelector('.inline-flex');
                    if (btn.textContent.trim() === 'Mark unavailable') {
                        badge.textContent = 'Unavailable';
                        badge.className = 'inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold border border-rose-200 bg-rose-50 text-rose-700 whitespace-nowrap';
                        btn.textContent = 'Mark available';
                        showToast(`"${itemName}" marked as Unavailable.`, 'Status Updated');
                    } else {
                        badge.textContent = 'Available';
                        badge.className = 'inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold border border-brand-border bg-[#f8fafc] text-brand-primary-dark whitespace-nowrap';
                        btn.textContent = 'Mark unavailable';
                        showToast(`"${itemName}" marked as Available.`, 'Status Updated');
                    }
                });
            });
        });
    </script>
@endsection