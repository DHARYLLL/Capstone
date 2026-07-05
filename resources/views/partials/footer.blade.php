{{-- filepath: c:\Users\dhary\Desktop\Capstone\capstone1\resources\views\partials\footer.blade.php --}}
@php
    $footerBusinessName = data_get($business ?? null, 'name', 'Project RED AI');
    $footerBusinessTagline = data_get($business ?? null, 'tagline', 'AI-Powered Business Platform');
    $footerBusinessDescription = data_get(
        $business ?? null,
        'description',
        'Explore services, locations, offers, and FAQs through one intelligent customer experience.'
    );
    $footerBusinesses = collect($footerBusinesses ?? [
        ['label' => 'Business One', 'href' => '#businesses'],
        ['label' => 'Business Two', 'href' => '#businesses'],
        ['label' => 'Business Three', 'href' => '#businesses'],
    ]);
@endphp

<footer class="border-t border-[#e2e8f0] bg-[#ffffff]">
    <div class="mx-auto max-w-7xl px-4 py-12 lg:px-8">
        <div class="grid gap-10 md:grid-cols-2 lg:grid-cols-4">
            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-brand-primary text-white">
                        <svg viewBox="0 0 24 24" class="h-5 w-5 fill-current" aria-hidden="true">
                            <path d="M12 2l8 4v12l-8 4-8-4V6l8-4z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="font-extrabold text-gray-900">{{ $footerBusinessName }}</div>
                        <div class="text-xs text-gray-500">{{ $footerBusinessTagline }}</div>
                    </div>
                </div>
                <p class="max-w-sm text-sm leading-6 text-gray-600">
                    {{ $footerBusinessDescription }}
                </p>
            </div>

            <div>
                <h4 class="mb-4 font-bold text-gray-900">Navigation</h4>
                <ul class="space-y-3 text-sm text-gray-600">
                    <li><a href="#home" class="hover:text-brand-primary">Home</a></li>
                    <li><a href="#businesses" class="hover:text-brand-primary">Our Businesses</a></li>
                    <li><a href="#about" class="hover:text-brand-primary">About Us</a></li>
                    <li><a href="#gallery" class="hover:text-brand-primary">Gallery</a></li>
                </ul>
            </div>

            <div>
                <h4 class="mb-4 font-bold text-gray-900">Businesses</h4>
                <ul class="space-y-3 text-sm text-gray-600">
                    @foreach ($footerBusinesses as $footerBusiness)
                        <li><a href="{{ $footerBusiness['href'] ?? '#businesses' }}" class="hover:text-brand-primary">{{ $footerBusiness['label'] ?? 'Business' }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div>
                <h4 class="mb-4 font-bold text-gray-900">Contact</h4>
                <ul class="space-y-3 text-sm text-gray-600">
                    <li>Email: info@projectredai.com</li>
                    <li>Phone: +63 900 000 0000</li>
                    <li>Location: Centralized Business Hub</li>
                </ul>
            </div>
        </div>

        <div class="mt-10 border-t border-[#e2e8f0] pt-6 text-sm text-gray-500">
            © {{ date('Y') }} Project RED AI. All rights reserved.
        </div>
    </div>
</footer>
