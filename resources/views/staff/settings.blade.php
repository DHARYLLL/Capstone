{{-- filepath: resources/views/staff/settings.blade.php --}}
@extends('layouts.staff')

@section('page_title', 'Account Settings')
@section('breadcrumbs', 'Staff / Settings')

@section('content')
    @php
        $user = auth()->user();
        $userName = $user?->name ?? session('user_name', 'Guest Operator');
        $userEmail = $user?->email ?? session('user_email', 'operator@dariv.com');
        $userRole = session('user_role', 'Lead Operator');
        $userInitials = strtoupper(substr($userName, 0, 2));
    @endphp

    <div class="max-w-4xl mx-auto space-y-6">
        
        <!-- Top Title Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-200/80 pb-4">
            <div>
                <h1 class="text-xl font-black tracking-tight text-slate-900">Account Settings</h1>
                <p class="text-xs text-slate-500 mt-0.5">Manage your operator profile details and account security.</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-violet-50 px-3 py-1 text-xs font-bold text-violet-700 ring-1 ring-violet-200">
                    <span class="h-1.5 w-1.5 rounded-full bg-violet-600"></span>
                    {{ $userRole }}
                </span>
            </div>
        </div>

        <!-- Alerts -->
        @if (session('profile_success'))
            <div class="flex items-center gap-3 rounded-2xl bg-emerald-50 border border-emerald-200 p-4 text-xs font-semibold text-emerald-800 animate-in fade-in slide-in-from-top-2">
                <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                    </svg>
                </div>
                <span>{{ session('profile_success') }}</span>
            </div>
        @endif

        @if (session('password_success'))
            <div class="flex items-center gap-3 rounded-2xl bg-emerald-50 border border-emerald-200 p-4 text-xs font-semibold text-emerald-800 animate-in fade-in slide-in-from-top-2">
                <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                    </svg>
                </div>
                <span>{{ session('password_success') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="flex items-start gap-3 rounded-2xl bg-rose-50 border border-rose-200 p-4 text-xs font-semibold text-rose-800 animate-in fade-in slide-in-from-top-2">
                <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-xl bg-rose-100 text-rose-700 mt-0.5">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 8.25h.008v.008H12v-.008Z" />
                    </svg>
                </div>
                <div>
                    <div class="font-bold">Please correct the following errors:</div>
                    <ul class="list-disc list-inside mt-1 space-y-0.5 text-rose-700">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <!-- CARD 1: Profile Information -->
        <div class="rounded-3xl border border-slate-200/80 bg-white p-6 sm:p-8 shadow-xs">
            <div class="flex items-center gap-3 pb-5 border-b border-slate-100">
                <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-gradient-to-tr from-violet-600 to-indigo-600 text-white font-black text-sm shadow-xs">
                    {{ $userInitials }}
                </div>
                <div>
                    <h2 class="text-sm font-bold text-slate-900">Profile Details</h2>
                    <p class="text-xs text-slate-400">Update your operator display name and registered email address.</p>
                </div>
            </div>

            <form action="{{ route('staff.settings.profile.update') }}" method="POST" class="mt-6 space-y-5">
                @csrf
                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                            Full Name <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" required value="{{ old('name', $userName) }}" 
                            class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3.5 py-2.5 text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:border-violet-500 focus:outline-none transition shadow-2xs @error('name') border-rose-300 bg-rose-50/20 @enderror">
                        @error('name')
                            <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                            Email Address <span class="text-rose-500">*</span>
                        </label>
                        <input type="email" id="email" name="email" required value="{{ old('email', $userEmail) }}" 
                            class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3.5 py-2.5 text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:border-violet-500 focus:outline-none transition shadow-2xs @error('email') border-rose-300 bg-rose-50/20 @enderror">
                        @error('email')
                            <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-violet-600 px-5 py-2.5 text-xs font-bold text-white shadow-xs hover:bg-violet-700 transition">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                        <span>Save Profile Changes</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- CARD 2: Security & Change Password -->
        <div class="rounded-3xl border border-slate-200/80 bg-white p-6 sm:p-8 shadow-xs">
            <div class="flex items-center gap-3 pb-5 border-b border-slate-100">
                <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-amber-50 text-amber-600 ring-1 ring-amber-200 font-black text-sm">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-slate-900">Security & Password</h2>
                    <p class="text-xs text-slate-400">Ensure your operator account is protected with a secure password.</p>
                </div>
            </div>

            <form action="{{ route('staff.settings.password.update') }}" method="POST" class="mt-6 space-y-5">
                @csrf
                
                <!-- Current Password -->
                <div>
                    <label for="current_password" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                        Current Password <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="password" id="current_password" name="current_password" required placeholder="Enter current password" 
                            class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3.5 py-2.5 pr-10 text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:border-violet-500 focus:outline-none transition shadow-2xs @error('current_password') border-rose-300 bg-rose-50/20 @enderror">
                        <button type="button" onclick="togglePasswordVisibility('current_password', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition">
                            <svg class="h-4 w-4 eye-icon" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                        </button>
                    </div>
                    @error('current_password')
                        <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- New Password and Confirmation -->
                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                            New Password <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" id="password" name="password" required placeholder="Minimum 8 characters" oninput="evaluatePasswordStrength(this.value)"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3.5 py-2.5 pr-10 text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:border-violet-500 focus:outline-none transition shadow-2xs @error('password') border-rose-300 bg-rose-50/20 @enderror">
                            <button type="button" onclick="togglePasswordVisibility('password', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition">
                                <svg class="h-4 w-4 eye-icon" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                        @enderror

                        <!-- Live Strength Meter -->
                        <div class="mt-2.5 space-y-1.5">
                            <div class="flex items-center justify-between text-[11px]">
                                <span class="text-slate-400 font-medium">Strength:</span>
                                <span id="strength-label" class="font-bold text-slate-400">None</span>
                            </div>
                            <div class="h-1.5 w-full bg-slate-100 rounded-full overflow-hidden">
                                <div id="strength-bar" class="h-full w-0 bg-slate-300 rounded-full transition-all duration-300"></div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                            Confirm New Password <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="Repeat new password" oninput="checkPasswordMatch()"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3.5 py-2.5 pr-10 text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:border-violet-500 focus:outline-none transition shadow-2xs">
                            <button type="button" onclick="togglePasswordVisibility('password_confirmation', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition">
                                <svg class="h-4 w-4 eye-icon" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                            </button>
                        </div>
                        <p id="match-feedback" class="text-[11px] font-semibold mt-1.5 hidden"></p>
                    </div>
                </div>

                <div class="flex justify-end pt-3">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-5 py-2.5 text-xs font-bold text-white shadow-xs hover:bg-slate-800 transition">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                        </svg>
                        <span>Update Password</span>
                    </button>
                </div>
            </form>
        </div>

    </div>

    <script>
        // Toggle Password Visibility
        function togglePasswordVisibility(inputId, btn) {
            const input = document.getElementById(inputId);
            if (!input) return;

            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';

            const icon = btn.querySelector('.eye-icon');
            if (icon) {
                if (isPassword) {
                    icon.innerHTML = `
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                    `;
                    btn.classList.add('text-violet-600');
                } else {
                    icon.innerHTML = `
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    `;
                    btn.classList.remove('text-violet-600');
                }
            }
        }

        // Live Password Strength Meter
        function evaluatePasswordStrength(password) {
            const bar = document.getElementById('strength-bar');
            const label = document.getElementById('strength-label');
            if (!bar || !label) return;

            if (!password) {
                bar.style.width = '0%';
                bar.className = 'h-full w-0 bg-slate-300 rounded-full transition-all duration-300';
                label.textContent = 'None';
                label.className = 'font-bold text-slate-400';
                return;
            }

            let score = 0;
            if (password.length >= 8) score++;
            if (password.length >= 12) score++;
            if (/[0-9]/.test(password)) score++;
            if (/[A-Z]/.test(password) && /[a-z]/.test(password)) score++;
            if (/[^A-Za-z0-9]/.test(password)) score++;

            if (score <= 1) {
                bar.style.width = '25%';
                bar.className = 'h-full bg-rose-500 rounded-full transition-all duration-300';
                label.textContent = 'Weak';
                label.className = 'font-bold text-rose-600';
            } else if (score === 2 || score === 3) {
                bar.style.width = '55%';
                bar.className = 'h-full bg-amber-500 rounded-full transition-all duration-300';
                label.textContent = 'Medium';
                label.className = 'font-bold text-amber-600';
            } else if (score >= 4) {
                bar.style.width = '100%';
                bar.className = 'h-full bg-emerald-500 rounded-full transition-all duration-300';
                label.textContent = 'Strong';
                label.className = 'font-bold text-emerald-600';
            }

            checkPasswordMatch();
        }

        // Check password confirmation match
        function checkPasswordMatch() {
            const pass = document.getElementById('password')?.value || '';
            const confirm = document.getElementById('password_confirmation')?.value || '';
            const feedback = document.getElementById('match-feedback');
            if (!feedback) return;

            if (!confirm) {
                feedback.classList.add('hidden');
                return;
            }

            feedback.classList.remove('hidden');
            if (pass === confirm) {
                feedback.textContent = '✓ Passwords match';
                feedback.className = 'text-[11px] font-semibold mt-1.5 text-emerald-600';
            } else {
                feedback.textContent = '✕ Passwords do not match yet';
                feedback.className = 'text-[11px] font-semibold mt-1.5 text-rose-500';
            }
        }
    </script>
@endsection
