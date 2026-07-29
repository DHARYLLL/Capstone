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
                                <tr class="border-b border-gray-100 hover:bg-slate-50/50 transition">
                                    <td class="py-3.5 px-4 font-bold text-gray-900 text-sm">Mae S.</td>
                                    <td class="py-3.5 px-4 text-gray-500 font-mono text-xs">mae.s@dariv.com</td>
                                    <td class="py-3.5 px-4 text-gray-700 text-sm">Staff</td>
                                    <td class="py-3.5 px-4"><span class="badge badge-success badge-outline rounded-full text-[10px] font-bold">Online</span></td>
                                </tr>
                                <tr class="border-b border-gray-100 hover:bg-slate-50/50 transition">
                                    <td class="py-3.5 px-4 font-bold text-gray-900 text-sm">Jon P.</td>
                                    <td class="py-3.5 px-4 text-gray-500 font-mono text-xs">jon.p@dariv.com</td>
                                    <td class="py-3.5 px-4 text-gray-700 text-sm">Staff</td>
                                    <td class="py-3.5 px-4"><span class="badge badge-outline rounded-full text-[10px] font-bold">Away</span></td>
                                </tr>
                                <tr class="border-b border-gray-100 hover:bg-slate-50/50 transition">
                                    <td class="py-3.5 px-4 font-bold text-gray-900 text-sm">Rina T.</td>
                                    <td class="py-3.5 px-4 text-gray-500 font-mono text-xs">rina.t@dariv.com</td>
                                    <td class="py-3.5 px-4 text-gray-700 text-sm">Administrator</td>
                                    <td class="py-3.5 px-4"><span class="badge badge-success badge-outline rounded-full text-[10px] font-bold">Online</span></td>
                                </tr>
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
            
            <form id="add-staff-form" class="space-y-4" method="dialog">
                <!-- Name -->
                <div>
                    <label for="staff-name" class="block text-xs font-bold text-gray-500 mb-1.5 uppercase tracking-wide">Full Name</label>
                    <input type="text" id="staff-name" required placeholder="e.g. Maria D." 
                        class="input input-bordered w-full rounded-2xl border-gray-200 bg-white text-sm text-gray-800 focus:border-indigo-500 focus:outline-none">
                </div>

                <!-- Email -->
                <div>
                    <label for="staff-email" class="block text-xs font-bold text-gray-500 mb-1.5 uppercase tracking-wide">Email Address</label>
                    <input type="email" id="staff-email" required placeholder="e.g. maria.d@dariv.com" 
                        class="input input-bordered w-full rounded-2xl border-gray-200 bg-white text-sm text-gray-800 focus:border-indigo-500 focus:outline-none">
                </div>

                <!-- Password -->
                <div>
                    <label for="staff-password" class="block text-xs font-bold text-gray-500 mb-1.5 uppercase tracking-wide">Account Password</label>
                    <input type="password" id="staff-password" required placeholder="••••••••" 
                        class="input input-bordered w-full rounded-2xl border-gray-200 bg-white text-sm text-gray-800 focus:border-indigo-500 focus:outline-none">
                </div>

                <!-- Role Selection -->
                <div>
                    <label for="staff-role" class="block text-xs font-bold text-gray-500 mb-1.5 uppercase tracking-wide">Role</label>
                    <select id="staff-role" required class="select select-bordered w-full rounded-2xl border-gray-200 bg-white text-sm text-gray-800 focus:border-indigo-500 focus:outline-none">
                        <option value="" disabled selected>Select Role...</option>
                        <option value="Administrator">Administrator</option>
                        <option value="Staff">Staff</option>
                    </select>
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

    <!-- Client-Side Addition Simulation script -->
    <script>
        document.getElementById('add-staff-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const name = document.getElementById('staff-name').value;
            const email = document.getElementById('staff-email').value;
            const role = document.getElementById('staff-role').value;

            // Formulate new table row
            const newRow = document.createElement('tr');
            newRow.className = 'border-b border-gray-100 hover:bg-slate-50/50 transition';
            newRow.innerHTML = `
                <td class="py-3.5 px-4 font-bold text-gray-900 text-sm">${name}</td>
                <td class="py-3.5 px-4 text-gray-500 font-mono text-xs">${email}</td>
                <td class="py-3.5 px-4 text-gray-700 text-sm">${role}</td>
                <td class="py-3.5 px-4"><span class="badge badge-success badge-outline rounded-full text-[10px] font-bold">Online</span></td>
            `;

            // Append to table body
            document.getElementById('team-table-body').appendChild(newRow);

            // Reset Form and close modal
            document.getElementById('add-staff-form').reset();
            document.getElementById('add_staff_modal').close();
        });
    </script>
@endsection