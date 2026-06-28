{{-- filepath: resources/views/partials/manager-sidebar.blade.php --}}
{{-- Reusable manager sidebar partial — requires $slug, $unitName, $unitType, $unitIcon, $sidebarExtras --}}
<aside class="hidden w-72 shrink-0 flex-col border-r border-gray-200 bg-base-100 md:flex">

    <div class="border-b border-gray-200 px-6 py-5">
        <a href="{{ route('admin.businesses.manager-dashboard', $slug) }}" class="flex items-center gap-3">
            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[#1e293b] text-xl text-white shadow-sm">
                {{ $unitIcon }}
            </div>
            <div>
                <div class="text-base font-extrabold tracking-tight text-gray-900">{{ $unitName }}</div>
                <div class="text-xs text-gray-500">Manager Portal</div>
            </div>
        </a>
        <div class="mt-4 rounded-2xl border border-[#e2e8f0] bg-[#ffffff] px-4 py-3">
            <div class="text-xs font-semibold uppercase tracking-wider text-gray-400">Your assigned unit</div>
            <div class="mt-1 text-sm font-bold text-gray-900">{{ $unitName }}</div>
            <div class="mt-1 flex items-center gap-2 text-xs text-gray-500">
                <span class="inline-flex h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                {{ $unitType }} · Live
            </div>
        </div>
    </div>

    <nav class="flex-1 px-4 py-5">
        @php
            $mgrNav = [
                ['label' => 'Overview',            'href' => route('admin.businesses.manager-dashboard',   $slug), 'route' => 'admin.businesses.manager-dashboard',   'icon' => 'M4 13h7V4H4v9zm0 7h7v-5H4v5zm9 0h7V11h-7v9zm0-16v5h7V4h-7z'],
                ['label' => 'Knowledge Base',       'href' => route('admin.businesses.manager-knowledge-base', $slug), 'route' => 'admin.businesses.manager-knowledge-base', 'icon' => 'M12 2a7 7 0 0 0-7 7v13h14V9a7 7 0 0 0-7-7zm-2 8h4v2h-4v-2zm0 4h4v2h-4v-2z'],
                ['label' => 'Live Chat & Handoff', 'href' => route('admin.businesses.manager-chat',        $slug), 'route' => 'admin.businesses.manager-chat',        'icon' => 'M4 4h16v12H7l-3 3V4zm4 5h8v2H8V9zm0 4h6v2H8v-2z'],
                ['label' => 'Analytics',           'href' => route('admin.businesses.manager-analytics',   $slug), 'route' => 'admin.businesses.manager-analytics',   'icon' => 'M12 3C7.03 3 3 6.58 3 11c0 2.47 1.22 4.7 3.22 6.29L5 21l3.9-1.96c.97.25 2 .38 3.1.38 4.97 0 9-3.58 9-8s-4.03-8-9-8zm-3 9H7v-2h2v2zm4 0h-2v-2h2v2zm4 0h-2v-2h2v2z'],
                ['label' => 'Edit Business Profile','href' => route('admin.businesses.edit',               $slug), 'route' => 'admin.businesses.edit',               'icon' => 'M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zm18-10.5a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z'],
                ['label' => 'Staff & Roles',       'href' => route('admin.businesses.manager-staff',       $slug), 'route' => 'admin.businesses.manager-staff',       'icon' => 'M12 12a4 4 0 1 0-4-4 4 4 0 0 0 4 4zm0 2c-4.4 0-8 2.2-8 5v1h16v-1c0-2.8-3.6-5-8-5z'],
            ];
        @endphp

        <div class="mb-2 px-3 text-[11px] font-semibold uppercase tracking-[0.2em] text-gray-400">{{ $unitName }} Workspace</div>
        <ul class="space-y-1">
            @foreach ($mgrNav as $item)
                <li>
                    <a href="{{ $item['href'] }}"
                       class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium transition-colors
                              {{ request()->routeIs($item['route'])
                                  ? 'bg-[#1e293b] font-semibold text-white shadow-sm'
                                  : 'text-gray-700 hover:bg-[#f5f3ff] hover:text-[#1e293b]' }}">
                        <svg viewBox="0 0 24 24" class="h-5 w-5 shrink-0 fill-current" aria-hidden="true">
                            <path d="{{ $item['icon'] }}"/>
                        </svg>
                        {{ $item['label'] }}
                    </a>
                </li>
            @endforeach
        </ul>

        <div class="my-4 border-t border-gray-100"></div>
        <div class="mb-2 px-3 text-[11px] font-semibold uppercase tracking-[0.2em] text-gray-400">{{ $unitType }} Tools</div>
        <ul class="space-y-1">
            @foreach ($sidebarExtras as $extra)
                <li>
                    <button type="button" class="flex w-full items-center gap-3 rounded-2xl px-4 py-2.5 text-sm text-gray-600 transition hover:bg-[#f5f3ff] hover:text-[#1e293b]">
                        <span class="h-1.5 w-1.5 rounded-full bg-[#1e293b] opacity-50"></span>
                        {{ $extra }}
                    </button>
                </li>
            @endforeach
        </ul>
    </nav>

    <div class="mt-auto border-t border-gray-200 p-4 space-y-2">
        <div class="rounded-2xl bg-[#ffffff] px-4 py-2.5 text-xs text-gray-500">
            Scoped to <span class="font-semibold text-gray-800">{{ $unitName }}</span> only.
        </div>
        <button class="btn w-full justify-start rounded-2xl border-0 bg-[#f5f3ff] text-gray-800 hover:bg-[#f5f3ff]">
            <svg viewBox="0 0 24 24" class="h-5 w-5 fill-current"><path d="M10 17l1.4-1.4L8.8 13H20v-2H8.8l2.6-2.6L10 7l-5 5 5 5zM4 4h7v2H6v12h5v2H4V4z"/></svg>
            Logout
        </button>
    </div>
</aside>
