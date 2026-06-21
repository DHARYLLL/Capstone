{{-- filepath: c:\Users\dhary\Desktop\Capstone\capstone1\resources\views\partials\navbar.blade.php --}}
@php
    $navBusiness   = $business ?? null;
    $navName       = data_get($navBusiness, 'name', 'Project RED AI');
    $navSubtitle   = data_get($navBusiness, 'tagline', 'AI-Powered Business Platform');
    $navLogoPath   = data_get($navBusiness, 'logo_path');
    $navLogoUrl    = $navLogoPath ? asset('storage/' . ltrim($navLogoPath, '/')) : null;
@endphp

<nav class="fixed top-0 z-40 w-full border-b border-[#E9E2D6] bg-[#FCFBF8]/90 backdrop-blur">
    <div class="navbar mx-auto max-w-7xl px-4 lg:px-8">
        <div class="navbar-start">
            <a href="#home" class="flex items-center gap-3">
                @if ($navLogoUrl)
                    <img src="{{ $navLogoUrl }}" alt="{{ $navName }} logo" class="h-11 w-11 rounded-2xl object-cover shadow-sm">
                @else
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[#5A3E2B] text-white shadow-sm">
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

        <div class="navbar-center hidden lg:flex">
            <ul class="menu menu-horizontal gap-1 px-1 text-sm font-medium text-gray-700">
                <li><a href="#home" class="rounded-full hover:bg-[#F4EEDF] hover:text-[#5A3E2B] px-4 py-2 transition-colors">Home</a></li>
                <li><a href="#businesses" class="rounded-full hover:bg-[#F4EEDF] hover:text-[#5A3E2B] px-4 py-2 transition-colors">Our Businesses</a></li>
                <li><a href="#about" class="rounded-full hover:bg-[#F4EEDF] hover:text-[#5A3E2B] px-4 py-2 transition-colors">About</a></li>
                <li><a href="#gallery" class="rounded-full hover:bg-[#F4EEDF] hover:text-[#5A3E2B] px-4 py-2 transition-colors">Gallery</a></li>
            </ul>
        </div>

        <div class="navbar-end gap-2">
            {{-- Chat CTA --}}
            <label for="chatbot-toggle" class="btn btn-sm rounded-full border-0 bg-[#5A3E2B] px-5 text-white hover:bg-[#453020] shadow-sm transition-all duration-300 cursor-pointer">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h8M8 14h5m-7 7l-4 1 1-4A9 9 0 1118 6 9 9 0 016 21z"/>
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
                <ul tabindex="0" class="dropdown-content menu menu-sm z-[1] mt-3 w-52 rounded-2xl border border-[#E9E2D6] bg-[#FCFBF8] p-2 shadow-lg text-sm font-medium text-gray-700">
                    <li><a href="#home">Home</a></li>
                    <li><a href="#businesses">Our Businesses</a></li>
                    <li><a href="#about">About</a></li>
                    <li><a href="#gallery">Gallery</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>
