@php
    $tenantCount = $tenantCount ?? 3;
    $platformStatus = $platformStatus ?? 'Healthy';
@endphp

<aside class="hidden w-80 shrink-0 flex-col border-r border-gray-200 bg-base-100 md:flex">
    <div class="border-b border-gray-200 px-6 py-6">
        <a href="{{ route('super-admin.dashboard') }}" class="flex items-center gap-3">
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-brand-primary text-white shadow-sm">
                <svg viewBox="0 0 24 24" class="h-6 w-6 fill-current" aria-hidden="true">
                    <path d="M12 2l8 4v12l-8 4-8-4V6l8-4zm0 2.3L6 7.1v9.8l6 3 6-3V7.1l-6-2.8z"/>
                </svg>
            </div>
            <div>
                <div class="text-lg font-extrabold tracking-tight text-gray-900">Project RED AI</div>
                <div class="text-xs text-gray-500">Super Admin Console</div>
            </div>
        </a>

        <div class="mt-5 rounded-2xl border border-[#e2e8f0] bg-[#ffffff] px-4 py-4">
            <div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-gray-400">Platform state</div>
            <div class="mt-2 flex items-center justify-between gap-4">
                <div>
                    <div class="text-sm font-bold text-gray-900">{{ $platformStatus }}</div>
                    <div class="text-xs text-gray-500">{{ $tenantCount }} active tenants</div>
                </div>
                <span class="inline-flex h-2.5 w-2.5 rounded-full bg-emerald-400"></span>
            </div>
        </div>
    </div>

    <nav class="flex-1 px-4 py-5">
        @php
            $mainNavItems = [
                [
                    'href'   => route('super-admin.dashboard'),
                    'label'  => 'Overview',
                    'icon'   => 'M4 13h7V4H4v9zm0 7h7v-5H4v5zm9 0h7V11h-7v9zm0-16v5h7V4h-7z',
                    'active' => request()->routeIs('super-admin.dashboard'),
                ],
                [
                    'href'   => route('super-admin.tenants'),
                    'label'  => 'Tenant Ledger',
                    'icon'   => 'M4 5h16v14H4V5zm2 2v10h12V7H6zm2 2h8v2H8V9zm0 4h6v2H8v-2z',
                    'active' => request()->routeIs('super-admin.tenants'),
                ],
                [
                    'href'   => route('super-admin.resources'),
                    'label'  => 'Resources',
                    'icon'   => 'M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm1 15h-2v-2h2zm0-4h-2V7h2z',
                    'active' => request()->routeIs('super-admin.resources'),
                ],
                [
                    'href'   => route('super-admin.audit-logs'),
                    'label'  => 'Audit & Health',
                    'icon'   => 'M12 2l8 4v12l-8 4-8-4V6l8-4zm0 2.3L6 7.1v9.8l6 3 6-3V7.1l-6-2.8z',
                    'active' => request()->routeIs('super-admin.audit-logs'),
                ],
            ];

            $actionNavItems = [
                ['href' => '#', 'label' => 'Provision Tenant', 'icon' => 'M19 11H13V5h-2v6H5v2h6v6h2v-6h6z'],
                ['href' => '#', 'label' => 'Manage Limits',    'icon' => 'M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zm18-10.5a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z'],
            ];
        @endphp

        <div class="mb-3 px-3 text-[11px] font-semibold uppercase tracking-[0.22em] text-gray-400">Global Control</div>
        <ul class="space-y-2 text-sm">
            @foreach ($mainNavItems as $item)
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

        <div class="mt-8 mb-3 px-3 text-[11px] font-semibold uppercase tracking-[0.22em] text-gray-400">System Actions</div>
        <ul class="space-y-2 text-sm">
            @foreach ($actionNavItems as $item)
                <li>
                    <a href="{{ $item['href'] }}"
                       class="flex items-center gap-3 rounded-2xl px-4 py-3 font-medium text-gray-700 transition-colors hover:bg-[#f5f3ff] hover:text-brand-primary">
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
        <button class="btn w-full justify-start rounded-2xl border-0 bg-[#f5f3ff] text-gray-800 hover:bg-[#f5f3ff]">Logout</button>
    </div>
</aside>