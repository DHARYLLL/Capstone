{{-- filepath: resources/views/chat/playground.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        ::-webkit-scrollbar { display: none; }
    </style>
</head>
@php
    $slug = request('business', 'dariv');
    if ($slug === 'dariv') {
        $unitName = 'DARIV Waterproofing';
        $bgGradient = 'from-sky-500 to-indigo-600';
    } elseif ($slug === 'hydroguard') {
        $unitName = 'HydroGuard Solutions';
        $bgGradient = 'from-teal-500 to-emerald-600';
    } else {
        $unitName = 'DryMax Sealants';
        $bgGradient = 'from-violet-500 to-fuchsia-600';
    }
@endphp
<body class="bg-white text-gray-800 antialiased min-h-screen flex flex-col justify-between overflow-x-hidden">
    <div>
        <!-- Hero Banner -->
        <div class="bg-gradient-to-br {{ $bgGradient }} p-6 text-white pt-8 pb-10">
            <div class="text-[10px] uppercase font-extrabold tracking-widest bg-white/20 px-2 py-0.5 rounded-full inline-block">Official Website</div>
            <h2 class="text-xl font-black mt-2 leading-tight">{{ $unitName }}</h2>
            <p class="text-white/80 text-[10px] leading-relaxed mt-1.5">Professional waterproofing services. We seal roofs, basements, balconies, and bathrooms with a long-term warranty.</p>
        </div>

        <!-- Content Cards -->
        <div class="p-4 space-y-4">
            <div class="bg-slate-50 border border-slate-200/60 p-4 rounded-2xl">
                <span class="text-xs font-bold text-slate-700 block">⭐ Client Reviews</span>
                <p class="text-[11px] text-slate-500 mt-1 leading-relaxed">"Highly recommended! The team successfully stopped a persistent roof deck leak that had bothered us for years."</p>
            </div>
            <div class="bg-slate-50 border border-slate-200/60 p-4 rounded-2xl">
                <span class="text-xs font-bold text-slate-700 block">📍 Location</span>
                <p class="text-[11px] text-slate-500 mt-1 leading-relaxed">Serving Cebu City and nearby municipalities. Book a site inspection today.</p>
            </div>
        </div>
    </div>

    <!-- Sticky Footer -->
    <div class="bg-slate-50 border-t border-slate-200/60 p-4 text-center text-[10px] text-slate-400 mt-auto">
        &copy; {{ date('Y') }} {{ $unitName }}. Powered by Project RED.
    </div>

    <!-- Injected widget -->
    {{-- <script src="{{ asset('js/chat-widget.js') }}" data-business="{{ $slug }}"></script> --}}
    <script src="{{ asset('js/chat-widget.js') }}" data-business="{{ $businessUnit->id ?? 1 }}"></script>
</body>
</html>
