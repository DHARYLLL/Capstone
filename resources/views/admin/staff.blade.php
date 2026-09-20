@extends('layouts.admin')

@section('page_title', 'Staff & Roles')
@section('breadcrumbs', 'Admin / Staff & Roles')

@section('content')
    <div class="space-y-8">
        <!-- Header Banner -->
        <section class="rounded-[2rem] border border-[#e2e8f0] bg-white p-6 shadow-sm lg:p-8">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <h1 class="text-3xl font-black tracking-tight text-gray-900 font-sans">Staff & Roles</h1>
                    <p class="mt-2 max-w-3xl text-sm leading-6 text-gray-500">Manage operators, credentials, and access permissions.</p>
                </div>
                <button onclick="document.getElementById('add_staff_modal').showModal()" 
                    class="btn rounded-full border-0 bg-brand-primary text-white hover:bg-[#6d28d9] px-6 py-3 h-auto font-bold font-sans">
                    Add staff member
                </button>
            </div>
        </section>

        <!-- Main Content Section -->
        <section class="grid gap-6">
            <!-- Team List Table Card -->
            <div class="card bg-base-100 shadow-sm border border-gray-100 rounded-3xl">
                <div class="card-body p-6">
                    <h2 class="text-lg font-bold text-gray-900">Team list</h2>
                    <div class="mt-4 overflow-x-auto">
                        <table class="table w-full">
                            <thead>
                                <tr class="text-slate-500 border-b border-gray-100">
                                    <th class="py-3 px-4 font-bold text-xs uppercase tracking-wider text-left">Name</th>
                                    <th class="py-3 px-4 font-bold text-xs uppercase tracking-wider text-left">Email</th>
                                    <th class="py-3 px-4 font-bold text-xs uppercase tracking-wider text-left">Role</th>
                                    <th class="py-3 px-4 font-bold text-xs uppercase tracking-wider text-left">Status</th>
                                </tr>
                            </thead>
                            <tbody id="team-table-body">
                                @forelse ($staffMembers as $staffMember)
                                    @php
                                        $staff = $staffMember['user'];
                                        $status = $staffMember['status'];
                                        $statusClasses = match ($status) {
                                            'Active' => 'badge badge-info badge-outline rounded-full text-[10px] font-bold',
                                            'Online' => 'badge badge-success badge-outline rounded-full text-[10px] font-bold',
                                            'Terminated' => 'badge badge-error badge-outline rounded-full text-[10px] font-bold',
                                            default => 'badge badge-outline rounded-full text-[10px] font-bold',
                                        };
                                    @endphp
                                    <tr class="border-b border-gray-100 hover:bg-slate-50/50 transition">
                                        <td class="py-3.5 px-4 font-bold text-gray-900 text-sm">{{ $staff->name }}</td>
                                        <td class="py-3.5 px-4 text-gray-500 font-mono text-xs">{{ $staff->email }}</td>
                                        <td class="py-3.5 px-4 text-gray-700 text-sm">{{ $staff->role }}</td>
                                        <td class="py-3.5 px-4">
                                            <span class="{{ $statusClasses }}">{{ $status }}</span>
                                            <details class="inline-block ml-2 align-middle">
                                                <summary class="btn btn-ghost btn-xs text-gray-500 cursor-pointer">Actions</summary>
                                                <div class="absolute z-10 mt-1 w-32 rounded-xl border border-gray-100 bg-white p-1 shadow-lg">
                                                    <button type="button" class="btn btn-ghost btn-xs w-full justify-start" data-edit-staff
                                                        data-id="{{ $staff->id }}" data-name="{{ $staff->name }}" data-email="{{ $staff->email }}" data-role="{{ $staff->role }}">Edit</button>
                                                    @if ($status === 'Terminated')
                                                        <form method="POST" action="{{ route('admin.staff.restore', $staff->id) }}">
                                                            @csrf
                                                            <button type="submit" class="btn btn-ghost btn-xs w-full justify-start">Restore</button>
                                                        </form>
                                                    @else
                                                        <form method="POST" action="{{ route('admin.staff.destroy', $staff->id) }}" onsubmit="return confirm('Terminate this staff member?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-ghost btn-xs w-full justify-start text-error">Terminate</button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </details>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="border-b border-gray-100 hover:bg-slate-50/50 transition">
                                        <td colspan="4" class="py-3.5 px-4 text-gray-500 text-sm">No staff members found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Add Staff Member Modal -->
    <dialog id="add_staff_modal" class="modal bg-slate-900/60 backdrop-blur-sm">
        <div class="modal-box bg-white max-w-md rounded-[2rem] p-8 border border-gray-100 shadow-2xl relative">
            <h3 class="text-2xl font-black text-gray-900 tracking-tight font-sans">Add Staff Member</h3>
            <p class="text-xs text-gray-400 mt-1 mb-6">Create a new user account mapped to the business database structure.</p>
            
            <form id="add-staff-form" class="space-y-4" method="POST" action="{{ route('admin.staff.store') }}">
                @csrf
                <input type="hidden" name="form_context" value="add_staff">
                @if ($errors->any())
                    <div class="text-xs text-red-500 mt-1 block">Please correct the errors below.</div>
                @endif
                <!-- Name -->
                <div>
                    <label for="staff-name" class="block text-xs font-bold text-gray-500 mb-1.5 uppercase tracking-wide">Full Name</label>
                    <input type="text" id="staff-name" name="name" value="{{ old('name') }}" required placeholder="e.g. Maria D." 
                        class="input input-bordered w-full rounded-2xl border-gray-200 bg-white text-sm text-gray-800 focus:border-indigo-500 focus:outline-none">
                    @error('name')
                        <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="staff-email" class="block text-xs font-bold text-gray-500 mb-1.5 uppercase tracking-wide">Email Address</label>
                    <input type="email" id="staff-email" name="email" value="{{ old('email') }}" required placeholder="e.g. maria.d@dariv.com" 
                        class="input input-bordered w-full rounded-2xl border-gray-200 bg-white text-sm text-gray-800 focus:border-indigo-500 focus:outline-none">
                    @error('email')
                        <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="staff-password" class="block text-xs font-bold text-gray-500 mb-1.5 uppercase tracking-wide">Account Password</label>
                    <input type="password" id="staff-password" name="password" required placeholder="********" 
                        class="input input-bordered w-full rounded-2xl border-gray-200 bg-white text-sm text-gray-800 focus:border-indigo-500 focus:outline-none">
                    @error('password')
                        <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="staff-password-confirmation" class="block text-xs font-bold text-gray-500 mb-1.5 uppercase tracking-wide">Confirm Password</label>
                    <input type="password" id="staff-password-confirmation" name="password_confirmation" required placeholder="********"
                        class="input input-bordered w-full rounded-2xl border-gray-200 bg-white text-sm text-gray-800 focus:border-indigo-500 focus:outline-none">
                </div>

                <!-- Role Selection -->
                <div>
                    <label for="staff-role" class="block text-xs font-bold text-gray-500 mb-1.5 uppercase tracking-wide">Role</label>
                    <select id="staff-role" name="role" required class="select select-bordered w-full rounded-2xl border-gray-200 bg-white text-sm text-gray-800 focus:border-indigo-500 focus:outline-none">
                        <option value="" disabled @selected(! old('role'))>Select Role...</option>
                        <option value="Administrator" @selected(old('role') === 'Administrator')>Administrator</option>
                        <option value="Staff" @selected(old('role') === 'Staff')>Staff</option>
                        <option value="Lead Operator" @selected(old('role') === 'Lead Operator')>Lead Operator</option>
                    </select>
                    @error('role')
                        <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Form Action Buttons -->
                <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                    <button type="button" onclick="document.getElementById('add_staff_modal').close()" 
                        class="btn rounded-2xl border border-gray-200 bg-white hover:bg-slate-50 text-gray-700 font-bold px-5 py-3 h-auto">
                        Cancel
                    </button>
                    <button type="submit" 
                        class="btn rounded-2xl border-0 bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-6 py-3 h-auto">
                        Add Staff
                    </button>
                </div>
            </form>
        </div>
    </dialog>

    <dialog id="edit_staff_modal" class="modal bg-slate-900/60 backdrop-blur-sm">
        <div class="modal-box bg-white max-w-md rounded-[2rem] p-8 border border-gray-100 shadow-2xl relative">
            <h3 class="text-2xl font-black text-gray-900 tracking-tight font-sans">Edit Staff Member</h3>
            <p class="text-xs text-gray-400 mt-1 mb-6">Update account details and access permissions.</p>

            <form id="edit-staff-form" class="space-y-4" method="POST">
                @csrf
                @method('PATCH')
                <div>
                    <label for="edit-staff-name" class="block text-xs font-bold text-gray-500 mb-1.5 uppercase tracking-wide">Full Name</label>
                    <input type="text" id="edit-staff-name" name="name" required class="input input-bordered w-full rounded-2xl border-gray-200 bg-white text-sm text-gray-800 focus:border-indigo-500 focus:outline-none">
                </div>
                <div>
                    <label for="edit-staff-email" class="block text-xs font-bold text-gray-500 mb-1.5 uppercase tracking-wide">Email Address</label>
                    <input type="email" id="edit-staff-email" name="email" required class="input input-bordered w-full rounded-2xl border-gray-200 bg-white text-sm text-gray-800 focus:border-indigo-500 focus:outline-none">
                </div>
                <div>
                    <label for="edit-staff-password" class="block text-xs font-bold text-gray-500 mb-1.5 uppercase tracking-wide">New Password</label>
                    <input type="password" id="edit-staff-password" name="password" placeholder="Leave blank to keep current password" class="input input-bordered w-full rounded-2xl border-gray-200 bg-white text-sm text-gray-800 focus:border-indigo-500 focus:outline-none">
                </div>
                <div>
                    <label for="edit-staff-role" class="block text-xs font-bold text-gray-500 mb-1.5 uppercase tracking-wide">Role</label>
                    <select id="edit-staff-role" name="role" required class="select select-bordered w-full rounded-2xl border-gray-200 bg-white text-sm text-gray-800 focus:border-indigo-500 focus:outline-none">
                        <option value="Administrator">Administrator</option>
                        <option value="Staff">Staff</option>
                        <option value="Lead Operator">Lead Operator</option>
                        <option value="agent">agent</option>
                    </select>
                </div>
                <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                    <button type="button" onclick="document.getElementById('edit_staff_modal').close()" class="btn rounded-2xl border border-gray-200 bg-white hover:bg-slate-50 text-gray-700 font-bold px-5 py-3 h-auto">Cancel</button>
                    <button type="submit" class="btn rounded-2xl border-0 bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-6 py-3 h-auto">Save Changes</button>
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