{{-- filepath: resources/views/partials/staff-header.blade.php --}}
@php
    $userName = session('user_name', 'Guest Operator');
    $userRole = session('user_role', 'Lead Operator');
    $userAvatar = session('user_avatar', '👤');
@endphp
<header class="sticky top-0 z-20 border-b border-gray-200 bg-[#f8fafc]/95 backdrop-blur">
    <div class="flex items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.18em] text-gray-400">
                @yield('breadcrumbs', 'Staff / Chat & Handoff')
            </div>
            <h1 class="mt-1 text-2xl font-extrabold tracking-tight text-gray-800">@yield('page_title', 'Staff Console')</h1>
            <p class="mt-1 text-sm text-gray-500">@yield('page_description')</p>
        </div>

        <!-- User Profile Dropdown Popover (Downward-End) -->
        <div class="dropdown dropdown-end dropdown-hover">
            <div tabindex="0" role="button" class="flex items-center gap-3 rounded-2xl bg-base-100 px-4 py-2.5 shadow-sm border border-gray-150 hover:bg-slate-50 transition cursor-pointer">
                <div class="text-right leading-tight">
                    <div class="font-bold text-gray-800 text-sm">{{ $userName }}</div>
                    <div class="text-[10px] font-semibold text-indigo-600 uppercase tracking-wider mt-0.5">{{ $userRole }}</div>
                </div>
                <div class="avatar placeholder">
                    <div class="h-10 w-10 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center font-extrabold text-lg">
                        {{ $userAvatar }}
                    </div>
                </div>
                <svg class="h-4 w-4 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
            <ul tabindex="0" class="dropdown-content z-30 menu p-2 shadow-lg bg-white border border-gray-200 rounded-2xl w-56 mt-2 space-y-1">
                <li>
                    <a href="{{ route('staff.settings') }}" class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-sm text-gray-700 hover:bg-[#f5f3ff] hover:text-indigo-600 font-semibold transition">
                        <svg viewBox="0 0 24 24" class="h-4 w-4 fill-current">
                            <path d="M19.4 13c0-.3.1-.6.1-.9 0-.3 0-.6-.1-.9l2.1-1.7c.2-.2.2-.5 0-.7l-2-3.5c-.1-.2-.4-.3-.6-.2l-2.5 1c-.5-.4-1.1-.7-1.7-.9l-.4-2.6c0-.2-.2-.4-.5-.4h-4c-.3 0-.5.2-.5.4l-.4 2.6c-.6.2-1.2.5-1.7.9l-2.5-1c-.2-.1-.5 0-.6.2l-2 3.5c-.1.2-.1.5.1.7l2.1 1.7c-.1.3-.1.6-.1.9 0 .3 0 .6.1.9l-2.1 1.7c-.2.2-.2.5 0 .7l2 3.5c.1.2.4.3.6.2l2.5-1c.5.4 1.1.7 1.7.9l.4 2.6c0 .2.2.4.5.4h4c.3 0 .5-.2.5-.4l.4-2.6c.6-.2 1.2-.5 1.7-.9l2.5 1c.2.1.5 0 .6-.2l2-3.5c.1-.2.1-.5-.1-.7l-2.1-1.7zm-7.4 2.5c-1.9 0-3.5-1.6-3.5-3.5s1.6-3.5 3.5-3.5 3.5 1.6 3.5 3.5-1.6 3.5-3.5 3.5z"/>
                        </svg>
                        Account Settings
                    </a>
                </li>
                <li>
                    <a href="{{ route('logout') }}" class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-sm text-gray-700 hover:bg-rose-50 hover:text-rose-600 font-semibold transition">
                        <svg viewBox="0 0 24 24" class="h-4 w-4 fill-current">
                            <path d="M10 17l1.4-1.4L8.8 13H20v-2H8.8l2.6-2.6L10 7l-5 5 5 5zM4 4h7v2H6v12h5v2H4V4z"/>
                        </svg>
                        Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
</header>
