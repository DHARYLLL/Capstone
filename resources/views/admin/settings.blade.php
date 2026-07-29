@extends('layouts.admin')

@section('page_title', 'Account Settings')
@section('breadcrumbs', 'Admin / Settings')

@section('content')
    @php
        $userName = session('user_name', 'Admin User');
        $userEmail = session('user_email', 'admin@dariv.com');
        $userRole = session('user_role', 'Administrator');
        $userAvatar = session('user_avatar', '🛡️');
    @endphp

    <div class="max-w-4xl mx-auto space-y-8">
        
        <!-- Alerts -->
        @if (session('success'))
            <div class="alert alert-success bg-emerald-500/15 border border-emerald-500/30 text-emerald-800 rounded-2xl p-4 text-sm flex gap-2">
                <svg class="h-5 w-5 shrink-0 stroke-emerald-600" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <section class="grid gap-6 md:grid-cols-[1fr_2fr]">
            <!-- Profile Info Card -->
            <div class="card bg-base-100 shadow-sm h-fit">
                <div class="card-body p-6 flex flex-col items-center text-center space-y-4">
                    <div class="flex h-20 w-20 items-center justify-center rounded-3xl bg-violet-100 text-violet-700 font-bold text-3xl shadow-sm">
                        {{ $userAvatar }}
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">{{ $userName }}</h2>
                        <p class="text-xs font-semibold text-violet-600 uppercase tracking-wider mt-1">{{ $userRole }}</p>
                        <p class="text-xs text-gray-400 mt-1 font-mono">{{ $userEmail }}</p>
                    </div>
                </div>
            </div>

            <!-- Credentials Update Form -->
            <div class="card bg-base-100 shadow-sm">
                <div class="card-body p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-6">Update Credentials</h2>
                    
                    <form action="{{ route('admin.settings.post') }}" method="POST" class="space-y-5">
                        @csrf
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="name" class="block text-xs font-bold text-gray-500 mb-1.5 uppercase tracking-wide">Full Name</label>
                                <input type="text" id="name" name="name" required value="{{ $userName }}" 
                                    class="input input-bordered w-full rounded-2xl border-gray-200 bg-white text-sm text-gray-800 focus:border-indigo-500 focus:outline-none">
                            </div>

                            <div>
                                <label for="email" class="block text-xs font-bold text-gray-500 mb-1.5 uppercase tracking-wide">Email Address</label>
                                <input type="email" id="email" name="email" required value="{{ $userEmail }}" 
                                    class="input input-bordered w-full rounded-2xl border-gray-200 bg-white text-sm text-gray-800 focus:border-indigo-500 focus:outline-none">
                            </div>
                        </div>

                        <div class="border-t border-gray-100 pt-5 space-y-4">
                            <h3 class="text-sm font-bold text-gray-800">Change Password</h3>
                            
                            <div>
                                <label for="current_password" class="block text-xs font-bold text-gray-500 mb-1.5 uppercase tracking-wide">Current Password</label>
                                <input type="password" id="current_password" name="current_password" placeholder="••••••••" 
                                    class="input input-bordered w-full rounded-2xl border-gray-200 bg-white text-sm text-gray-800 focus:border-indigo-500 focus:outline-none">
                            </div>

                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label for="password" class="block text-xs font-bold text-gray-500 mb-1.5 uppercase tracking-wide">New Password</label>
                                    <input type="password" id="password" name="password" placeholder="••••••••" 
                                        class="input input-bordered w-full rounded-2xl border-gray-200 bg-white text-sm text-gray-800 focus:border-indigo-500 focus:outline-none">
                                </div>

                                <div>
                                    <label for="password_confirmation" class="block text-xs font-bold text-gray-500 mb-1.5 uppercase tracking-wide">Confirm New Password</label>
                                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="••••••••" 
                                        class="input input-bordered w-full rounded-2xl border-gray-200 bg-white text-sm text-gray-800 focus:border-indigo-500 focus:outline-none">
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end pt-4">
                            <button type="submit" class="btn rounded-2xl border-0 bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-6 py-3 h-auto transition-colors">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>

    </div>
@endsection
