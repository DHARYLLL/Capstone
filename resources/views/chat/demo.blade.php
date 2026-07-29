<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Embeddable Chat Widget Showcase</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                },
            },
        }
    </script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css" rel="stylesheet" type="text/css" />
</head>
@php
    $unitName = 'DARIV Waterproofing';
    $unitDesc = 'Specialized in professional waterproofing, roof deck sealants, balconies, foundations, and leak prevention.';
    $bgGradient = 'from-sky-500 to-indigo-600';
    $selectedBusiness = 'dariv';
@endphp
<body class="bg-[#f8fafc] font-sans text-gray-800 antialiased min-h-screen flex flex-col">

    <!-- Header Navigation -->
    <header class="bg-white border-b border-gray-200/80 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-900 p-1.5 text-white">
                    <img src="{{ asset('images/logo.svg') }}" alt="DARIV Logo" class="h-full w-full object-contain filter invert">
                </span>
                <div>
                    <h1 class="text-base font-extrabold tracking-tight text-gray-900 font-sans">DARIV Chat Widget</h1>
                    <p class="text-[10px] font-semibold text-violet-600 uppercase tracking-widest">Integration Console</p>
                </div>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline rounded-xl font-semibold border-slate-200 text-slate-700 hover:bg-slate-50 hover:text-slate-900">
                    Go to Admin Panel
                </a>
            </div>
        </div>
    </header>

    <!-- Main Workspace -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-6 py-10 grid lg:grid-cols-[1.1fr_0.9fr] gap-10">
        
        <!-- Left Side: Interactive Playground / Instruction Guide -->
        <div class="space-y-8">
            <div class="rounded-[2rem] border border-[#e2e8f0] bg-white p-6 shadow-sm lg:p-8">
                <span class="badge bg-violet-100 text-violet-700 font-bold border-0 px-3.5 py-2.5 text-xs mb-4">WIDGET INTEGRATION</span>
                <h2 class="text-3xl font-black tracking-tight text-gray-900 leading-tight">Embed Chat on Your Website</h2>
                <p class="mt-3 text-slate-500 text-sm leading-relaxed">
                    Now that you have your own website, you only need this platform's live chat system. Copy the single-line script integration below and paste it into the HTML of your existing website to start chatting with customers immediately!
                </p>
            </div>

            <!-- Demo Selector Tabs -->
            <div class="card bg-white border border-[#e2e8f0] shadow-sm rounded-[2rem] overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="font-bold text-gray-900">1. Integration Parameters</h3>
                    <p class="text-xs text-gray-500 mt-1">Configure widget parameters for your single-company setup.</p>
                </div>

                <div class="p-6 space-y-6">
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-gray-400">Company Information</h4>
                        <div class="mt-2 p-4 bg-gray-50 rounded-2xl border border-gray-100">
                            <span class="text-sm font-extrabold text-gray-900 block">{{ $unitName }}</span>
                            <span class="text-xs text-slate-500 leading-relaxed block mt-1">{{ $unitDesc }}</span>
                        </div>
                    </div>

                    <!-- Code Snippet -->
                    <div>
                        <h3 class="font-bold text-gray-900 text-sm">2. Copy script tag</h3>
                        <p class="text-xs text-gray-500 mt-1">Add this snippet right before the closing <code>&lt;/body&gt;</code> tag on your website.</p>
                        
                        <div class="relative mt-3">
                            <pre class="bg-slate-900 text-slate-100 p-5 rounded-2xl text-xs overflow-x-auto font-mono select-all leading-5"><code>&lt;!-- DARIV Waterproofing Live Chat Widget --&gt;
&lt;script 
    src="{{ asset('js/chat-widget.js') }}" 
    data-business="dariv"&gt;
&lt;/script&gt;</code></pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Preview Frame -->
        <div class="space-y-6">
            <div class="sticky top-28">
                <div class="rounded-[2.5rem] border-8 border-slate-900/90 shadow-2xl bg-white aspect-[9/16] max-w-[340px] mx-auto overflow-hidden relative">
                    <!-- Phone status bar -->
                    <div class="bg-slate-900 text-white text-[10px] px-6 py-2.5 flex justify-between items-center font-bold tracking-tight">
                        <span>12:30</span>
                        <div class="flex gap-1.5 items-center">
                            <span>5G</span>
                            <span class="inline-block w-4 h-2.5 bg-white rounded-sm border border-slate-900"></span>
                        </div>
                    </div>

                    <!-- Phone Content (Simulating Customer Website inside Iframe) -->
                    <div class="h-full pb-10">
                        <iframe src="{{ route('chat.playground', ['business' => $selectedBusiness]) }}" class="w-full h-full border-0" style="background: white;"></iframe>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <footer class="bg-white border-t border-gray-100 py-6 mt-12 text-center text-xs text-gray-500">
        Project RED AI Chat System &middot; &copy; {{ date('Y') }} Capstone.
    </footer>

</body>
</html>
