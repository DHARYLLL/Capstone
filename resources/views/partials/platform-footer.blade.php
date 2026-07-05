{{-- filepath: resources/views/partials/platform-footer.blade.php --}}
<footer class="border-t border-[#e2e8f0] bg-[#ffffff]">
    <div class="mx-auto max-w-7xl px-4 py-12 lg:px-8">
        <div class="grid gap-10 md:grid-cols-2 lg:grid-cols-4">
            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-brand-primary text-white">
                        <svg viewBox="0 0 24 24" class="h-5 w-5 fill-current" aria-hidden="true">
                            <path d="M12 2l8 4v12l-8 4-8-4V6l8-4z" />
                        </svg>
                    </div>
                    <div>
                        <div class="font-extrabold text-gray-900">Project RED AI</div>
                        <div class="text-xs text-gray-500">AI Business Support SaaS</div>
                    </div>
                </div>
                <p class="max-w-sm text-sm leading-6 text-gray-600">
                    Launch AI customer support with document ingestion, centralized knowledge, and staff handoff for growing
                    business groups.
                </p>
            </div>

            <div>
                <h4 class="mb-4 font-bold text-gray-900">Platform</h4>
                <ul class="space-y-3 text-sm text-gray-600">
                    <li><a href="#home" class="hover:text-brand-primary">Home</a></li>
                    <li><a href="#feature-tour" class="hover:text-brand-primary">Feature Tour</a></li>
                    <li><a href="#pricing" class="hover:text-brand-primary">Pricing</a></li>
                    <li><a href="#register" class="hover:text-brand-primary">Register</a></li>
                </ul>
            </div>

            <div>
                <h4 class="mb-4 font-bold text-gray-900">SaaS Plans</h4>
                <ul class="space-y-3 text-sm text-gray-600">
                    <li><a href="#pricing" class="hover:text-brand-primary">Free - up to 1 business</a></li>
                    <li><a href="#pricing" class="hover:text-brand-primary">Growth - up to 3 businesses</a></li>
                    <li><a href="#pricing" class="hover:text-brand-primary">Scale - up to 5 businesses</a></li>
                </ul>
            </div>

            <div>
                <h4 class="mb-4 font-bold text-gray-900">Included</h4>
                <ul class="space-y-3 text-sm text-gray-600">
                    <li>PDF and CSV ingestion</li>
                    <li>Staff handoff terminal</li>
                    <li>Centralized AI knowledge</li>
                </ul>
            </div>
        </div>

        <div class="mt-10 border-t border-[#e2e8f0] pt-6 text-sm text-gray-500">
            © {{ date('Y') }} Project RED AI. All rights reserved.
        </div>
    </div>
</footer>
