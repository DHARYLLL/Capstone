{{-- filepath: c:\Users\dhary\Desktop\Capstone\capstone1\resources\views\partials\admin-sidebar.blade.php --}}
@php
    $tenantName = $tenantName ?? 'Villa Carmelita';
    $tenantType = $tenantType ?? 'Business Tenant';
    $tenantStatus = $tenantStatus ?? 'Live';
@endphp

<aside class="sticky top-0 hidden h-screen w-72 shrink-0 flex-col overflow-hidden border-r border-gray-200 bg-base-100 md:flex">
    <div class="border-b border-gray-200 px-6 py-5">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-brand-primary text-white shadow-sm">
                <svg viewBox="0 0 24 24" class="h-6 w-6 fill-current" aria-hidden="true">
                    <path d="M12 2l8 4v12l-8 4-8-4V6l8-4zm0 2.3L6 7.1v9.8l6 3 6-3V7.1l-6-2.8z"/>
                </svg>
            </div>
            <div>
                <div class="text-lg font-extrabold tracking-tight text-gray-900">Project RED AI</div>
                <div class="text-xs text-gray-500">Tenant Portal</div>
            </div>
        </a>
        <div class="mt-4 rounded-2xl border border-[#e2e8f0] bg-[#ffffff] px-4 py-3">
            <div class="text-xs font-semibold uppercase tracking-wider text-gray-400">Current tenant</div>
            <div class="mt-1 text-sm font-bold text-gray-900">{{ $tenantName }}</div>
            <div class="mt-1 flex items-center gap-2 text-xs text-gray-500">
                <span class="inline-flex h-2 w-2 rounded-full bg-emerald-500"></span>
                {{ $tenantType }} · {{ $tenantStatus }}
            </div>
        </div>
    </div>

    <nav class="min-h-0 flex-1 overflow-y-auto px-4 py-5">
        @php
            $navItems = [
                [
                    'route'  => 'admin.dashboard',
                    'href'   => route('admin.dashboard'),
                    'label'  => 'Dashboard',
                    'icon'   => 'M4 13h7V4H4v9zm0 7h7v-5H4v5zm9 0h7V11h-7v9zm0-16v5h7V4h-7z',
                    'active' => request()->routeIs('admin.dashboard'),
                ],
                [
                    'route'  => 'admin.businesses.index',
                    'href'   => route('admin.businesses.index'),
                    'label'  => 'Businesses',
                    'icon'   => 'M4 5h16v14H4V5zm2 2v10h12V7H6zm2 2h8v2H8V9zm0 4h6v2H8v-2z',
                    'active' => request()->routeIs('admin.businesses.index'),
                ],
                [
                    'route'  => 'admin.businesses.knowledge-base',
                    'href'   => route('admin.businesses.knowledge-base'),
                    'label'  => 'Knowledge Base',
                    'icon'   => 'M12 2a7 7 0 0 0-7 7v13h14V9a7 7 0 0 0-7-7zm-2 8h4v2h-4v-2zm0 4h4v2h-4v-2z',
                    'active' => request()->routeIs('admin.businesses.knowledge-base'),
                ],
                [
                    'route'  => 'admin.businesses.analytics',
                    'href'   => route('admin.businesses.analytics'),
                    'label'  => 'Reporting & Analytics',
                    'icon'   => 'M12 3C7.03 3 3 6.58 3 11c0 2.47 1.22 4.7 3.22 6.29L5 21l3.9-1.96c.97.25 2 .38 3.1.38 4.97 0 9-3.58 9-8s-4.03-8-9-8zm-3 9H7v-2h2v2zm4 0h-2v-2h2v2zm4 0h-2v-2h2v2z',
                    'active' => request()->routeIs('admin.businesses.analytics'),
                ],
                [
                    'route'  => 'admin.businesses.seo',
                    'href'   => route('admin.businesses.seo'),
                    'label'  => 'SEO & Profile',
                    'icon'   => 'M19.14 12.94a7.14 7.14 0 0 0 .05-.94 7.14 7.14 0 0 0-.05-.94l2.03-1.58a.5.5 0 0 0 .12-.63l-1.92-3.32a.5.5 0 0 0-.6-.22l-2.39.96a7.08 7.08 0 0 0-1.62-.94l-.36-2.54A.5.5 0 0 0 14 2h-4a.5.5 0 0 0-.49.42L9.15 4.96c-.57.23-1.11.53-1.62.88l-2.39-.96a.5.5 0 0 0-.6.22L2.62 8.42a.5.5 0 0 0 .12.63l2.03 1.58c-.04.31-.05.62-.05.94s.01.63.05.94L2.74 14.1a.5.5 0 0 0-.12.63l1.92 3.32a.5.5 0 0 0 .6.22l2.39-.96c.51.35 1.05.65 1.62.88l.36 2.54A.5.5 0 0 0 10 22h4a.5.5 0 0 0 .49-.42l.36-2.54c.57-.23 1.11-.53 1.62-.88l2.39.96a.5.5 0 0 0 .6-.22l1.92-3.32a.5.5 0 0 0-.12-.63l-2.02-1.57zM12 15.5A3.5 3.5 0 1 1 12 8a3.5 3.5 0 0 1 0 7.5z',
                    'active' => request()->routeIs('admin.businesses.seo'),
                ],
                [
                    'route'  => 'admin.businesses.landing-editor-index',
                    'href'   => route('admin.businesses.landing-editor-index'),
                    'label'  => 'Landing Page Editor',
                    'icon'   => 'M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zm18-10.5a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z',
                    'active' => request()->routeIs('admin.businesses.landing-editor*'),
                ],
                [
                    'route'  => 'admin.businesses.chat',
                    'href'   => route('admin.businesses.chat'),
                    'label'  => 'Chat & Handoff',
                    'icon'   => 'M4 4h16v12H7l-3 3V4zm4 5h8v2H8V9zm0 4h6v2H8v-2z',
                    'active' => request()->routeIs('admin.businesses.chat'),
                ],
                [
                    'route'  => 'admin.businesses.staff',
                    'href'   => route('admin.businesses.staff'),
                    'label'  => 'Staff & Roles',
                    'icon'   => 'M12 12a4 4 0 1 0-4-4 4 4 0 0 0 4 4zm0 2c-4.4 0-8 2.2-8 5v1h16v-1c0-2.8-3.6-5-8-5z',
                    'active' => request()->routeIs('admin.businesses.staff'),
                ],
                [
                    'route'  => 'admin.businesses.logs',
                    'href'   => route('admin.businesses.logs'),
                    'label'  => 'Logs',
                    'icon'   => 'M4 6h16v2H4zm0 5h16v2H4zm0 5h16v2H4z',
                    'active' => request()->routeIs('admin.businesses.logs'),
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

    <div class="mt-auto border-t border-gray-200 p-4">
        <button class="btn w-full justify-start rounded-2xl border-0 bg-[#f5f3ff] text-gray-800 hover:bg-[#f5f3ff]">
            <svg viewBox="0 0 24 24" class="h-5 w-5 fill-current" aria-hidden="true">
                <path d="M10 17l1.4-1.4L8.8 13H20v-2H8.8l2.6-2.6L10 7l-5 5 5 5zM4 4h7v2H6v12h5v2H4V4z"/>
            </svg>
            Logout
        </button>
    </div>
</aside>
