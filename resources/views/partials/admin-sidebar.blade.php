{{-- filepath: resources/views/partials/admin-sidebar.blade.php --}}
@php
    $tenantName = 'DARIV Waterproofing';
    $tenantType = 'Residential & Commercial';

    $userName = session('user_name', 'Guest Operator');
    $userEmail = session('user_email', 'operator@dariv.com');
    $userRole = session('user_role', 'Lead Operator');
    $userInitials = strtoupper(substr($userName, 0, 2));
@endphp

<aside class="sticky top-0 hidden h-screen w-72 shrink-0 flex-col overflow-hidden border-r border-slate-200/80 bg-white md:flex">
    
    <!-- Top Brand & Tenant Section -->
    <div class="border-b border-slate-100 p-5">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 group">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-tr from-violet-600 to-indigo-600 text-white shadow-xs transition group-hover:scale-105">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                </svg>
            </div>
            <div>
                <div class="text-sm font-black tracking-tight text-slate-900 leading-tight">RED AI</div>
                <div class="text-[10px] font-bold text-violet-600 uppercase tracking-wider">Tenant Console</div>
            </div>
        </a>

        <div class="mt-4 rounded-xl border border-slate-200/70 bg-slate-50/60 p-3 transition-all duration-200 hover:border-violet-300 hover:bg-violet-50/30 hover:shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Current Workspace</span>
                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-1.5 py-0.5 text-[9px] font-bold text-emerald-700">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    Live
                </span>
            </div>
            <div class="mt-1 text-xs font-bold text-slate-900 truncate">{{ $tenantName }}</div>
            <div class="mt-0.5 text-[11px] text-slate-400 truncate">{{ $tenantType }}</div>
        </div>
    </div>

    <!-- Navigation Items -->
    <nav class="min-h-0 flex-1 overflow-y-auto px-4 py-4 space-y-5">
        @php
            $navSections = [
                [
                    'title' => 'Core Operations',
                    'items' => [
                        [
                            'route'      => 'admin.dashboard',
                            'href'       => route('admin.dashboard'),
                            'label'      => 'Overview',
                            'icon'       => 'overview',
                            'active'     => request()->routeIs('admin.dashboard'),
                            'admin_only' => false,
                        ],
                        [
                            'route'      => 'admin.chat',
                            'href'       => route('admin.chat'),
                            'label'      => 'Live Chat & Queue',
                            'icon'       => 'chat',
                            'active'     => request()->routeIs('admin.chat'),
                            'admin_only' => false,
                            'hasBadge'   => true,
                        ],
                        [
                            'route'      => 'chat.demo',
                            'href'       => route('chat.demo'),
                            'label'      => 'Widget Playground',
                            'icon'       => 'widget',
                            'active'     => request()->routeIs('chat.demo'),
                            'admin_only' => false,
                        ],
                    ]
                ],
                [
                    'title' => 'Intelligence & Data',
                    'items' => [
                        [
                            'route'      => 'admin.knowledge-base',
                            'href'       => route('admin.knowledge-base'),
                            'label'      => 'Knowledge Base',
                            'icon'       => 'kb',
                            'active'     => request()->routeIs('admin.knowledge-base'),
                            'admin_only' => true,
                        ],
                        [
                            'route'      => 'admin.analytics',
                            'href'       => route('admin.analytics'),
                            'label'      => 'Reporting & Analytics',
                            'icon'       => 'analytics',
                            'active'     => request()->routeIs('admin.analytics'),
                            'admin_only' => true,
                        ],
                        [
                            'route'      => 'admin.logs',
                            'href'       => route('admin.logs'),
                            'label'      => 'Ingestion Logs',
                            'icon'       => 'logs',
                            'active'     => request()->routeIs('admin.logs'),
                            'admin_only' => true,
                        ],
                    ]
                ],
                [
                    'title' => 'Management',
                    'items' => [
                        [
                            'route'      => 'admin.staff',
                            'href'       => route('admin.staff'),
                            'label'      => 'Staff & Roles',
                            'icon'       => 'staff',
                            'active'     => request()->routeIs('admin.staff'),
                            'admin_only' => true,
                        ],
                    ]
                ]
            ];
        @endphp

        @foreach ($navSections as $section)
            <div>
                <div class="px-2 mb-2 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                    {{ $section['title'] }}
                </div>
                <ul class="space-y-1">
                    @foreach ($section['items'] as $item)
                        @if (!$item['admin_only'] || $userRole === 'Administrator')
                            <li>
                                <a href="{{ $item['href'] }}"
                                   class="group flex items-center justify-between rounded-xl px-3 py-2.5 text-xs font-semibold transition-all duration-200
                                          {{ $item['active']
                                              ? 'bg-violet-600 text-white shadow-xs font-bold hover:bg-violet-700'
                                              : 'text-slate-600 hover:bg-violet-50/90 hover:text-violet-700 hover:translate-x-1 hover:shadow-2xs active:translate-x-0' }}">
                                    <div class="flex items-center gap-3">
                                        @if ($item['icon'] === 'overview')
                                            <svg class="h-4 w-4 shrink-0 transition-transform duration-200 {{ $item['active'] ? 'text-white' : 'text-slate-400 group-hover:scale-110 group-hover:text-violet-600' }}" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                                            </svg>
                                        @elseif ($item['icon'] === 'chat')
                                            <svg class="h-4 w-4 shrink-0 transition-transform duration-200 {{ $item['active'] ? 'text-white' : 'text-slate-400 group-hover:scale-110 group-hover:text-violet-600' }}" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                                            </svg>
                                        @elseif ($item['icon'] === 'widget')
                                            <svg class="h-4 w-4 shrink-0 transition-transform duration-200 {{ $item['active'] ? 'text-white' : 'text-slate-400 group-hover:scale-110 group-hover:text-violet-600' }}" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75 22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3-4.5 16.5" />
                                            </svg>
                                        @elseif ($item['icon'] === 'kb')
                                            <svg class="h-4 w-4 shrink-0 transition-transform duration-200 {{ $item['active'] ? 'text-white' : 'text-slate-400 group-hover:scale-110 group-hover:text-violet-600' }}" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                                            </svg>
                                        @elseif ($item['icon'] === 'analytics')
                                            <svg class="h-4 w-4 shrink-0 transition-transform duration-200 {{ $item['active'] ? 'text-white' : 'text-slate-400 group-hover:scale-110 group-hover:text-violet-600' }}" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                                            </svg>
                                        @elseif ($item['icon'] === 'logs')
                                            <svg class="h-4 w-4 shrink-0 transition-transform duration-200 {{ $item['active'] ? 'text-white' : 'text-slate-400 group-hover:scale-110 group-hover:text-violet-600' }}" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z" />
                                            </svg>
                                        @else
                                            <svg class="h-4 w-4 shrink-0 transition-transform duration-200 {{ $item['active'] ? 'text-white' : 'text-slate-400 group-hover:scale-110 group-hover:text-violet-600' }}" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                                            </svg>
                                        @endif
                                        <span>{{ $item['label'] }}</span>
                                    </div>

                                    @if (!empty($item['hasBadge']))
                                        <span id="pending-handoff-badge" class="hidden rounded-full bg-rose-500 px-1.5 py-0.5 text-[10px] font-black text-white transition-transform group-hover:scale-110">0</span>
                                    @endif
                                </a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        @endforeach
    </nav>

    <!-- Bottom Operator Status Card -->
    <div class="border-t border-slate-100 p-4">
        <div class="flex items-center gap-3 rounded-xl bg-slate-50 p-2.5 transition-all duration-200 hover:border-violet-300 hover:bg-violet-50/40 hover:shadow-xs">
            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-violet-100 text-violet-700 text-xs font-bold transition-transform group-hover:scale-105">
                {{ $userInitials }}
            </div>
            <div class="min-w-0 flex-1">
                <div class="text-xs font-bold text-slate-800 truncate">{{ $userName }}</div>
                <div class="text-[10px] text-slate-400 truncate">{{ $userRole }}</div>
            </div>
            <a href="{{ route('admin.settings') }}" class="text-slate-400 hover:text-violet-600 hover:scale-110 transition-all p-1" title="Settings">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.384.764.78.93.398.164.855.142 1.205-.108l.737-.527a1.125 1.125 0 0 1 1.45.12l.773.774c.39.389.44 1.002.12 1.45l-.527.737c-.25.35-.272.806-.107 1.204.165.397.505.71.93.78l.893.15c.543.09.94.559.94 1.109v1.094c0 .55-.397 1.02-.94 1.11l-.894.149c-.424.07-.764.383-.929.78-.165.398-.143.854.107 1.204l.527.738c.32.447.27.1.06-.12l-.773.773a1.125 1.125 0 0 1-1.449.12l-.738-.527c-.35-.25-.806-.272-1.203-.107-.398.165-.71.505-.781.929l-.149.894c-.09.542-.56.94-1.11.94h-1.094c-.55 0-1.019-.398-1.11-.94l-.148-.894c-.071-.424-.384-.764-.781-.93-.398-.164-.854-.142-1.204.108l-.738.527c-.447.32-1.06.27-1.45-.12l-.773-.774a1.125 1.125 0 0 1-.12-1.45l.527-.737c.25-.35.272-.806.108-1.204-.165-.397-.506-.71-.93-.78l-.894-.15c-.542-.09-.94-.56-.94-1.11v-1.093c0-.55.398-1.02.94-1.11l.894-.149c.424-.07.765-.383.93-.78.165-.398.143-.854-.108-1.204l-.526-.738a1.125 1.125 0 0 1 .12-1.45l.773-.773a1.125 1.125 0 0 1 1.45-.12l.737.527c.35.25.807.272 1.204.107.397-.165.71-.505.78-.929l.15-.894Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>
            </a>
        </div>
    </div>

</aside>

<script>
    (() => {
        const badge = document.getElementById('pending-handoff-badge');
        if (!badge) return;

        const pendingCountUrl = @json(route('admin.chat.pending-count'));

        async function refreshPendingHandoffBadge() {
            try {
                const response = await fetch(pendingCountUrl, {
                    cache: 'no-store',
                    headers: { 'Accept': 'application/json' },
                });

                if (!response.ok) return;

                const payload = await response.json();
                const count = Number(payload.count) || 0;
                badge.textContent = String(count);
                badge.classList.toggle('hidden', count === 0);
            } catch (error) {
                console.error('Pending handoff count refresh failed:', error);
            }
        }

        refreshPendingHandoffBadge();
        window.setInterval(refreshPendingHandoffBadge, 5000);
    })();
</script>

