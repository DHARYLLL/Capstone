{{-- filepath: c:\Users\dhary\Desktop\Capstone\capstone1\resources\views\admin\businesses\edit.blade.php --}}
@extends('layouts.admin')

@section('content')
    @php
        // Default to dakong-balay if not set
        $currentSlug = $slug ?? 'dakong-balay';

        // Initialize variables based on current selection
        if ($currentSlug === 'villa-carmelita') {
            $businessName = 'Villa Carmelita';
            $businessType = 'Hotel / Villa';
            $businessDesc = 'Comfortable stay options with a welcoming ambiance for guests, families, and tour groups seeking a tranquil escape.';
            $businessImage = 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=900&q=80';
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
            $businessImage = 'https://images.unsplash.com/photo-1576013551627-0cc20b96c2a7?auto=format&fit=crop&w=900&q=80';
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
            $businessImage = 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?auto=format&fit=crop&w=900&q=80';
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
                            Edit the business profile, confirm its current status, and keep services visible to guests and chatbot responses.
                        </p>
                    </div>

                    <div class="flex flex-wrap sm:flex-nowrap items-center gap-2 shrink-0">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline rounded-full border-gray-300 text-gray-700 transition-colors duration-200 hover:border-brand-border hover:bg-[#f5f3ff] hover:text-gray-900 whitespace-nowrap">
                            <svg viewBox="0 0 24 24" class="h-4 w-4 fill-current" aria-hidden="true">
                                <path d="M19 11H8.41l4.3-4.29L11.29 5 5 11.29l6.29 6.29 1.42-1.42-4.3-4.3H19v-2z"/>
                            </svg>
                            Back to dashboard
                        </a>
                        <button type="button" id="save-all-btn" class="btn rounded-full border-0 bg-brand-primary text-white transition-colors duration-200 hover:bg-[#6d28d9] hover:text-white whitespace-nowrap">
                            <svg viewBox="0 0 24 24" class="h-4 w-4 fill-current" aria-hidden="true">
                                <path d="M17 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V7l-4-4zm-5 16a3 3 0 1 1 0-6 3 3 0 0 1 0 6zm3-10H5V5h10v4z"/>
                            </svg>
                            Save all changes
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <section class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <div class="lg:col-span-2">
                <div class="card bg-base-100 shadow-sm">
                    <div class="card-body gap-6 p-6 lg:p-8">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                            <div>
                                <h2 class="text-xl font-bold text-gray-900">Business information</h2>
                                <p class="mt-1 text-sm leading-6 text-gray-500">
                                    Update the core profile, visual identity, and publishing status for this business.
                                </p>
                            </div>
                            <span class="badge badge-success badge-outline self-start">Active</span>
                        </div>

                        <div class="rounded-3xl bg-[#f8fafc] p-4 sm:p-5">
                            <div class="grid gap-4 md:grid-cols-[220px_minmax(0,1fr)] md:items-center">
                                <img src="{{ $businessImage }}" alt="{{ $businessName }} preview"
                                    class="h-44 w-full rounded-2xl object-cover shadow-sm md:h-40">
                                <div class="space-y-3">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="badge badge-success badge-outline">{{ $businessType }}</span>
                                        <span class="badge border-0 bg-brand-primary-light text-brand-primary-dark">Featured business</span>
                                    </div>
                                    <div>
                                        <h3 class="text-2xl font-bold text-gray-900">{{ $businessName }}</h3>
                                        <p class="mt-2 max-w-2xl text-sm leading-6 text-gray-600">
                                            {{ $businessDesc }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <form id="edit-details-form" class="space-y-6">
                            <div class="grid gap-4 md:grid-cols-2">
                                <label class="form-control">
                                    <div class="label">
                                        <span class="label-text font-medium text-gray-700">Business Name</span>
                                    </div>
                                    <input type="text" id="details-name" value="{{ $businessName }}"
                                        class="input input-bordered w-full bg-base-100">
                                </label>

                                <label class="form-control">
                                    <div class="label">
                                        <span class="label-text font-medium text-gray-700">Category</span>
                                    </div>
                                    <select id="details-category" class="select select-bordered w-full bg-base-100">
                                        <option value="Restaurant" @if($businessType === 'Restaurant') selected @endif>
                                            Restaurant</option>
                                        <option value="Swimming Pool" @if($businessType === 'Swimming Pool') selected @endif>
                                            Swimming Pool</option>
                                        <option value="Hotel / Villa" @if($businessType === 'Hotel / Villa') selected @endif>
                                            Hotel / Villa</option>
                                    </select>
                                </label>
                            </div>

                            <label class="form-control">
                                <div class="label">
                                    <span class="label-text font-medium text-gray-700">Description</span>
                                </div>
                                <textarea id="details-description"
                                    class="textarea textarea-bordered min-h-32 w-full bg-base-100">{{ $businessDesc }}</textarea>
                            </label>

                            <div class="grid gap-3 md:grid-cols-[minmax(0,1fr)_auto] md:items-end">
                                <label class="form-control">
                                    <div class="label">
                                        <span class="label-text font-medium text-gray-700">Image</span>
                                    </div>
                                    <input type="text" value="{{ $businessCover }}" readonly
                                        class="input input-bordered w-full bg-base-100 text-gray-600">
                                </label>

                                <button type="button"
                                    class="btn btn-outline rounded-full border-gray-300 text-gray-700 transition-colors duration-200 hover:border-brand-border hover:bg-[#f5f3ff] hover:text-gray-900">
                                    Replace image
                                </button>
                            </div>

                            <div class="rounded-3xl bg-base-200 p-4">
                                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                    <div class="space-y-1">
                                        <div class="font-semibold text-gray-900">Business Status</div>
                                        <p class="text-sm leading-6 text-gray-500">
                                            Active businesses remain visible on the platform and can be referenced in
                                            chatbot discovery.
                                        </p>
                                    </div>
                                    <input type="checkbox" class="toggle toggle-success" checked>
                                </div>
                            </div>

                            <div class="flex flex-col gap-3 sm:flex-row">
                                <button type="submit"
                                    class="btn rounded-full border-0 bg-brand-primary text-white hover:bg-[#6d28d9]">
                                    Save business info
                                </button>
                                <a href="{{ route('admin.dashboard') }}"
                                    class="btn btn-outline rounded-full border-gray-300 text-gray-700 hover:bg-[#f5f3ff]">
                                    Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="card bg-base-100 shadow-sm">
                    <div class="card-body gap-5 p-6">
                        <h3 class="text-lg font-bold text-gray-900">Selected business summary</h3>
                        <div class="space-y-4">
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

                <div class="card bg-base-100 shadow-sm">
                    <div class="card-body gap-4 p-6">
                        <h3 class="text-lg font-bold text-gray-900">Admin notes</h3>
                        <div class="rounded-2xl bg-[#f8fafc] p-4 text-sm leading-6 text-gray-600">
                            Keep the business description short, accurate, and easy to scan. Use consistent naming so the
                            public site and internal records stay aligned.
                        </div>
                        <div class="rounded-2xl bg-emerald-600 p-4 text-sm leading-6 text-white shadow-sm">
                            Chatbot discovery works best when product names, service labels, and business details are
                            updated together and remain visible to guests.
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Room Availability Grid (Villa Carmelita) / Product list (Others) -->
        <section class="card bg-base-100 shadow-sm">
            <div class="card-body gap-6 p-6 lg:p-8">
                @if ($currentSlug === 'villa-carmelita')
                    <!-- Rooms & Availability Panel -->
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between border-b border-gray-100 pb-6">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-950">Rooms & Live Availability</h2>
                            <p class="mt-1 text-sm leading-6 text-gray-500">
                                View room status, filter by categories, and toggle live occupancy states for Villa Carmelita.
                            </p>
                        </div>

                        <!-- Room Status Counter Badges -->
                        <div class="flex flex-wrap gap-2 text-xs font-semibold">
                            <div
                                class="flex items-center gap-1.5 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1.5 text-emerald-800">
                                <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                Available: <span id="count-avail">15</span>
                            </div>
                            <div
                                class="flex items-center gap-1.5 rounded-full border border-amber-200 bg-amber-50 px-3 py-1.5 text-amber-800">
                                <span class="h-2 w-2 rounded-full bg-amber-500"></span>
                                Occupied: <span id="count-occu">4</span>
                            </div>
                            <div
                                class="flex items-center gap-1.5 rounded-full border border-rose-200 bg-rose-50 px-3 py-1.5 text-rose-800">
                                <span class="h-2 w-2 rounded-full bg-rose-500"></span>
                                Maintenance: <span id="count-maint">2</span>
                            </div>
                            <div
                                class="flex items-center gap-1.5 rounded-full border border-gray-200 bg-gray-50 px-3 py-1.5 text-gray-800">
                                Total rooms: 21
                            </div>
                        </div>
                    </div>

                    <!-- Room Filter Controls -->
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between bg-gray-50 p-4 rounded-2xl">
                        <div class="flex flex-wrap gap-1.5" id="room-filter-container">
                            <button type="button"
                                class="btn btn-sm rounded-full filter-btn active border-0 bg-brand-primary text-white hover:bg-[#6d28d9]"
                                data-filter="all">All Rooms</button>
                            <button type="button"
                                class="btn btn-sm rounded-full filter-btn btn-outline border-gray-300 text-gray-700 hover:bg-[#ffffff]"
                                data-filter="Standard">Standard</button>
                            <button type="button"
                                class="btn btn-sm rounded-full filter-btn btn-outline border-gray-300 text-gray-700 hover:bg-[#ffffff]"
                                data-filter="Junior Suite">Junior Suite</button>
                            <button type="button"
                                class="btn btn-sm rounded-full filter-btn btn-outline border-gray-300 text-gray-700 hover:bg-[#ffffff]"
                                data-filter="Deluxe Twin">Deluxe Twin</button>
                            <button type="button"
                                class="btn btn-sm rounded-full filter-btn btn-outline border-gray-300 text-gray-700 hover:bg-[#ffffff]"
                                data-filter="Family Suite">Family Suite</button>
                            <button type="button"
                                class="btn btn-sm rounded-full filter-btn btn-outline border-gray-300 text-gray-700 hover:bg-[#ffffff]"
                                data-filter="Super Deluxe Room">Super Deluxe</button>
                        </div>

                        <div class="text-xs text-gray-500">
                            * Changes reflect immediately in guest booking display and AI chatbot responses.
                        </div>
                    </div>

                    <!-- Room Grid Layout -->
                    <div class="grid gap-4 grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5" id="rooms-grid">
                        @foreach ($rooms as $room)
                            <div class="room-card card border border-gray-150 bg-white hover:shadow-md transition-all rounded-3xl"
                                data-room-number="{{ $room['number'] }}" data-room-type="{{ $room['type'] }}"
                                data-room-price="{{ $room['price'] }}" data-room-status="{{ $room['status'] }}">
                                <div class="card-body p-4 space-y-3">
                                    <div class="flex items-start justify-between">
                                        <div class="space-y-0.5">
                                            <span class="text-xs font-semibold text-gray-400">ROOM</span>
                                            <h4 class="text-lg font-black text-gray-900 tracking-tight">{{ $room['number'] }}</h4>
                                        </div>

                                        <!-- Interactive Status Badge -->
                                        <span
                                            class="badge badge-sm border text-[10px] font-bold px-2.5 py-1.5 room-status-badge {{ $room['status_class'] }}">
                                            {{ $room['status'] }}
                                        </span>
                                    </div>

                                    <div class="space-y-1 text-xs">
                                        <div class="flex justify-between">
                                            <span class="text-gray-500">Category:</span>
                                            <span class="font-medium text-gray-800">{{ $room['type'] }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-500">Rate:</span>
                                            <span class="font-semibold text-gray-950">{{ $room['price'] }}</span>
                                        </div>
                                    </div>

                                    <!-- Status Toggle Dropdown -->
                                    <div class="pt-2 border-t border-gray-100 flex flex-col gap-1.5">
                                        <span class="text-[10px] font-medium text-gray-400">MANAGE STATUS</span>
                                        <select
                                            class="select select-xs select-bordered w-full rounded-lg bg-base-100 text-xs font-medium status-select"
                                            data-room="{{ $room['number'] }}">
                                            <option value="Available" @if($room['status'] === 'Available') selected @endif>Available
                                            </option>
                                            <option value="Occupied" @if($room['status'] === 'Occupied') selected @endif>Occupied
                                            </option>
                                            <option value="Maintenance" @if($room['status'] === 'Maintenance') selected @endif>
                                                Maintenance</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Empty State for Filters -->
                    <div id="no-rooms-alert"
                        class="hidden text-center py-10 rounded-3xl border-2 border-dashed border-gray-200">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <h3 class="mt-2 text-sm font-semibold text-gray-900">No rooms found</h3>
                        <p class="mt-1 text-sm text-gray-500">No rooms match the selected category filter.</p>
                    </div>

                    <!-- Pagination -->
                    <div class="flex flex-col gap-4 border-t border-gray-150 pt-4 sm:flex-row sm:items-center sm:justify-between">
                        <div class="text-xs text-gray-500" id="rooms-pagination-info"></div>
                        <div class="join flex-wrap" id="rooms-pagination-buttons"></div>
                    </div>

                @else
                    <!-- Products & Services Panel (Dakong Balay & Monclaire Pool) -->
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">Products & services for {{ $businessName }}</h2>
                            <p class="mt-1 text-sm leading-6 text-gray-500">
                                Manage published items, archive older entries, and keep customer-facing availability up to date.
                            </p>
                        </div>

                        <div class="flex flex-wrap items-center gap-3 lg:justify-end">
                            <button type="button"
                                class="btn rounded-full border-0 bg-brand-primary whitespace-nowrap text-white transition-colors duration-200 hover:bg-[#6d28d9] hover:text-white">Active items</button>
                            <button type="button"
                                class="btn btn-outline rounded-full border-gray-300 whitespace-nowrap text-gray-700 transition-colors duration-200 hover:border-brand-border hover:bg-[#f5f3ff] hover:text-gray-900">Archived items</button>
                            <button type="button" class="btn rounded-full border-0 bg-brand-primary whitespace-nowrap text-white transition-colors duration-200 hover:bg-[#6d28d9] hover:text-white">
                                <svg viewBox="0 0 24 24" class="h-4 w-4 fill-current" aria-hidden="true">
                                    <path d="M19 11H13V5h-2v6H5v2h6v6h2v-6h6z" />
                                </svg>
                                Add item
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="table table-zebra">
                            <thead>
                                <tr class="text-gray-500">
                                    <th>Item</th>
                                    <th>Description</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Availability</th>
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
                                                {{ $product['description'] }}
                                            </div>
                                        </td>
                                        <td class="whitespace-nowrap text-sm text-gray-600">{{ $product['category'] }}</td>
                                        <td class="whitespace-nowrap text-sm font-medium text-gray-900">{{ $product['price'] }}</td>
                                        <td>
                                            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold border border-brand-border bg-[#f8fafc] text-brand-primary-dark whitespace-nowrap">
                                                {{ $product['availability'] }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                                                <button type="button" class="btn btn-outline btn-sm rounded-full border-gray-300 whitespace-nowrap text-gray-700 transition-colors duration-200 hover:border-brand-border hover:bg-[#f5f3ff] hover:text-gray-900 product-edit-btn">
                                                    Edit details
                                                </button>
                                                <button type="button" class="btn btn-outline btn-sm rounded-full border-gray-300 whitespace-nowrap text-gray-700 transition-colors duration-200 hover:border-brand-border hover:bg-[#f5f3ff] hover:text-gray-900 product-toggle-btn">
                                                    Mark unavailable
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 border-t border-gray-150 pt-4 mt-4">
                        <div class="text-xs text-gray-500">
                            Showing <strong>1</strong> to <strong>4</strong> of <strong>4</strong> items
                        </div>
                        <div class="join">
                            <button type="button" class="join-item btn btn-xs btn-outline border-gray-300 text-gray-700 transition-colors duration-200 hover:border-brand-border hover:bg-[#f5f3ff] hover:text-gray-900" disabled>«</button>
                            <button type="button" class="join-item btn btn-xs btn-active border-0 bg-brand-primary text-white transition-colors duration-200 hover:bg-[#6d28d9] hover:text-white">1</button>
                            <button type="button" class="join-item btn btn-xs btn-outline border-gray-300 text-gray-700 transition-colors duration-200 hover:border-brand-border hover:bg-[#f5f3ff] hover:text-gray-900" disabled>»</button>
                        </div>
                    </div>
                @endif
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

            // Form submit simulator
            const detailsForm = document.querySelector('#edit-details-form');
            if (detailsForm) {
                detailsForm.addEventListener('submit', (e) => {
                    e.preventDefault();
                    const name = document.querySelector('#details-name').value;
                    showToast(`Business profile updated for ${name}.`, 'Profile Saved');
                });
            }

            // Product buttons simulators (for other businesses)
            document.querySelectorAll('.product-edit-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const row = e.target.closest('tr');
                    const itemName = row.querySelector('.font-semibold').textContent;
                    showToast(`Editing panel for "${itemName}" simulated.`, 'Product Edit');
                });
            });

            document.querySelectorAll('.product-toggle-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const row = e.target.closest('tr');
                    const itemName = row.querySelector('.font-semibold').textContent;
                    const badge = row.querySelector('.badge');

                    if (badge.textContent === 'Unavailable') {
                        badge.textContent = 'Available';
                        badge.className = 'badge badge-outline border-brand-border bg-[#f8fafc] text-brand-primary-dark';
                        btn.textContent = 'Mark unavailable';
                        showToast(`"${itemName}" is now marked as Available.`, 'Status Updated');
                    } else {
                        badge.textContent = 'Unavailable';
                        badge.className = 'badge badge-outline border-rose-200 bg-rose-50 text-rose-700';
                        btn.textContent = 'Mark available';
                        showToast(`"${itemName}" is now marked as Unavailable.`, 'Status Updated');
                    }
                });
            });

            // Rooms filters & managers (only when Villa Carmelita is selected)
            if (document.querySelector('#rooms-grid')) {
                const roomsPerPage = 8;
                let currentFilter = 'all';
                let currentPage = 1;
                const filterButtons = document.querySelectorAll('.filter-btn');
                const roomCards = Array.from(document.querySelectorAll('.room-card'));
                const noRoomsAlert = document.querySelector('#no-rooms-alert');
                const paginationInfo = document.querySelector('#rooms-pagination-info');
                const paginationButtons = document.querySelector('#rooms-pagination-buttons');

                const getFilteredCards = () => roomCards.filter(card => {
                    const type = card.getAttribute('data-room-type');
                    return currentFilter === 'all' || type === currentFilter;
                });

                const setFilterButtonState = (activeButton) => {
                    filterButtons.forEach(button => {
                        button.classList.remove('active', 'bg-brand-primary', 'text-white');
                        button.classList.add('btn-outline', 'border-gray-300', 'text-gray-700');
                    });

                    activeButton.classList.add('active', 'bg-brand-primary', 'text-white');
                    activeButton.classList.remove('btn-outline', 'border-gray-300', 'text-gray-700');
                };

                const renderPagination = () => {
                    const filteredCards = getFilteredCards();
                    const totalItems = filteredCards.length;
                    const totalPages = Math.max(1, Math.ceil(totalItems / roomsPerPage));

                    currentPage = Math.min(Math.max(currentPage, 1), totalPages);

                    roomCards.forEach(card => card.classList.add('hidden'));

                    if (totalItems === 0) {
                        noRoomsAlert.classList.remove('hidden');

                        if (paginationInfo) {
                            paginationInfo.textContent = '';
                        }

                        if (paginationButtons) {
                            paginationButtons.innerHTML = '';
                        }

                        return;
                    }

                    noRoomsAlert.classList.add('hidden');

                    const startIndex = (currentPage - 1) * roomsPerPage;
                    const endIndex = Math.min(startIndex + roomsPerPage, totalItems);

                    filteredCards.slice(startIndex, endIndex).forEach(card => card.classList.remove('hidden'));

                    if (paginationInfo) {
                        paginationInfo.innerHTML = `Showing <strong>${startIndex + 1}</strong> to <strong>${endIndex}</strong> of <strong>${totalItems}</strong> rooms`;
                    }

                    if (!paginationButtons) {
                        return;
                    }

                    paginationButtons.innerHTML = '';

                    const createPageButton = (label, page, isActive = false, isDisabled = false, ariaLabel = null) => {
                        const button = document.createElement('button');
                        button.type = 'button';
                        button.textContent = label;
                        button.className = isActive
                            ? 'join-item btn btn-xs btn-active border-0 bg-brand-primary text-white hover:bg-[#6d28d9]'
                            : 'join-item btn btn-xs btn-outline border-gray-300 text-gray-700 hover:bg-[#f5f3ff]';

                        if (ariaLabel) {
                            button.setAttribute('aria-label', ariaLabel);
                        }

                        if (isDisabled) {
                            button.disabled = true;
                        } else {
                            button.addEventListener('click', () => {
                                currentPage = page;
                                renderPagination();
                            });
                        }

                        return button;
                    };

                    paginationButtons.appendChild(
                        createPageButton('«', Math.max(1, currentPage - 1), false, currentPage === 1, 'Previous room page')
                    );

                    for (let page = 1; page <= totalPages; page += 1) {
                        paginationButtons.appendChild(
                            createPageButton(String(page), page, page === currentPage, false, `Go to room page ${page}`)
                        );
                    }

                    paginationButtons.appendChild(
                        createPageButton('»', Math.min(totalPages, currentPage + 1), false, currentPage === totalPages, 'Next room page')
                    );
                };

                // Filter logic
                filterButtons.forEach(btn => {
                    btn.addEventListener('click', () => {
                        currentFilter = btn.getAttribute('data-filter');
                        currentPage = 1;

                        setFilterButtonState(btn);
                        renderPagination();
                    });
                });

                // Status selectors live updating simulation
                const statusSelects = document.querySelectorAll('.status-select');

                // Helper function to update count badges
                const updateCounts = () => {
                    let avail = 0;
                    let occu = 0;
                    let maint = 0;

                    document.querySelectorAll('.room-card').forEach(card => {
                        const status = card.getAttribute('data-room-status');
                        if (status === 'Available') avail++;
                        else if (status === 'Occupied') occu++;
                        else if (status === 'Maintenance') maint++;
                    });

                    document.querySelector('#count-avail').textContent = avail;
                    document.querySelector('#count-occu').textContent = occu;
                    document.querySelector('#count-maint').textContent = maint;
                };

                statusSelects.forEach(select => {
                    select.addEventListener('change', (e) => {
                        const newStatus = e.target.value;
                        const roomNum = select.getAttribute('data-room');
                        const card = select.closest('.room-card');
                        const badge = card.querySelector('.room-status-badge');

                        // Set room card status attribute
                        card.setAttribute('data-room-status', newStatus);
                        badge.textContent = newStatus;

                        // Update badge classes
                        if (newStatus === 'Available') {
                            badge.className = 'badge badge-sm border text-[10px] font-bold px-2.5 py-1.5 room-status-badge badge-success bg-emerald-50 text-emerald-700 border-emerald-200';
                        } else if (newStatus === 'Occupied') {
                            badge.className = 'badge badge-sm border text-[10px] font-bold px-2.5 py-1.5 room-status-badge badge-warning bg-amber-50 text-amber-700 border-amber-200';
                        } else if (newStatus === 'Maintenance') {
                            badge.className = 'badge badge-sm border text-[10px] font-bold px-2.5 py-1.5 room-status-badge badge-error bg-rose-50 text-rose-700 border-rose-200';
                        }

                        // Re-run counters
                        updateCounts();

                        // Fire toast
                        showToast(`Status of room ${roomNum} changed to ${newStatus}.`, 'Room Status Updated');
                    });
                });

                setFilterButtonState(document.querySelector('.filter-btn.active') || filterButtons[0]);
                renderPagination();
                updateCounts();
            }
        });
    </script>
@endsection