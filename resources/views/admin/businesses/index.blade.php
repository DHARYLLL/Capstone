{{-- filepath: c:\Users\dhary\Desktop\Capstone\capstone1\resources\views\admin\businesses\index.blade.php --}}
@extends('layouts.admin')

@section('page_title', 'Manage Businesses')
@section('page_description', 'View, edit, and manage all registered businesses on the platform.')
@section('breadcrumbs', 'Admin / Businesses')

@section('content')
    <div class="space-y-8">
        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="card bg-base-100 shadow-sm">
                <div class="card-body p-6">
                    <div class="text-sm font-medium text-gray-500">Total Businesses</div>
                    <div class="mt-2 text-4xl font-black text-gray-800">3</div>
                    <p class="mt-2 text-sm leading-6 text-gray-500">Dakong Balay, Monclaire Pool, and Villa Carmelita</p>
                </div>
            </div>

            <div class="card bg-base-100 shadow-sm">
                <div class="card-body p-6">
                    <div class="text-sm font-medium text-gray-500">Active Products/Services</div>
                    <div class="mt-2 text-4xl font-black text-gray-800">42</div>
                    <p class="mt-2 text-sm leading-6 text-gray-500">Currently visible in the platform for guest access</p>
                </div>
            </div>

            <div class="card bg-base-100 shadow-sm">
                <div class="card-body p-6">
                    <div class="text-sm font-medium text-gray-500">Inactive (Archived)</div>
                    <div class="mt-2 text-4xl font-black text-gray-800">7</div>
                    <p class="mt-2 text-sm leading-6 text-gray-500">Archived items hidden from the public experience</p>
                </div>
            </div>

            <div class="card bg-base-100 shadow-sm">
                <div class="card-body p-6">
                    <div class="text-sm font-medium text-gray-500">Last Updated</div>
                    <div class="mt-2 text-4xl font-black text-gray-800">11:24 AM</div>
                    <p class="mt-2 text-sm leading-6 text-gray-500">Latest sync across business records and chatbot data</p>
                </div>
            </div>
        </section>

        <section class="grid gap-6 lg:grid-cols-[2fr_1fr]">
            <div class="card bg-base-100 shadow-sm">
                <div class="card-body p-6">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h2 class="text-xl font-bold text-gray-800">Recent activity</h2>
                            <p class="mt-1 text-sm text-gray-500">Latest updates from business management and content edits.
                            </p>
                        </div>
                        <button
                            class="btn btn-sm rounded-full border border-gray-300 bg-white text-gray-700 transition-colors duration-200 hover:border-brand-border hover:bg-[#f5f3ff] hover:text-gray-900">
                            View history
                        </button>
                    </div>

                    <div class="mt-6 space-y-3">
                        <div class="flex gap-4 rounded-2xl border border-gray-100 bg-[#ffffff] p-4">
                            <div class="mt-1 h-10 w-1 rounded-full bg-brand-primary"></div>
                            <div>
                                <div class="font-semibold text-gray-800">Updated Dakong Balay description</div>
                                <p class="mt-1 text-sm text-gray-500">Refined the restaurant overview and highlighted
                                    Filipino cuisine offerings.</p>
                            </div>
                        </div>

                        <div class="flex gap-4 rounded-2xl border border-gray-100 bg-[#ffffff] p-4">
                            <div class="mt-1 h-10 w-1 rounded-full bg-emerald-500"></div>
                            <div>
                                <div class="font-semibold text-gray-800">Archived pool service</div>
                                <p class="mt-1 text-sm text-gray-500">Moved an outdated seasonal package to inactive
                                    records.</p>
                            </div>
                        </div>

                        <div class="flex gap-4 rounded-2xl border border-gray-100 bg-[#ffffff] p-4">
                            <div class="mt-1 h-10 w-1 rounded-full bg-sky-500"></div>
                            <div>
                                <div class="font-semibold text-gray-800">Synced chatbot knowledge base</div>
                                <p class="mt-1 text-sm text-gray-500">Aligned assistant responses with the newest business
                                    details.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card bg-base-100 shadow-sm">
                <div class="card-body p-6">
                    <h2 class="text-xl font-bold text-gray-800">Dashboard note</h2>
                    <p class="mt-1 text-sm text-gray-500">Quick reminders for maintaining accurate and consistent content.
                    </p>

                    <div class="mt-5 space-y-4">
                        <div class="rounded-2xl bg-[#f8fafc] p-4">
                            <div class="font-semibold text-gray-800">Centralized business control</div>
                            <p class="mt-1 text-sm leading-6 text-gray-600">All business records are updated in one place to
                                keep public pages aligned.</p>
                        </div>

                        <div class="rounded-2xl bg-[#f8fafc] p-4">
                            <div class="font-semibold text-gray-800">Chatbot-aware content</div>
                            <p class="mt-1 text-sm leading-6 text-gray-600">Keep descriptions concise so the assistant can
                                answer quickly and clearly.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="card bg-base-100 shadow-sm">
            <div class="card-body p-6">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Manage Businesses</h2>
                        <p class="mt-1 text-sm text-gray-500">View and edit the core business records shown across the
                            platform.</p>
                    </div>
                    <button
                        class="btn rounded-full border-0 bg-brand-primary text-white transition-colors duration-200 hover:bg-[#6d28d9] hover:text-white">
                        <svg viewBox="0 0 24 24" class="h-4 w-4 fill-current" aria-hidden="true">
                            <path d="M19 11H13V5h-2v6H5v2h6v6h2v-6h6z" />
                        </svg>
                        Add business
                    </button>
                </div>

                <div class="mt-6 overflow-x-auto">
                    <table class="table table-zebra">
                        <thead>
                            <tr class="text-gray-500">
                                <th>Business</th>
                                <th>Type</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="cursor-pointer hover:bg-base-200 transition-colors business-row border-l-4 border-transparent"
                                data-name="Dakong Balay" data-category="Restaurant"
                                data-description="Authentic Filipino cuisine and family-style dining in a warm, inviting setting."
                                data-image="dakong-balay-cover.jpg" data-status="active">
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="h-12 w-12 rounded-2xl bg-gradient-to-br from-brand-primary-light to-brand-border">
                                        </div>
                                        <div>
                                            <div class="font-semibold text-gray-800">Dakong Balay</div>
                                            <div class="text-sm text-gray-500">Restaurant and dining experience</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-gray-600">Restaurant</td>
                                <td class="max-w-[320px] truncate text-gray-600">Authentic Filipino cuisine and family-style
                                    dining in a warm, inviting setting.</td>
                                <td><span class="badge badge-success badge-outline">Active</span></td>
                                <td>
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.businesses.manager-products', 'dakong-balay') }}"
                                            class="btn btn-sm inline-flex items-center gap-2 rounded-full border border-gray-300 bg-white whitespace-nowrap text-gray-700 transition-colors duration-200 hover:border-brand-border hover:bg-[#f5f3ff] hover:text-gray-900">
                                            <svg viewBox="0 0 24 24" class="h-4 w-4 fill-current" aria-hidden="true">
                                                <path
                                                    d="M19 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2zm-7 3a3 3 0 1 1 0 6 3 3 0 0 1 0-6zm6 12H6v-.5c0-2 4-3.1 6-3.1s6 1.1 6 3.1V18z" />
                                            </svg>
                                            Products
                                        </a>
                                        <a href="{{ route('admin.businesses.edit', 'dakong-balay') }}"
                                            class="btn btn-sm inline-flex items-center gap-2 rounded-full border border-gray-300 bg-white whitespace-nowrap text-gray-700 transition-colors duration-200 hover:border-brand-border hover:bg-[#f5f3ff] hover:text-gray-900">
                                            <svg viewBox="0 0 24 24" class="h-4 w-4 fill-current" aria-hidden="true">
                                                <path
                                                    d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zm18-10.5a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z" />
                                            </svg>
                                            Edit
                                        </a>
                                    </div>
                                </td>
                            </tr>

                            <tr class="cursor-pointer hover:bg-base-200 transition-colors business-row border-l-4 border-transparent"
                                data-name="Monclaire Pool" data-category="Swimming Pool"
                                data-description="A relaxing space for leisure, family gatherings, and refreshing weekend escapes."
                                data-image="monclaire-pool-cover.jpg" data-status="active">
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="h-12 w-12 rounded-2xl bg-gradient-to-br from-[#D7E6EE] to-[#B8D0DD]">
                                        </div>
                                        <div>
                                            <div class="font-semibold text-gray-800">Monclaire Pool</div>
                                            <div class="text-sm text-gray-500">Swimming and leisure destination</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-gray-600">Swimming Pool</td>
                                <td class="max-w-[320px] truncate text-gray-600">A relaxing space for leisure, family
                                    gatherings, and refreshing weekend escapes.</td>
                                <td><span class="badge badge-success badge-outline">Active</span></td>
                                <td>
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.businesses.manager-products', 'monclaire-pool') }}"
                                            class="btn btn-sm inline-flex items-center gap-2 rounded-full border border-gray-300 bg-white whitespace-nowrap text-gray-700 transition-colors duration-200 hover:border-brand-border hover:bg-[#f5f3ff] hover:text-gray-900">
                                            <svg viewBox="0 0 24 24" class="h-4 w-4 fill-current" aria-hidden="true">
                                                <path
                                                    d="M19 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2zm-7 3a3 3 0 1 1 0 6 3 3 0 0 1 0-6zm6 12H6v-.5c0-2 4-3.1 6-3.1s6 1.1 6 3.1V18z" />
                                            </svg>
                                            Products
                                        </a>
                                        <a href="{{ route('admin.businesses.edit', 'monclaire-pool') }}"
                                            class="btn btn-sm inline-flex items-center gap-2 rounded-full border border-gray-300 bg-white whitespace-nowrap text-gray-700 transition-colors duration-200 hover:border-brand-border hover:bg-[#f5f3ff] hover:text-gray-900">
                                            <svg viewBox="0 0 24 24" class="h-4 w-4 fill-current" aria-hidden="true">
                                                <path
                                                    d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zm18-10.5a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z" />
                                            </svg>
                                            Edit
                                        </a>
                                    </div>
                                </td>
                            </tr>

                            <tr class="cursor-pointer hover:bg-base-200 transition-colors business-row border-l-4 border-transparent"
                                data-name="Villa Carmelita" data-category="Hotel / Villa"
                                data-description="Comfortable stay options with a welcoming ambiance for guests and groups."
                                data-image="villa-carmelita-cover.jpg" data-status="active">
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="h-12 w-12 rounded-2xl bg-gradient-to-br from-[#EFE3D4] to-[#D8C1AA]">
                                        </div>
                                        <div>
                                            <div class="font-semibold text-gray-800">Villa Carmelita</div>
                                            <div class="text-sm text-gray-500">Stay and hospitality destination</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-gray-600">Hotel / Villa</td>
                                <td class="max-w-[320px] truncate text-gray-600">Comfortable stay options with a welcoming
                                    ambiance for guests and groups.</td>
                                <td><span class="badge badge-success badge-outline">Active</span></td>
                                <td>
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.businesses.manager-products', 'villa-carmelita') }}"
                                            class="btn btn-sm inline-flex items-center gap-2 rounded-full border border-gray-300 bg-white whitespace-nowrap text-gray-700 transition-colors duration-200 hover:border-brand-border hover:bg-[#f5f3ff] hover:text-gray-900">
                                            <svg viewBox="0 0 24 24" class="h-4 w-4 fill-current" aria-hidden="true">
                                                <path
                                                    d="M19 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2zm-7 3a3 3 0 1 1 0 6 3 3 0 0 1 0-6zm6 12H6v-.5c0-2 4-3.1 6-3.1s6 1.1 6 3.1V18z" />
                                            </svg>
                                            Rooms
                                        </a>
                                        <a href="{{ route('admin.businesses.edit', 'villa-carmelita') }}"
                                            class="btn btn-sm inline-flex items-center gap-2 rounded-full border border-gray-300 bg-white whitespace-nowrap text-gray-700 transition-colors duration-200 hover:border-brand-border hover:bg-[#f5f3ff] hover:text-gray-900">
                                            <svg viewBox="0 0 24 24" class="h-4 w-4 fill-current" aria-hidden="true">
                                                <path
                                                    d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zm18-10.5a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z" />
                                            </svg>
                                            Edit
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div
                    class="flex flex-col sm:flex-row items-center justify-between gap-4 border-t border-gray-100 pt-4 mt-4">
                    <div class="text-xs text-gray-500">
                        Showing <strong>1</strong> to <strong>3</strong> of <strong>3</strong> businesses
                    </div>
                    <div class="join">
                        <button type="button"
                            class="join-item btn btn-xs border border-gray-300 bg-white text-gray-700 transition-colors duration-200 hover:border-brand-border hover:bg-[#f5f3ff] hover:text-gray-900"
                            disabled>«</button>
                        <button type="button"
                            class="join-item btn btn-xs btn-active border-0 bg-brand-primary text-white transition-colors duration-200 hover:bg-[#6d28d9] hover:text-white">1</button>
                        <button type="button"
                            class="join-item btn btn-xs border border-gray-300 bg-white text-gray-700 transition-colors duration-200 hover:border-brand-border hover:bg-[#f5f3ff] hover:text-gray-900"
                            disabled>»</button>
                    </div>
                </div>
            </div>
        </section>

        </section>
    </div>
@endsection