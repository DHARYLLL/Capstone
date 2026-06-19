<header class="sticky top-0 z-20 border-b border-gray-200 bg-[#F9F8F6]/95 backdrop-blur">
    <div class="flex items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
        <div>
            <div class="flex items-center gap-2 text-[11px] font-semibold uppercase tracking-[0.22em] text-gray-400">
                @yield('breadcrumbs', 'Super Admin / Overview')
            </div>
            <h1 class="mt-1 text-2xl font-black tracking-tight text-gray-800">@yield('page_title', 'Super Admin Console')</h1>
            <p class="mt-1 text-sm text-gray-500">@yield('page_description', 'Global control panel for platform operators.')
            </p>
        </div>

        <div class="flex items-center gap-3 rounded-2xl bg-base-100 px-4 py-3 shadow-sm">
            <div class="text-right leading-tight">
                <div class="font-bold text-gray-800">Startup Owner</div>
                <div class="text-xs text-gray-500">Platform Administrator</div>
            </div>
            <div class="avatar">
                <div class="h-11 w-11 rounded-full ring-2 ring-[#5A3E2B] ring-offset-2 ring-offset-base-100">
                    <img src="https://i.pravatar.cc/100?img=32" alt="Super admin avatar">
                </div>
            </div>
        </div>
    </div>
</header>