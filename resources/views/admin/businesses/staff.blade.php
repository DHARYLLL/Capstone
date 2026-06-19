@extends('layouts.admin')

@section('page_title', 'Staff & Roles')
@section('page_description', 'Manage operators, permissions, shifts, and routing responsibilities.')
@section('breadcrumbs', 'Admin / Businesses / Staff')

@section('content')
    <div class="space-y-8">
        <section class="rounded-[2rem] border border-[#eadfce] bg-white p-6 shadow-sm lg:p-8">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <h1 class="text-3xl font-black tracking-tight text-gray-900">Staff & Roles</h1>
                    <p class="mt-2 max-w-3xl text-sm leading-6 text-gray-500">Manage operators, permissions, shifts, and routing responsibilities.</p>
                </div>
                <button class="btn rounded-full border-0 bg-[#5A3E2B] text-white hover:bg-[#453020]">Add staff member</button>
            </div>
        </section>

        <section class="grid gap-6 lg:grid-cols-[1.4fr_1fr]">
            <div class="card bg-base-100 shadow-sm">
                <div class="card-body p-6">
                    <h2 class="text-lg font-bold text-gray-900">Team list</h2>
                    <div class="mt-4 overflow-x-auto">
                        <table class="table">
                            <thead><tr><th>Name</th><th>Role</th><th>Status</th><th>Routing</th></tr></thead>
                            <tbody>
                                <tr><td>Mae S.</td><td>Lead operator</td><td><span class="badge badge-success badge-outline">Online</span></td><td>All escalations</td></tr>
                                <tr><td>Jon P.</td><td>Front desk support</td><td><span class="badge badge-outline">Away</span></td><td>Booking questions</td></tr>
                                <tr><td>Rina T.</td><td>Manager</td><td><span class="badge badge-success badge-outline">Online</span></td><td>High priority only</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card bg-base-100 shadow-sm">
                <div class="card-body p-6">
                    <h2 class="text-lg font-bold text-gray-900">Routing rules</h2>
                    <div class="mt-4 space-y-3 text-sm text-gray-600">
                        <div class="rounded-2xl bg-[#F8F1E7] p-4">Route payment and refund issues to management.</div>
                        <div class="rounded-2xl bg-[#F8F1E7] p-4">Route reservation questions to the front desk.</div>
                        <div class="rounded-2xl bg-[#F8F1E7] p-4">Escalate unanswered requests after 90 seconds.</div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection