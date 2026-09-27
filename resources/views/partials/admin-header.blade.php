@php
    $userName = session('user_name', 'Guest Operator');
    $userRole = session('user_role', 'Lead Operator');
    $userInitials = strtoupper(substr($userName, 0, 2));
@endphp

<header class="sticky top-0 z-30 border-b border-slate-200/80 bg-white/85 backdrop-blur-md transition-all">
    <div class="flex items-center justify-between gap-4 px-4 py-3 sm:px-6 lg:px-8">
        
        <!-- Left: Page Title & Breadcrumbs -->
        <div class="flex items-center gap-4 min-w-0">
            <!-- Mobile Menu Toggle Button -->
            <label for="admin-mobile-drawer" class="btn btn-ghost btn-sm btn-square md:hidden text-slate-600 hover:bg-violet-50 hover:text-violet-600 transition-transform active:scale-95">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </label>

            <div class="min-w-0">
                <div class="flex items-center gap-1.5 text-[11px] font-semibold tracking-wide text-slate-400">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-violet-600 transition-colors">Admin</a>
                    <svg class="h-3 w-3 text-slate-300" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                    </svg>
                    <span class="text-slate-600 truncate">@yield('page_title', 'Overview')</span>
                </div>
                <h1 class="text-lg font-black tracking-tight text-slate-900 leading-tight truncate sm:text-xl">
                    @yield('page_title', 'Console Overview')
                </h1>
            </div>
        </div>

        <!-- Right: Status Pill, Queue Shortcut, and Profile Dropdown -->
        <div class="flex items-center gap-3">
            
            <!-- Live Queue Pill with hover effect -->
            <a href="{{ route('admin.chat') }}" 
               class="group hidden sm:inline-flex items-center gap-2 rounded-full border border-slate-200 bg-slate-50/80 px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-2xs transition-all duration-200 hover:-translate-y-0.5 hover:border-emerald-300 hover:bg-emerald-50/60 hover:text-emerald-800 hover:shadow-xs active:translate-y-0">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                </span>
                <span>Live Queue</span>
                <svg class="h-3.5 w-3.5 text-slate-400 transition-transform duration-200 group-hover:translate-x-0.5 group-hover:text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                </svg>
            </a>

            <!-- User Profile Dropdown with enhanced hover -->
            <div class="dropdown dropdown-end">
                <div tabindex="0" role="button" 
                     class="group flex items-center gap-2.5 rounded-full border border-slate-200 bg-white p-1.5 pr-3 shadow-2xs transition-all duration-200 hover:-translate-y-0.5 hover:border-violet-300 hover:bg-violet-50/30 hover:shadow-md active:translate-y-0 cursor-pointer select-none">
                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-gradient-to-tr from-violet-600 to-indigo-600 text-xs font-black text-white shadow-xs transition-transform duration-200 group-hover:scale-105 group-hover:shadow-violet-200">
                        {{ $userInitials }}
                    </div>
                    <div class="hidden text-left leading-none md:block">
                        <div class="text-xs font-bold text-slate-800 group-hover:text-violet-900 transition-colors">{{ $userName }}</div>
                        <div class="text-[10px] font-semibold text-violet-600 uppercase tracking-wider mt-0.5">{{ $userRole }}</div>
                    </div>
                    <svg class="h-3.5 w-3.5 text-slate-400 shrink-0 transition-transform duration-200 group-hover:rotate-180 group-hover:text-violet-600" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                    </svg>
                </div>

                <!-- Dropdown Menu Box -->
                <div tabindex="0" class="dropdown-content z-40 mt-2 w-60 rounded-2xl border border-slate-200 bg-white p-2 shadow-xl animate-in fade-in slide-in-from-top-2 duration-150">
                    <div class="px-3 py-2.5 border-b border-slate-100">
                        <p class="text-xs font-bold text-slate-900">{{ $userName }}</p>
                        <p class="text-[11px] text-slate-400 truncate">{{ session('user_email', 'operator@dariv.com') }}</p>
                        <span class="mt-1.5 inline-block rounded-md bg-violet-50 px-2 py-0.5 text-[10px] font-bold text-violet-700">
                            {{ $userRole }}
                        </span>
                    </div>

                    <div class="py-1 space-y-0.5 text-xs">
                        <a href="{{ route('admin.settings') }}" class="group flex items-center gap-2.5 rounded-xl px-3 py-2 font-semibold text-slate-700 hover:bg-violet-50 hover:text-violet-700 hover:translate-x-1 transition-all duration-150">
                            <svg class="h-4 w-4 text-slate-400 group-hover:text-violet-600 transition-transform group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                            Account Settings
                        </a>
                        <a href="{{ route('chat.demo') }}" target="_blank" class="group flex items-center gap-2.5 rounded-xl px-3 py-2 font-semibold text-slate-700 hover:bg-violet-50 hover:text-violet-700 hover:translate-x-1 transition-all duration-150">
                            <svg class="h-4 w-4 text-slate-400 group-hover:text-violet-600 transition-transform group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                            </svg>
                            Open Widget Playground
                        </a>
                    </div>

                    <div class="pt-1 border-t border-slate-100">
                        <a href="{{ route('logout') }}" class="group flex items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 hover:translate-x-1 transition-all duration-150">
                            <svg class="h-4 w-4 text-rose-500 transition-transform group-hover:-translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" />
                            </svg>
                            Sign Out
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</header>