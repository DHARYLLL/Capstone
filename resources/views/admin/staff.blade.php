@extends('layouts.admin')

@section('page_title', 'Staff & Roles')
@section('breadcrumbs', 'Admin / Staff & Roles')

@section('content')
    <div class="space-y-6">
        <!-- Minimal Top Control Header -->
        <div class="flex flex-col gap-4 rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-[#D8210B] to-[#EE4E56] text-lg font-black text-white shadow-xs shadow-[#D8210B]/20">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-xl font-bold tracking-tight text-slate-900">Staff & Roles</h1>
                        <span class="inline-flex items-center gap-1 rounded-full bg-red-50 px-2.5 py-0.5 text-xs font-semibold text-[#D8210B] ring-1 ring-red-600/20">
                            {{ count($staffMembers) }} Members
                        </span>
                    </div>
                    <p class="text-xs font-medium text-slate-400">Manage operators, credentials, and access permissions</p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <button type="button" onclick="document.getElementById('add_staff_modal').showModal()" 
                    class="inline-flex items-center gap-2 rounded-xl bg-[#D8210B] px-4 py-2.5 text-xs font-semibold text-white shadow-xs shadow-[#D8210B]/20 hover:bg-[#EE4E56] transition">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Add staff member
                </button>
            </div>
        </div>

        @php
            $activeCount = collect($staffMembers)->where('status', 'Active')->count();
            $onlineCount = collect($staffMembers)->where('status', 'Online')->count();
            $offlineCount = collect($staffMembers)->where('status', 'Offline')->count();
            $terminatedCount = collect($staffMembers)->where('status', 'Terminated')->count();
        @endphp

        <!-- Quick Metrics Overview -->
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs transition hover:border-slate-300">
                <div class="flex items-center justify-between">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                        <span class="h-2.5 w-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    </div>
                    <span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-xs font-semibold text-emerald-700 bg-emerald-50">
                        Live
                    </span>
                </div>
                <div class="mt-4">
                    <div class="text-2xl font-black tracking-tight text-slate-900">{{ $activeCount }}</div>
                    <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider mt-0.5">In Active Chats</div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs transition hover:border-slate-300">
                <div class="flex items-center justify-between">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </div>
                    <span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-xs font-semibold text-blue-700 bg-blue-50">
                        Available
                    </span>
                </div>
                <div class="mt-4">
                    <div class="text-2xl font-black tracking-tight text-slate-900">{{ $onlineCount }}</div>
                    <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider mt-0.5">Online Operators</div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs transition hover:border-slate-300">
                <div class="flex items-center justify-between">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100 text-slate-600">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </div>
                    <span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-xs font-semibold text-slate-600 bg-slate-100">
                        Standby
                    </span>
                </div>
                <div class="mt-4">
                    <div class="text-2xl font-black tracking-tight text-slate-900">{{ $offlineCount }}</div>
                    <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider mt-0.5">Offline Staff</div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs transition hover:border-slate-300">
                <div class="flex items-center justify-between">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-rose-50 text-rose-600">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636" />
                        </svg>
                    </div>
                    <span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-xs font-semibold text-rose-700 bg-rose-50">
                        Archived
                    </span>
                </div>
                <div class="mt-4">
                    <div class="text-2xl font-black tracking-tight text-slate-900">{{ $terminatedCount }}</div>
                    <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider mt-0.5">Terminated</div>
                </div>
            </div>
        </div>

        <!-- Team List Table Card -->
        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-xs">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Team Directory</h2>
                    <p class="text-xs text-slate-400 mt-0.5">All staff accounts authorized under this company</p>
                </div>
            </div>

            <div class="overflow-x-auto rounded-xl border border-slate-100">
                <table class="table w-full text-left">
                    <thead>
                        <tr class="bg-slate-50/70 text-slate-500 border-b border-slate-100">
                            <th class="py-3 px-4 font-bold text-xs uppercase tracking-wider">Member</th>
                            <th class="py-3 px-4 font-bold text-xs uppercase tracking-wider">Email</th>
                            <th class="py-3 px-4 font-bold text-xs uppercase tracking-wider">Role</th>
                            <th class="py-3 px-4 font-bold text-xs uppercase tracking-wider">Status</th>
                            <th class="py-3 px-4 font-bold text-xs uppercase tracking-wider text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="team-table-body" class="divide-y divide-slate-100 text-sm">
                        @forelse ($staffMembers as $staffMember)
                            @php
                                $staff = $staffMember['user'];
                                $status = $staffMember['status'];
                                $initials = strtoupper(substr($staff->name ?? 'U', 0, 2));
                                $statusBadge = match ($status) {
                                    'Active' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
                                    'Online' => 'bg-blue-50 text-blue-700 ring-blue-600/20',
                                    'Terminated' => 'bg-rose-50 text-rose-700 ring-rose-600/20',
                                    default => 'bg-slate-100 text-slate-600 ring-slate-500/20',
                                };
                                $statusDot = match ($status) {
                                    'Active' => 'bg-emerald-500 animate-pulse',
                                    'Online' => 'bg-blue-500',
                                    'Terminated' => 'bg-rose-500',
                                    default => 'bg-slate-400',
                                };
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="py-3.5 px-4 font-bold text-slate-900">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-red-100 text-xs font-bold text-[#D8210B]">
                                            {{ $initials }}
                                        </div>
                                        <div>
                                            <span class="block font-bold text-slate-900">{{ $staff->name }}</span>
                                            <span class="block text-xs font-normal text-slate-400 sm:hidden">{{ $staff->email }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 text-slate-500 font-mono text-xs">{{ $staff->email }}</td>
                                <td class="py-3.5 px-4 text-slate-700 font-medium">
                                    <span class="inline-flex items-center rounded-lg bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700">
                                        {{ $staff->role }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-semibold ring-1 {{ $statusBadge }}">
                                        <span class="h-1.5 w-1.5 rounded-full {{ $statusDot }}"></span>
                                        {{ $status }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button type="button" 
                                            class="inline-flex items-center gap-1 rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition"
                                            data-edit-staff
                                            data-id="{{ $staff->id }}" 
                                            data-name="{{ $staff->name }}" 
                                            data-email="{{ $staff->email }}" 
                                            data-role="{{ $staff->role }}">
                                            Edit
                                        </button>
                                        @if ($status === 'Terminated')
                                            <form method="POST" action="{{ route('admin.staff.restore', $staff->id) }}" class="inline">
                                                @csrf
                                                <button type="submit" 
                                                    class="inline-flex items-center gap-1 rounded-xl border border-emerald-200 bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700 hover:bg-emerald-100 transition">
                                                    Restore
                                                </button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('admin.staff.destroy', $staff->id) }}" onsubmit="return confirm('Terminate this staff member?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                    class="inline-flex items-center gap-1 rounded-xl border border-rose-200 bg-rose-50 px-3 py-1.5 text-xs font-semibold text-rose-700 hover:bg-rose-100 transition">
                                                    Terminate
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-slate-400">
                                    <svg class="mx-auto h-8 w-8 text-slate-300 mb-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                                    </svg>
                                    <p class="text-xs font-semibold">No staff members found</p>
                                    <p class="text-[11px] text-slate-400 mt-0.5">Click "Add staff member" to authorize a new user.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Staff Member Modal -->
    <dialog id="add_staff_modal" class="modal bg-slate-900/60 backdrop-blur-sm">
        <div class="modal-box bg-white max-w-md rounded-2xl p-6 border border-slate-200/80 shadow-2xl relative">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div>
                    <h3 class="text-lg font-bold text-slate-900">Add Staff Member</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Create a user account for your team</p>
                </div>
                <button type="button" onclick="document.getElementById('add_staff_modal').close()" class="rounded-xl p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <form id="add-staff-form" class="space-y-4 pt-4" method="POST" action="{{ route('admin.staff.store') }}">
                @csrf
                <input type="hidden" name="form_context" value="add_staff">
                @if ($errors->any())
                    <div class="rounded-xl bg-rose-50 p-3 text-xs font-semibold text-rose-700">Please correct the errors below.</div>
                @endif

                <div>
                    <label for="staff-name" class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">Full Name</label>
                    <input type="text" id="staff-name" name="name" value="{{ old('name') }}" required placeholder="e.g. Maria D." 
                        class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-800 placeholder:text-slate-400 focus:border-[#D8210B] focus:ring-1 focus:ring-[#D8210B] focus:outline-none transition">
                    @error('name')
                        <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="staff-email" class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">Email Address</label>
                    <input type="email" id="staff-email" name="email" value="{{ old('email') }}" required placeholder="e.g. maria.d@dariv.com" 
                        class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-800 placeholder:text-slate-400 focus:border-[#D8210B] focus:ring-1 focus:ring-[#D8210B] focus:outline-none transition">
                    @error('email')
                        <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="staff-password" class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">Account Password</label>
                    <input type="password" id="staff-password" name="password" required placeholder="••••••••" 
                        class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-800 placeholder:text-slate-400 focus:border-[#D8210B] focus:ring-1 focus:ring-[#D8210B] focus:outline-none transition">
                    @error('password')
                        <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="staff-password-confirmation" class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">Confirm Password</label>
                    <input type="password" id="staff-password-confirmation" name="password_confirmation" required placeholder="••••••••"
                        class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-800 placeholder:text-slate-400 focus:border-[#D8210B] focus:ring-1 focus:ring-[#D8210B] focus:outline-none transition">
                </div>

                <div>
                    <label for="staff-role" class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">Role</label>
                    <select id="staff-role" name="role" required class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-800 focus:border-[#D8210B] focus:ring-1 focus:ring-[#D8210B] focus:outline-none transition">
                        <option value="" disabled @selected(! old('role'))>Select Role...</option>
                        <option value="Administrator" @selected(old('role') === 'Administrator')>Administrator</option>
                        <option value="Staff" @selected(old('role') === 'Staff')>Staff</option>
                        <option value="Lead Operator" @selected(old('role') === 'Lead Operator')>Lead Operator</option>
                    </select>
                    @error('role')
                        <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex items-center justify-end gap-2 pt-4 border-t border-slate-100">
                    <button type="button" onclick="document.getElementById('add_staff_modal').close()" 
                        class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition">
                        Cancel
                    </button>
                    <button type="submit" 
                        class="rounded-xl bg-[#D8210B] px-4 py-2.5 text-xs font-semibold text-white shadow-xs shadow-[#D8210B]/20 hover:bg-[#EE4E56] transition">
                        Add Staff
                    </button>
                </div>
            </form>
        </div>
    </dialog>

    <!-- Edit Staff Member Modal -->
    <dialog id="edit_staff_modal" class="modal bg-slate-900/60 backdrop-blur-sm">
        <div class="modal-box bg-white max-w-md rounded-2xl p-6 border border-slate-200/80 shadow-2xl relative">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div>
                    <h3 class="text-lg font-bold text-slate-900">Edit Staff Member</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Update account details and access permissions</p>
                </div>
                <button type="button" onclick="document.getElementById('edit_staff_modal').close()" class="rounded-xl p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form id="edit-staff-form" class="space-y-4 pt-4" method="POST">
                @csrf
                @method('PATCH')
                <div>
                    <label for="edit-staff-name" class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">Full Name</label>
                    <input type="text" id="edit-staff-name" name="name" required 
                        class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-800 focus:border-[#D8210B] focus:ring-1 focus:ring-[#D8210B] focus:outline-none transition">
                </div>
                <div>
                    <label for="edit-staff-email" class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">Email Address</label>
                    <input type="email" id="edit-staff-email" name="email" required 
                        class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-800 focus:border-[#D8210B] focus:ring-1 focus:ring-[#D8210B] focus:outline-none transition">
                </div>
                <div>
                    <label for="edit-staff-password" class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">New Password</label>
                    <input type="password" id="edit-staff-password" name="password" placeholder="Leave blank to keep current password" 
                        class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-800 placeholder:text-slate-400 focus:border-[#D8210B] focus:ring-1 focus:ring-[#D8210B] focus:outline-none transition">
                </div>
                <div>
                    <label for="edit-staff-role" class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">Role</label>
                    <select id="edit-staff-role" name="role" required 
                        class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-800 focus:border-[#D8210B] focus:ring-1 focus:ring-[#D8210B] focus:outline-none transition">
                        <option value="Administrator">Administrator</option>
                        <option value="Staff">Staff</option>
                        <option value="Lead Operator">Lead Operator</option>
                        <option value="agent">agent</option>
                    </select>
                </div>
                <div class="flex items-center justify-end gap-2 pt-4 border-t border-slate-100">
                    <button type="button" onclick="document.getElementById('edit_staff_modal').close()" 
                        class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition">
                        Cancel
                    </button>
                    <button type="submit" 
                        class="rounded-xl bg-[#D8210B] px-4 py-2.5 text-xs font-semibold text-white shadow-xs shadow-[#D8210B]/20 hover:bg-[#EE4E56] transition">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </dialog>

    <script>
        document.querySelectorAll('[data-edit-staff]').forEach((button) => {
            button.addEventListener('click', () => {
                const form = document.getElementById('edit-staff-form');
                form.action = `/admin/staff/${button.dataset.id}`;
                document.getElementById('edit-staff-name').value = button.dataset.name;
                document.getElementById('edit-staff-email').value = button.dataset.email;
                document.getElementById('edit-staff-role').value = button.dataset.role;
                document.getElementById('edit-staff-password').value = '';
                document.getElementById('edit_staff_modal').showModal();
            });
        });
    </script>

    @if ($errors->any() && old('form_context') === 'add_staff')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('add_staff_modal').showModal();
            });
        </script>
    @endif
@endsection
