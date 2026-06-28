{{-- filepath: c:\Users\dhary\Desktop\Capstone\capstone1\resources\views\landing\dynamic.blade.php --}}
{{-- Public tenant landing page — rendered for customers/guests scanning a QR code or visiting a slug URL --}}
@extends('layouts.landing')

@php
    $business = $business ?? null;

    // ── Core identity ──────────────────────────────────────────────────────────
    $businessName        = data_get($business, 'name', 'Your Business');
    $badgeLabel          = data_get($business, 'badge_label', 'AI-Powered Business Platform');
    $businessHeadline    = data_get($business, 'headline', 'Experience All Our Services in One Smart Platform');
    $businessTagline     = data_get($business, 'tagline', 'Explore services, locations, offers, and FAQs through a single polished business experience.');
    $businessDescription = data_get($business, 'description', 'Discover services, locations, offers, and FAQs through a polished landing page experience tailored to your business.');
    $primaryCtaLabel     = data_get($business, 'primary_cta_label', 'Explore Businesses');
    $primaryCtaUrl       = data_get($business, 'primary_cta_url', '#businesses');
    $secondaryCtaLabel   = data_get($business, 'secondary_cta_label', 'Chat with AI');
    $secondaryCtaUrl     = data_get($business, 'secondary_cta_url', '#chatbot');
    $assistantTitle      = data_get($business, 'assistant_title', 'One AI assistant connecting multiple businesses');
    $assistantDescription = data_get($business, 'assistant_description', 'Ask once and get clear answers about services, locations, offers, and FAQs.');

    // ── Cover image ────────────────────────────────────────────────────────────
    $businessCoverPath  = data_get($business, 'cover_photo_path');
    $businessCoverImage = filled($businessCoverPath)
        ? asset('storage/' . ltrim($businessCoverPath, '/'))
        : asset('images/business-fallback.svg');

    // ── About section ──────────────────────────────────────────────────────────
    $aboutBadge       = data_get($business, 'about_badge', 'About ' . $businessName);
    $aboutHeadline    = data_get($business, 'about_headline', 'One platform. One assistant. Multiple businesses.');
    $aboutDescription = data_get($business, 'about_description', $businessDescription);
    $aboutQuote       = data_get($business, 'about_quote', 'Fast answers, consistent information, and a smoother customer journey.');

    // ── Feature cards (up to 3 business highlights) ───────────────────────────
    $features = collect([
        [
            'label'       => data_get($business, 'feature_one_label', 'Featured Business One'),
            'description' => data_get($business, 'feature_one_description', 'A warm dining destination for authentic cuisine, family gatherings, and memorable meals.'),
            'bullets'     => array_filter([
                data_get($business, 'feature_one_bullet_1'),
                data_get($business, 'feature_one_bullet_2'),
                data_get($business, 'feature_one_bullet_3'),
                data_get($business, 'feature_one_bullet_4'),
            ]),
        ],
        [
            'label'       => data_get($business, 'feature_two_label', 'Featured Business Two'),
            'description' => data_get($business, 'feature_two_description', 'Relax and enjoy a refreshing experience with amenities ideal for leisure and celebrations.'),
            'bullets'     => array_filter([
                data_get($business, 'feature_two_bullet_1'),
                data_get($business, 'feature_two_bullet_2'),
                data_get($business, 'feature_two_bullet_3'),
                data_get($business, 'feature_two_bullet_4'),
            ]),
        ],
        [
            'label'       => data_get($business, 'feature_three_label', 'Featured Business Three'),
            'description' => data_get($business, 'feature_three_description', 'A comfortable hospitality destination for stays, gatherings, and poolside moments.'),
            'bullets'     => array_filter([
                data_get($business, 'feature_three_bullet_1'),
                data_get($business, 'feature_three_bullet_2'),
                data_get($business, 'feature_three_bullet_3'),
                data_get($business, 'feature_three_bullet_4'),
            ]),
            'has_rooms'   => (bool) data_get($business, 'feature_three_has_rooms', true),
        ],
    ]);

    // ── Gallery ────────────────────────────────────────────────────────────────
    $galleryItems = collect([
        [
            'title'       => data_get($business, 'gallery_one_title', $features[0]['label']),
            'description' => data_get($business, 'gallery_one_description', 'Warm spaces and inviting hospitality.'),
            'image_path'  => data_get($business, 'gallery_one_image_path'),
            'gradient'    => 'from-brand-primary-light to-brand-bg-base',
        ],
        [
            'title'       => data_get($business, 'gallery_two_title', 'Dining Moments'),
            'description' => data_get($business, 'gallery_two_description', 'Memorable meals and shared experiences.'),
            'image_path'  => data_get($business, 'gallery_two_image_path'),
            'gradient'    => 'from-brand-border to-brand-bg-base',
        ],
        [
            'title'       => data_get($business, 'gallery_three_title', 'Poolside Views'),
            'description' => data_get($business, 'gallery_three_description', 'Relaxing scenes from leisure and stay destinations.'),
            'image_path'  => data_get($business, 'gallery_three_image_path'),
            'gradient'    => 'from-brand-primary-light to-brand-bg-base',
        ],
    ]);

    // ── Footer links ───────────────────────────────────────────────────────────
    $footerBusinesses = $features->map(fn ($f) => [
        'label' => $f['label'],
        'href'  => '#businesses',
    ]);

    // ── Room modal ─────────────────────────────────────────────────────────────
    $roomBusinessName    = data_get($business, 'room_business_name', $businessName);
    $roomModalTitle      = data_get($business, 'room_modal_title', "{$roomBusinessName} Rooms & Availability");
    $roomModalDescription = data_get($business, 'room_modal_description', "View real-time room rates and vacancies at {$roomBusinessName}");
    $roomInquiryText     = data_get($business, 'room_inquiry_text', 'For booking inquiries, select "Chat with AI" or call support.');
@endphp

@section('page_title', $businessName . ' — ' . $badgeLabel)
@section('page_description', $businessDescription)

@section('content')

{{-- ── HERO ──────────────────────────────────────────────────────────────────── --}}
<section id="home" class="mx-auto max-w-7xl px-4 py-10 lg:px-8 lg:py-16">
    <div class="grid items-center gap-12 lg:grid-cols-2">
        <div class="space-y-6">
            <div class="badge border-0 bg-[#f5f3ff] px-4 py-3 text-xs font-semibold text-brand-primary">
                {{ $badgeLabel }}
            </div>

            <div class="space-y-5">
                <h1 class="max-w-2xl text-4xl font-extrabold leading-tight tracking-tight text-gray-900 md:text-6xl">
                    {{ $businessHeadline }}
                </h1>
                <p class="max-w-xl text-base leading-7 text-gray-600 md:text-lg">
                    {{ $businessDescription }}
                </p>
                <p class="max-w-xl text-sm leading-6 text-gray-500 md:text-base">
                    {{ $businessTagline }}
                </p>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row">
                <a href="{{ $primaryCtaUrl }}" class="btn rounded-full border-0 bg-brand-primary px-6 text-white hover:bg-[#6d28d9]">
                    {{ $primaryCtaLabel }}
                </a>
                <label for="chatbot-toggle" class="btn cursor-pointer rounded-full border border-brand-primary bg-transparent px-6 text-brand-primary hover:bg-[#f5f3ff] hover:text-brand-primary">
                    {{ $secondaryCtaLabel }}
                </label>
            </div>

            <div class="rounded-3xl border border-[#e2e8f0] bg-[#f8fafc] p-5 shadow-sm">
                <div class="flex items-start gap-4">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-brand-primary text-white">
                        <svg viewBox="0 0 24 24" class="h-5 w-5 fill-current" aria-hidden="true">
                            <path d="M12 2a4 4 0 0 0-4 4v1H7a3 3 0 0 0-3 3v7a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3v-7a3 3 0 0 0-3-3h-1V6a4 4 0 0 0-4-4zm-2 5V6a2 2 0 1 1 4 0v1h-4zm2 5a1.5 1.5 0 0 1 .75 2.8V16h-1.5v-1.2A1.5 1.5 0 0 1 12 12z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-gray-900">{{ $assistantTitle }}</h2>
                        <p class="mt-1 text-sm leading-6 text-gray-600">{{ $assistantDescription }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-4">
            <div class="overflow-hidden rounded-[2rem] border border-white/70 bg-white shadow-[0_20px_60px_rgba(124,58,237,0.12)]">
                <div class="relative aspect-[4/5] min-h-[320px] overflow-hidden bg-[#f8fafc]">
                    <img src="{{ $businessCoverImage }}" alt="{{ $businessName }} cover photo" class="h-full w-full object-cover">
                    <div class="absolute inset-0 bg-[linear-gradient(180deg,rgba(255,255,255,0.04)_0%,rgba(34,24,17,0.32)_100%)]"></div>
                    <div class="absolute inset-x-0 bottom-0 p-5">
                        <div class="inline-flex rounded-2xl bg-white/80 px-4 py-2 text-sm font-semibold text-gray-800 backdrop-blur">
                            {{ $businessName }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── BUSINESSES / FEATURES ────────────────────────────────────────────────── --}}
<section id="businesses" class="mx-auto max-w-7xl px-4 py-14 lg:px-8">
    <div class="max-w-2xl space-y-3">
        <h2 class="text-3xl font-extrabold tracking-tight text-gray-900 md:text-4xl">Our Businesses</h2>
        <p class="text-gray-600">
            Discover the businesses connected through one intelligent platform for faster browsing, better support, and easier inquiries.
        </p>
    </div>

    <div class="mt-12 space-y-16">
        @foreach ($features as $index => $feature)
            @php $isEven = $index % 2 === 0; @endphp

            <div class="grid items-center gap-8 lg:grid-cols-2">
                {{-- Visual card --}}
                <div class="{{ !$isEven ? 'order-1 lg:order-2' : '' }} rounded-[2rem] bg-gradient-to-br from-brand-primary-light to-brand-border p-6 shadow-lg">
                    <div class="flex min-h-[320px] items-end rounded-[1.6rem] bg-[linear-gradient(135deg,rgba(255,255,255,0.25),rgba(255,255,255,0.08))] p-6">
                        <div class="rounded-2xl bg-white/70 px-4 py-2 text-sm font-semibold text-gray-800">
                            {{ $feature['label'] }}
                        </div>
                    </div>
                </div>

                {{-- Content --}}
                <div class="{{ !$isEven ? 'order-2 lg:order-1' : '' }} space-y-5">
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-brand-primary text-white">
                            @if ($index === 0)
                                <svg viewBox="0 0 24 24" class="h-6 w-6 fill-current" aria-hidden="true"><path d="M4 5h16v2H4V5zm2 3h12v11H6V8zm2 2v7h8v-7H8z"/></svg>
                            @elseif ($index === 1)
                                <svg viewBox="0 0 24 24" class="h-6 w-6 fill-current" aria-hidden="true"><path d="M12 3C8 3 5 6 5 10v10h14V10c0-4-3-7-7-7zm0 4a3 3 0 0 1 3 3v6H9v-6a3 3 0 0 1 3-3z"/></svg>
                            @else
                                <svg viewBox="0 0 24 24" class="h-6 w-6 fill-current" aria-hidden="true"><path d="M4 20h16v-2H4v2zm2-4h12V8l-6-4-6 4v8zm2-2V9.1l4-2.67 4 2.67V14H8z"/></svg>
                            @endif
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900">{{ $feature['label'] }}</h3>
                    </div>

                    <p class="text-gray-600">{{ $feature['description'] }}</p>

                    @if (!empty($feature['bullets']))
                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                            @foreach ($feature['bullets'] as $bullet)
                                <div class="flex items-start gap-3 rounded-2xl bg-white p-4 shadow-sm">
                                    <span class="mt-0.5 text-brand-primary">✓</span>
                                    <p class="text-sm text-gray-700">{{ $bullet }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if (!empty($feature['has_rooms']))
                        <div class="pt-4">
                            <button onclick="room_availability_modal.showModal()" class="btn rounded-full border-0 bg-brand-primary text-white hover:bg-[#6d28d9]">
                                <svg viewBox="0 0 24 24" class="h-5 w-5 fill-current" aria-hidden="true">
                                    <path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"/>
                                </svg>
                                Check Rooms &amp; Live Availability
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</section>

{{-- ── ABOUT ─────────────────────────────────────────────────────────────────── --}}
<section id="about" class="bg-[#f5f3ff] py-16">
    <div class="mx-auto grid max-w-7xl gap-10 px-4 lg:grid-cols-2 lg:px-8">
        <div class="space-y-5">
            <div class="badge border-0 bg-white px-4 py-3 text-xs font-semibold text-brand-primary">
                {{ $aboutBadge }}
            </div>
            <h2 class="max-w-xl text-3xl font-extrabold tracking-tight text-gray-900 md:text-5xl">
                {{ $aboutHeadline }}
            </h2>
            <p class="max-w-xl text-gray-600">
                {{ $aboutDescription }}
            </p>
            <div class="rounded-3xl border border-brand-border bg-white p-6 shadow-sm">
                <p class="text-lg font-semibold text-gray-900">"{{ $aboutQuote }}"</p>
            </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <div class="card bg-white shadow-sm">
                <div class="card-body">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#f5f3ff] text-brand-primary">
                        <svg viewBox="0 0 24 24" class="h-5 w-5 fill-current" aria-hidden="true"><path d="M4 4h16v2H4V4zm0 6h16v2H4v-2zm0 6h10v2H4v-2z"/></svg>
                    </div>
                    <h3 class="card-title mt-3 text-lg">Centralized Information</h3>
                    <p class="text-sm text-gray-600">One place for locations, services, offerings, and essential details.</p>
                </div>
            </div>
            <div class="card bg-white shadow-sm">
                <div class="card-body">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#f5f3ff] text-brand-primary">
                        <svg viewBox="0 0 24 24" class="h-5 w-5 fill-current" aria-hidden="true"><path d="M12 2a7 7 0 0 0-7 7v4a5 5 0 0 0 5 5h1v-5H8l4-11zm0 0 4 11h-3v5h1a5 5 0 0 0 5-5V9a7 7 0 0 0-7-7z"/></svg>
                    </div>
                    <h3 class="card-title mt-3 text-lg">AI-Powered Assistant</h3>
                    <p class="text-sm text-gray-600">A virtual guide ready to answer your questions instantly.</p>
                </div>
            </div>
            <div class="card bg-white shadow-sm">
                <div class="card-body">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#f5f3ff] text-brand-primary">
                        <svg viewBox="0 0 24 24" class="h-5 w-5 fill-current" aria-hidden="true"><path d="M13 3L4 14h7l-1 7 10-12h-7l0-6z"/></svg>
                    </div>
                    <h3 class="card-title mt-3 text-lg">Faster Inquiries</h3>
                    <p class="text-sm text-gray-600">Get quick answers without searching across multiple channels.</p>
                </div>
            </div>
            <div class="card bg-white shadow-sm">
                <div class="card-body">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#f5f3ff] text-brand-primary">
                        <svg viewBox="0 0 24 24" class="h-5 w-5 fill-current" aria-hidden="true"><path d="M7 3h10l4 4v14H3V3h4zm0 2v4h10V5H7zm0 8v6h10v-6H7z"/></svg>
                    </div>
                    <h3 class="card-title mt-3 text-lg">Seamless Experience</h3>
                    <p class="text-sm text-gray-600">A clean, consistent interface that's easy to use on any device.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── GALLERY ───────────────────────────────────────────────────────────────── --}}
<section id="gallery" class="mx-auto max-w-7xl px-4 py-16 lg:px-8">
    <div class="max-w-2xl space-y-3">
        <h2 class="text-3xl font-extrabold tracking-tight text-gray-900 md:text-4xl">Gallery</h2>
        <p class="text-gray-600">A visual preview of the experiences and spaces at {{ $businessName }}.</p>
    </div>

    <div class="mt-12 grid gap-6 md:grid-cols-3">
        @foreach ($galleryItems as $item)
            <div class="overflow-hidden rounded-[2rem] bg-white shadow-lg">
                @if (!empty($item['image_path']))
                    <img src="{{ asset('storage/' . ltrim($item['image_path'], '/')) }}"
                         alt="{{ $item['title'] }}"
                         class="h-56 w-full object-cover">
                @else
                    <div class="h-56 bg-gradient-to-br {{ $item['gradient'] }}"></div>
                @endif
                <div class="space-y-2 p-5">
                    <h3 class="font-bold text-gray-900">{{ $item['title'] }}</h3>
                    <p class="text-sm text-gray-600">{{ $item['description'] }}</p>
                </div>
            </div>
        @endforeach
    </div>
</section>

{{-- ── FOOTER + CHATBOT ─────────────────────────────────────────────────────── --}}
@include('partials.footer', ['footerBusinesses' => $footerBusinesses])

@include('partials.chatbot', ['business' => $business])

{{-- ── ROOM AVAILABILITY MODAL ──────────────────────────────────────────────── --}}
<dialog id="room_availability_modal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box max-w-5xl bg-[#f8fafc] rounded-[2rem] border border-[#e2e8f0] p-6 lg:p-8">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-4 top-4 text-gray-500 hover:bg-[#f5f3ff]">✕</button>
        </form>

        <div class="space-y-6">
            {{-- Header --}}
            <div class="flex items-center gap-3">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-brand-primary text-white shadow-sm">
                    <svg viewBox="0 0 24 24" class="h-6 w-6 fill-current" aria-hidden="true">
                        <path d="M12 3C8 3 5 6 5 10v10h14V10c0-4-3-7-7-7zm0 4a3 3 0 0 1 3 3v6H9v-6a3 3 0 0 1 3-3z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-950">{{ $roomModalTitle }}</h3>
                    <p class="text-sm text-gray-500">{{ $roomModalDescription }}</p>
                </div>
            </div>

            {{-- Rate summary --}}
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <div class="rounded-2xl border border-gray-150 bg-white p-4">
                    <span class="block text-xs font-semibold uppercase tracking-wider text-gray-400">Standard Room</span>
                    <span class="mt-1 block text-xl font-black text-brand-primary">PHP 1,800 / night</span>
                    <span class="mt-1 block text-xs font-medium text-emerald-600">✓ 3 of 4 rooms vacant</span>
                </div>
                <div class="rounded-2xl border border-gray-150 bg-white p-4">
                    <span class="block text-xs font-semibold uppercase tracking-wider text-gray-400">Deluxe Twin</span>
                    <span class="mt-1 block text-xl font-black text-brand-primary">PHP 2,250 / night</span>
                    <span class="mt-1 block text-xs font-medium text-emerald-600">✓ 5 of 8 rooms vacant</span>
                </div>
                <div class="rounded-2xl border border-gray-150 bg-white p-4">
                    <span class="block text-xs font-semibold uppercase tracking-wider text-gray-400">Family Suite</span>
                    <span class="mt-1 block text-xl font-black text-brand-primary">PHP 3,500 / night</span>
                    <span class="mt-1 block text-xs font-medium text-emerald-600">✓ 2 of 4 rooms vacant</span>
                </div>
            </div>

            {{-- Filters --}}
            <div class="flex flex-col gap-2 rounded-2xl bg-gray-50 p-4">
                <span class="text-xs font-bold uppercase tracking-wider text-gray-500">Filter Room Type:</span>
                <div class="flex flex-wrap gap-1.5" id="guest-filter-container">
                    <button type="button" class="btn btn-xs sm:btn-sm rounded-full guest-filter-btn border-0 bg-brand-primary text-white hover:bg-[#6d28d9]" data-filter="all">All Rooms</button>
                    <button type="button" class="btn btn-xs sm:btn-sm rounded-full guest-filter-btn border border-gray-300 bg-white text-gray-700 hover:bg-[#ffffff] hover:text-gray-700" data-filter="Standard">Standard (PHP 1,800)</button>
                    <button type="button" class="btn btn-xs sm:btn-sm rounded-full guest-filter-btn border border-gray-300 bg-white text-gray-700 hover:bg-[#ffffff] hover:text-gray-700" data-filter="Junior Suite">Junior Suite (PHP 1,950)</button>
                    <button type="button" class="btn btn-xs sm:btn-sm rounded-full guest-filter-btn border border-gray-300 bg-white text-gray-700 hover:bg-[#ffffff] hover:text-gray-700" data-filter="Deluxe Twin">Deluxe Twin (PHP 2,250)</button>
                    <button type="button" class="btn btn-xs sm:btn-sm rounded-full guest-filter-btn border border-gray-300 bg-white text-gray-700 hover:bg-[#ffffff] hover:text-gray-700" data-filter="Family Suite">Family Suite (PHP 3,500)</button>
                    <button type="button" class="btn btn-xs sm:btn-sm rounded-full guest-filter-btn border border-gray-300 bg-white text-gray-700 hover:bg-[#ffffff] hover:text-gray-700" data-filter="Super Deluxe Room">Super Deluxe (PHP 3,000)</button>
                </div>
            </div>

            {{-- Room grid --}}
            <div class="grid gap-3 grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5" id="guest-rooms-grid">
                @php
                    $rooms = [
                        ['number' => 'RM 310', 'type' => 'Standard',        'status' => 'vacant'],
                        ['number' => 'RM 312', 'type' => 'Standard',        'status' => 'vacant'],
                        ['number' => 'RM 314', 'type' => 'Standard',        'status' => 'booked'],
                        ['number' => 'RM 315', 'type' => 'Standard',        'status' => 'vacant'],
                        ['number' => 'RM 301', 'type' => 'Junior Suite',    'status' => 'vacant'],
                        ['number' => 'RM 308', 'type' => 'Junior Suite',    'status' => 'booked'],
                        ['number' => 'RM 302', 'type' => 'Deluxe Twin',     'status' => 'vacant'],
                        ['number' => 'RM 303', 'type' => 'Deluxe Twin',     'status' => 'vacant'],
                        ['number' => 'RM 304', 'type' => 'Deluxe Twin',     'status' => 'maintenance'],
                        ['number' => 'RM 305', 'type' => 'Deluxe Twin',     'status' => 'vacant'],
                        ['number' => 'RM 306', 'type' => 'Deluxe Twin',     'status' => 'booked'],
                        ['number' => 'RM 307', 'type' => 'Deluxe Twin',     'status' => 'vacant'],
                        ['number' => 'RM 309', 'type' => 'Deluxe Twin',     'status' => 'vacant'],
                        ['number' => 'RM 311', 'type' => 'Deluxe Twin',     'status' => 'booked'],
                        ['number' => 'RM 201', 'type' => 'Family Suite',    'status' => 'vacant'],
                        ['number' => 'RM 202', 'type' => 'Family Suite',    'status' => 'booked'],
                        ['number' => 'RM 206', 'type' => 'Family Suite',    'status' => 'vacant'],
                        ['number' => 'RM 207', 'type' => 'Family Suite',    'status' => 'maintenance'],
                        ['number' => 'RM 203', 'type' => 'Super Deluxe Room', 'status' => 'vacant'],
                        ['number' => 'RM 204', 'type' => 'Super Deluxe Room', 'status' => 'booked'],
                        ['number' => 'RM 205', 'type' => 'Super Deluxe Room', 'status' => 'vacant'],
                    ];
                @endphp

                @foreach ($rooms as $room)
                    @php
                        $isVacant      = $room['status'] === 'vacant';
                        $isMaintenance = $room['status'] === 'maintenance';
                        $cardOpacity   = $isVacant ? '' : ($isMaintenance ? 'opacity-60' : 'opacity-50');
                        $numColor      = $isVacant ? 'text-gray-900' : 'text-gray-400';
                        $typeColor     = $isVacant ? 'text-gray-500' : 'text-gray-400';
                        if ($isVacant) {
                            $badgeClass = 'bg-emerald-100 text-emerald-800';
                            $badgeText  = 'VACANT';
                        } elseif ($isMaintenance) {
                            $badgeClass = 'bg-amber-100 text-amber-800';
                            $badgeText  = 'MAINTENANCE';
                        } else {
                            $badgeClass = 'bg-rose-100 text-rose-800';
                            $badgeText  = 'BOOKED';
                        }
                    @endphp
                    <div class="guest-room-card rounded-2xl border border-gray-150 bg-white p-3.5 space-y-2 transition-all {{ $isVacant ? 'hover:shadow-sm' : '' }} {{ $cardOpacity }}"
                         data-room-type="{{ $room['type'] }}">
                        <div class="flex items-center justify-between">
                            <span class="font-extrabold {{ $numColor }}">{{ $room['number'] }}</span>
                            <span class="badge border-0 {{ $badgeClass }} font-bold px-2 py-1.5 text-[9px]">{{ $badgeText }}</span>
                        </div>
                        <div class="text-[11px] {{ $typeColor }}">{{ $room['type'] }}</div>
                    </div>
                @endforeach
            </div>

            {{-- Empty state --}}
            <div id="guest-no-rooms" class="hidden rounded-[1.5rem] border border-dashed border-gray-200 py-10 text-center">
                <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <p class="mt-2 text-sm font-medium text-gray-500">No rooms match this filter.</p>
            </div>

            {{-- Modal footer --}}
            <div class="flex flex-col items-center justify-between gap-3 border-t border-gray-100 pt-4 text-center sm:flex-row sm:text-left">
                <span class="text-xs text-gray-500">{{ $roomInquiryText }}</span>
                <form method="dialog">
                    <button class="btn btn-sm w-full rounded-full border-0 bg-brand-primary px-5 text-white hover:bg-[#6d28d9] sm:w-auto">Close Window</button>
                </form>
            </div>
        </div>
    </div>
</dialog>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const filterBtns = document.querySelectorAll('.guest-filter-btn');
        const cards      = document.querySelectorAll('.guest-room-card');
        const noRooms    = document.querySelector('#guest-no-rooms');

        filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                filterBtns.forEach(b => {
                    b.classList.remove('bg-brand-primary', 'text-white', 'border-0');
                    b.classList.add('border', 'border-gray-300', 'bg-white', 'text-gray-700');
                });
                btn.classList.add('bg-brand-primary', 'text-white', 'border-0');
                btn.classList.remove('border', 'border-gray-300', 'bg-white', 'text-gray-700');

                const filter = btn.dataset.filter;
                let visible  = 0;

                cards.forEach(card => {
                    const show = filter === 'all' || card.dataset.roomType === filter;
                    card.style.display = show ? '' : 'none';
                    if (show) visible++;
                });

                noRooms.classList.toggle('hidden', visible > 0);
            });
        });
    });
</script>

@endsection
