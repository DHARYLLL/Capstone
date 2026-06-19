{{-- filepath: c:\Users\dhary\Desktop\Capstone\capstone1\resources\views\landing\index.blade.php --}}
@extends('layouts.landing')

@section('content')
    @php
        $business = $business ?? null;

        $badgeLabel = data_get($business, 'badge_label') ?? 'AI-Powered Business Platform';
        $businessHeadline = data_get($business, 'headline') ?? 'Experience All Our Services in One Smart Platform';
        $businessTagline = data_get($business, 'tagline') ?? 'Explore services, locations, offers, and FAQs through a single polished business experience.';
        $businessDescription = data_get($business, 'description') ?? 'Discover services, locations, offers, and FAQs through a polished landing page experience tailored to your business.';
        $primaryCtaLabel = data_get($business, 'primary_cta_label') ?? 'Explore Businesses';
        $primaryCtaUrl = data_get($business, 'primary_cta_url') ?? '#businesses';
        $secondaryCtaLabel = data_get($business, 'secondary_cta_label') ?? 'Start Chat';
        $secondaryCtaUrl = data_get($business, 'secondary_cta_url') ?? '#chatbot';
        $assistantTitle = data_get($business, 'assistant_title') ?? 'One AI assistant connecting multiple businesses';
        $assistantDescription = data_get($business, 'assistant_description') ?? 'Ask once and get clear information about services, locations, offers, and FAQs across all partner businesses.';
        $businessName = data_get($business, 'name') ?? 'Your Business';
        $businessCards = collect([
            [
                'label' => data_get($business, 'feature_one_label') ?? 'Featured Business One',
                'description' => data_get($business, 'feature_one_description') ?? 'A warm dining destination for authentic cuisine, family gatherings, and memorable meals in a welcoming setting.',
            ],
            [
                'label' => data_get($business, 'feature_two_label') ?? 'Featured Business Two',
                'description' => data_get($business, 'feature_two_description') ?? 'Relax, unwind, and enjoy a refreshing experience with amenities ideal for leisure, celebrations, and weekend escapes.',
            ],
            [
                'label' => data_get($business, 'feature_three_label') ?? 'Featured Business Three',
                'description' => data_get($business, 'feature_three_description') ?? 'A comfortable hospitality destination for stays, gatherings, and poolside moments designed for relaxation and convenience.',
            ],
        ]);
        $featureOneLabel = $businessCards[0]['label'];
        $featureTwoLabel = $businessCards[1]['label'];
        $featureThreeLabel = $businessCards[2]['label'];
        $featureOneDescription = $businessCards[0]['description'];
        $featureTwoDescription = $businessCards[1]['description'];
        $featureThreeDescription = $businessCards[2]['description'];
        $galleryOneTitle = data_get($business, 'gallery_one_title') ?? $featureOneLabel;
        $galleryTwoTitle = data_get($business, 'gallery_two_title') ?? 'Dining Moments';
        $galleryThreeTitle = data_get($business, 'gallery_three_title') ?? 'Poolside Views';
        $galleryOneDescription = data_get($business, 'gallery_one_description') ?? 'Warm dining spaces and inviting hospitality.';
        $galleryTwoDescription = data_get($business, 'gallery_two_description') ?? 'Memorable meals and shared experiences.';
        $galleryThreeDescription = data_get($business, 'gallery_three_description') ?? 'Relaxing scenes from leisure and stay destinations.';
        $footerBusinesses = $businessCards->map(function ($businessCard) {
            return [
                'label' => $businessCard['label'],
                'href' => '#businesses',
            ];
        });
        $roomBusinessName = data_get($business, 'room_business_name') ?? $businessName;
        $roomModalTitle = data_get($business, 'room_modal_title') ?? "{$roomBusinessName} Rooms & Availability";
        $roomModalDescription = data_get($business, 'room_modal_description') ?? "View real-time room rates and vacancies at {$roomBusinessName}";
        $roomInquiryText = data_get($business, 'room_inquiry_text') ?? 'For booking inquiries, select "Chat with Assistant" or call support.';
        $businessCoverPath = data_get($business, 'cover_photo_path');
        $businessCoverImage = filled($businessCoverPath)
            ? asset('storage/' . ltrim($businessCoverPath, '/'))
            : asset('images/business-fallback.svg');
    @endphp

    <section id="home" class="mx-auto max-w-7xl px-4 py-10 lg:px-8 lg:py-16">
        <div class="grid items-center gap-12 lg:grid-cols-2">
            <div class="space-y-6">
                <div class="badge border-0 bg-[#F1E4D2] px-4 py-3 text-xs font-semibold text-[#5A3E2B]">
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
                    <a href="{{ $primaryCtaUrl }}" class="btn rounded-full border-0 bg-[#5A3E2B] px-6 text-white hover:bg-[#453020]">
                        {{ $primaryCtaLabel }}
                    </a>
                    <a href="{{ $secondaryCtaUrl }}" class="btn btn-outline rounded-full border-[#5A3E2B] px-6 text-[#5A3E2B] hover:bg-[#F4EEDF]">
                        {{ $secondaryCtaLabel }}
                    </a>
                </div>

                <div class="rounded-3xl border border-[#E8DFD2] bg-[#F8F1E7] p-5 shadow-sm">
                    <div class="flex items-start gap-4">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-[#5A3E2B] text-white">
                            <svg viewBox="0 0 24 24" class="h-5 w-5 fill-current" aria-hidden="true">
                                <path d="M12 2a4 4 0 0 0-4 4v1H7a3 3 0 0 0-3 3v7a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3v-7a3 3 0 0 0-3-3h-1V6a4 4 0 0 0-4-4zm-2 5V6a2 2 0 1 1 4 0v1h-4zm2 5a1.5 1.5 0 0 1 .75 2.8V16h-1.5v-1.2A1.5 1.5 0 0 1 12 12z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-sm font-bold text-gray-900">{{ $assistantTitle }}</h2>
                            <p class="mt-1 text-sm leading-6 text-gray-600">
                                {{ $assistantDescription }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <div class="overflow-hidden rounded-[2rem] border border-white/70 bg-white shadow-[0_20px_60px_rgba(90,62,43,0.12)]">
                    <div class="relative aspect-[4/5] min-h-[320px] overflow-hidden bg-[#FCFBF8]">
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

    <section id="businesses" class="mx-auto max-w-7xl px-4 py-14 lg:px-8">
        <div class="max-w-2xl space-y-3">
            <h2 class="text-3xl font-extrabold tracking-tight text-gray-900 md:text-4xl">Our Businesses</h2>
            <p class="text-gray-600">
                Discover the businesses connected through one intelligent platform for faster browsing, better support, and easier inquiries.
            </p>
        </div>

        <div class="mt-12 space-y-16">
            <div class="grid items-center gap-8 lg:grid-cols-2">
                <div class="rounded-[2rem] bg-gradient-to-br from-[#EED9C4] to-[#D9C0A5] p-6 shadow-lg">
                    <div class="flex min-h-[320px] items-end rounded-[1.6rem] bg-[linear-gradient(135deg,rgba(255,255,255,0.25),rgba(255,255,255,0.08))] p-6">
                        <div class="rounded-2xl bg-white/70 px-4 py-2 text-sm font-semibold text-gray-800">{{ $featureOneLabel }}</div>
                    </div>
                </div>

                <div class="space-y-5">
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#5A3E2B] text-white">
                            <svg viewBox="0 0 24 24" class="h-6 w-6 fill-current" aria-hidden="true">
                                <path d="M4 5h16v2H4V5zm2 3h12v11H6V8zm2 2v7h8v-7H8z"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900">{{ $featureOneLabel }}</h3>
                    </div>
                    <p class="text-gray-600">
                        {{ $featureOneDescription }}
                    </p>

                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                        <div class="flex items-start gap-3 rounded-2xl bg-white p-4 shadow-sm">
                            <span class="mt-0.5 text-[#5A3E2B]">✓</span><p class="text-sm text-gray-700">Authentic Filipino cuisine</p>
                        </div>
                        <div class="flex items-start gap-3 rounded-2xl bg-white p-4 shadow-sm">
                            <span class="mt-0.5 text-[#5A3E2B]">✓</span><p class="text-sm text-gray-700">Family-style dining</p>
                        </div>
                        <div class="flex items-start gap-3 rounded-2xl bg-white p-4 shadow-sm">
                            <span class="mt-0.5 text-[#5A3E2B]">✓</span><p class="text-sm text-gray-700">Event-ready venue</p>
                        </div>
                        <div class="flex items-start gap-3 rounded-2xl bg-white p-4 shadow-sm">
                            <span class="mt-0.5 text-[#5A3E2B]">✓</span><p class="text-sm text-gray-700">Comfortable hospitality</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid items-center gap-8 lg:grid-cols-2">
                <div class="order-2 space-y-5 lg:order-1">
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#5A3E2B] text-white">
                            <svg viewBox="0 0 24 24" class="h-6 w-6 fill-current" aria-hidden="true">
                                <path d="M12 3C8 3 5 6 5 10v10h14V10c0-4-3-7-7-7zm0 4a3 3 0 0 1 3 3v6H9v-6a3 3 0 0 1 3-3z"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900">{{ $featureTwoLabel }}</h3>
                    </div>
                    <p class="text-gray-600">
                        {{ $featureTwoDescription }}
                    </p>

                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                        <div class="flex items-start gap-3 rounded-2xl bg-white p-4 shadow-sm">
                            <span class="mt-0.5 text-[#5A3E2B]">✓</span><p class="text-sm text-gray-700">Swimming and leisure area</p>
                        </div>
                        <div class="flex items-start gap-3 rounded-2xl bg-white p-4 shadow-sm">
                            <span class="mt-0.5 text-[#5A3E2B]">✓</span><p class="text-sm text-gray-700">Family-friendly atmosphere</p>
                        </div>
                        <div class="flex items-start gap-3 rounded-2xl bg-white p-4 shadow-sm">
                            <span class="mt-0.5 text-[#5A3E2B]">✓</span><p class="text-sm text-gray-700">Private event packages</p>
                        </div>
                        <div class="flex items-start gap-3 rounded-2xl bg-white p-4 shadow-sm">
                            <span class="mt-0.5 text-[#5A3E2B]">✓</span><p class="text-sm text-gray-700">Clean and relaxing space</p>
                        </div>
                    </div>
                </div>

                <div class="order-1 rounded-[2rem] bg-gradient-to-br from-[#EED9C4] to-[#D9C0A5] p-6 shadow-lg lg:order-2">
                    <div class="flex min-h-[320px] items-end rounded-[1.6rem] bg-[linear-gradient(135deg,rgba(255,255,255,0.25),rgba(255,255,255,0.08))] p-6">
                        <div class="rounded-2xl bg-white/70 px-4 py-2 text-sm font-semibold text-gray-800">{{ $featureTwoLabel }}</div>
                    </div>
                </div>
            </div>

            <div class="grid items-center gap-8 lg:grid-cols-2">
                <div class="rounded-[2rem] bg-gradient-to-br from-[#EED9C4] to-[#D9C0A5] p-6 shadow-lg">
                    <div class="flex min-h-[320px] items-end rounded-[1.6rem] bg-[linear-gradient(135deg,rgba(255,255,255,0.25),rgba(255,255,255,0.08))] p-6">
                        <div class="rounded-2xl bg-white/70 px-4 py-2 text-sm font-semibold text-gray-800">{{ $featureThreeLabel }}</div>
                    </div>
                </div>

                <div class="space-y-5">
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#5A3E2B] text-white">
                            <svg viewBox="0 0 24 24" class="h-6 w-6 fill-current" aria-hidden="true">
                                <path d="M4 20h16v-2H4v2zm2-4h12V8l-6-4-6 4v8zm2-2V9.1l4-2.67 4 2.67V14H8z"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900">{{ $featureThreeLabel }}</h3>
                    </div>
                    <p class="text-gray-600">
                        {{ $featureThreeDescription }}
                    </p>

                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                        <div class="flex items-start gap-3 rounded-2xl bg-white p-4 shadow-sm">
                            <span class="mt-0.5 text-[#5A3E2B]">✓</span><p class="text-sm text-gray-700">Accommodating stay options</p>
                        </div>
                        <div class="flex items-start gap-3 rounded-2xl bg-white p-4 shadow-sm">
                            <span class="mt-0.5 text-[#5A3E2B]">✓</span><p class="text-sm text-gray-700">Poolside relaxation</p>
                        </div>
                        <div class="flex items-start gap-3 rounded-2xl bg-white p-4 shadow-sm">
                            <span class="mt-0.5 text-[#5A3E2B]">✓</span><p class="text-sm text-gray-700">Event and group hosting</p>
                        </div>
                        <div class="flex items-start gap-3 rounded-2xl bg-white p-4 shadow-sm">
                            <span class="mt-0.5 text-[#5A3E2B]">✓</span><p class="text-sm text-gray-700">Quiet and inviting ambiance</p>
                        </div>
                    </div>

                    <div class="pt-4 flex flex-col gap-3 sm:flex-row">
                        <button onclick="room_availability_modal.showModal()" class="btn rounded-full border-0 bg-[#5A3E2B] text-white hover:bg-[#453020]">
                            <svg viewBox="0 0 24 24" class="h-5 w-5 fill-current" aria-hidden="true">
                                <path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"/>
                            </svg>
                            Check Rooms & Live Availability
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="about" class="bg-[#F4EEDF] py-16">
        <div class="mx-auto grid max-w-7xl gap-10 px-4 lg:grid-cols-2 lg:px-8">
            <div class="space-y-5">
                <div class="badge border-0 bg-white px-4 py-3 text-xs font-semibold text-[#5A3E2B]">About Project RED AI</div>
                <h2 class="max-w-xl text-3xl font-extrabold tracking-tight text-gray-900 md:text-5xl">
                    One platform. One assistant. Multiple businesses.
                </h2>
                <p class="max-w-xl text-gray-600">
                    Project RED AI simplifies discovery and support by centralizing key business information into a single, elegant experience for customers across every connected business.
                </p>

                <div class="rounded-3xl border border-[#E7D9C8] bg-white p-6 shadow-sm">
                    <p class="text-lg font-semibold text-gray-900">
                        “Fast answers, consistent information, and a smoother customer journey across all our businesses.”
                    </p>
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div class="card bg-white shadow-sm">
                    <div class="card-body">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#F4EEDF] text-[#5A3E2B]">
                            <svg viewBox="0 0 24 24" class="h-5 w-5 fill-current" aria-hidden="true">
                                <path d="M4 4h16v2H4V4zm0 6h16v2H4v-2zm0 6h10v2H4v-2z"/>
                            </svg>
                        </div>
                        <h3 class="card-title mt-3 text-lg">Centralized Business Information</h3>
                        <p class="text-sm text-gray-600">One place for locations, services, offerings, and essential details.</p>
                    </div>
                </div>

                <div class="card bg-white shadow-sm">
                    <div class="card-body">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#F4EEDF] text-[#5A3E2B]">
                            <svg viewBox="0 0 24 24" class="h-5 w-5 fill-current" aria-hidden="true">
                                <path d="M12 2a7 7 0 0 0-7 7v4a5 5 0 0 0 5 5h1v-5H8l4-11zm0 0 4 11h-3v5h1a5 5 0 0 0 5-5V9a7 7 0 0 0-7-7z"/>
                            </svg>
                        </div>
                        <h3 class="card-title mt-3 text-lg">AI-Powered Assistant</h3>
                        <p class="text-sm text-gray-600">A helpful virtual guide ready to answer business questions instantly.</p>
                    </div>
                </div>

                <div class="card bg-white shadow-sm">
                    <div class="card-body">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#F4EEDF] text-[#5A3E2B]">
                            <svg viewBox="0 0 24 24" class="h-5 w-5 fill-current" aria-hidden="true">
                                <path d="M13 3L4 14h7l-1 7 10-12h-7l0-6z"/>
                            </svg>
                        </div>
                        <h3 class="card-title mt-3 text-lg">Faster and Easier Inquiries</h3>
                        <p class="text-sm text-gray-600">Get quick answers without searching across multiple channels.</p>
                    </div>
                </div>

                <div class="card bg-white shadow-sm">
                    <div class="card-body">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#F4EEDF] text-[#5A3E2B]">
                            <svg viewBox="0 0 24 24" class="h-5 w-5 fill-current" aria-hidden="true">
                                <path d="M7 3h10l4 4v14H3V3h4zm0 2v4h10V5H7zm0 8v6h10v-6H7z"/>
                            </svg>
                        </div>
                        <h3 class="card-title mt-3 text-lg">Seamless User Experience</h3>
                        <p class="text-sm text-gray-600">A clean interface that feels consistent, modern, and easy to use.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="gallery" class="mx-auto max-w-7xl px-4 py-16 lg:px-8">
        <div class="max-w-2xl space-y-3">
            <h2 class="text-3xl font-extrabold tracking-tight text-gray-900 md:text-4xl">Gallery</h2>
            <p class="text-gray-600">
                A visual preview of the experiences and spaces connected through Project RED AI.
            </p>
        </div>

        <div class="mt-12 grid gap-6 md:grid-cols-3">
            <div class="overflow-hidden rounded-[2rem] bg-white shadow-lg">
                <div class="h-56 bg-gradient-to-br from-[#DCC0A4] to-[#F0E3D2]"></div>
                <div class="space-y-2 p-5">
                    <h3 class="font-bold text-gray-900">{{ $galleryOneTitle }}</h3>
                    <p class="text-sm text-gray-600">{{ $galleryOneDescription }}</p>
                </div>
            </div>

            <div class="overflow-hidden rounded-[2rem] bg-white shadow-lg">
                <div class="h-56 bg-gradient-to-br from-[#D9D4C8] to-[#F4EFE6]"></div>
                <div class="space-y-2 p-5">
                    <h3 class="font-bold text-gray-900">{{ $galleryTwoTitle }}</h3>
                    <p class="text-sm text-gray-600">{{ $galleryTwoDescription }}</p>
                </div>
            </div>

            <div class="overflow-hidden rounded-[2rem] bg-white shadow-lg">
                <div class="h-56 bg-gradient-to-br from-[#D8C1AA] to-[#EFE3D2]"></div>
                <div class="space-y-2 p-5">
                    <h3 class="font-bold text-gray-900">{{ $galleryThreeTitle }}</h3>
                    <p class="text-sm text-gray-600">{{ $galleryThreeDescription }}</p>
                </div>
            </div>
        </div>
    </section>

    @include('partials.footer', ['footerBusinesses' => $footerBusinesses])
    @include('partials.chatbot')

    <!-- Room Availability Modal -->
    <dialog id="room_availability_modal" class="modal modal-bottom sm:modal-middle">
        <div class="modal-box max-w-5xl bg-[#FCFBF8] rounded-[2rem] border border-[#eadfce] p-6 lg:p-8">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-4 top-4 text-gray-500 hover:bg-[#F4EEDF]">✕</button>
            </form>
            
            <div class="space-y-6">
                <!-- Header -->
                <div class="flex items-center gap-3">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#5A3E2B] text-white shadow-sm">
                        <svg viewBox="0 0 24 24" class="h-6 w-6 fill-current" aria-hidden="true">
                            <path d="M12 3C8 3 5 6 5 10v10h14V10c0-4-3-7-7-7zm0 4a3 3 0 0 1 3 3v6H9v-6a3 3 0 0 1 3-3z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-950">{{ $roomModalTitle }}</h3>
                        <p class="text-sm text-gray-500">{{ $roomModalDescription }}</p>
                    </div>
                </div>

                <!-- Info summary -->
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    <div class="rounded-2xl border border-gray-150 bg-white p-4">
                        <span class="text-xs text-gray-400 font-semibold uppercase tracking-wider block">Standard Room Rate</span>
                        <span class="text-xl font-black text-[#5A3E2B] block mt-1">PHP 1,800 / night</span>
                        <span class="text-xs text-emerald-600 font-medium block mt-1">✓ 3 of 4 rooms vacant</span>
                    </div>
                    <div class="rounded-2xl border border-gray-150 bg-white p-4">
                        <span class="text-xs text-gray-400 font-semibold uppercase tracking-wider block">Deluxe Twin Rate</span>
                        <span class="text-xl font-black text-[#5A3E2B] block mt-1">PHP 2,250 / night</span>
                        <span class="text-xs text-emerald-600 font-medium block mt-1">✓ 5 of 8 rooms vacant</span>
                    </div>
                    <div class="rounded-2xl border border-gray-150 bg-white p-4">
                        <span class="text-xs text-gray-400 font-semibold uppercase tracking-wider block">Family Suite Rate</span>
                        <span class="text-xl font-black text-[#5A3E2B] block mt-1">PHP 3,500 / night</span>
                        <span class="text-xs text-emerald-600 font-medium block mt-1">✓ 2 of 4 rooms vacant</span>
                    </div>
                </div>

                <!-- Category Filters -->
                <div class="flex flex-col gap-2 bg-gray-50 p-4 rounded-2xl">
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Filter Room Type:</span>
                    <div class="flex flex-wrap gap-1.5" id="guest-filter-container">
                        <button type="button" class="btn btn-xs sm:btn-sm rounded-full guest-filter-btn active border-0 bg-[#5A3E2B] text-white hover:bg-[#453020]" data-filter="all">All Rooms</button>
                        <button type="button" class="btn btn-xs sm:btn-sm rounded-full guest-filter-btn btn-outline border-gray-300 text-gray-700 hover:bg-[#FAF8F4]" data-filter="Standard">Standard (PHP 1,800)</button>
                        <button type="button" class="btn btn-xs sm:btn-sm rounded-full guest-filter-btn btn-outline border-gray-300 text-gray-700 hover:bg-[#FAF8F4]" data-filter="Junior Suite">Junior Suite (PHP 1,950)</button>
                        <button type="button" class="btn btn-xs sm:btn-sm rounded-full guest-filter-btn btn-outline border-gray-300 text-gray-700 hover:bg-[#FAF8F4]" data-filter="Deluxe Twin">Deluxe Twin (PHP 2,250)</button>
                        <button type="button" class="btn btn-xs sm:btn-sm rounded-full guest-filter-btn btn-outline border-gray-300 text-gray-700 hover:bg-[#FAF8F4]" data-filter="Family Suite">Family Suite (PHP 3,500)</button>
                        <button type="button" class="btn btn-xs sm:btn-sm rounded-full guest-filter-btn btn-outline border-gray-300 text-gray-700 hover:bg-[#FAF8F4]" data-filter="Super Deluxe Room">Super Deluxe (PHP 3,000)</button>
                    </div>
                </div>

                <!-- Grid of rooms -->
                <div class="grid gap-3 grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5" id="guest-rooms-grid">
                    <!-- Standard Rooms -->
                    <div class="guest-room-card rounded-2xl border border-gray-150 bg-white p-3.5 space-y-2 hover:shadow-sm transition-all" data-room-type="Standard">
                        <div class="flex items-center justify-between">
                            <span class="font-extrabold text-gray-900">RM 310</span>
                            <span class="badge badge-success border-0 bg-emerald-100 text-emerald-800 font-bold px-2 py-1.5 text-[9px]">VACANT</span>
                        </div>
                        <div class="text-[11px] text-gray-500">Standard Room</div>
                    </div>
                    <div class="guest-room-card rounded-2xl border border-gray-150 bg-white p-3.5 space-y-2 hover:shadow-sm transition-all" data-room-type="Standard">
                        <div class="flex items-center justify-between">
                            <span class="font-extrabold text-gray-900">RM 312</span>
                            <span class="badge badge-success border-0 bg-emerald-100 text-emerald-800 font-bold px-2 py-1.5 text-[9px]">VACANT</span>
                        </div>
                        <div class="text-[11px] text-gray-500">Standard Room</div>
                    </div>
                    <div class="guest-room-card rounded-2xl border border-gray-150 bg-white p-3.5 space-y-2 opacity-50 transition-all" data-room-type="Standard">
                        <div class="flex items-center justify-between">
                            <span class="font-extrabold text-gray-400">RM 314</span>
                            <span class="badge border-0 bg-rose-100 text-rose-800 font-bold px-2 py-1.5 text-[9px]">BOOKED</span>
                        </div>
                        <div class="text-[11px] text-gray-400">Standard Room</div>
                    </div>
                    <div class="guest-room-card rounded-2xl border border-gray-150 bg-white p-3.5 space-y-2 hover:shadow-sm transition-all" data-room-type="Standard">
                        <div class="flex items-center justify-between">
                            <span class="font-extrabold text-gray-900">RM 315</span>
                            <span class="badge badge-success border-0 bg-emerald-100 text-emerald-800 font-bold px-2 py-1.5 text-[9px]">VACANT</span>
                        </div>
                        <div class="text-[11px] text-gray-500">Standard Room</div>
                    </div>

                    <!-- Junior Suites -->
                    <div class="guest-room-card rounded-2xl border border-gray-150 bg-white p-3.5 space-y-2 hover:shadow-sm transition-all" data-room-type="Junior Suite">
                        <div class="flex items-center justify-between">
                            <span class="font-extrabold text-gray-900">RM 301</span>
                            <span class="badge badge-success border-0 bg-emerald-100 text-emerald-800 font-bold px-2 py-1.5 text-[9px]">VACANT</span>
                        </div>
                        <div class="text-[11px] text-gray-500">Junior Suite</div>
                    </div>
                    <div class="guest-room-card rounded-2xl border border-gray-150 bg-white p-3.5 space-y-2 opacity-50 transition-all" data-room-type="Junior Suite">
                        <div class="flex items-center justify-between">
                            <span class="font-extrabold text-gray-400">RM 308</span>
                            <span class="badge border-0 bg-rose-100 text-rose-800 font-bold px-2 py-1.5 text-[9px]">BOOKED</span>
                        </div>
                        <div class="text-[11px] text-gray-400">Junior Suite</div>
                    </div>

                    <!-- Deluxe Twin -->
                    <div class="guest-room-card rounded-2xl border border-gray-150 bg-white p-3.5 space-y-2 hover:shadow-sm transition-all" data-room-type="Deluxe Twin">
                        <div class="flex items-center justify-between">
                            <span class="font-extrabold text-gray-900">RM 302</span>
                            <span class="badge badge-success border-0 bg-emerald-100 text-emerald-800 font-bold px-2 py-1.5 text-[9px]">VACANT</span>
                        </div>
                        <div class="text-[11px] text-gray-500">Deluxe Twin</div>
                    </div>
                    <div class="guest-room-card rounded-2xl border border-gray-150 bg-white p-3.5 space-y-2 hover:shadow-sm transition-all" data-room-type="Deluxe Twin">
                        <div class="flex items-center justify-between">
                            <span class="font-extrabold text-gray-900">RM 303</span>
                            <span class="badge badge-success border-0 bg-emerald-100 text-emerald-800 font-bold px-2 py-1.5 text-[9px]">VACANT</span>
                        </div>
                        <div class="text-[11px] text-gray-500">Deluxe Twin</div>
                    </div>
                    <div class="guest-room-card rounded-2xl border border-gray-150 bg-white p-3.5 space-y-2 opacity-60 transition-all" data-room-type="Deluxe Twin">
                        <div class="flex items-center justify-between">
                            <span class="font-extrabold text-gray-400">RM 304</span>
                            <span class="badge border-0 bg-amber-100 text-amber-800 font-bold px-2 py-1.5 text-[9px]">MAINTENANCE</span>
                        </div>
                        <div class="text-[11px] text-gray-400">Deluxe Twin</div>
                    </div>
                    <div class="guest-room-card rounded-2xl border border-gray-150 bg-white p-3.5 space-y-2 hover:shadow-sm transition-all" data-room-type="Deluxe Twin">
                        <div class="flex items-center justify-between">
                            <span class="font-extrabold text-gray-900">RM 305</span>
                            <span class="badge badge-success border-0 bg-emerald-100 text-emerald-800 font-bold px-2 py-1.5 text-[9px]">VACANT</span>
                        </div>
                        <div class="text-[11px] text-gray-500">Deluxe Twin</div>
                    </div>
                    <div class="guest-room-card rounded-2xl border border-gray-150 bg-white p-3.5 space-y-2 opacity-50 transition-all" data-room-type="Deluxe Twin">
                        <div class="flex items-center justify-between">
                            <span class="font-extrabold text-gray-400">RM 306</span>
                            <span class="badge border-0 bg-rose-100 text-rose-800 font-bold px-2 py-1.5 text-[9px]">BOOKED</span>
                        </div>
                        <div class="text-[11px] text-gray-400">Deluxe Twin</div>
                    </div>
                    <div class="guest-room-card rounded-2xl border border-gray-150 bg-white p-3.5 space-y-2 hover:shadow-sm transition-all" data-room-type="Deluxe Twin">
                        <div class="flex items-center justify-between">
                            <span class="font-extrabold text-gray-900">RM 307</span>
                            <span class="badge badge-success border-0 bg-emerald-100 text-emerald-800 font-bold px-2 py-1.5 text-[9px]">VACANT</span>
                        </div>
                        <div class="text-[11px] text-gray-500">Deluxe Twin</div>
                    </div>
                    <div class="guest-room-card rounded-2xl border border-gray-150 bg-white p-3.5 space-y-2 hover:shadow-sm transition-all" data-room-type="Deluxe Twin">
                        <div class="flex items-center justify-between">
                            <span class="font-extrabold text-gray-900">RM 309</span>
                            <span class="badge badge-success border-0 bg-emerald-100 text-emerald-800 font-bold px-2 py-1.5 text-[9px]">VACANT</span>
                        </div>
                        <div class="text-[11px] text-gray-500">Deluxe Twin</div>
                    </div>
                    <div class="guest-room-card rounded-2xl border border-gray-150 bg-white p-3.5 space-y-2 opacity-50 transition-all" data-room-type="Deluxe Twin">
                        <div class="flex items-center justify-between">
                            <span class="font-extrabold text-gray-400">RM 311</span>
                            <span class="badge border-0 bg-rose-100 text-rose-800 font-bold px-2 py-1.5 text-[9px]">BOOKED</span>
                        </div>
                        <div class="text-[11px] text-gray-400">Deluxe Twin</div>
                    </div>

                    <!-- Family Suites -->
                    <div class="guest-room-card rounded-2xl border border-gray-150 bg-white p-3.5 space-y-2 hover:shadow-sm transition-all" data-room-type="Family Suite">
                        <div class="flex items-center justify-between">
                            <span class="font-extrabold text-gray-900">RM 201</span>
                            <span class="badge badge-success border-0 bg-emerald-100 text-emerald-800 font-bold px-2 py-1.5 text-[9px]">VACANT</span>
                        </div>
                        <div class="text-[11px] text-gray-500">Family Suite</div>
                    </div>
                    <div class="guest-room-card rounded-2xl border border-gray-150 bg-white p-3.5 space-y-2 opacity-50 transition-all" data-room-type="Family Suite">
                        <div class="flex items-center justify-between">
                            <span class="font-extrabold text-gray-400">RM 202</span>
                            <span class="badge border-0 bg-rose-100 text-rose-800 font-bold px-2 py-1.5 text-[9px]">BOOKED</span>
                        </div>
                        <div class="text-[11px] text-gray-400">Family Suite</div>
                    </div>
                    <div class="guest-room-card rounded-2xl border border-gray-150 bg-white p-3.5 space-y-2 hover:shadow-sm transition-all" data-room-type="Family Suite">
                        <div class="flex items-center justify-between">
                            <span class="font-extrabold text-gray-900">RM 206</span>
                            <span class="badge badge-success border-0 bg-emerald-100 text-emerald-800 font-bold px-2 py-1.5 text-[9px]">VACANT</span>
                        </div>
                        <div class="text-[11px] text-gray-500">Family Suite</div>
                    </div>
                    <div class="guest-room-card rounded-2xl border border-gray-150 bg-white p-3.5 space-y-2 opacity-60 transition-all" data-room-type="Family Suite">
                        <div class="flex items-center justify-between">
                            <span class="font-extrabold text-gray-400">RM 207</span>
                            <span class="badge border-0 bg-amber-100 text-amber-800 font-bold px-2 py-1.5 text-[9px]">MAINTENANCE</span>
                        </div>
                        <div class="text-[11px] text-gray-400">Family Suite</div>
                    </div>

                    <!-- Super Deluxe Rooms -->
                    <div class="guest-room-card rounded-2xl border border-gray-150 bg-white p-3.5 space-y-2 hover:shadow-sm transition-all" data-room-type="Super Deluxe Room">
                        <div class="flex items-center justify-between">
                            <span class="font-extrabold text-gray-900">RM 203</span>
                            <span class="badge badge-success border-0 bg-emerald-100 text-emerald-800 font-bold px-2 py-1.5 text-[9px]">VACANT</span>
                        </div>
                        <div class="text-[11px] text-gray-500">Super Deluxe Room</div>
                    </div>
                    <div class="guest-room-card rounded-2xl border border-gray-150 bg-white p-3.5 space-y-2 opacity-50 transition-all" data-room-type="Super Deluxe Room">
                        <div class="flex items-center justify-between">
                            <span class="font-extrabold text-gray-400">RM 204</span>
                            <span class="badge border-0 bg-rose-100 text-rose-800 font-bold px-2 py-1.5 text-[9px]">BOOKED</span>
                        </div>
                        <div class="text-[11px] text-gray-400">Super Deluxe Room</div>
                    </div>
                    <div class="guest-room-card rounded-2xl border border-gray-150 bg-white p-3.5 space-y-2 hover:shadow-sm transition-all" data-room-type="Super Deluxe Room">
                        <div class="flex items-center justify-between">
                            <span class="font-extrabold text-gray-900">RM 205</span>
                            <span class="badge badge-success border-0 bg-emerald-100 text-emerald-800 font-bold px-2 py-1.5 text-[9px]">VACANT</span>
                        </div>
                        <div class="text-[11px] text-gray-500">Super Deluxe Room</div>
                    </div>
                </div>

                <!-- Empty State -->
                <div id="guest-no-rooms" class="hidden text-center py-10 rounded-[1.5rem] border border-dashed border-gray-200">
                    <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <p class="mt-2 text-sm text-gray-500 font-medium">No rooms match this category filter.</p>
                </div>

                <!-- Modal Actions -->
                <div class="flex flex-col sm:flex-row gap-3 justify-between items-center pt-4 border-t border-gray-100 text-center sm:text-left">
                    <span class="text-xs text-gray-500">{{ $roomInquiryText }}</span>
                    <form method="dialog">
                        <button class="btn btn-sm rounded-full border-0 bg-[#5A3E2B] text-white hover:bg-[#453020] px-5 w-full sm:w-auto">Close Window</button>
                    </form>
                </div>
            </div>
        </div>
    </dialog>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const filterBtns = document.querySelectorAll('.guest-filter-btn');
            const cards = document.querySelectorAll('.guest-room-card');
            const noRooms = document.querySelector('#guest-no-rooms');

            filterBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    filterBtns.forEach(b => {
                        b.classList.remove('active', 'bg-[#5A3E2B]', 'text-white');
                        b.classList.add('btn-outline', 'border-gray-300', 'text-gray-700');
                    });
                    btn.classList.add('active', 'bg-[#5A3E2B]', 'text-white');
                    btn.classList.remove('btn-outline', 'border-gray-300', 'text-gray-700');

                    const filter = btn.getAttribute('data-filter');
                    let count = 0;

                    cards.forEach(card => {
                        const type = card.getAttribute('data-room-type');
                        if (filter === 'all' || type === filter) {
                            card.style.display = 'block';
                            count++;
                        } else {
                            card.style.display = 'none';
                        }
                    });

                    if (count === 0) {
                        noRooms.classList.remove('hidden');
                    } else {
                        noRooms.classList.add('hidden');
                    }
                });
            });
        });
    </script>
@endsection