{{-- filepath: c:\Users\dhary\Desktop\Capstone\capstone1\resources\views\admin\businesses\edit.blade.php --}}
@php
    $currentSlug = $slug ?? 'dakong-balay';

    if ($currentSlug === 'villa-carmelita') {
        $unitName = 'Villa Carmelita';
        $unitType = 'Accommodation / Hotel';
        $unitIcon = '🏨';
        $unitBadgeBg = 'bg-[#F3ECE4] text-[#6B4226]';
        $sidebarExtras = ['Room Availability', 'Rate Configuration', 'Guest Inquiries'];
    } elseif ($currentSlug === 'monclaire-pool') {
        $unitName = 'Monclaire Pool';
        $unitType = 'Facility / Pool';
        $unitIcon = '🏊';
        $unitBadgeBg = 'bg-[#E4EEF3] text-[#2A5F7A]';
        $sidebarExtras = ['Pool Schedule', 'Pass & Rental Rates', 'Guest Inquiries'];
    } else {
        $unitName = 'Dakong Balay';
        $unitType = 'Food & Restaurant';
        $unitIcon = '🍽️';
        $unitBadgeBg = 'bg-[#F4EBE0] text-[#7A4A2B]';
        $sidebarExtras = ['Menu Management', 'Dining Availability', 'Guest Inquiries'];
    }
    $isManager = request()->routeIs('admin.businesses.manager-*');
@endphp

@extends($isManager ? 'layouts.manager' : 'layouts.admin')

@section('page_title', ($isManager ? $unitName . ' — ' : '') . 'Edit Business Profile')
@section('page_description', 'Edit business profile for ' . $unitType . ' · ' . $unitName)
@section('breadcrumbs', $isManager ? 'Manager / ' . $unitName . ' / Edit Business Profile' : 'Admin / Businesses / ' . $unitName . ' / Edit Business Profile')
@if ($isManager)
    @section('unit-type', $unitType)
@endif

@if ($isManager)
@section('manager-sidebar')
    <aside class="hidden w-72 shrink-0 flex-col border-r border-gray-200 bg-base-100 md:flex">

        {{-- Brand + unit identity --}}
        <div class="border-b border-gray-200 px-6 py-5">
            <a href="{{ route('admin.businesses.manager-dashboard', $currentSlug) }}" class="flex items-center gap-3">
                <div
                    class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[#1e293b] text-xl text-white shadow-sm">
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
                        'label' => 'Overview',
                        'href' => route('admin.businesses.manager-dashboard', $currentSlug),
                        'icon' => 'M4 13h7V4H4v9zm0 7h7v-5H4v5zm9 0h7V11h-7v9zm0-16v5h7V4h-7z',
                        'active' => request()->routeIs('admin.businesses.manager-dashboard'),
                    ],
                    [
                        'label' => 'Knowledge Base',
                        'href' => route('admin.businesses.manager-knowledge-base', $currentSlug),
                        'icon' => 'M12 2a7 7 0 0 0-7 7v13h14V9a7 7 0 0 0-7-7zm-2 8h4v2h-4v-2zm0 4h4v2h-4v-2z',
                        'active' => request()->routeIs('admin.businesses.manager-knowledge-base'),
                    ],
                    [
                        'label' => 'Live Chat & Handoff',
                        'href' => route('admin.businesses.manager-chat', $currentSlug),
                        'icon' => 'M4 4h16v12H7l-3 3V4zm4 5h8v2H8V9zm0 4h6v2H8v-2z',
                        'active' => request()->routeIs('admin.businesses.manager-chat'),
                    ],
                    [
                        'label' => 'Analytics',
                        'href' => route('admin.businesses.manager-analytics', $currentSlug),
                        'icon' => 'M12 3C7.03 3 3 6.58 3 11c0 2.47 1.22 4.7 3.22 6.29L5 21l3.9-1.96c.97.25 2 .38 3.1.38 4.97 0 9-3.58 9-8s-4.03-8-9-8zm-3 9H7v-2h2v2zm4 0h-2v-2h2v2zm4 0h-2v-2h2v2z',
                        'active' => request()->routeIs('admin.businesses.manager-analytics'),
                    ],
                    [
                        'label' => 'Edit Business Profile',
                        'href' => route('admin.businesses.manager-edit', $currentSlug),
                        'icon' => 'M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zm18-10.5a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z',
                        'active' => request()->routeIs('admin.businesses.manager-edit'),
                    ],
                    [
                        'label' => 'Products & Services',
                        'href' => route('admin.businesses.manager-products', $currentSlug),
                        'icon' => 'M19 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2zm-7 3a3 3 0 1 1 0 6 3 3 0 0 1 0-6zm6 12H6v-.5c0-2 4-3.1 6-3.1s6 1.1 6 3.1V18z',
                        'active' => false,
                    ],
                    [
                        'label' => 'Staff & Roles',
                        'href' => route('admin.businesses.manager-staff', $currentSlug),
                        'icon' => 'M12 12a4 4 0 1 0-4-4 4 4 0 0 0 4 4zm0 2c-4.4 0-8 2.2-8 5v1h16v-1c0-2.8-3.6-5-8-5z',
                        'active' => request()->routeIs('admin.businesses.manager-staff'),
                    ],
                    [
                        'label' => 'Logs',
                        'href' => route('admin.businesses.manager-logs', $currentSlug),
                        'icon' => 'M4 6h16v2H4zm0 5h16v2H4zm0 5h16v2H4z',
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
                            <a href="{{ $item['href'] }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium transition-colors
                                          {{ $item['active']
                    ? 'bg-[#1e293b] font-semibold text-white shadow-sm'
                    : 'text-gray-700 hover:bg-[#f5f3ff] hover:text-[#1e293b]' }}">
                                <svg viewBox="0 0 24 24" class="h-5 w-5 shrink-0 fill-current" aria-hidden="true">
                                    <path d="{{ $item['icon'] }}" />
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
                    <path d="M10 17l1.4-1.4L8.8 13H20v-2H8.8l2.6-2.6L10 7l-5 5 5 5zM4 4h7v2H6v12h5v2H4V4z" />
                </svg>
                Logout
            </button>
        </div>
    </aside>
@endsection
@endif

@section('content')
    @php
        // Initialize variables based on current selection
        if ($currentSlug === 'villa-carmelita') {
            $businessName = 'Villa Carmelita';
            $businessType = 'Hotel / Villa';
            $businessDesc = 'Comfortable stay options with a welcoming ambiance for guests, families, and tour groups seeking a tranquil escape.';
            $businessCover = 'villa-carmelita-cover.jpg';
            $activeCountText = '21 rooms currently managed';

            // Define rooms
            $rooms = [
                // Standard Rooms
                ['number' => 'RM 310', 'type' => 'Standard', 'price' => 'PHP 1,800', 'status' => 'Available', 'status_class' => 'badge-success bg-emerald-50 text-emerald-700 border-emerald-200'],
                ['number' => 'RM 312', 'type' => 'Standard', 'price' => 'PHP 1,800', 'status' => 'Available', 'status_class' => 'badge-success bg-emerald-50 text-emerald-700 border-emerald-200'],
                ['number' => 'RM 314', 'type' => 'Standard', 'price' => 'PHP 1,800', 'status' => 'Occupied', 'status_class' => 'badge-warning bg-amber-50 text-amber-700 border-amber-200'],
                ['number' => 'RM 315', 'type' => 'Standard', 'price' => 'PHP 1,800', 'status' => 'Available', 'status_class' => 'badge-success bg-emerald-50 text-emerald-700 border-emerald-200'],

                // Junior Suites
                ['number' => 'RM 301', 'type' => 'Junior Suite', 'price' => 'PHP 1,950', 'status' => 'Available', 'status_class' => 'badge-success bg-emerald-50 text-emerald-700 border-emerald-200'],
                ['number' => 'RM 308', 'type' => 'Junior Suite', 'price' => 'PHP 1,950', 'status' => 'Occupied', 'status_class' => 'badge-warning bg-amber-50 text-amber-700 border-amber-200'],

                // Deluxe Twin
                ['number' => 'RM 302', 'type' => 'Deluxe Twin', 'price' => 'PHP 2,250', 'status' => 'Available', 'status_class' => 'badge-success bg-emerald-50 text-emerald-700 border-emerald-200'],
                ['number' => 'RM 303', 'type' => 'Deluxe Twin', 'price' => 'PHP 2,250', 'status' => 'Available', 'status_class' => 'badge-success bg-emerald-50 text-emerald-700 border-emerald-200'],
                ['number' => 'RM 304', 'type' => 'Deluxe Twin', 'price' => 'PHP 2,250', 'status' => 'Maintenance', 'status_class' => 'badge-error bg-rose-50 text-rose-700 border-rose-200'],
                ['number' => 'RM 305', 'type' => 'Deluxe Twin', 'price' => 'PHP 2,250', 'status' => 'Available', 'status_class' => 'badge-success bg-emerald-50 text-emerald-700 border-emerald-200'],
                ['number' => 'RM 306', 'type' => 'Deluxe Twin', 'price' => 'PHP 2,250', 'status' => 'Occupied', 'status_class' => 'badge-warning bg-amber-50 text-amber-700 border-amber-200'],
                ['number' => 'RM 307', 'type' => 'Deluxe Twin', 'price' => 'PHP 2,250', 'status' => 'Available', 'status_class' => 'badge-success bg-emerald-50 text-emerald-700 border-emerald-200'],
                ['number' => 'RM 309', 'type' => 'Deluxe Twin', 'price' => 'PHP 2,250', 'status' => 'Available', 'status_class' => 'badge-success bg-emerald-50 text-emerald-700 border-emerald-200'],
                ['number' => 'RM 311', 'type' => 'Deluxe Twin', 'price' => 'PHP 2,250', 'status' => 'Occupied', 'status_class' => 'badge-warning bg-amber-50 text-amber-700 border-amber-200'],

                // Family Suites
                ['number' => 'RM 201', 'type' => 'Family Suite', 'price' => 'PHP 3,500', 'status' => 'Available', 'status_class' => 'badge-success bg-emerald-50 text-emerald-700 border-emerald-200'],
                ['number' => 'RM 202', 'type' => 'Family Suite', 'price' => 'PHP 3,500', 'status' => 'Occupied', 'status_class' => 'badge-warning bg-amber-50 text-amber-700 border-amber-200'],
                ['number' => 'RM 206', 'type' => 'Family Suite', 'price' => 'PHP 3,500', 'status' => 'Available', 'status_class' => 'badge-success bg-emerald-50 text-emerald-700 border-emerald-200'],
                ['number' => 'RM 207', 'type' => 'Family Suite', 'price' => 'PHP 3,500', 'status' => 'Maintenance', 'status_class' => 'badge-error bg-rose-50 text-rose-700 border-rose-200'],

                // Super Deluxe Room
                ['number' => 'RM 203', 'type' => 'Super Deluxe Room', 'price' => 'PHP 3,000', 'status' => 'Available', 'status_class' => 'badge-success bg-emerald-50 text-emerald-700 border-emerald-200'],
                ['number' => 'RM 204', 'type' => 'Super Deluxe Room', 'price' => 'PHP 3,000', 'status' => 'Occupied', 'status_class' => 'badge-warning bg-amber-50 text-amber-700 border-amber-200'],
                ['number' => 'RM 205', 'type' => 'Super Deluxe Room', 'price' => 'PHP 3,000', 'status' => 'Available', 'status_class' => 'badge-success bg-emerald-50 text-emerald-700 border-emerald-200'],
            ];
        } else if ($currentSlug === 'monclaire-pool') {
            $businessName = 'Monclaire Pool';
            $businessType = 'Swimming Pool';
            $businessDesc = 'A relaxing space for leisure, family gatherings, and refreshing weekend escapes.';
            $businessCover = 'monclaire-pool-cover.jpg';
            $activeCountText = '6 items currently published';

            $products = [
                [
                    'name' => 'Day Pass - Adult',
                    'note' => 'Standard access',
                    'description' => 'Single day admission to the main pool area and lounge spaces for adults.',
                    'category' => 'Pass',
                    'price' => 'PHP 150',
                    'availability' => 'Available daily',
                    'availability_class' => 'badge-outline border-brand-border bg-[#f8fafc] text-brand-primary-dark',
                ],
                [
                    'name' => 'Day Pass - Child',
                    'note' => 'Standard access',
                    'description' => 'Single day admission to the pool area for kids under 12. Adult supervision required.',
                    'category' => 'Pass',
                    'price' => 'PHP 100',
                    'availability' => 'Available daily',
                    'availability_class' => 'badge-outline border-brand-border bg-[#f8fafc] text-brand-primary-dark',
                ],
                [
                    'name' => 'Private Gazebo',
                    'note' => 'Shaded rental',
                    'description' => 'Reserved poolside gazebo with table, seating, and privacy curtains.',
                    'category' => 'Rental',
                    'price' => 'PHP 800',
                    'availability' => 'By schedule',
                    'availability_class' => 'badge-outline border-brand-border bg-[#f8fafc] text-brand-primary-dark',
                ],
                [
                    'name' => 'Pool Party Package',
                    'note' => 'Event package',
                    'description' => 'Exclusive group rental package with food platters, tables, and pool access for up to 20 guests.',
                    'category' => 'Event',
                    'price' => 'PHP 5,000',
                    'availability' => 'Reserve in advance',
                    'availability_class' => 'badge-outline border-brand-border bg-[#f8fafc] text-brand-primary-dark',
                ],
            ];
        } else {
            $businessName = 'Dakong Balay';
            $businessType = 'Restaurant';
            $businessDesc = 'Authentic Filipino cuisine and family-style dining in a warm, inviting setting.';
            $businessCover = 'dakong-balay-cover.jpg';
            $activeCountText = '12 products currently published';

            $products = [
                [
                    'name' => 'Chicken Inasal Meal',
                    'note' => 'Main dish',
                    'description' => 'Grilled chicken marinated in native spices, served with rice, dipping sauce, and fresh sides.',
                    'category' => 'Meal',
                    'price' => 'PHP 220',
                    'availability' => 'Available today',
                    'availability_class' => 'badge-outline border-brand-border bg-[#f8fafc] text-brand-primary-dark',
                ],
                [
                    'name' => 'Pork Sisig Platter',
                    'note' => 'Best seller',
                    'description' => 'Sizzling sisig served on a sharing platter, ideal for groups and a strong customer favorite.',
                    'category' => 'Sharing plate',
                    'price' => 'PHP 340',
                    'availability' => 'Limited stock',
                    'availability_class' => 'badge-outline border-brand-border bg-[#f8fafc] text-brand-primary-dark',
                ],
                [
                    'name' => 'Family Set Menu',
                    'note' => 'Bundle offering',
                    'description' => 'A complete food bundle for family dining with multiple mains, rice, and shared sides.',
                    'category' => 'Bundle',
                    'price' => 'PHP 1,250',
                    'availability' => 'Available daily',
                    'availability_class' => 'badge-outline border-brand-border bg-[#f8fafc] text-brand-primary-dark',
                ],
                [
                    'name' => 'Private Dining Area',
                    'note' => 'Service feature',
                    'description' => 'Reserved dining service for special gatherings, private meals, and small celebration setups.',
                    'category' => 'Service',
                    'price' => 'PHP 800',
                    'availability' => 'By schedule',
                    'availability_class' => 'badge-outline border-brand-border bg-[#f8fafc] text-brand-primary-dark',
                ],
            ];
        }

        $businessSummary = [
            ['label' => 'Business', 'value' => $businessName],
            ['label' => 'Type', 'value' => $businessType],
            ['label' => 'Active items', 'value' => $activeCountText],
            ['label' => 'Last updated', 'value' => 'Today - 11:24 AM'],
        ];
    @endphp

    <div class="space-y-8 bg-base-200 border-rounded px-4 py-8">
        <section class="space-y-2">
            <h1 class="text-3xl font-black tracking-tight text-gray-900 sm:text-4xl">Business Details</h1>
            <p class="max-w-2xl text-sm leading-6 text-gray-500 sm:text-base">
                Review and update business information, manage visibility, and keep the product catalog aligned with the
                latest record.
            </p>
        </section>

        <section class="card bg-base-100 shadow-sm">
            <div class="card-body gap-6 p-6 lg:p-8">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                    <div class="space-y-2 min-w-0 flex-1">
                        <div class="flex items-center gap-3">
                            <h2 class="text-2xl font-bold text-gray-900">{{ $businessName }} selected</h2>
                            <span class="badge badge-success badge-outline">Active</span>
                        </div>
                        <p class="max-w-3xl text-sm leading-6 text-gray-500">
                            Edit the business profile, confirm its current status, and keep services visible to guests and
                            chatbot responses.
                        </p>
                    </div>

                    <div class="flex flex-wrap sm:flex-nowrap items-center gap-2 shrink-0">
                        <a href="{{ route('admin.businesses.manager-dashboard', $currentSlug) }}"
                            class="btn btn-outline rounded-full border-gray-300 text-gray-700 transition-colors duration-200 hover:border-brand-border hover:bg-[#f5f3ff] hover:text-gray-900 whitespace-nowrap">
                            <svg viewBox="0 0 24 24" class="h-4 w-4 fill-current" aria-hidden="true">
                                <path d="M19 11H8.41l4.3-4.29L11.29 5 5 11.29l6.29 6.29 1.42-1.42-4.3-4.3H19v-2z" />
                            </svg>
                            Back to dashboard
                        </a>
                        <button type="button" id="save-all-btn"
                            class="btn rounded-full border-0 bg-brand-primary text-white transition-colors duration-200 hover:bg-[#6d28d9] hover:text-white whitespace-nowrap">
                            <svg viewBox="0 0 24 24" class="h-4 w-4 fill-current" aria-hidden="true">
                                <path
                                    d="M17 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V7l-4-4zm-5 16a3 3 0 1 1 0-6 3 3 0 0 1 0 6zm3-10H5V5h10v4z" />
                            </svg>
                            Save all changes
                        </button>
                    </div>
                </div>
            </div>
        </section>

        {{-- Business status + summary strip --}}
        <section class="card bg-base-100 shadow-sm">
            <div class="card-body gap-6 p-6 lg:p-8">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">

                    {{-- Status toggle --}}
                    <div class="flex-1 rounded-3xl bg-base-200 p-5">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                            <div class="space-y-1">
                                <div class="font-semibold text-gray-900">Business Status</div>
                                <p class="text-sm leading-6 text-gray-500">
                                    Active businesses remain visible on the platform and can be referenced in chatbot
                                    discovery.
                                </p>
                            </div>
                            <input type="checkbox" class="toggle toggle-success" checked>
                        </div>
                    </div>

                    {{-- Summary rows --}}
                    <div class="w-full lg:w-72 shrink-0">
                        <div class="space-y-3">
                            @foreach ($businessSummary as $row)
                                <div
                                    class="flex items-start justify-between gap-4 border-b border-base-300 pb-3 last:border-b-0 last:pb-0">
                                    <span class="text-sm text-gray-500">{{ $row['label'] }}</span>
                                    <span class="text-right text-sm font-medium text-gray-900">{{ $row['value'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>
        </section>

        {{-- Products & services have moved to /admin/businesses/{slug}/products --}}

        {{-- ─────────────────────────────────────────────────────────────────── --}}
        {{-- Products link card --}}
        {{-- ─────────────────────────────────────────────────────────────────── --}}
        <section class="card bg-base-100 shadow-sm">
            <div class="card-body p-6 lg:p-8">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Products & Services</h2>
                        <p class="mt-1 text-sm text-gray-500">
                            Manage menu items, room availability, or pass pricing for {{ $businessName }}.
                        </p>
                    </div>
                    <a href="{{ route('admin.businesses.manager-products', $currentSlug) }}"
                        class="btn rounded-full border-0 bg-brand-primary text-white hover:bg-[#6d28d9] shrink-0 whitespace-nowrap">
                        <svg viewBox="0 0 24 24" class="h-4 w-4 fill-current" aria-hidden="true">
                            <path
                                d="M19 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2zm-7 3a3 3 0 1 1 0 6 3 3 0 0 1 0-6zm6 12H6v-.5c0-2 4-3.1 6-3.1s6 1.1 6 3.1V18z" />
                        </svg>
                        Manage Products
                    </a>
                </div>
            </div>
        </section>

        {{-- ─────────────────────────────────────────────────────────────────── --}}
        {{-- Landing Page Content Editor --}}
        {{-- ─────────────────────────────────────────────────────────────────── --}}
        <section id="landing-editor" class="card bg-base-100 shadow-sm">
            <div class="card-body gap-6 p-6 lg:p-8">

                {{-- Header --}}
                <div
                    class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between border-b border-gray-100 pb-6">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Landing Page Content</h2>
                        <p class="mt-1 text-sm text-gray-500">
                            Edit the text and labels shown on the public-facing page at /{{ $currentSlug }}.
                        </p>
                    </div>
                    <a href="/{{ $currentSlug }}" target="_blank"
                        class="btn btn-sm btn-outline rounded-full border-gray-300 text-gray-700 hover:bg-[#f5f3ff] shrink-0">
                        Preview public page ↗
                    </a>
                </div>

                {{-- Tab bar --}}
                <div id="lp-tabs" role="tablist" class="flex flex-wrap gap-1.5">
                    <button type="button" role="tab"
                        class="lp-tab btn btn-sm rounded-full bg-[#1e293b] text-white hover:bg-[#334155]" data-tab="hero"
                        aria-selected="true">
                        Hero
                    </button>
                    <button type="button" role="tab"
                        class="lp-tab btn btn-sm rounded-full bg-[#f8fafc] text-gray-700 hover:bg-[#f5f3ff]"
                        data-tab="about" aria-selected="false">
                        About
                    </button>
                    <button type="button" role="tab"
                        class="lp-tab btn btn-sm rounded-full bg-[#f8fafc] text-gray-700 hover:bg-[#f5f3ff]"
                        data-tab="features" aria-selected="false">
                        Feature Cards
                    </button>
                    <button type="button" role="tab"
                        class="lp-tab btn btn-sm rounded-full bg-[#f8fafc] text-gray-700 hover:bg-[#f5f3ff]"
                        data-tab="gallery" aria-selected="false">
                        Gallery
                    </button>
                    <button type="button" role="tab"
                        class="lp-tab btn btn-sm rounded-full bg-[#f8fafc] text-gray-700 hover:bg-[#f5f3ff]"
                        data-tab="room-modal" aria-selected="false">
                        Room Modal
                    </button>
                </div>

                {{-- Tab panels --}}
                <div id="lp-panels">
                    <div id="lp-panel-hero" role="tabpanel" class="lp-panel">
                        <div class="space-y-5">
                            {{-- Row 1: Badge Label + Primary CTA Label --}}
                            <div class="grid gap-4 md:grid-cols-2">
                                <label class="form-control">
                                    <div class="label">
                                        <span class="label-text font-medium text-gray-700">Badge Label</span>
                                    </div>
                                    <input type="text" value="AI-Powered Business Platform"
                                        class="input input-bordered w-full bg-base-100">
                                </label>
                                <label class="form-control">
                                    <div class="label">
                                        <span class="label-text font-medium text-gray-700">Primary CTA Label</span>
                                    </div>
                                    <input type="text" value="Explore Businesses"
                                        class="input input-bordered w-full bg-base-100">
                                </label>
                            </div>

                            {{-- Row 2: Headline (full-width) --}}
                            <label class="form-control">
                                <div class="label">
                                    <span class="label-text font-medium text-gray-700">Headline</span>
                                </div>
                                <textarea rows="2"
                                    class="textarea textarea-bordered w-full bg-base-100">Experience All Our Services in One Smart Platform</textarea>
                            </label>

                            {{-- Row 3: Tagline + Description --}}
                            <div class="grid gap-4 md:grid-cols-2">
                                <label class="form-control">
                                    <div class="label">
                                        <span class="label-text font-medium text-gray-700">Tagline</span>
                                    </div>
                                    <textarea rows="2"
                                        class="textarea textarea-bordered w-full bg-base-100">Explore services, locations, offers, and FAQs through a single polished business experience.</textarea>
                                </label>
                                <label class="form-control">
                                    <div class="label">
                                        <span class="label-text font-medium text-gray-700">Description</span>
                                    </div>
                                    <textarea rows="3"
                                        class="textarea textarea-bordered w-full bg-base-100">Discover services, locations, offers, and FAQs through a polished landing page experience tailored to your business.</textarea>
                                </label>
                            </div>

                            {{-- Row 4: Primary CTA URL + Secondary CTA Label --}}
                            <div class="grid gap-4 md:grid-cols-2">
                                <label class="form-control">
                                    <div class="label">
                                        <span class="label-text font-medium text-gray-700">Primary CTA URL</span>
                                    </div>
                                    <input type="text" value="#businesses" class="input input-bordered w-full bg-base-100">
                                </label>
                                <label class="form-control">
                                    <div class="label">
                                        <span class="label-text font-medium text-gray-700">Secondary CTA Label</span>
                                    </div>
                                    <input type="text" value="Chat with AI" class="input input-bordered w-full bg-base-100">
                                </label>
                            </div>

                            {{-- Row 5: Assistant Title (full-width) --}}
                            <label class="form-control">
                                <div class="label">
                                    <span class="label-text font-medium text-gray-700">Assistant Title</span>
                                </div>
                                <input type="text" value="One AI assistant connecting multiple businesses"
                                    class="input input-bordered w-full bg-base-100">
                            </label>

                            {{-- Row 6: Assistant Description (full-width) --}}
                            <label class="form-control">
                                <div class="label">
                                    <span class="label-text font-medium text-gray-700">Assistant Description</span>
                                </div>
                                <textarea rows="2"
                                    class="textarea textarea-bordered w-full bg-base-100">Ask once and get clear answers about services, locations, offers, and FAQs.</textarea>
                            </label>

                            {{-- Row 7: Cover Photo --}}
                            <div class="grid gap-3 md:grid-cols-[minmax(0,1fr)_auto] md:items-end">
                                <label class="form-control">
                                    <div class="label">
                                        <span class="label-text font-medium text-gray-700">Cover Photo</span>
                                    </div>
                                    <input type="text" value="{{ $businessCover }}" readonly
                                        class="input input-bordered w-full bg-base-100 text-gray-600">
                                </label>
                                <button type="button" disabled
                                    class="btn btn-outline rounded-full border-gray-300 text-gray-400 cursor-not-allowed">Replace
                                    image (UI only)</button>
                            </div>
                        </div>
                    </div>
                    <div id="lp-panel-about" role="tabpanel" class="lp-panel hidden">
                        <div class="space-y-5">
                            <div class="grid gap-4 md:grid-cols-2">
                                <label class="form-control">
                                    <div class="label">
                                        <span class="label-text font-medium text-gray-700">About Badge</span>
                                    </div>
                                    <input type="text" value="About Your Business"
                                        class="input input-bordered w-full bg-base-100">
                                </label>

                                <label class="form-control">
                                    <div class="label">
                                        <span class="label-text font-medium text-gray-700">About Headline</span>
                                    </div>
                                    <input type="text" value="One platform. One assistant. Multiple businesses."
                                        class="input input-bordered w-full bg-base-100">
                                </label>
                            </div>

                            <label class="form-control">
                                <div class="label">
                                    <span class="label-text font-medium text-gray-700">About Description</span>
                                </div>
                                <textarea rows="3"
                                    class="textarea textarea-bordered w-full bg-base-100">Discover services, locations, offers, and FAQs through a polished landing page experience tailored to your business.</textarea>
                            </label>

                            <label class="form-control">
                                <div class="label">
                                    <span class="label-text font-medium text-gray-700">About Quote</span>
                                </div>
                                <textarea rows="2"
                                    class="textarea textarea-bordered w-full bg-base-100">Fast answers, consistent information, and a smoother customer journey.</textarea>
                            </label>
                        </div>
                    </div>
                    <div id="lp-panel-features" role="tabpanel" class="lp-panel hidden">
                        <div class="space-y-6">

                            {{-- Feature Card 1 --}}
                            <div class="rounded-3xl bg-[#f8fafc] p-5 space-y-4">
                                <div class="text-sm font-bold text-gray-700 border-b border-gray-200 pb-2">Feature Card 1
                                </div>
                                <div class="space-y-4">
                                    <div class="grid gap-4 md:grid-cols-2">
                                        <label class="form-control">
                                            <div class="label">
                                                <span class="label-text font-medium text-gray-700">Label</span>
                                            </div>
                                            <input type="text" value="Featured Business One"
                                                class="input input-bordered w-full bg-base-100">
                                        </label>
                                        <label class="form-control">
                                            <div class="label">
                                                <span class="label-text font-medium text-gray-700">Description</span>
                                            </div>
                                            <textarea rows="2"
                                                class="textarea textarea-bordered w-full bg-base-100">A warm dining destination for authentic cuisine, family gatherings, and memorable meals.</textarea>
                                        </label>
                                    </div>
                                    <div class="grid gap-4 md:grid-cols-2">
                                        <label class="form-control">
                                            <div class="label">
                                                <span class="label-text font-medium text-gray-700">Bullet 1</span>
                                            </div>
                                            <input type="text" value="" placeholder="e.g. Key feature or offering"
                                                class="input input-bordered w-full bg-base-100">
                                        </label>
                                        <label class="form-control">
                                            <div class="label">
                                                <span class="label-text font-medium text-gray-700">Bullet 2</span>
                                            </div>
                                            <input type="text" value="" placeholder="e.g. Key feature or offering"
                                                class="input input-bordered w-full bg-base-100">
                                        </label>
                                    </div>
                                    <div class="grid gap-4 md:grid-cols-2">
                                        <label class="form-control">
                                            <div class="label">
                                                <span class="label-text font-medium text-gray-700">Bullet 3</span>
                                            </div>
                                            <input type="text" value="" placeholder="e.g. Key feature or offering"
                                                class="input input-bordered w-full bg-base-100">
                                        </label>
                                        <label class="form-control">
                                            <div class="label">
                                                <span class="label-text font-medium text-gray-700">Bullet 4</span>
                                            </div>
                                            <input type="text" value="" placeholder="e.g. Key feature or offering"
                                                class="input input-bordered w-full bg-base-100">
                                        </label>
                                    </div>
                                </div>
                            </div>

                            {{-- Feature Card 2 --}}
                            <div class="rounded-3xl bg-[#f8fafc] p-5 space-y-4">
                                <div class="text-sm font-bold text-gray-700 border-b border-gray-200 pb-2">Feature Card 2
                                </div>
                                <div class="space-y-4">
                                    <div class="grid gap-4 md:grid-cols-2">
                                        <label class="form-control">
                                            <div class="label">
                                                <span class="label-text font-medium text-gray-700">Label</span>
                                            </div>
                                            <input type="text" value="Featured Business Two"
                                                class="input input-bordered w-full bg-base-100">
                                        </label>
                                        <label class="form-control">
                                            <div class="label">
                                                <span class="label-text font-medium text-gray-700">Description</span>
                                            </div>
                                            <textarea rows="2"
                                                class="textarea textarea-bordered w-full bg-base-100">Relax and enjoy a refreshing experience with amenities ideal for leisure and celebrations.</textarea>
                                        </label>
                                    </div>
                                    <div class="grid gap-4 md:grid-cols-2">
                                        <label class="form-control">
                                            <div class="label">
                                                <span class="label-text font-medium text-gray-700">Bullet 1</span>
                                            </div>
                                            <input type="text" value="" placeholder="e.g. Key feature or offering"
                                                class="input input-bordered w-full bg-base-100">
                                        </label>
                                        <label class="form-control">
                                            <div class="label">
                                                <span class="label-text font-medium text-gray-700">Bullet 2</span>
                                            </div>
                                            <input type="text" value="" placeholder="e.g. Key feature or offering"
                                                class="input input-bordered w-full bg-base-100">
                                        </label>
                                    </div>
                                    <div class="grid gap-4 md:grid-cols-2">
                                        <label class="form-control">
                                            <div class="label">
                                                <span class="label-text font-medium text-gray-700">Bullet 3</span>
                                            </div>
                                            <input type="text" value="" placeholder="e.g. Key feature or offering"
                                                class="input input-bordered w-full bg-base-100">
                                        </label>
                                        <label class="form-control">
                                            <div class="label">
                                                <span class="label-text font-medium text-gray-700">Bullet 4</span>
                                            </div>
                                            <input type="text" value="" placeholder="e.g. Key feature or offering"
                                                class="input input-bordered w-full bg-base-100">
                                        </label>
                                    </div>
                                </div>
                            </div>

                            {{-- Feature Card 3 --}}
                            <div class="rounded-3xl bg-[#f8fafc] p-5 space-y-4">
                                <div class="text-sm font-bold text-gray-700 border-b border-gray-200 pb-2">Feature Card 3
                                </div>
                                <div class="space-y-4">
                                    <div class="grid gap-4 md:grid-cols-2">
                                        <label class="form-control">
                                            <div class="label">
                                                <span class="label-text font-medium text-gray-700">Label</span>
                                            </div>
                                            <input type="text" value="Featured Business Three"
                                                class="input input-bordered w-full bg-base-100">
                                        </label>
                                        <label class="form-control">
                                            <div class="label">
                                                <span class="label-text font-medium text-gray-700">Description</span>
                                            </div>
                                            <textarea rows="2"
                                                class="textarea textarea-bordered w-full bg-base-100">A comfortable hospitality destination for stays, gatherings, and poolside moments.</textarea>
                                        </label>
                                    </div>
                                    <div class="grid gap-4 md:grid-cols-2">
                                        <label class="form-control">
                                            <div class="label">
                                                <span class="label-text font-medium text-gray-700">Bullet 1</span>
                                            </div>
                                            <input type="text" value="" placeholder="e.g. Key feature or offering"
                                                class="input input-bordered w-full bg-base-100">
                                        </label>
                                        <label class="form-control">
                                            <div class="label">
                                                <span class="label-text font-medium text-gray-700">Bullet 2</span>
                                            </div>
                                            <input type="text" value="" placeholder="e.g. Key feature or offering"
                                                class="input input-bordered w-full bg-base-100">
                                        </label>
                                    </div>
                                    <div class="grid gap-4 md:grid-cols-2">
                                        <label class="form-control">
                                            <div class="label">
                                                <span class="label-text font-medium text-gray-700">Bullet 3</span>
                                            </div>
                                            <input type="text" value="" placeholder="e.g. Key feature or offering"
                                                class="input input-bordered w-full bg-base-100">
                                        </label>
                                        <label class="form-control">
                                            <div class="label">
                                                <span class="label-text font-medium text-gray-700">Bullet 4</span>
                                            </div>
                                            <input type="text" value="" placeholder="e.g. Key feature or offering"
                                                class="input input-bordered w-full bg-base-100">
                                        </label>
                                    </div>
                                    <div
                                        class="flex items-center justify-between rounded-2xl bg-white border border-[#e2e8f0] px-4 py-3">
                                        <div>
                                            <div class="text-sm font-medium text-gray-700">Show "Check Rooms &amp; Live
                                                Availability" button</div>
                                            <p class="text-xs text-gray-500 mt-0.5">Displays the room availability modal on
                                                the public page.</p>
                                        </div>
                                        <input type="checkbox" class="toggle toggle-success" checked>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div id="lp-panel-gallery" role="tabpanel" class="lp-panel hidden">
                        <div class="space-y-6">

                            {{-- Gallery Item 1 --}}
                            <div class="rounded-3xl bg-[#f8fafc] p-5 space-y-4">
                                <div class="text-sm font-bold text-gray-700 border-b border-gray-200 pb-2">Gallery Item 1
                                </div>

                                <div class="grid gap-4 md:grid-cols-2">
                                    <label class="form-control">
                                        <div class="label">
                                            <span class="label-text font-medium text-gray-700">Title</span>
                                        </div>
                                        <input type="text" value="Warm Spaces"
                                            class="input input-bordered w-full bg-base-100">
                                    </label>

                                    <label class="form-control">
                                        <div class="label">
                                            <span class="label-text font-medium text-gray-700">Description</span>
                                        </div>
                                        <textarea rows="2"
                                            class="textarea textarea-bordered w-full bg-base-100">Warm spaces and inviting hospitality.</textarea>
                                    </label>
                                </div>

                                <div class="space-y-2">
                                    <div class="label">
                                        <span class="label-text font-medium text-gray-700">Image</span>
                                    </div>
                                    <div
                                        class="h-28 w-full rounded-2xl bg-gradient-to-br from-[#f5f3ff] to-[#e2e8f0] flex items-center justify-center">
                                        <span class="text-xs text-gray-400">No image uploaded</span>
                                    </div>
                                    <button type="button" disabled
                                        class="btn btn-sm btn-outline rounded-full border-gray-300 text-gray-400 cursor-not-allowed">
                                        Upload image (coming soon)
                                    </button>
                                </div>
                            </div>

                            {{-- Gallery Item 2 --}}
                            <div class="rounded-3xl bg-[#f8fafc] p-5 space-y-4">
                                <div class="text-sm font-bold text-gray-700 border-b border-gray-200 pb-2">Gallery Item 2
                                </div>

                                <div class="grid gap-4 md:grid-cols-2">
                                    <label class="form-control">
                                        <div class="label">
                                            <span class="label-text font-medium text-gray-700">Title</span>
                                        </div>
                                        <input type="text" value="Dining Moments"
                                            class="input input-bordered w-full bg-base-100">
                                    </label>

                                    <label class="form-control">
                                        <div class="label">
                                            <span class="label-text font-medium text-gray-700">Description</span>
                                        </div>
                                        <textarea rows="2"
                                            class="textarea textarea-bordered w-full bg-base-100">Memorable meals and shared experiences.</textarea>
                                    </label>
                                </div>

                                <div class="space-y-2">
                                    <div class="label">
                                        <span class="label-text font-medium text-gray-700">Image</span>
                                    </div>
                                    <div
                                        class="h-28 w-full rounded-2xl bg-gradient-to-br from-[#f5f3ff] to-[#e2e8f0] flex items-center justify-center">
                                        <span class="text-xs text-gray-400">No image uploaded</span>
                                    </div>
                                    <button type="button" disabled
                                        class="btn btn-sm btn-outline rounded-full border-gray-300 text-gray-400 cursor-not-allowed">
                                        Upload image (coming soon)
                                    </button>
                                </div>
                            </div>

                            {{-- Gallery Item 3 --}}
                            <div class="rounded-3xl bg-[#f8fafc] p-5 space-y-4">
                                <div class="text-sm font-bold text-gray-700 border-b border-gray-200 pb-2">Gallery Item 3
                                </div>

                                <div class="grid gap-4 md:grid-cols-2">
                                    <label class="form-control">
                                        <div class="label">
                                            <span class="label-text font-medium text-gray-700">Title</span>
                                        </div>
                                        <input type="text" value="Poolside Views"
                                            class="input input-bordered w-full bg-base-100">
                                    </label>

                                    <label class="form-control">
                                        <div class="label">
                                            <span class="label-text font-medium text-gray-700">Description</span>
                                        </div>
                                        <textarea rows="2"
                                            class="textarea textarea-bordered w-full bg-base-100">Relaxing scenes from leisure and stay destinations.</textarea>
                                    </label>
                                </div>

                                <div class="space-y-2">
                                    <div class="label">
                                        <span class="label-text font-medium text-gray-700">Image</span>
                                    </div>
                                    <div
                                        class="h-28 w-full rounded-2xl bg-gradient-to-br from-[#f5f3ff] to-[#e2e8f0] flex items-center justify-center">
                                        <span class="text-xs text-gray-400">No image uploaded</span>
                                    </div>
                                    <button type="button" disabled
                                        class="btn btn-sm btn-outline rounded-full border-gray-300 text-gray-400 cursor-not-allowed">
                                        Upload image (coming soon)
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div id="lp-panel-room-modal" role="tabpanel" class="lp-panel hidden">
                        <div class="space-y-5">
                            {{-- Informational note --}}
                            <div class="rounded-2xl bg-[#f8fafc] border border-[#e2e8f0] px-4 py-3 text-sm text-gray-500">
                                These fields control the modal that appears when a visitor clicks "Check Rooms &amp; Live
                                Availability" on Feature Card 3.
                            </div>

                            {{-- Two-column row: Room Business Name + Modal Title --}}
                            <div class="grid gap-4 md:grid-cols-2">
                                <label class="form-control">
                                    <div class="label">
                                        <span class="label-text font-medium text-gray-700">Room Business Name</span>
                                    </div>
                                    <input type="text" value="Your Business"
                                        class="input input-bordered w-full bg-base-100">
                                </label>

                                <label class="form-control">
                                    <div class="label">
                                        <span class="label-text font-medium text-gray-700">Modal Title</span>
                                    </div>
                                    <input type="text" value="Your Business Rooms &amp; Availability"
                                        class="input input-bordered w-full bg-base-100">
                                </label>
                            </div>

                            {{-- Full-width Modal Description --}}
                            <label class="form-control">
                                <div class="label">
                                    <span class="label-text font-medium text-gray-700">Modal Description</span>
                                </div>
                                <textarea rows="2"
                                    class="textarea textarea-bordered w-full bg-base-100">View real-time room rates and vacancies at Your Business</textarea>
                            </label>

                            {{-- Full-width Inquiry Text --}}
                            <label class="form-control">
                                <div class="label">
                                    <span class="label-text font-medium text-gray-700">Inquiry Text</span>
                                </div>
                                <textarea rows="2"
                                    class="textarea textarea-bordered w-full bg-base-100">For booking inquiries, select "Chat with AI" or call support.</textarea>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div
                    class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between border-t border-gray-100 pt-6">
                    <p class="text-xs text-gray-400">
                        Changes are preview-only until the backend is connected.
                    </p>
                    <button type="button" id="lp-save-btn"
                        class="btn rounded-full border-0 bg-brand-primary text-white hover:bg-[#6d28d9]">
                        Save landing page content
                    </button>
                </div>

            </div>
        </section>

    </div>

    <!-- Scripts to support interactive room management & simulation toasts -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Toast notification creator
            const showToast = (message, title = 'Success!') => {
                const toast = document.createElement('div');
                toast.className = 'fixed bottom-4 right-4 z-50';
                toast.innerHTML = `
                            <div class="alert alert-success bg-brand-primary text-white border-0 shadow-2xl rounded-2xl p-4 flex items-center gap-3">
                                <svg class="h-6 w-6 shrink-0 stroke-current text-white" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <div>
                                    <span class="font-bold">${title}</span>
                                    <div class="text-xs text-white/80">${message}</div>
                                </div>
                            </div>
                        `;
                document.body.appendChild(toast);
                setTimeout(() => {
                    toast.classList.add('opacity-0', 'transition-opacity', 'duration-500');
                    setTimeout(() => toast.remove(), 500);
                }, 3000);
            };

            // Global save simulator
            const saveBtn = document.querySelector('#save-all-btn');
            if (saveBtn) {
                saveBtn.addEventListener('click', () => {
                    showToast('All changes saved successfully to database records.', 'Changes Saved');
                });
            }

            // ── Landing Page Editor — Tab Switching ──────────────────────────
            const lpTabs = document.querySelectorAll('.lp-tab');
            const lpPanels = document.querySelectorAll('.lp-panel');

            lpTabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    // Deactivate all tabs
                    lpTabs.forEach(t => {
                        t.classList.remove('bg-[#1e293b]', 'text-white');
                        t.classList.add('bg-[#f8fafc]', 'text-gray-700');
                        t.setAttribute('aria-selected', 'false');
                    });
                    // Hide all panels
                    lpPanels.forEach(p => p.classList.add('hidden'));

                    // Activate clicked tab
                    tab.classList.add('bg-[#1e293b]', 'text-white');
                    tab.classList.remove('bg-[#f8fafc]', 'text-gray-700');
                    tab.setAttribute('aria-selected', 'true');

                    // Show matching panel
                    const panel = document.getElementById('lp-panel-' + tab.dataset.tab);
                    if (panel) {
                        panel.classList.remove('hidden');
                    }
                });
            });

            // ── Landing Page Editor — Save Handler ───────────────────────────
            const lpSaveBtn = document.getElementById('lp-save-btn');
            if (lpSaveBtn) {
                lpSaveBtn.addEventListener('click', () => {
                    lpSaveBtn.disabled = true;
                    lpSaveBtn.textContent = 'Saving…';

                    setTimeout(() => {
                        showToast('Landing page content updated successfully.', 'Content Saved');
                        lpSaveBtn.disabled = false;
                        lpSaveBtn.textContent = 'Save landing page content';
                    }, 600);
                });
            }
        });
    </script>
@endsection