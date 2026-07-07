{{-- filepath: resources/views/admin/businesses/landing-editor.blade.php --}}
{{-- Admin-level landing page content editor — no manager sidebar required --}}
@extends('layouts.admin')

@section('page_title', 'Landing Page Editor')
@section('page_description', 'Edit the text, labels, and content shown on the public-facing website.')
@section('breadcrumbs', 'Admin / Businesses / Landing Page Editor')

@section('content')
    @php
        $currentSlug = $slug ?? 'dakong-balay';

        if ($currentSlug === 'villa-carmelita') {
            $businessName = 'Villa Carmelita';
            $businessType = 'Hotel / Villa';
            $businessCover = 'villa-carmelita-cover.jpg';
        } elseif ($currentSlug === 'monclaire-pool') {
            $businessName = 'Monclaire Pool';
            $businessType = 'Swimming Pool';
            $businessCover = 'monclaire-pool-cover.jpg';
        } else {
            $businessName = 'Dakong Balay';
            $businessType = 'Restaurant';
            $businessCover = 'dakong-balay-cover.jpg';
        }
    @endphp

    <div class="space-y-8">

        {{-- Page heading --}}
        <section class="space-y-2">
            <h1 class="text-3xl font-black tracking-tight text-gray-900 sm:text-4xl">Landing Page Editor</h1>
            <p class="max-w-2xl text-sm leading-6 text-gray-500 sm:text-base">
                Edit the text and labels shown on the public-facing website for each business.
                Select a business below then update its landing page content.
            </p>
        </section>

        {{-- Business selector --}}
        <section class="card bg-base-100 shadow-sm">
            <div class="card-body gap-6 p-6 lg:p-8">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div class="space-y-1">
                        <h2 class="text-xl font-bold text-gray-900">Select Business</h2>
                        <p class="text-sm text-gray-500">Choose which business landing page to edit.</p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('admin.businesses.landing-editor', 'dakong-balay') }}"
                           class="btn btn-sm rounded-full {{ $currentSlug === 'dakong-balay' ? 'bg-[#1e293b] text-white hover:bg-[#334155]' : 'bg-[#f8fafc] text-gray-700 hover:bg-[#f5f3ff]' }}">
                            🍽️ Dakong Balay
                        </a>
                        <a href="{{ route('admin.businesses.landing-editor', 'villa-carmelita') }}"
                           class="btn btn-sm rounded-full {{ $currentSlug === 'villa-carmelita' ? 'bg-[#1e293b] text-white hover:bg-[#334155]' : 'bg-[#f8fafc] text-gray-700 hover:bg-[#f5f3ff]' }}">
                            🏨 Villa Carmelita
                        </a>
                        <a href="{{ route('admin.businesses.landing-editor', 'monclaire-pool') }}"
                           class="btn btn-sm rounded-full {{ $currentSlug === 'monclaire-pool' ? 'bg-[#1e293b] text-white hover:bg-[#334155]' : 'bg-[#f8fafc] text-gray-700 hover:bg-[#f5f3ff]' }}">
                            🏊 Monclaire Pool
                        </a>
                    </div>
                </div>

                {{-- Current selection info --}}
                <div class="flex items-center gap-3 rounded-2xl bg-[#f8fafc] border border-[#e2e8f0] px-4 py-3">
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#1e293b] text-white text-sm">
                        @if($currentSlug === 'villa-carmelita') 🏨
                        @elseif($currentSlug === 'monclaire-pool') 🏊
                        @else 🍽️
                        @endif
                    </div>
                    <div>
                        <div class="text-sm font-bold text-gray-900">{{ $businessName }}</div>
                        <div class="text-xs text-gray-500">{{ $businessType }} · Editing landing page content</div>
                    </div>
                    <a href="/{{ $currentSlug }}" target="_blank"
                       class="ml-auto btn btn-sm btn-outline rounded-full border-gray-300 text-gray-700 hover:bg-[#f5f3ff] shrink-0">
                        Preview public page ↗
                    </a>
                </div>
            </div>
        </section>

        {{-- ─────────────────────────────────────────────────────────────────── --}}
        {{-- Landing Page Content Editor                                        --}}
        {{-- ─────────────────────────────────────────────────────────────────── --}}
        <section id="landing-editor" class="card bg-base-100 shadow-sm">
            <div class="card-body gap-6 p-6 lg:p-8">

                {{-- Header --}}
                <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between border-b border-gray-100 pb-6">
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
                            class="lp-tab btn btn-sm rounded-full bg-[#1e293b] text-white hover:bg-[#334155]"
                            data-tab="hero" aria-selected="true">
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
                                    <input type="text" value="AI-Powered Business Platform" class="input input-bordered w-full bg-base-100">
                                </label>
                                <label class="form-control">
                                    <div class="label">
                                        <span class="label-text font-medium text-gray-700">Primary CTA Label</span>
                                    </div>
                                    <input type="text" value="Explore Businesses" class="input input-bordered w-full bg-base-100">
                                </label>
                            </div>

                            {{-- Row 2: Headline (full-width) --}}
                            <label class="form-control">
                                <div class="label">
                                    <span class="label-text font-medium text-gray-700">Headline</span>
                                </div>
                                <textarea rows="2" class="textarea textarea-bordered w-full bg-base-100">Experience All Our Services in One Smart Platform</textarea>
                            </label>

                            {{-- Row 3: Tagline + Description --}}
                            <div class="grid gap-4 md:grid-cols-2">
                                <label class="form-control">
                                    <div class="label">
                                        <span class="label-text font-medium text-gray-700">Tagline</span>
                                    </div>
                                    <textarea rows="2" class="textarea textarea-bordered w-full bg-base-100">Explore services, locations, offers, and FAQs through a single polished business experience.</textarea>
                                </label>
                                <label class="form-control">
                                    <div class="label">
                                        <span class="label-text font-medium text-gray-700">Description</span>
                                    </div>
                                    <textarea rows="3" class="textarea textarea-bordered w-full bg-base-100">Discover services, locations, offers, and FAQs through a polished landing page experience tailored to your business.</textarea>
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
                                <input type="text" value="One AI assistant connecting multiple businesses" class="input input-bordered w-full bg-base-100">
                            </label>

                            {{-- Row 6: Assistant Description (full-width) --}}
                            <label class="form-control">
                                <div class="label">
                                    <span class="label-text font-medium text-gray-700">Assistant Description</span>
                                </div>
                                <textarea rows="2" class="textarea textarea-bordered w-full bg-base-100">Ask once and get clear answers about services, locations, offers, and FAQs.</textarea>
                            </label>

                            {{-- Row 7: Cover Photo --}}
                            <div class="grid gap-3 md:grid-cols-[minmax(0,1fr)_auto] md:items-end">
                                <label class="form-control">
                                    <div class="label">
                                        <span class="label-text font-medium text-gray-700">Cover Photo</span>
                                    </div>
                                    <input type="text" value="{{ $businessCover }}" readonly class="input input-bordered w-full bg-base-100 text-gray-600">
                                </label>
                                <button type="button" disabled class="btn btn-outline rounded-full border-gray-300 text-gray-400 cursor-not-allowed">Replace image (UI only)</button>
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
                                    <input type="text" value="About Your Business" class="input input-bordered w-full bg-base-100">
                                </label>

                                <label class="form-control">
                                    <div class="label">
                                        <span class="label-text font-medium text-gray-700">About Headline</span>
                                    </div>
                                    <input type="text" value="One platform. One assistant. Multiple businesses." class="input input-bordered w-full bg-base-100">
                                </label>
                            </div>

                            <label class="form-control">
                                <div class="label">
                                    <span class="label-text font-medium text-gray-700">About Description</span>
                                </div>
                                <textarea rows="3" class="textarea textarea-bordered w-full bg-base-100">Discover services, locations, offers, and FAQs through a polished landing page experience tailored to your business.</textarea>
                            </label>

                            <label class="form-control">
                                <div class="label">
                                    <span class="label-text font-medium text-gray-700">About Quote</span>
                                </div>
                                <textarea rows="2" class="textarea textarea-bordered w-full bg-base-100">Fast answers, consistent information, and a smoother customer journey.</textarea>
                            </label>
                        </div>
                    </div>
                    <div id="lp-panel-features" role="tabpanel" class="lp-panel hidden">
                        <div class="space-y-6">

                            {{-- Feature Card 1 --}}
                            <div class="rounded-3xl bg-[#f8fafc] p-5 space-y-4">
                                <div class="text-sm font-bold text-gray-700 border-b border-gray-200 pb-2">Feature Card 1</div>
                                <div class="space-y-4">
                                    <div class="grid gap-4 md:grid-cols-2">
                                        <label class="form-control">
                                            <div class="label">
                                                <span class="label-text font-medium text-gray-700">Label</span>
                                            </div>
                                            <input type="text" value="Featured Business One" class="input input-bordered w-full bg-base-100">
                                        </label>
                                        <label class="form-control">
                                            <div class="label">
                                                <span class="label-text font-medium text-gray-700">Description</span>
                                            </div>
                                            <textarea rows="2" class="textarea textarea-bordered w-full bg-base-100">A warm dining destination for authentic cuisine, family gatherings, and memorable meals.</textarea>
                                        </label>
                                    </div>
                                    <div class="grid gap-4 md:grid-cols-2">
                                        <label class="form-control">
                                            <div class="label">
                                                <span class="label-text font-medium text-gray-700">Bullet 1</span>
                                            </div>
                                            <input type="text" value="" placeholder="e.g. Key feature or offering" class="input input-bordered w-full bg-base-100">
                                        </label>
                                        <label class="form-control">
                                            <div class="label">
                                                <span class="label-text font-medium text-gray-700">Bullet 2</span>
                                            </div>
                                            <input type="text" value="" placeholder="e.g. Key feature or offering" class="input input-bordered w-full bg-base-100">
                                        </label>
                                    </div>
                                    <div class="grid gap-4 md:grid-cols-2">
                                        <label class="form-control">
                                            <div class="label">
                                                <span class="label-text font-medium text-gray-700">Bullet 3</span>
                                            </div>
                                            <input type="text" value="" placeholder="e.g. Key feature or offering" class="input input-bordered w-full bg-base-100">
                                        </label>
                                        <label class="form-control">
                                            <div class="label">
                                                <span class="label-text font-medium text-gray-700">Bullet 4</span>
                                            </div>
                                            <input type="text" value="" placeholder="e.g. Key feature or offering" class="input input-bordered w-full bg-base-100">
                                        </label>
                                    </div>
                                </div>
                            </div>

                            {{-- Feature Card 2 --}}
                            <div class="rounded-3xl bg-[#f8fafc] p-5 space-y-4">
                                <div class="text-sm font-bold text-gray-700 border-b border-gray-200 pb-2">Feature Card 2</div>
                                <div class="space-y-4">
                                    <div class="grid gap-4 md:grid-cols-2">
                                        <label class="form-control">
                                            <div class="label">
                                                <span class="label-text font-medium text-gray-700">Label</span>
                                            </div>
                                            <input type="text" value="Featured Business Two" class="input input-bordered w-full bg-base-100">
                                        </label>
                                        <label class="form-control">
                                            <div class="label">
                                                <span class="label-text font-medium text-gray-700">Description</span>
                                            </div>
                                            <textarea rows="2" class="textarea textarea-bordered w-full bg-base-100">Relax and enjoy a refreshing experience with amenities ideal for leisure and celebrations.</textarea>
                                        </label>
                                    </div>
                                    <div class="grid gap-4 md:grid-cols-2">
                                        <label class="form-control">
                                            <div class="label">
                                                <span class="label-text font-medium text-gray-700">Bullet 1</span>
                                            </div>
                                            <input type="text" value="" placeholder="e.g. Key feature or offering" class="input input-bordered w-full bg-base-100">
                                        </label>
                                        <label class="form-control">
                                            <div class="label">
                                                <span class="label-text font-medium text-gray-700">Bullet 2</span>
                                            </div>
                                            <input type="text" value="" placeholder="e.g. Key feature or offering" class="input input-bordered w-full bg-base-100">
                                        </label>
                                    </div>
                                    <div class="grid gap-4 md:grid-cols-2">
                                        <label class="form-control">
                                            <div class="label">
                                                <span class="label-text font-medium text-gray-700">Bullet 3</span>
                                            </div>
                                            <input type="text" value="" placeholder="e.g. Key feature or offering" class="input input-bordered w-full bg-base-100">
                                        </label>
                                        <label class="form-control">
                                            <div class="label">
                                                <span class="label-text font-medium text-gray-700">Bullet 4</span>
                                            </div>
                                            <input type="text" value="" placeholder="e.g. Key feature or offering" class="input input-bordered w-full bg-base-100">
                                        </label>
                                    </div>
                                </div>
                            </div>

                            {{-- Feature Card 3 --}}
                            <div class="rounded-3xl bg-[#f8fafc] p-5 space-y-4">
                                <div class="text-sm font-bold text-gray-700 border-b border-gray-200 pb-2">Feature Card 3</div>
                                <div class="space-y-4">
                                    <div class="grid gap-4 md:grid-cols-2">
                                        <label class="form-control">
                                            <div class="label">
                                                <span class="label-text font-medium text-gray-700">Label</span>
                                            </div>
                                            <input type="text" value="Featured Business Three" class="input input-bordered w-full bg-base-100">
                                        </label>
                                        <label class="form-control">
                                            <div class="label">
                                                <span class="label-text font-medium text-gray-700">Description</span>
                                            </div>
                                            <textarea rows="2" class="textarea textarea-bordered w-full bg-base-100">A comfortable hospitality destination for stays, gatherings, and poolside moments.</textarea>
                                        </label>
                                    </div>
                                    <div class="grid gap-4 md:grid-cols-2">
                                        <label class="form-control">
                                            <div class="label">
                                                <span class="label-text font-medium text-gray-700">Bullet 1</span>
                                            </div>
                                            <input type="text" value="" placeholder="e.g. Key feature or offering" class="input input-bordered w-full bg-base-100">
                                        </label>
                                        <label class="form-control">
                                            <div class="label">
                                                <span class="label-text font-medium text-gray-700">Bullet 2</span>
                                            </div>
                                            <input type="text" value="" placeholder="e.g. Key feature or offering" class="input input-bordered w-full bg-base-100">
                                        </label>
                                    </div>
                                    <div class="grid gap-4 md:grid-cols-2">
                                        <label class="form-control">
                                            <div class="label">
                                                <span class="label-text font-medium text-gray-700">Bullet 3</span>
                                            </div>
                                            <input type="text" value="" placeholder="e.g. Key feature or offering" class="input input-bordered w-full bg-base-100">
                                        </label>
                                        <label class="form-control">
                                            <div class="label">
                                                <span class="label-text font-medium text-gray-700">Bullet 4</span>
                                            </div>
                                            <input type="text" value="" placeholder="e.g. Key feature or offering" class="input input-bordered w-full bg-base-100">
                                        </label>
                                    </div>
                                    <div class="flex items-center justify-between rounded-2xl bg-white border border-[#e2e8f0] px-4 py-3">
                                        <div>
                                            <div class="text-sm font-medium text-gray-700">Show "Check Rooms &amp; Live Availability" button</div>
                                            <p class="text-xs text-gray-500 mt-0.5">Displays the room availability modal on the public page.</p>
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
                                <div class="text-sm font-bold text-gray-700 border-b border-gray-200 pb-2">Gallery Item 1</div>

                                <div class="grid gap-4 md:grid-cols-2">
                                    <label class="form-control">
                                        <div class="label">
                                            <span class="label-text font-medium text-gray-700">Title</span>
                                        </div>
                                        <input type="text" value="Warm Spaces" class="input input-bordered w-full bg-base-100">
                                    </label>

                                    <label class="form-control">
                                        <div class="label">
                                            <span class="label-text font-medium text-gray-700">Description</span>
                                        </div>
                                        <textarea rows="2" class="textarea textarea-bordered w-full bg-base-100">Warm spaces and inviting hospitality.</textarea>
                                    </label>
                                </div>

                                <div class="space-y-2">
                                    <div class="label">
                                        <span class="label-text font-medium text-gray-700">Image</span>
                                    </div>
                                    <div class="h-28 w-full rounded-2xl bg-gradient-to-br from-[#f5f3ff] to-[#e2e8f0] flex items-center justify-center">
                                        <span class="text-xs text-gray-400">No image uploaded</span>
                                    </div>
                                    <button type="button" disabled class="btn btn-sm btn-outline rounded-full border-gray-300 text-gray-400 cursor-not-allowed">
                                        Upload image (coming soon)
                                    </button>
                                </div>
                            </div>

                            {{-- Gallery Item 2 --}}
                            <div class="rounded-3xl bg-[#f8fafc] p-5 space-y-4">
                                <div class="text-sm font-bold text-gray-700 border-b border-gray-200 pb-2">Gallery Item 2</div>

                                <div class="grid gap-4 md:grid-cols-2">
                                    <label class="form-control">
                                        <div class="label">
                                            <span class="label-text font-medium text-gray-700">Title</span>
                                        </div>
                                        <input type="text" value="Dining Moments" class="input input-bordered w-full bg-base-100">
                                    </label>

                                    <label class="form-control">
                                        <div class="label">
                                            <span class="label-text font-medium text-gray-700">Description</span>
                                        </div>
                                        <textarea rows="2" class="textarea textarea-bordered w-full bg-base-100">Memorable meals and shared experiences.</textarea>
                                    </label>
                                </div>

                                <div class="space-y-2">
                                    <div class="label">
                                        <span class="label-text font-medium text-gray-700">Image</span>
                                    </div>
                                    <div class="h-28 w-full rounded-2xl bg-gradient-to-br from-[#f5f3ff] to-[#e2e8f0] flex items-center justify-center">
                                        <span class="text-xs text-gray-400">No image uploaded</span>
                                    </div>
                                    <button type="button" disabled class="btn btn-sm btn-outline rounded-full border-gray-300 text-gray-400 cursor-not-allowed">
                                        Upload image (coming soon)
                                    </button>
                                </div>
                            </div>

                            {{-- Gallery Item 3 --}}
                            <div class="rounded-3xl bg-[#f8fafc] p-5 space-y-4">
                                <div class="text-sm font-bold text-gray-700 border-b border-gray-200 pb-2">Gallery Item 3</div>

                                <div class="grid gap-4 md:grid-cols-2">
                                    <label class="form-control">
                                        <div class="label">
                                            <span class="label-text font-medium text-gray-700">Title</span>
                                        </div>
                                        <input type="text" value="Poolside Views" class="input input-bordered w-full bg-base-100">
                                    </label>

                                    <label class="form-control">
                                        <div class="label">
                                            <span class="label-text font-medium text-gray-700">Description</span>
                                        </div>
                                        <textarea rows="2" class="textarea textarea-bordered w-full bg-base-100">Relaxing scenes from leisure and stay destinations.</textarea>
                                    </label>
                                </div>

                                <div class="space-y-2">
                                    <div class="label">
                                        <span class="label-text font-medium text-gray-700">Image</span>
                                    </div>
                                    <div class="h-28 w-full rounded-2xl bg-gradient-to-br from-[#f5f3ff] to-[#e2e8f0] flex items-center justify-center">
                                        <span class="text-xs text-gray-400">No image uploaded</span>
                                    </div>
                                    <button type="button" disabled class="btn btn-sm btn-outline rounded-full border-gray-300 text-gray-400 cursor-not-allowed">
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
                                These fields control the modal that appears when a visitor clicks "Check Rooms &amp; Live Availability" on Feature Card 3.
                            </div>

                            {{-- Two-column row: Room Business Name + Modal Title --}}
                            <div class="grid gap-4 md:grid-cols-2">
                                <label class="form-control">
                                    <div class="label">
                                        <span class="label-text font-medium text-gray-700">Room Business Name</span>
                                    </div>
                                    <input type="text" value="Your Business" class="input input-bordered w-full bg-base-100">
                                </label>

                                <label class="form-control">
                                    <div class="label">
                                        <span class="label-text font-medium text-gray-700">Modal Title</span>
                                    </div>
                                    <input type="text" value="Your Business Rooms &amp; Availability" class="input input-bordered w-full bg-base-100">
                                </label>
                            </div>

                            {{-- Full-width Modal Description --}}
                            <label class="form-control">
                                <div class="label">
                                    <span class="label-text font-medium text-gray-700">Modal Description</span>
                                </div>
                                <textarea rows="2" class="textarea textarea-bordered w-full bg-base-100">View real-time room rates and vacancies at Your Business</textarea>
                            </label>

                            {{-- Full-width Inquiry Text --}}
                            <label class="form-control">
                                <div class="label">
                                    <span class="label-text font-medium text-gray-700">Inquiry Text</span>
                                </div>
                                <textarea rows="2" class="textarea textarea-bordered w-full bg-base-100">For booking inquiries, select "Chat with AI" or call support.</textarea>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between border-t border-gray-100 pt-6">
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
@endsection

@section('scripts')
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

        // ── Landing Page Editor — Tab Switching ──────────────────────────
        const lpTabs   = document.querySelectorAll('.lp-tab');
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
