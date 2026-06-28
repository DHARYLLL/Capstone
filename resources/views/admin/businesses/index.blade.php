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
                            <p class="mt-1 text-sm text-gray-500">Latest updates from business management and content edits.</p>
                        </div>
                        <button class="btn btn-sm rounded-full border border-gray-300 bg-white text-gray-700 transition-colors duration-200 hover:border-brand-border hover:bg-[#f5f3ff] hover:text-gray-900">
                            View history
                        </button>
                    </div>

                    <div class="mt-6 space-y-3">
                        <div class="flex gap-4 rounded-2xl border border-gray-100 bg-[#ffffff] p-4">
                            <div class="mt-1 h-10 w-1 rounded-full bg-brand-primary"></div>
                            <div>
                                <div class="font-semibold text-gray-800">Updated Dakong Balay description</div>
                                <p class="mt-1 text-sm text-gray-500">Refined the restaurant overview and highlighted Filipino cuisine offerings.</p>
                            </div>
                        </div>

                        <div class="flex gap-4 rounded-2xl border border-gray-100 bg-[#ffffff] p-4">
                            <div class="mt-1 h-10 w-1 rounded-full bg-emerald-500"></div>
                            <div>
                                <div class="font-semibold text-gray-800">Archived pool service</div>
                                <p class="mt-1 text-sm text-gray-500">Moved an outdated seasonal package to inactive records.</p>
                            </div>
                        </div>

                        <div class="flex gap-4 rounded-2xl border border-gray-100 bg-[#ffffff] p-4">
                            <div class="mt-1 h-10 w-1 rounded-full bg-sky-500"></div>
                            <div>
                                <div class="font-semibold text-gray-800">Synced chatbot knowledge base</div>
                                <p class="mt-1 text-sm text-gray-500">Aligned assistant responses with the newest business details.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card bg-base-100 shadow-sm">
                <div class="card-body p-6">
                    <h2 class="text-xl font-bold text-gray-800">Dashboard note</h2>
                    <p class="mt-1 text-sm text-gray-500">Quick reminders for maintaining accurate and consistent content.</p>

                    <div class="mt-5 space-y-4">
                        <div class="rounded-2xl bg-[#f8fafc] p-4">
                            <div class="font-semibold text-gray-800">Centralized business control</div>
                            <p class="mt-1 text-sm leading-6 text-gray-600">All business records are updated in one place to keep public pages aligned.</p>
                        </div>

                        <div class="rounded-2xl bg-[#f8fafc] p-4">
                            <div class="font-semibold text-gray-800">Chatbot-aware content</div>
                            <p class="mt-1 text-sm leading-6 text-gray-600">Keep descriptions concise so the assistant can answer quickly and clearly.</p>
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
                        <p class="mt-1 text-sm text-gray-500">View and edit the core business records shown across the platform.</p>
                    </div>
                    <button class="btn rounded-full border-0 bg-brand-primary text-white transition-colors duration-200 hover:bg-[#6d28d9] hover:text-white">
                        <svg viewBox="0 0 24 24" class="h-4 w-4 fill-current" aria-hidden="true">
                            <path d="M19 11H13V5h-2v6H5v2h6v6h2v-6h6z"/>
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
                                data-name="Dakong Balay"
                                data-category="Restaurant"
                                data-description="Authentic Filipino cuisine and family-style dining in a warm, inviting setting."
                                data-image="dakong-balay-cover.jpg"
                                data-status="active">
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="h-12 w-12 rounded-2xl bg-gradient-to-br from-brand-primary-light to-brand-border"></div>
                                        <div>
                                            <div class="font-semibold text-gray-800">Dakong Balay</div>
                                            <div class="text-sm text-gray-500">Restaurant and dining experience</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-gray-600">Restaurant</td>
                                <td class="max-w-[320px] truncate text-gray-600">Authentic Filipino cuisine and family-style dining in a warm, inviting setting.</td>
                                <td><span class="badge badge-success badge-outline">Active</span></td>
                                <td>
                                    <div class="flex justify-end">
                                        <a href="{{ route('admin.businesses.edit', 'dakong-balay') }}" class="btn btn-sm inline-flex items-center gap-2 rounded-full border border-gray-300 bg-white whitespace-nowrap text-gray-700 transition-colors duration-200 hover:border-brand-border hover:bg-[#f5f3ff] hover:text-gray-900">
                                            <svg viewBox="0 0 24 24" class="h-4 w-4 fill-current" aria-hidden="true">
                                                <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zm18-10.5a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                                            </svg>
                                            Edit
                                        </a>
                                    </div>
                                </td>
                            </tr>

                            <tr class="cursor-pointer hover:bg-base-200 transition-colors business-row border-l-4 border-transparent"
                                data-name="Monclaire Pool"
                                data-category="Swimming Pool"
                                data-description="A relaxing space for leisure, family gatherings, and refreshing weekend escapes."
                                data-image="monclaire-pool-cover.jpg"
                                data-status="active">
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="h-12 w-12 rounded-2xl bg-gradient-to-br from-[#D7E6EE] to-[#B8D0DD]"></div>
                                        <div>
                                            <div class="font-semibold text-gray-800">Monclaire Pool</div>
                                            <div class="text-sm text-gray-500">Swimming and leisure destination</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-gray-600">Swimming Pool</td>
                                <td class="max-w-[320px] truncate text-gray-600">A relaxing space for leisure, family gatherings, and refreshing weekend escapes.</td>
                                <td><span class="badge badge-success badge-outline">Active</span></td>
                                <td>
                                    <div class="flex justify-end">
                                        <a href="{{ route('admin.businesses.edit', 'monclaire-pool') }}" class="btn btn-sm inline-flex items-center gap-2 rounded-full border border-gray-300 bg-white whitespace-nowrap text-gray-700 transition-colors duration-200 hover:border-brand-border hover:bg-[#f5f3ff] hover:text-gray-900">
                                            <svg viewBox="0 0 24 24" class="h-4 w-4 fill-current" aria-hidden="true">
                                                <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zm18-10.5a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                                            </svg>
                                            Edit
                                        </a>
                                    </div>
                                </td>
                            </tr>

                            <tr class="cursor-pointer hover:bg-base-200 transition-colors business-row border-l-4 border-transparent"
                                data-name="Villa Carmelita"
                                data-category="Hotel / Villa"
                                data-description="Comfortable stay options with a welcoming ambiance for guests and groups."
                                data-image="villa-carmelita-cover.jpg"
                                data-status="active">
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="h-12 w-12 rounded-2xl bg-gradient-to-br from-[#EFE3D4] to-[#D8C1AA]"></div>
                                        <div>
                                            <div class="font-semibold text-gray-800">Villa Carmelita</div>
                                            <div class="text-sm text-gray-500">Stay and hospitality destination</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-gray-600">Hotel / Villa</td>
                                <td class="max-w-[320px] truncate text-gray-600">Comfortable stay options with a welcoming ambiance for guests and groups.</td>
                                <td><span class="badge badge-success badge-outline">Active</span></td>
                                <td>
                                    <div class="flex justify-end">
                                        <a href="{{ route('admin.businesses.edit', 'villa-carmelita') }}" class="btn btn-sm inline-flex items-center gap-2 rounded-full border border-gray-300 bg-white whitespace-nowrap text-gray-700 transition-colors duration-200 hover:border-brand-border hover:bg-[#f5f3ff] hover:text-gray-900">
                                            <svg viewBox="0 0 24 24" class="h-4 w-4 fill-current" aria-hidden="true">
                                                <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zm18-10.5a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
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
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 border-t border-gray-100 pt-4 mt-4">
                    <div class="text-xs text-gray-500">
                        Showing <strong>1</strong> to <strong>3</strong> of <strong>3</strong> businesses
                    </div>
                    <div class="join">
                        <button type="button" class="join-item btn btn-xs border border-gray-300 bg-white text-gray-700 transition-colors duration-200 hover:border-brand-border hover:bg-[#f5f3ff] hover:text-gray-900" disabled>«</button>
                        <button type="button" class="join-item btn btn-xs btn-active border-0 bg-brand-primary text-white transition-colors duration-200 hover:bg-[#6d28d9] hover:text-white">1</button>
                        <button type="button" class="join-item btn btn-xs border border-gray-300 bg-white text-gray-700 transition-colors duration-200 hover:border-brand-border hover:bg-[#f5f3ff] hover:text-gray-900" disabled>»</button>
                    </div>
                </div>
            </div>
        </section>

        <section id="edit-form-section" class="grid gap-6 lg:grid-cols-[2fr_1fr]">
            <div class="card bg-base-100 shadow-sm">
                <div class="card-body p-6">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-3">
                                <h2 class="text-xl font-bold text-gray-800">Edit Business Details</h2>
                                <span id="edit-business-badge" class="badge badge-success badge-outline">Active</span>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">Update the selected business record and keep chatbot content aligned.</p>
                        </div>
                    </div>

                    <form id="edit-business-form" class="mt-6 space-y-6">
                        <div class="grid gap-4 md:grid-cols-2">
                            <label class="form-control">
                                <div class="label">
                                    <span class="label-text font-medium text-gray-700">Business Name</span>
                                </div>
                                <input type="text" id="edit-business-name" value="Dakong Balay" class="input input-bordered bg-base-100">
                            </label>

                            <label class="form-control">
                                <div class="label">
                                    <span class="label-text font-medium text-gray-700">Category</span>
                                </div>
                                <select id="edit-business-category" class="select select-bordered bg-base-100">
                                    <option value="Restaurant">Restaurant</option>
                                    <option value="Swimming Pool">Swimming Pool</option>
                                    <option value="Hotel / Villa">Hotel / Villa</option>
                                </select>
                            </label>
                        </div>

                        <label class="form-control">
                            <div class="label">
                                <span class="label-text font-medium text-gray-700">Description</span>
                            </div>
                            <textarea id="edit-business-description" class="textarea textarea-bordered min-h-32 bg-base-100">Authentic Filipino cuisine and family-style dining in a warm, inviting setting.</textarea>
                        </label>

                        <div class="flex flex-col gap-3 rounded-2xl border border-dashed border-gray-300 bg-[#ffffff] p-4 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <div class="font-medium text-gray-800">Image</div>
                                <p id="edit-business-image-text" class="text-sm text-gray-500">dakong-balay-cover.jpg</p>
                            </div>
                            <button type="button" class="btn rounded-full border border-gray-300 bg-white text-gray-700 transition-colors duration-200 hover:border-brand-border hover:bg-[#f5f3ff] hover:text-gray-900">
                                Replace image
                            </button>
                        </div>

                        <div class="rounded-2xl bg-[#ffffff] p-4">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <div class="font-medium text-gray-800">Business Status</div>
                                    <p class="text-sm text-gray-500">Active businesses appear in the public platform and chatbot responses.</p>
                                </div>
                                <input type="checkbox" id="edit-business-status" class="toggle toggle-success" checked>
                            </div>
                        </div>

                        <div class="flex flex-col gap-3 sm:flex-row">
                            <button type="submit" class="btn rounded-full border-0 bg-brand-primary text-white transition-colors duration-200 hover:bg-[#6d28d9] hover:text-white">
                                Save changes
                            </button>
                            <button type="button" class="btn rounded-full border border-gray-300 bg-white text-gray-700 transition-colors duration-200 hover:border-brand-border hover:bg-[#f5f3ff] hover:text-gray-900">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="space-y-6">
                <div class="card bg-base-100 shadow-sm">
                    <div class="card-body p-6">
                        <h3 class="text-lg font-bold text-gray-800">Selected business summary</h3>
                        <div class="mt-4 space-y-3 text-sm text-gray-600">
                            <div class="flex items-center justify-between gap-4">
                                <span class="text-gray-500">Business Name</span>
                                <span id="summary-name" class="font-medium text-gray-800">Dakong Balay</span>
                            </div>
                            <div class="flex items-center justify-between gap-4">
                                <span class="text-gray-500">Type</span>
                                <span id="summary-category" class="font-medium text-gray-800">Restaurant</span>
                            </div>
                            <div class="flex items-center justify-between gap-4">
                                <span class="text-gray-500">Visibility</span>
                                <span id="summary-visibility" class="font-medium text-emerald-600">Currently visible to users</span>
                            </div>
                            <div class="flex items-center justify-between gap-4">
                                <span class="text-gray-500">Last editor</span>
                                <span class="font-medium text-gray-800">Admin User</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card bg-base-100 shadow-sm">
                    <div class="card-body p-6">
                        <h3 class="text-lg font-bold text-gray-800">Editing guidelines</h3>
                        <div class="mt-4 rounded-2xl bg-[#f8fafc] p-4 text-sm leading-6 text-gray-600">
                            Keep business descriptions short, accurate, and chatbot-friendly. Use consistent naming, clear service categories, and updated images for the best user experience.
                        </div>
                    </div>
                </div>

                <div class="rounded-3xl bg-emerald-600 p-6 text-white shadow-sm">
                    <h3 class="text-lg font-bold">Active status reminder</h3>
                    <p class="mt-2 text-sm leading-6 text-white/90">
                        Active businesses are searchable on the platform and may be referenced by the AI assistant in customer queries.
                    </p>
                </div>
            </div>
        </section>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const rows = document.querySelectorAll('.business-row');
            const formName = document.querySelector('#edit-business-name');
            const formCategory = document.querySelector('#edit-business-category');
            const formDescription = document.querySelector('#edit-business-description');
            const formImageText = document.querySelector('#edit-business-image-text');
            const formStatus = document.querySelector('#edit-business-status');
            const formBadge = document.querySelector('#edit-business-badge');
            
            // Summary elements
            const summaryName = document.querySelector('#summary-name');
            const summaryCategory = document.querySelector('#summary-category');
            const summaryVisibility = document.querySelector('#summary-visibility');

            // Set initial highlight for the first row (Dakong Balay)
            if (rows.length > 0) {
                rows[0].classList.add('bg-[#ffffff]', 'border-l-brand-primary');
            }

            rows.forEach(row => {
                row.addEventListener('click', (e) => {
                    // Ignore clicks if they were on the Edit link button itself
                    if (e.target.closest('a')) {
                        return;
                    }

                    const name = row.getAttribute('data-name');
                    const category = row.getAttribute('data-category');
                    const description = row.getAttribute('data-description');
                    const image = row.getAttribute('data-image');
                    const status = row.getAttribute('data-status');

                    // Update form fields
                    if (formName) formName.value = name;
                    if (formCategory) formCategory.value = category;
                    if (formDescription) formDescription.value = description;
                    if (formImageText) formImageText.textContent = image;
                    if (formStatus) formStatus.checked = (status === 'active');
                    
                    // Update badge
                    if (formBadge) {
                        formBadge.textContent = status.charAt(0).toUpperCase() + status.slice(1);
                        if (status === 'active') {
                            formBadge.className = 'badge badge-success badge-outline';
                        } else {
                            formBadge.className = 'badge badge-warning badge-outline';
                        }
                    }

                    // Update summary card
                    if (summaryName) summaryName.textContent = name;
                    if (summaryCategory) summaryCategory.textContent = category;
                    if (summaryVisibility) {
                        if (status === 'active') {
                            summaryVisibility.textContent = 'Currently visible to users';
                            summaryVisibility.className = 'font-medium text-emerald-600';
                        } else {
                            summaryVisibility.textContent = 'Hidden from users';
                            summaryVisibility.className = 'font-medium text-amber-600';
                        }
                    }

                    // Smooth scroll to form on small screens
                    const formSection = document.querySelector('#edit-form-section');
                    if (formSection && window.innerWidth < 1024) {
                        formSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }

                    // Highlight selected row
                    rows.forEach(r => {
                        r.classList.remove('bg-[#ffffff]', 'border-l-brand-primary');
                        r.classList.add('border-transparent');
                    });
                    row.classList.remove('border-transparent');
                    row.classList.add('bg-[#ffffff]', 'border-l-brand-primary');
                });
            });
            
            // Handle form submit simulation
            const editForm = document.querySelector('#edit-business-form');
            if (editForm) {
                editForm.addEventListener('submit', (e) => {
                    e.preventDefault();
                    
                    // Create visual toast notification
                    const toast = document.createElement('div');
                    toast.className = 'fixed bottom-4 right-4 z-50';
                    toast.innerHTML = `
                        <div class="alert alert-success bg-brand-primary text-white border-0 shadow-2xl rounded-2xl p-4 flex items-center gap-3">
                            <svg class="h-6 w-6 shrink-0 stroke-current text-white" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <div>
                                <span class="font-bold">Success!</span>
                                <div class="text-xs text-white/80">Changes saved for ${formName.value}.</div>
                            </div>
                        </div>
                    `;
                    document.body.appendChild(toast);
                    setTimeout(() => {
                        toast.classList.add('opacity-0', 'transition-opacity', 'duration-500');
                        setTimeout(() => toast.remove(), 500);
                    }, 3000);
                });
            }
        });
    </script>
@endsection