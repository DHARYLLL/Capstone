{{-- filepath: resources/views/partials/admin-sidebar.blade.php --}}
@php
    $tenantName = 'DARIV Waterproofing';
    $tenantType = 'Residential & Roof Sealing';
    $tenantStatus = 'Live';

    $userName = session('user_name', 'Guest Operator');
    $userEmail = session('user_email', 'operator@dariv.com');
    $userRole = session('user_role', 'Lead Operator');
    $userAvatar = session('user_avatar', '👤');
@endphp

<aside class="sticky top-0 hidden h-screen w-72 shrink-0 flex-col overflow-hidden border-r border-gray-200 bg-base-100 md:flex">
    <div class="border-b border-gray-200 px-6 py-5">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-brand-primary p-2 text-white shadow-sm">
                <img src="{{ asset('images/logo.svg') }}" alt="DARIV Logo" class="h-full w-full object-contain filter invert">
            </div>
            <div>
                <div class="text-base font-extrabold tracking-tight text-gray-900 leading-tight">DARIV</div>
                <div class="text-[10px] font-bold text-violet-600 uppercase tracking-widest">Admin Console</div>
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
                    'route'      => 'admin.dashboard',
                    'href'       => route('admin.dashboard'),
                    'label'      => 'Overview Dashboard',
                    'icon'       => 'M4 13h7V4H4v9zm0 7h7v-5H4v5zm9 0h7V11h-7v9zm0-16v5h7V4h-7z',
                    'active'     => request()->routeIs('admin.dashboard'),
                    'admin_only' => true,
                ],
                [
                    'route'      => 'admin.chat',
                    'href'       => route('admin.chat'),
                    'label'      => 'Live Chat & Handoff',
                    'icon'       => 'M4 4h16v12H7l-3 3V4zm4 5h8v2H8V9zm0 4h6v2H8v-2z',
                    'active'     => request()->routeIs('admin.chat'),
                    'admin_only' => false,
                ],
                [
                    'route'      => 'chat.demo',
                    'href'       => route('chat.demo'),
                    'label'      => 'Widget Embed Code',
                    'icon'       => 'M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z',
                    'active'     => request()->routeIs('chat.demo'),
                    'admin_only' => false,
                ],
                [
                    'route'      => 'admin.knowledge-base',
                    'href'       => route('admin.knowledge-base'),
                    'label'      => 'Knowledge Base',
                    'icon'       => 'M12 2a7 7 0 0 0-7 7v13h14V9a7 7 0 0 0-7-7zm-2 8h4v2h-4v-2zm0 4h4v2h-4v-2z',
                    'active'     => request()->routeIs('admin.knowledge-base'),
                    'admin_only' => true,
                ],
                [
                    'route'      => 'admin.analytics',
                    'href'       => route('admin.analytics'),
                    'label'      => 'Reporting & Analytics',
                    'icon'       => 'M12 3C7.03 3 3 6.58 3 11c0 2.47 1.22 4.7 3.22 6.29L5 21l3.9-1.96c.97.25 2 .38 3.1.38 4.97 0 9-3.58 9-8s-4.03-8-9-8zm-3 9H7v-2h2v2zm4 0h-2v-2h2v2zm4 0h-2v-2h2v2z',
                    'active'     => request()->routeIs('admin.analytics'),
                    'admin_only' => true,
                ],
                [
                    'route'      => 'admin.staff',
                    'href'       => route('admin.staff'),
                    'label'      => 'Staff & Roles',
                    'icon'       => 'M12 12a4 4 0 1 0-4-4 4 4 0 0 0 4 4zm0 2c-4.4 0-8 2.2-8 5v1h16v-1c0-2.8-3.6-5-8-5z',
                    'active'     => request()->routeIs('admin.staff'),
                    'admin_only' => true,
                ],
                [
                    'route'      => 'admin.logs',
                    'href'       => route('admin.logs'),
                    'label'      => 'Ingestion Logs',
                    'icon'       => 'M4 6h16v2H4zm0 5h16v2H4zm0 5h16v2H4z',
                    'active'     => request()->routeIs('admin.logs'),
                    'admin_only' => true,
                ],
            ];
        @endphp
        <ul class="space-y-2">
            @foreach ($navItems as $item)
                @if (!$item['admin_only'] || $userRole === 'Administrator')
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
                @endif
            @endforeach
        </ul>
    </nav>

</aside>
