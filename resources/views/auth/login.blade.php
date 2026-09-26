<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In — Project RED AI</title>
    <meta name="description" content="Sign in to Project RED AI — your enterprise-grade centralized technical support intelligence platform.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    animation: {
                        'blob': 'blob 12s ease-in-out infinite',
                        'fade-up': 'fadeUp 0.45s ease-out both',
                    },
                    keyframes: {
                        blob: {
                            '0%, 100%': { transform: 'translate(0px, 0px) scale(1)' },
                            '33%':       { transform: 'translate(28px, -36px) scale(1.08)' },
                            '66%':       { transform: 'translate(-18px, 18px) scale(0.94)' },
                        },
                        fadeUp: {
                            '0%':   { opacity: '0', transform: 'translateY(14px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                    }
                },
            },
        }
    </script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css" rel="stylesheet">

    <style>
        html, body { height: 100%; }

        /* Staggered entrance animations */
        .form-card > * { animation: fadeUp 0.4s ease-out both; }
        .form-card > *:nth-child(1) { animation-delay: 0.05s; }
        .form-card > *:nth-child(2) { animation-delay: 0.10s; }
        .form-card > *:nth-child(3) { animation-delay: 0.15s; }
        .form-card > *:nth-child(4) { animation-delay: 0.20s; }
        .form-card > *:nth-child(5) { animation-delay: 0.25s; }
        .form-card > *:nth-child(6) { animation-delay: 0.30s; }
        .form-card > *:nth-child(7) { animation-delay: 0.35s; }

        /* Reduced motion override */
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
</head>

<body class="bg-slate-50 font-sans text-slate-900 antialiased h-full flex overflow-hidden relative selection:bg-slate-900 selection:text-white">

    {{-- ── Ambient Background Effects (behind everything) ────────────────────── --}}
    <div class="fixed inset-0 pointer-events-none overflow-hidden z-0" aria-hidden="true">
        <div class="absolute -top-48 -left-48 w-[30rem] h-[30rem] bg-violet-400/20 rounded-full blur-3xl animate-blob"></div>
        <div class="absolute top-1/4 -right-24 w-[36rem] h-[36rem] bg-indigo-400/15 rounded-full blur-3xl animate-blob [animation-delay:2.5s]"></div>
        <div class="absolute -bottom-40 left-1/4 w-[32rem] h-[32rem] bg-sky-400/15 rounded-full blur-3xl animate-blob [animation-delay:5s]"></div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════════════════
         LEFT PANEL — Login Form
    ══════════════════════════════════════════════════════════════════════════ --}}
    <div class="relative z-10 w-full lg:w-[46%] xl:w-[42%] flex flex-col h-full px-6 sm:px-10 xl:px-14 py-6">

        {{-- Form Card (glass) --}}
        <div class="form-card w-full max-w-[420px] mx-auto flex flex-col justify-between flex-1
                    rounded-3xl bg-white/85 backdrop-blur-2xl
                    border border-white/70 shadow-2xl shadow-slate-300/40
                    ring-1 ring-slate-900/5 px-8 py-8 sm:px-10">

            {{-- ── TOP: Brand Mark + Heading ──────────────────────────────── --}}
            <div class="space-y-5">

                {{-- Brand Mark (no status pill) --}}
                <div class="flex items-center gap-3">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl
                                bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900
                                text-white text-sm font-black shadow-md shadow-slate-900/15
                                ring-1 ring-inset ring-white/10 select-none">
                        R
                    </div>
                    <div class="flex flex-col leading-none">
                        <span class="text-sm font-bold text-slate-900 tracking-tight">Project RED</span>
                        <span class="text-[10px] font-semibold tracking-widest text-indigo-600 uppercase mt-0.5">Enterprise AI</span>
                    </div>
                </div>

                {{-- Heading --}}
                <div class="space-y-1">
                    <h1 class="text-2xl font-black tracking-tight text-slate-900 leading-tight">
                        Welcome back
                    </h1>
                    <p class="text-sm text-slate-500 leading-relaxed">
                        Sign in to continue to your workspace.
                    </p>
                </div>

            </div>

            {{-- ── MIDDLE: Form (grows to fill remaining space, centered) ───── --}}
            <div class="flex flex-col justify-center flex-1 space-y-4 py-4">

                {{-- Validation errors --}}
                @if ($errors->any())
                    <div role="alert"
                         class="flex items-start gap-3 rounded-2xl border border-rose-200 bg-rose-50/90 px-4 py-3 text-sm text-rose-700 shadow-sm">
                        <svg class="h-4 w-4 shrink-0 mt-0.5 stroke-rose-500" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form action="{{ route('login.attempt') }}" method="POST" class="space-y-4" novalidate>
                    @csrf

                    {{-- Email --}}
                    <div class="space-y-1.5">
                        <label for="email" class="block text-xs font-semibold text-slate-700 tracking-wide">
                            Email address
                        </label>
                        <div class="relative">
                            <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <input
                                id="email"
                                name="email"
                                type="email"
                                value="{{ old('email') }}"
                                autocomplete="email"
                                placeholder="you@company.com"
                                aria-required="true"
                                class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200
                                       bg-slate-50/80 text-sm text-slate-900 placeholder-slate-400
                                       focus:bg-white focus:border-indigo-500
                                       focus:ring-4 focus:ring-indigo-500/10 focus:outline-none
                                       transition-all duration-200 cursor-text">
                        </div>
                        @error('email')
                            <p class="text-xs font-medium text-rose-600 mt-1 flex items-center gap-1">
                                <svg class="h-3 w-3 shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="space-y-1.5">
                        <label for="password" class="block text-xs font-semibold text-slate-700 tracking-wide">
                            Password
                        </label>
                        <div class="relative">
                            <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            <input
                                id="password"
                                name="password"
                                type="password"
                                autocomplete="current-password"
                                placeholder="••••••••"
                                aria-required="true"
                                class="w-full pl-10 pr-10 py-2.5 rounded-xl border border-slate-200
                                       bg-slate-50/80 text-sm text-slate-900 placeholder-slate-400
                                       focus:bg-white focus:border-indigo-500
                                       focus:ring-4 focus:ring-indigo-500/10 focus:outline-none
                                       transition-all duration-200 cursor-text">
                            <button type="button" id="toggle-password"
                                    onclick="togglePassword()"
                                    aria-label="Show or hide password"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors cursor-pointer">
                                <svg id="eye-icon" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-xs font-medium text-rose-600 mt-1 flex items-center gap-1">
                                <svg class="h-3 w-3 shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Keep me signed in + Forgot password --}}
                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 cursor-pointer select-none group" for="remember">
                            <input
                                type="checkbox"
                                id="remember"
                                name="remember"
                                value="1"
                                @checked(old('remember'))
                                class="h-3.5 w-3.5 rounded border-slate-300 text-indigo-600
                                       focus:ring-2 focus:ring-indigo-500/30 focus:ring-offset-0
                                       checked:bg-indigo-600 checked:border-indigo-600
                                       transition-colors duration-150 cursor-pointer">
                            <span class="text-xs font-medium text-slate-600 group-hover:text-slate-900 transition-colors">
                                Keep me signed in
                            </span>
                        </label>
                        <a href="#"
                           class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 hover:underline underline-offset-2 transition-colors cursor-pointer">
                            Forgot password?
                        </a>
                    </div>

                    {{-- Submit --}}
                    <button
                        id="signin-btn"
                        type="submit"
                        class="relative w-full py-2.5 rounded-xl border-0 cursor-pointer
                               bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900
                               hover:from-indigo-900 hover:via-slate-900 hover:to-indigo-900
                               text-white font-bold text-sm tracking-wide
                               shadow-lg shadow-indigo-950/25 hover:shadow-indigo-900/35
                               transition-all duration-200 active:scale-[0.975]
                               flex items-center justify-center gap-2
                               focus:outline-none focus:ring-4 focus:ring-indigo-500/30"
                        aria-label="Sign in to Dashboard">
                        <span id="signin-label">Sign In</span>
                        <svg id="signin-arrow" class="w-4 h-4 fill-none stroke-current" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                </form>

            </div>

            {{-- ── Registration link ─────────────────────────────────────────── --}}
            <p class="text-center text-xs text-slate-400">
                Don't have an account?
                <a href="#" class="font-semibold text-indigo-600 hover:text-indigo-800 hover:underline underline-offset-2 transition-colors cursor-pointer">
                    Contact your administrator
                </a>
            </p>

            {{-- ── Legal microcopy ───────────────────────────────────────────── --}}
            <p class="text-center text-[11px] text-slate-400 leading-relaxed -mt-2">
                By signing in you agree to our
                <a href="#" class="font-medium text-slate-500 underline underline-offset-2 hover:text-indigo-600 transition-colors">Terms</a>
                and
                <a href="#" class="font-medium text-slate-500 underline underline-offset-2 hover:text-indigo-600 transition-colors">Privacy Notice</a>.
            </p>

        </div>{{-- /form-card --}}
    </div>{{-- /left panel --}}

    {{-- ══════════════════════════════════════════════════════════════════════════
         RIGHT PANEL — Image (hidden on mobile)
    ══════════════════════════════════════════════════════════════════════════ --}}
    <div class="hidden lg:block flex-1 relative z-10 p-6 xl:p-8" aria-hidden="true">
        <div class="w-full h-full rounded-[2.5rem] overflow-hidden relative group
                    bg-slate-900 shadow-2xl ring-1 ring-slate-900/10 p-1">
            <div class="w-full h-full rounded-[2.3rem] overflow-hidden relative">

                {{-- Hero image --}}
                <img
                    src="{{ asset('images/Login_pic.jpg') }}"
                    alt=""
                    role="presentation"
                    class="w-full h-full object-cover object-center block
                           group-hover:scale-[1.03] transition-transform duration-700 ease-out">

                {{-- Ambient gradient overlay — deeper at bottom so text is readable --}}
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-950/30 to-transparent"></div>



                {{-- Text overlaid directly on the gradient — no card, no border --}}
                <div class="absolute bottom-8 left-8 right-8">
                    <h2 class="text-xl font-bold text-white tracking-tight leading-snug">
                        Technical Support Intelligence
                    </h2>
                    <p class="text-sm text-slate-300 mt-2 leading-relaxed">
                        Centralized knowledge synthesis, fast query response, and automated resolution workflows.
                    </p>
                </div>

            </div>
        </div>
    </div>{{-- /right panel --}}

    <script>
        /* Password show/hide toggle */
        function togglePassword() {
            const input = document.getElementById('password');
            const icon  = document.getElementById('eye-icon');
            const isHidden = input.type === 'password';

            input.type = isHidden ? 'text' : 'password';

            icon.innerHTML = isHidden
                ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>'
                : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
        }

        /* Submit loading state */
        document.querySelector('form').addEventListener('submit', function() {
            const btn   = document.getElementById('signin-btn');
            const label = document.getElementById('signin-label');
            const arrow = document.getElementById('signin-arrow');

            btn.disabled = true;
            btn.classList.add('opacity-80', 'cursor-not-allowed');
            arrow.style.display = 'none';
            label.innerHTML = '<svg class="h-4 w-4 animate-spin mr-2 inline" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Signing in…';
        });
    </script>

</body>
</html>
