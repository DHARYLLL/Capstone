<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Project RED AI</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: { brand: '#1e293b' },
                },
            },
        }
    </script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css" rel="stylesheet">

    <style>
        html, body { height: 100%; }
    </style>
</head>

<body class="bg-white font-sans text-gray-900 antialiased h-full flex flex-col overflow-hidden">

    {{-- ── Top Nav ──────────────────────────────────────────────────────────── --}}
    <header class="shrink-0 w-full px-8 py-4 flex items-center justify-between border-b border-[#e2e8f0]">
        <div class="flex items-center gap-2.5">
            <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-[#1e293b] text-white text-xs font-black select-none">
                R
            </div>
            <span class="text-sm font-bold text-gray-900 tracking-tight">Project RED AI</span>
        </div>
        <div class="w-32 hidden md:block"></div>
    </header>

    {{-- ── Main ─────────────────────────────────────────────────────────────── --}}
    <main class="flex-1 flex overflow-hidden">

        {{-- ── Left: Image panel — large rounded card with padding ────────── --}}
        <div class="hidden lg:flex w-1/2 items-center justify-center p-8 xl:p-12">
            <div class="w-full h-full rounded-[2.5rem] overflow-hidden shadow-2xl ring-1 ring-black/[0.07]">
                <img
                    src="{{ asset('images/Login_pic.jpg') }}"
                    alt="Project RED AI platform preview"
                    class="w-full h-full object-cover object-center block">
            </div>
        </div>

        {{-- ── Vertical divider ─────────────────────────────────────────────── --}}
        <div class="hidden lg:flex items-center">
            <div class="h-3/4 w-px bg-[#e2e8f0]"></div>
        </div>

        {{-- ── Right: Login Form ────────────────────────────────────────────── --}}
        <div class="w-full lg:w-1/2 flex items-center justify-center px-8 sm:px-12 xl:px-20">
            <div class="w-full max-w-sm flex flex-col gap-4">

                {{-- Heading --}}
                <div class="space-y-1">
                    <h1 class="text-2xl font-black tracking-tight text-gray-900 leading-snug">
                        Welcome to<br>Project RED
                    </h1>
                    <p class="text-sm text-gray-500">
                        Your centralized AI assistant for enterprise support.
                    </p>
                </div>

                {{-- Quick test presets --}}
                <div class="rounded-[1.25rem] border border-[#e2e8f0] bg-[#f8fafc] p-3 space-y-2">
                    <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 text-center">Quick Test Presets</p>
                    <div class="grid grid-cols-2 gap-2">
                        <button type="button"
                            onclick="fillCredentials('admin@dariv.com','password')"
                            class="btn btn-sm h-8 rounded-full border border-[#e2e8f0] bg-white text-gray-700 font-semibold shadow-sm hover:bg-[#f8fafc] hover:border-gray-300 transition-all text-xs">
                            🔑 Admin Login
                        </button>
                        <button type="button"
                            onclick="fillCredentials('mae.s@dariv.com','password')"
                            class="btn btn-sm h-8 rounded-full border border-[#e2e8f0] bg-white text-gray-700 font-semibold shadow-sm hover:bg-[#f8fafc] hover:border-gray-300 transition-all text-xs">
                            💬 Operator Login
                        </button>
                    </div>
                </div>

                {{-- Divider --}}
                <div class="flex items-center gap-3">
                    <div class="flex-1 h-px bg-[#e2e8f0]"></div>
                    <span class="text-xs text-gray-400 whitespace-nowrap">or login with credentials</span>
                    <div class="flex-1 h-px bg-[#e2e8f0]"></div>
                </div>

                {{-- Validation errors --}}
                @if ($errors->any())
                    <div class="flex items-start gap-2.5 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                        <svg class="h-4 w-4 shrink-0 mt-0.5 stroke-rose-500" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                {{-- Form --}}
                <form action="{{ route('login.attempt') }}" method="POST" class="space-y-3">
                    @csrf

                    <div class="space-y-1">
                        <label for="email" class="block text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Email address
                        </label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email') }}"
                            autocomplete="email"
                            placeholder="you@company.com"
                            class="w-full rounded-2xl border border-[#e2e8f0] bg-[#f8fafc] px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:bg-white focus:border-gray-400 focus:outline-none transition-colors">
                        @error('email')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1">
                        <label for="password" class="block text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Password
                        </label>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            autocomplete="current-password"
                            placeholder="••••••••"
                            class="w-full rounded-2xl border border-[#e2e8f0] bg-[#f8fafc] px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:bg-white focus:border-gray-400 focus:outline-none transition-colors">
                        @error('password')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Remember + forgot --}}
                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" name="remember" value="1"
                                class="checkbox checkbox-xs rounded"
                                @checked(old('remember'))>
                            <span class="text-xs text-gray-500">Remember me</span>
                        </label>
                        <a href="#" class="text-xs font-medium text-gray-400 hover:text-gray-700 transition-colors">
                            Forgot password?
                        </a>
                    </div>

                    <button type="submit"
                        class="btn w-full h-auto py-2.5 rounded-2xl border-0 bg-[#1e293b] hover:bg-[#0f172a] text-white font-bold text-sm tracking-wide transition-all active:scale-[0.98]">
                        Continue
                    </button>
                </form>

                {{-- Microcopy --}}
                <p class="text-center text-[11px] text-gray-400 leading-relaxed">
                    By signing in, you agree to our
                    <a href="#" class="underline underline-offset-2 hover:text-gray-600 transition-colors">Terms of Use</a>,
                    <a href="#" class="underline underline-offset-2 hover:text-gray-600 transition-colors">Privacy Notice</a>,
                    and <a href="#" class="underline underline-offset-2 hover:text-gray-600 transition-colors">Cookie Notice</a>.
                </p>

            </div>
        </div>{{-- /right form --}}

    </main>

    {{-- ── Footer ───────────────────────────────────────────────────────────── --}}
    <footer class="shrink-0 w-full border-t border-[#e2e8f0] bg-[#1e293b] px-8 py-4 flex items-center justify-between">
        <div class="flex items-center gap-2.5 text-xs font-semibold text-white/80">
            <div class="flex h-5 w-5 items-center justify-center rounded-md bg-white/10 text-[9px] font-black text-white select-none">R</div>
            Project RED AI Platform
            <span class="h-1.5 w-1.5 rounded-full bg-emerald-400 animate-pulse ml-1"></span>
        </div>
        <p class="text-xs text-white/40 font-medium">Curated for Enterprise Technical Support</p>
    </footer>

    <script>
        function fillCredentials(email, password) {
            document.getElementById('email').value    = email;
            document.getElementById('password').value = password;
        }
    </script>
</body>
</html>
