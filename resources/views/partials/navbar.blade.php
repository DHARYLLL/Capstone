{{-- filepath: c:\Users\dhary\Desktop\Capstone\capstone1\resources\views\partials\navbar.blade.php --}}
@php
    $navBusiness   = $business ?? null;
    $navName       = data_get($navBusiness, 'name', 'Project RED AI');
    $navSubtitle   = data_get($navBusiness, 'tagline', 'AI-Powered Business Platform');
    $navLogoPath   = data_get($navBusiness, 'logo_path');
    $navLogoUrl    = $navLogoPath ? asset('storage/' . ltrim($navLogoPath, '/')) : null;
@endphp

<nav class="sticky top-0 z-20 w-full border-b border-gray-200 bg-[#f8fafc]/95 backdrop-blur">
    <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
        <div class="flex min-w-0 items-center">
            <a href="#home" class="flex items-center gap-3">
                @if ($navLogoUrl)
                    <img src="{{ $navLogoUrl }}" alt="{{ $navName }} logo" class="h-11 w-11 rounded-2xl object-cover shadow-sm">
                @else
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-brand-primary text-white shadow-sm">
                        <svg viewBox="0 0 24 24" class="h-6 w-6 fill-current" aria-hidden="true">
                            <path d="M12 2l8 4v12l-8 4-8-4V6l8-4zm0 2.3L6 7.1v9.8l6 3 6-3V7.1l-6-2.8z"/>
                        </svg>
                    </div>
                @endif
                <div class="leading-tight">
                    <div class="text-base font-extrabold tracking-tight text-gray-900">{{ $navName }}</div>
                    <div class="text-xs font-medium text-gray-500 max-w-[180px] truncate">{{ $navSubtitle }}</div>
                </div>
            </a>
        </div>

        <div class="hidden lg:flex">
            <ul class="menu menu-horizontal gap-1 px-1 text-sm font-medium text-gray-700">
                <li><a href="#home" class="rounded-full px-4 py-2 transition-colors hover:bg-[#f5f3ff] hover:text-brand-primary">Home</a></li>
                <li><a href="#businesses" class="rounded-full px-4 py-2 transition-colors hover:bg-[#f5f3ff] hover:text-brand-primary">Our Businesses</a></li>
                <li><a href="#about" class="rounded-full px-4 py-2 transition-colors hover:bg-[#f5f3ff] hover:text-brand-primary">About</a></li>
                <li><a href="#gallery" class="rounded-full px-4 py-2 transition-colors hover:bg-[#f5f3ff] hover:text-brand-primary">Gallery</a></li>
            </ul>
        </div>

        <div class="flex items-center gap-2">
            <label for="chatbot-toggle" class="btn btn-sm cursor-pointer rounded-full border-0 bg-brand-primary px-5 text-white shadow-sm transition-all duration-300 hover:bg-[#6d28d9]">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h8M8 14h5m-7 7l-4 1 1-4A9 9 0 1118 6 9 9 0 016 21z" />
                </svg>
                Chat with AI
            </label>

            {{-- Mobile hamburger --}}
            <div class="dropdown dropdown-end lg:hidden">
                <label tabindex="0" class="btn btn-ghost btn-sm">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </label>
                <ul tabindex="0" class="dropdown-content menu menu-sm z-[1] mt-3 w-52 rounded-2xl border border-[#e2e8f0] bg-[#f8fafc] p-2 shadow-lg text-sm font-medium text-gray-700">
                    <li><a href="#home">Home</a></li>
                    <li><a href="#businesses">Our Businesses</a></li>
                    <li><a href="#about">About</a></li>
                    <li><a href="#gallery">Gallery</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>
