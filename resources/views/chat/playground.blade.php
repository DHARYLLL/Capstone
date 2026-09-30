{{-- filepath: resources/views/chat/playground.blade.php --}}
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $company->name ?? 'DARIV Waterproofing' }} | Official Site</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
    </style>
</head>
@php
    $companyKey = $company?->api_key ?? request('api_key', '');
    $unitName = $company?->name ?? 'DARIV Waterproofing';
    $heroGradient = 'from-violet-600 via-indigo-600 to-slate-900';
@endphp
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col justify-between selection:bg-violet-500 selection:text-white">

    <!-- Mock Navigation Header -->
    <header class="sticky top-0 z-40 bg-white/90 backdrop-blur-md border-b border-slate-200/80">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-3.5 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="h-8 w-8 rounded-xl bg-gradient-to-tr from-violet-600 to-indigo-600 flex items-center justify-center text-white font-black text-sm shadow-xs">
                    {{ strtoupper(substr($unitName, 0, 1)) }}
                </div>
                <div>
                    <div class="text-sm font-black tracking-tight text-slate-900">{{ $unitName }}</div>
                    <div class="text-[9px] font-bold text-violet-600 uppercase tracking-wider">Premium Waterproofing</div>
                </div>
            </div>

            <nav class="hidden md:flex items-center gap-6 text-xs font-semibold text-slate-600">
                <a href="#services" class="hover:text-violet-600 transition">Services</a>
                <a href="#about" class="hover:text-violet-600 transition">About Us</a>
                <a href="#reviews" class="hover:text-violet-600 transition">Reviews</a>
                <a href="#contact" class="hover:text-violet-600 transition">Contact</a>
            </nav>

            <a href="#contact" class="inline-flex items-center gap-1.5 rounded-xl bg-slate-900 px-3.5 py-2 text-xs font-bold text-white shadow-xs hover:bg-violet-600 transition duration-200">
                <span>Book Inspection</span>
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                </svg>
            </a>
        </div>
    </header>

    <!-- Main Content Flow -->
    <main class="flex-1">
        <!-- Hero Section -->
        <section class="relative overflow-hidden bg-gradient-to-br {{ $heroGradient }} text-white px-5 py-12 sm:py-16 text-center">
            <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px]"></div>
            <div class="relative max-w-3xl mx-auto space-y-4">
                <div class="inline-flex items-center gap-2 rounded-full bg-white/15 px-3 py-1 text-[10px] font-bold uppercase tracking-wider backdrop-blur-md ring-1 ring-white/20">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    Certified Waterproofing Specialists
                </div>
                <h1 class="text-2xl sm:text-4xl font-black tracking-tight leading-tight">
                    Long-Lasting Roof Deck & Concrete Waterproofing
                </h1>
                <p class="text-xs sm:text-sm text-white/80 max-w-xl mx-auto leading-relaxed">
                    Protect your home and commercial building against leaks, rainwater intrusion, and moisture damage with our multi-layer membrane technology.
                </p>
                <div class="pt-2 flex flex-wrap items-center justify-center gap-3">
                    <a href="#contact" class="rounded-xl bg-white px-4 py-2.5 text-xs font-bold text-slate-900 shadow-md hover:bg-slate-100 transition">
                        Get Instant Free Quote
                    </a>
                    <button type="button" onclick="window.parent.postMessage({ action: 'triggerChat' }, '*')" class="rounded-xl bg-white/15 border border-white/20 px-4 py-2.5 text-xs font-bold text-white hover:bg-white/25 transition flex items-center gap-2">
                        <span>💬 Chat with AI Assistant</span>
                    </button>
                </div>
            </div>
        </section>

        <!-- Trust Badges -->
        <section class="border-y border-slate-200/80 bg-white py-4">
            <div class="max-w-5xl mx-auto px-4 grid grid-cols-2 sm:grid-cols-4 gap-4 text-center">
                <div class="p-2">
                    <div class="text-lg font-black text-slate-900">10+ Years</div>
                    <div class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Industry Experience</div>
                </div>
                <div class="p-2">
                    <div class="text-lg font-black text-slate-900">1,200+</div>
                    <div class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Completed Projects</div>
                </div>
                <div class="p-2">
                    <div class="text-lg font-black text-slate-900">10-Year</div>
                    <div class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Warranty Guarantee</div>
                </div>
                <div class="p-2">
                    <div class="text-lg font-black text-slate-900">100%</div>
                    <div class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Satisfaction Rate</div>
                </div>
            </div>
        </section>

        <!-- Services Section -->
        <section id="services" class="max-w-5xl mx-auto px-4 py-10">
            <div class="text-center max-w-md mx-auto mb-8">
                <span class="text-[10px] font-bold text-violet-600 uppercase tracking-wider">Our Solutions</span>
                <h2 class="text-xl font-black text-slate-900 tracking-tight mt-1">Specialized Waterproofing Services</h2>
            </div>

            <div class="grid sm:grid-cols-3 gap-4">
                <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs hover:border-violet-300 hover:shadow-md transition">
                    <div class="h-10 w-10 rounded-xl bg-violet-50 text-violet-600 flex items-center justify-center text-lg mb-3">
                        🏢
                    </div>
                    <h3 class="text-sm font-bold text-slate-900">Roof Deck Sealing</h3>
                    <p class="text-[11px] text-slate-500 mt-1.5 leading-relaxed">
                        Polyurethane and elastomeric membrane applications designed to withstand heavy rain and solar UV exposure.
                    </p>
                </div>

                <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs hover:border-violet-300 hover:shadow-md transition">
                    <div class="h-10 w-10 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center text-lg mb-3">
                        🚿
                    </div>
                    <h3 class="text-sm font-bold text-slate-900">Bathroom & Wet Areas</h3>
                    <p class="text-[11px] text-slate-500 mt-1.5 leading-relaxed">
                        Under-tile cementitious barrier waterproofing for residential restrooms, kitchens, and laundry zones.
                    </p>
                </div>

                <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs hover:border-violet-300 hover:shadow-md transition">
                    <div class="h-10 w-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg mb-3">
                        🏗️
                    </div>
                    <h3 class="text-sm font-bold text-slate-900">Basement & Foundation</h3>
                    <p class="text-[11px] text-slate-500 mt-1.5 leading-relaxed">
                        Negative and positive side crystalline waterproofing preventing subterranean water seepage and soil pressure leaks.
                    </p>
                </div>
            </div>
        </section>

        <!-- Customer Reviews -->
        <section id="reviews" class="bg-white border-y border-slate-200/80 py-10 px-4">
            <div class="max-w-5xl mx-auto">
                <div class="text-center max-w-md mx-auto mb-8">
                    <span class="text-[10px] font-bold text-violet-600 uppercase tracking-wider">Testimonials</span>
                    <h2 class="text-xl font-black text-slate-900 tracking-tight mt-1">What Our Clients Say</h2>
                </div>

                <div class="grid sm:grid-cols-2 gap-4">
                    <div class="rounded-2xl bg-slate-50 border border-slate-200/70 p-4">
                        <div class="flex text-amber-400 text-xs mb-2">★★★★★</div>
                        <p class="text-xs text-slate-600 leading-relaxed italic">
                            "DARIV completely resolved our severe roof deck leak that three previous contractors couldn't fix. Fast, clean, and professional team!"
                        </p>
                        <div class="mt-3 text-[11px] font-bold text-slate-900">— Engr. Roberto Tan, Cebu City</div>
                    </div>

                    <div class="rounded-2xl bg-slate-50 border border-slate-200/70 p-4">
                        <div class="flex text-amber-400 text-xs mb-2">★★★★★</div>
                        <p class="text-xs text-slate-600 leading-relaxed italic">
                            "Excellent warranty coverage and super responsive customer support. The live chat system gave me instant answers and an inspection schedule right away."
                        </p>
                        <div class="mt-3 text-[11px] font-bold text-slate-900">— Maria Santos, Mandaue City</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Mock Contact / Booking Form -->
        <section id="contact" class="max-w-5xl mx-auto px-4 py-10">
            <div class="rounded-3xl bg-slate-900 text-white p-6 sm:p-10 relative overflow-hidden">
                <div class="relative max-w-xl">
                    <span class="text-[10px] font-bold text-violet-400 uppercase tracking-wider">Free Site Evaluation</span>
                    <h2 class="text-2xl font-black tracking-tight mt-1">Book a Waterproofing Inspection</h2>
                    <p class="text-xs text-slate-400 mt-2 leading-relaxed">
                        Leave your contact info or click the chat bubble in the bottom right corner to speak with our AI consultant directly!
                    </p>

                    <form onsubmit="event.preventDefault(); alert('Inspection inquiry submitted! In production, our team or AI bot follows up immediately.');" class="mt-5 space-y-3">
                        <div class="grid sm:grid-cols-2 gap-3">
                            <input type="text" placeholder="Your Name" class="w-full rounded-xl bg-slate-800 border border-slate-700 px-3.5 py-2.5 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-violet-500" required>
                            <input type="tel" placeholder="Mobile Number" class="w-full rounded-xl bg-slate-800 border border-slate-700 px-3.5 py-2.5 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-violet-500" required>
                        </div>
                        <input type="text" placeholder="Project Location (e.g. Cebu City)" class="w-full rounded-xl bg-slate-800 border border-slate-700 px-3.5 py-2.5 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-violet-500" required>
                        <button type="submit" class="w-full rounded-xl bg-gradient-to-r from-violet-600 to-indigo-600 py-3 text-xs font-bold text-white shadow-md hover:from-violet-500 hover:to-indigo-500 transition">
                            Schedule Free On-Site Inspection
                        </button>
                    </form>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200/80 py-6 px-4 text-center text-xs text-slate-400">
        <p>&copy; {{ date('Y') }} {{ $unitName }}. All rights reserved &middot; Powered by Project RED AI.</p>
    </footer>

    <!-- Injected Chat Widget -->
    <script src="/js/chat-widget.js" data-company-key="{{ $companyKey }}" async></script>
</body>
</html>
