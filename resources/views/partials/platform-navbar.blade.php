{{-- filepath: resources/views/partials/platform-navbar.blade.php --}}
<nav class="sticky top-0 z-20 w-full border-b border-gray-200 bg-[#f8fafc]/95 backdrop-blur">
    <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
        <div class="flex min-w-0 items-center">
            <a href="#home" class="flex items-center gap-3">
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-brand-primary text-white shadow-sm">
                    <svg viewBox="0 0 24 24" class="h-6 w-6 fill-current" aria-hidden="true">
                        <path d="M12 2l8 4v12l-8 4-8-4V6l8-4zm0 2.3L6 7.1v9.8l6 3 6-3V7.1l-6-2.8z" />
                    </svg>
                </div>
                <div class="leading-tight">
                    <div class="text-base font-extrabold tracking-tight text-gray-900">Project RED AI</div>
                    <div class="max-w-[180px] truncate text-xs font-medium text-gray-500">AI Business Support SaaS</div>
                </div>
            </a>
        </div>

        <div class="hidden lg:flex">
            <ul class="menu menu-horizontal gap-1 px-1 text-sm font-medium text-gray-700">
                <li><a href="#home" class="rounded-full px-4 py-2 transition-colors hover:bg-[#f5f3ff] hover:text-brand-primary">Home</a></li>
                <li><a href="#feature-tour" class="rounded-full px-4 py-2 transition-colors hover:bg-[#f5f3ff] hover:text-brand-primary">Feature Tour</a></li>
                <li><a href="#pricing" class="rounded-full px-4 py-2 transition-colors hover:bg-[#f5f3ff] hover:text-brand-primary">Pricing</a></li>
                <li><button type="button" onclick="signup_modal.showModal()" class="rounded-full px-4 py-2 transition-colors hover:bg-[#f5f3ff] hover:text-brand-primary">Sign Up</button></li>
            </ul>
        </div>

        <div class="flex items-center gap-2">
            <button type="button" onclick="signup_modal.showModal()" class="btn btn-sm rounded-full border-0 bg-brand-primary px-5 text-white shadow-sm transition-all duration-300 hover:bg-[#6d28d9]">
                Sign Up
            </button>

            <div class="dropdown dropdown-end lg:hidden">
                <label tabindex="0" class="btn btn-ghost btn-sm">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </label>
                <ul tabindex="0" class="dropdown-content menu menu-sm z-[1] mt-3 w-52 rounded-2xl border border-[#e2e8f0] bg-[#f8fafc] p-2 text-sm font-medium text-gray-700 shadow-lg">
                    <li><a href="#home">Home</a></li>
                    <li><a href="#feature-tour">Feature Tour</a></li>
                    <li><a href="#pricing">Pricing</a></li>
                    <li><button type="button" onclick="signup_modal.showModal()">Sign Up</button></li>
                </ul>
            </div>
        </div>
    </div>
</nav>
