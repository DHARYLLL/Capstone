{{-- filepath: resources/views/partials/staff-sidebar.blade.php --}}
@php
    $tenantName = 'DARIV Waterproofing';
    $tenantType = 'Residential & Roof Sealing';

    $userName = session('user_name', 'Guest Operator');
    $userRole = session('user_role', 'Lead Operator');
    $userAvatar = session('user_avatar', '👤');
@endphp

<aside class="sticky top-0 hidden h-screen w-72 shrink-0 flex-col overflow-hidden border-r border-gray-200 bg-base-100 md:flex">
    <div class="border-b border-gray-200 px-6 py-5">
        <a href="{{ route('staff.chat') }}" class="flex items-center gap-3">
            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-purple-500 to-indigo-600 p-2 text-white shadow-sm">
                <img src="{{ asset('images/logo.svg') }}" alt="DARIV Logo" class="h-full w-full object-contain filter invert">
            </div>
            <div>
                <div class="text-base font-extrabold tracking-tight text-gray-900 leading-tight">DARIV</div>
                <div class="text-[10px] font-bold text-indigo-600 uppercase tracking-widest">Staff Portal</div>
            </div>
        </a>
        <div class="mt-4 rounded-2xl border border-[#e2e8f0] bg-[#ffffff] px-4 py-3">
            <div class="text-xs font-semibold uppercase tracking-wider text-gray-400"> Waterproofing Co.</div>
            <div class="mt-1 text-sm font-bold text-gray-900">{{ $tenantName }}</div>
            <div class="mt-1 flex items-center gap-2 text-xs text-gray-500">
                <span class="inline-flex h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                {{ $tenantType }} · Active
            </div>
        </div>
    </div>

    <nav class="min-h-0 flex-1 overflow-y-auto px-4 py-5">
        @php
            $navItems = [
                [
                    'route'  => 'staff.chat',
                    'href'   => route('staff.chat'),
                    'label'  => 'Live Chat & Handoff',
                    'icon'   => 'M4 4h16v12H7l-3 3V4zm4 5h8v2H8V9zm0 4h6v2H8v-2z',
                    'active' => request()->routeIs('staff.chat'),
                ],
            ];
        @endphp
        <ul class="space-y-2">
            @foreach ($navItems as $item)
                <li>
                    <a href="{{ $item['href'] }}"
                       class="flex items-center gap-3 rounded-2xl px-4 py-3 font-medium transition-colors
                              {{ $item['active']
                                  ? 'bg-brand-primary font-semibold text-white shadow-sm'
                                  : 'text-gray-700 hover:bg-[#f5f3ff] hover:text-brand-primary' }}">
                        <svg viewBox="0 0 24 24" class="h-5 w-5 shrink-0 fill-current" aria-hidden="true">
                            <path d="{{ $item['icon'] }}"/>
                        </svg>
                        {{ $item['label'] }}
                    </a>
                </li>
            @endforeach
        </ul>
    </nav>

</aside>
