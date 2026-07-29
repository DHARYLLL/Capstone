<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | DARIV Waterproofing Portal</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    },
                },
            },
        }
    </script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css" rel="stylesheet" type="text/css" />
</head>

<body
    class="bg-gradient-to-tr from-slate-900 via-indigo-950 to-slate-900 font-sans text-gray-100 antialiased min-h-screen flex items-center justify-center p-6 relative overflow-hidden">

    <!-- Decorative background blobs -->
    <div
        class="absolute -top-40 -left-40 h-[500px] w-[500px] rounded-full bg-violet-600/10 blur-[120px] pointer-events-none">
    </div>
    <div
        class="absolute -bottom-40 -right-40 h-[500px] w-[500px] rounded-full bg-sky-500/10 blur-[120px] pointer-events-none">
    </div>

    <div
        class="w-full max-w-md bg-slate-900/60 backdrop-blur-xl border border-slate-800/80 p-8 rounded-[2.5rem] shadow-2xl flex flex-col gap-6 relative z-10">

        <!-- Header / Logo -->
        <div class="text-center space-y-2">
            <div
                class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 p-3 shadow-lg shadow-indigo-500/20">
                <img src="{{ asset('images/logo.svg') }}" alt="DARIV Logo"
                    class="h-full w-full object-contain filter invert">
            </div>
            <h1 class="text-2xl font-black tracking-tight text-white mt-4 font-sans">DARIV Waterproofing</h1>
            <p class="text-xs text-indigo-300 font-semibold tracking-wider uppercase">Role-Based Console Login</p>
        </div>

        <!-- Role Quick-fill Selector -->
        <div class="bg-slate-950/80 p-4 rounded-2xl border border-slate-800/80 space-y-3">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 block text-center">Quick Test
                Presets (RBAC Demo)</span>
            <div class="grid grid-cols-2 gap-2">
                <button type="button" id="fill-admin"
                    class="btn btn-xs rounded-xl font-bold bg-indigo-600 hover:bg-indigo-700 text-white border-0 py-2.5 h-auto transition-all">
                    🛡️ Login as Admin
                </button>
                <button type="button" id="fill-operator"
                    class="btn btn-xs rounded-xl font-bold bg-purple-600 hover:bg-purple-700 text-white border-0 py-2.5 h-auto transition-all">
                    ☔ Login as Operator
                </button>
            </div>
        </div>

        <!-- Validation Errors -->
        @if ($errors->any())
            <div class="alert alert-error bg-rose-500/15 border border-rose-500/30 text-rose-200 rounded-2xl p-4 text-xs">
                <div class="flex items-start gap-2">
                    <svg class="h-4 w-4 shrink-0 stroke-rose-400 mt-0.5" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span>{{ $errors->first() }}</span>
                </div>
            </div>
        @endif

        <!-- Form -->
        <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="email" class="block text-xs font-bold text-slate-400 mb-1.5 uppercase tracking-wide">Login
                    Email</label>
                <input type="email" id="email" name="email" required placeholder="name@dariv.com"
                    class="input input-bordered w-full rounded-2xl border-slate-800 bg-slate-950/60 text-sm text-white focus:border-indigo-500 focus:outline-none placeholder-slate-600 focus:ring-1 focus:ring-indigo-500">
            </div>

            <div>
                <label for="password"
                    class="block text-xs font-bold text-slate-400 mb-1.5 uppercase tracking-wide">Password</label>
                <div class="relative">
                    <input type="password" id="password" name="password" required placeholder="••••••••"
                        class="input input-bordered w-full rounded-2xl border-slate-800 bg-slate-950/60 text-sm text-white focus:border-indigo-500 focus:outline-none placeholder-slate-600 focus:ring-1 focus:ring-indigo-500 pr-12">
                    <button type="button" id="toggle-pw"
                        class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 hover:text-white transition-colors"
                        aria-label="Toggle Password Visibility">
                        <svg id="eye-icon" viewBox="0 0 24 24" class="h-5 w-5 fill-current">
                            <path
                                d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z" />
                        </svg>
                    </button>
                </div>
            </div>

            <button type="submit"
                class="btn w-full rounded-2xl border-0 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-bold tracking-wide hover:shadow-lg hover:shadow-indigo-500/20 active:scale-[0.98] transition-all py-3.5 h-auto mt-2">
                Authenticate & Login
            </button>
        </form>

    </div>

    <!-- Script for Preset Autofill & Password Toggle -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            const togglePwBtn = document.getElementById('toggle-pw');
            const fillAdminBtn = document.getElementById('fill-admin');
            const fillOperatorBtn = document.getElementById('fill-operator');

            // Quick Preset Autofill
            fillAdminBtn.addEventListener('click', () => {
                emailInput.value = 'admin@dariv.com';
                passwordInput.value = 'password';
            });

            fillOperatorBtn.addEventListener('click', () => {
                emailInput.value = 'mae.s@dariv.com';
                passwordInput.value = 'password';
            });

            // Password Toggle Visibility
            togglePwBtn.addEventListener('click', () => {
                const isPassword = passwordInput.type === 'password';
                passwordInput.type = isPassword ? 'text' : 'password';
                togglePwBtn.classList.toggle('text-indigo-400', isPassword);
            });
        });
    </script>
</body>

</html>