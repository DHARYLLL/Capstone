{{-- filepath: c:\Users\dhary\Desktop\Capstone\capstone1\resources\views\partials\navbar.blade.php --}}
<nav class="fixed top-0 z-40 w-full border-b border-[#E9E2D6] bg-[#FCFBF8]/90 backdrop-blur">
    <div class="navbar mx-auto max-w-7xl px-4 lg:px-8">
        <div class="navbar-start">
            <a href="#home" class="flex items-center gap-3">
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[#5A3E2B] text-white shadow-sm">
                    <svg viewBox="0 0 24 24" class="h-6 w-6 fill-current" aria-hidden="true">
                        <path d="M12 2l8 4v12l-8 4-8-4V6l8-4zm0 2.3L6 7.1v9.8l6 3 6-3V7.1l-6-2.8z"/>
                    </svg>
                </div>
                <div class="leading-tight">
                    <div class="text-base font-extrabold tracking-tight text-gray-900">Project RED AI</div>
                    <div class="text-xs font-medium text-gray-500">Centralized Business Support Platform</div>
                </div>
            </a>
        </div>

        <div class="navbar-center hidden lg:flex">
            <ul class="menu menu-horizontal gap-2 px-1 text-sm font-medium text-gray-700">
                <li><a href="#home" class="rounded-full hover:bg-[#F4EEDF]">Home</a></li>
                <li><a href="#businesses" class="rounded-full hover:bg-[#F4EEDF]">Our Businesses</a></li>
                <li><a href="#about" class="rounded-full hover:bg-[#F4EEDF]">About Us</a></li>
                <li><a href="#gallery" class="rounded-full hover:bg-[#F4EEDF]">Gallery</a></li>
            </ul>
        </div>

        <div class="navbar-end">
            <a href="#chatbot" class="btn btn-sm rounded-full border-0 bg-[#5A3E2B] px-5 text-white hover:bg-[#453020]">
                <svg viewBox="0 0 24 24" class="h-4 w-4 fill-current" aria-hidden="true">
                    <path d="M4 4h16v11H7l-3 3V4zm3 4h10v2H7V8zm0 4h7v2H7v-2z"/>
                </svg>
                Chat with Assistant
            </a>
        </div>
    </div>
</nav>