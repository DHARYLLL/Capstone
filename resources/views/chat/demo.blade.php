{{-- filepath: resources/views/chat/demo.blade.php --}}
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Widget Developer Sandbox & Live Simulator | RED AI</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    },
                },
            },
        }
    </script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css" rel="stylesheet" type="text/css" />
    <style>
        .device-transition {
            transition: max-width 0.4s cubic-bezier(0.16, 1, 0.3, 1), height 0.4s cubic-bezier(0.16, 1, 0.3, 1), transform 0.3s ease;
        }
    </style>
</head>
@php
    $company = $company ?? \App\Models\Company::first();
    $companyKey = $company?->api_key ?? 'pk_live_' . str_repeat('x', 24);
    $companyName = $company?->name ?? 'DARIV Waterproofing';
    $businessUnitName = $businessUnit?->name ?? 'Residential & Commercial';
    $scriptUrl = url('/js/chat-widget.js');
@endphp
<body class="bg-slate-950 font-sans text-slate-100 antialiased min-h-screen flex flex-col selection:bg-violet-600 selection:text-white">

    <!-- Top Developer Header Bar -->
    <header class="bg-slate-900/90 border-b border-slate-800/80 sticky top-0 z-50 backdrop-blur-xl">
        <div class="max-w-[1800px] mx-auto px-4 sm:px-6 py-3 flex items-center justify-between gap-4">
            
            <!-- Left: Brand & Status -->
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.dashboard') }}" class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-tr from-violet-600 to-indigo-600 text-white shadow-md hover:scale-105 transition">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                    </svg>
                </a>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-sm font-black tracking-tight text-white font-sans">RED AI Widget Sandbox</h1>
                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-500/10 px-2 py-0.5 text-[10px] font-bold text-emerald-400 ring-1 ring-emerald-500/30">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            Live Engine Active
                        </span>
                    </div>
                    <p class="text-[11px] text-slate-400 font-medium truncate">
                        {{ $companyName }} &middot; <span class="text-violet-400">{{ $businessUnitName }}</span>
                    </p>
                </div>
            </div>

            <!-- Right: Actions -->
            <div class="flex items-center gap-2.5">
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 rounded-xl bg-slate-800/80 border border-slate-700/80 px-3.5 py-2 text-xs font-semibold text-slate-200 hover:bg-slate-800 hover:text-white transition shadow-xs">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                    </svg>
                    <span>Admin Dashboard</span>
                </a>
                <a href="{{ route('admin.chat') }}" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-violet-600 to-indigo-600 px-3.5 py-2 text-xs font-bold text-white hover:from-violet-500 hover:to-indigo-500 transition shadow-md shadow-violet-900/30">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                    </svg>
                    <span>Operator Queue</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Sandbox Workspace -->
    <main class="flex-1 max-w-[1800px] w-full mx-auto p-4 sm:p-6 grid lg:grid-cols-[460px_1fr] xl:grid-cols-[500px_1fr] gap-6 items-start">
        
        <!-- Left Side: Code Integration & Sandbox Controls -->
        <div class="space-y-5">
            
            <!-- Quick Embed Code Card -->
            <div class="rounded-3xl border border-slate-800 bg-slate-900/80 p-5 sm:p-6 shadow-xl relative overflow-hidden backdrop-blur-md">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-violet-500/10 text-violet-400 ring-1 ring-violet-500/20">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75 22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3-4.5 16.5" />
                            </svg>
                        </div>
                        <h2 class="text-sm font-bold text-white">Embed Script Tag</h2>
                    </div>
                    <span class="rounded-full bg-violet-500/10 px-2.5 py-0.5 text-[10px] font-bold text-violet-400 ring-1 ring-violet-500/30">
                        1-Line Integration
                    </span>
                </div>

                <p class="text-xs text-slate-400 leading-relaxed mb-3">
                    Paste this snippet directly before the closing <code class="text-violet-300 font-mono text-[11px] bg-violet-950/60 px-1.5 py-0.5 rounded">&lt;/body&gt;</code> tag on any website:
                </p>

                <!-- Code Container with Copy Button -->
                <div class="relative group">
                    <pre class="bg-slate-950/90 border border-slate-800 rounded-2xl p-4 text-xs font-mono text-slate-200 overflow-x-auto leading-relaxed scrollbar-none"><code id="embed-script-code">&lt;!-- Project RED AI Chat Widget --&gt;
&lt;script 
    src="{{ $scriptUrl }}" 
    data-company-key="{{ $companyKey }}" 
    async&gt;
&lt;/script&gt;</code></pre>
                    <button id="copy-script-btn" onclick="copySnippet('embed-script-code', this)" class="absolute top-2.5 right-2.5 flex items-center gap-1.5 rounded-xl bg-slate-800/90 hover:bg-violet-600 border border-slate-700 hover:border-violet-500 px-3 py-1.5 text-xs font-bold text-slate-200 hover:text-white transition shadow-md">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0 0 13.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 0 1-.75.75H9a.75.75 0 0 1-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 0 1-2.25 2.25H6.75A2.25 2.25 0 0 1 4.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 0 1 1.927-.184" />
                        </svg>
                        <span>Copy Code</span>
                    </button>
                </div>

                <!-- API Key Card -->
                <div class="mt-4 rounded-2xl bg-slate-950/60 border border-slate-800/70 p-3.5 flex items-center justify-between gap-3">
                    <div class="min-w-0">
                        <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Production Tenant Key</div>
                        <div id="masked-api-key" class="text-xs font-mono font-semibold text-violet-300 truncate mt-0.5">
                            {{ substr($companyKey, 0, 10) }}••••••••••••••••
                        </div>
                    </div>
                    <button onclick="copyToClipboard('{{ $companyKey }}', this)" class="shrink-0 rounded-xl bg-slate-800 hover:bg-slate-700 px-3 py-1.5 text-xs font-semibold text-slate-300 hover:text-white transition">
                        Copy Key
                    </button>
                </div>
            </div>

            <!-- Interactive Test Triggers & Scenarios -->
            <div class="rounded-3xl border border-slate-800 bg-slate-900/80 p-5 sm:p-6 shadow-xl backdrop-blur-md space-y-5">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-indigo-500/10 text-indigo-400 ring-1 ring-indigo-500/20">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                            </svg>
                        </div>
                        <h2 class="text-sm font-bold text-white">Interactive Test Tools</h2>
                    </div>
                </div>

                <!-- Remote Simulator Actions -->
                <div class="space-y-2.5">
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400">
                        Test Widget Triggers
                    </label>
                    <div class="grid sm:grid-cols-2 gap-2">
                        <button onclick="triggerRemoteWidgetToggle()" class="w-full flex items-center justify-center gap-2 rounded-xl bg-violet-600/20 hover:bg-violet-600/30 border border-violet-500/30 py-2.5 px-3 text-xs font-bold text-violet-300 hover:text-white transition">
                            <span>💬 Toggle Widget</span>
                        </button>
                        <button onclick="reloadSimulatorFrame()" class="w-full flex items-center justify-center gap-2 rounded-xl bg-slate-800 hover:bg-slate-700 py-2.5 px-3 text-xs font-bold text-slate-200 hover:text-white transition">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                            </svg>
                            <span>Reload Frame</span>
                        </button>
                    </div>
                </div>

                <!-- Prompt Scenario Quick Chips -->
                <div class="pt-3 border-t border-slate-800">
                    <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-2">
                        Sample Customer Questions
                    </div>
                    <div class="flex flex-wrap gap-1.5">
                        <button onclick="copyPrompt('Do you provide 10-year waterproofing warranty?')" class="rounded-lg bg-slate-950 hover:bg-violet-900/40 border border-slate-800 hover:border-violet-500/40 px-2.5 py-1.5 text-[11px] text-slate-300 hover:text-white transition text-left">
                            🛡️ 10-Year Warranty query
                        </button>
                        <button onclick="copyPrompt('How much is roof deck waterproofing per square meter?')" class="rounded-lg bg-slate-950 hover:bg-violet-900/40 border border-slate-800 hover:border-violet-500/40 px-2.5 py-1.5 text-[11px] text-slate-300 hover:text-white transition text-left">
                            💵 Pricing & quote query
                        </button>
                        <button onclick="copyPrompt('I would like to speak with a human operator.')" class="rounded-lg bg-slate-950 hover:bg-violet-900/40 border border-slate-800 hover:border-violet-500/40 px-2.5 py-1.5 text-[11px] text-slate-300 hover:text-white transition text-left">
                            🙋 Escalation to operator
                        </button>
                    </div>
                </div>
            </div>

            <!-- Framework Tabs Quick Guide -->
            <div class="rounded-3xl border border-slate-800 bg-slate-900/80 p-5 shadow-xl backdrop-blur-md">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">
                    Multi-Platform Deployment
                </h3>
                <div class="grid grid-cols-3 gap-2 text-center text-xs">
                    <div class="rounded-xl bg-slate-950 p-3 border border-slate-800">
                        <div class="text-base mb-1">⚡</div>
                        <div class="font-bold text-white text-[11px]">HTML / Blade</div>
                        <div class="text-[10px] text-slate-400 mt-0.5">Native script</div>
                    </div>
                    <div class="rounded-xl bg-slate-950 p-3 border border-slate-800">
                        <div class="text-base mb-1">⚛️</div>
                        <div class="font-bold text-white text-[11px]">React / Next.js</div>
                        <div class="text-[10px] text-slate-400 mt-0.5">&lt;Script&gt; tag</div>
                    </div>
                    <div class="rounded-xl bg-slate-950 p-3 border border-slate-800">
                        <div class="text-base mb-1">🌐</div>
                        <div class="font-bold text-white text-[11px]">WordPress</div>
                        <div class="text-[10px] text-slate-400 mt-0.5">Footer script</div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Right Side: Responsive Live Device Simulator -->
        <div class="space-y-4">
            
            <!-- Device Switcher Bar -->
            <div class="flex flex-wrap items-center justify-between gap-3 bg-slate-900/90 border border-slate-800 rounded-2xl p-2.5 backdrop-blur-md shadow-lg">
                <!-- Device Mode Buttons -->
                <div class="flex items-center gap-1.5 bg-slate-950 p-1 rounded-xl border border-slate-800/80">
                    <button id="btn-mobile" onclick="switchDevice('mobile')" class="device-btn flex items-center gap-2 rounded-lg bg-violet-600 px-3 py-1.5 text-xs font-bold text-white shadow-xs transition">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                        </svg>
                        <span>Mobile (380px)</span>
                    </button>
                    <button id="btn-tablet" onclick="switchDevice('tablet')" class="device-btn flex items-center gap-2 rounded-lg hover:bg-slate-800 px-3 py-1.5 text-xs font-semibold text-slate-400 hover:text-white transition">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5h3m-6.75 2.25h10.5a2.25 2.25 0 0 0 2.25-2.25V4.5a2.25 2.25 0 0 0-2.25-2.25H6.75A2.25 2.25 0 0 0 4.5 4.5v15a2.25 2.25 0 0 0 2.25 2.25Z" />
                        </svg>
                        <span>Tablet (640px)</span>
                    </button>
                    <button id="btn-desktop" onclick="switchDevice('desktop')" class="device-btn flex items-center gap-2 rounded-lg hover:bg-slate-800 px-3 py-1.5 text-xs font-semibold text-slate-400 hover:text-white transition">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25m18 0A2.25 2.25 0 0 0 18.75 3H5.25A2.25 2.25 0 0 0 3 5.25m18 0H3" />
                        </svg>
                        <span>Desktop (Full)</span>
                    </button>
                </div>

                <!-- URL Bar Simulator -->
                <div class="hidden sm:flex items-center gap-2 text-xs text-slate-400 font-mono bg-slate-950 px-3 py-1.5 rounded-xl border border-slate-800">
                    <span class="text-emerald-400">🔒</span>
                    <span id="simulator-url-display" class="truncate max-w-[280px]">https://dariv.com/official-site</span>
                </div>

                <!-- Live Indicator -->
                <div class="flex items-center gap-2">
                    <button onclick="reloadSimulatorFrame()" class="p-1.5 text-slate-400 hover:text-white hover:bg-slate-800 rounded-lg transition" title="Refresh frame">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Device Frame Container -->
            <div class="flex justify-center items-center py-2">
                <div id="device-wrapper" class="device-transition w-full max-w-[380px] rounded-[2.5rem] border-[10px] border-slate-900 shadow-2xl bg-slate-900 overflow-hidden relative" style="height: 740px;">
                    
                    <!-- Mobile Status Bar (Visible on mobile & tablet mode) -->
                    <div id="mobile-status-bar" class="bg-slate-900 text-white text-[10px] px-6 py-2.5 flex justify-between items-center font-bold tracking-tight select-none">
                        <span id="live-clock">12:30</span>
                        <div class="flex items-center gap-1.5">
                            <div class="h-2 w-12 bg-black rounded-full mx-2"></div>
                            <span>5G</span>
                            <div class="w-4 h-2.5 border border-white/80 rounded-xs p-0.5 flex items-center">
                                <div class="w-full h-full bg-white rounded-2xs"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Desktop Browser Bar (Visible on desktop mode) -->
                    <div id="desktop-browser-bar" class="hidden bg-slate-900 border-b border-slate-800 px-4 py-2.5 flex items-center justify-between select-none">
                        <div class="flex items-center gap-1.5">
                            <div class="h-3 w-3 rounded-full bg-rose-500/80"></div>
                            <div class="h-3 w-3 rounded-full bg-amber-500/80"></div>
                            <div class="h-3 w-3 rounded-full bg-emerald-500/80"></div>
                        </div>
                        <div class="bg-slate-950 rounded-lg px-4 py-1 text-[11px] text-slate-300 font-mono border border-slate-800/80 flex items-center gap-2">
                            <span class="text-emerald-400">🔒</span>
                            <span>https://dariv-waterproofing.com</span>
                        </div>
                        <div class="text-slate-600 text-xs font-mono font-bold">DEV SIMULATOR</div>
                    </div>

                    <!-- Frame Content (Simulating Client Website) -->
                    <div class="h-[calc(100%-36px)] w-full bg-white overflow-hidden relative">
                        <iframe 
                            id="preview-frame" 
                            src="{{ route('chat.playground', ['api_key' => $companyKey]) }}" 
                            class="w-full h-full border-0 bg-white"
                            title="Mock Client Website Preview">
                        </iframe>
                    </div>
                </div>
            </div>

        </div>

    </main>

    <!-- Global Toast Alert Container -->
    <div id="toast-container" class="fixed bottom-6 right-6 z-50 pointer-events-none flex flex-col gap-2"></div>

    <script>
        // Update Mobile Clock
        function updateClock() {
            const now = new Date();
            const timeStr = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            const clockEl = document.getElementById('live-clock');
            if (clockEl) clockEl.textContent = timeStr;
        }
        updateClock();
        setInterval(updateClock, 30000);

        // Switch Simulator Device Dimensions
        function switchDevice(mode) {
            const wrapper = document.getElementById('device-wrapper');
            const mobileBar = document.getElementById('mobile-status-bar');
            const desktopBar = document.getElementById('desktop-browser-bar');
            const buttons = document.querySelectorAll('.device-btn');

            buttons.forEach(btn => {
                btn.className = 'device-btn flex items-center gap-2 rounded-lg hover:bg-slate-800 px-3 py-1.5 text-xs font-semibold text-slate-400 hover:text-white transition';
            });

            const activeBtn = document.getElementById(`btn-${mode}`);
            if (activeBtn) {
                activeBtn.className = 'device-btn flex items-center gap-2 rounded-lg bg-violet-600 px-3 py-1.5 text-xs font-bold text-white shadow-xs transition';
            }

            if (mode === 'mobile') {
                wrapper.style.maxWidth = '380px';
                wrapper.style.height = '740px';
                wrapper.style.borderRadius = '2.5rem';
                wrapper.style.borderWidth = '10px';
                mobileBar.classList.remove('hidden');
                desktopBar.classList.add('hidden');
            } else if (mode === 'tablet') {
                wrapper.style.maxWidth = '640px';
                wrapper.style.height = '780px';
                wrapper.style.borderRadius = '2rem';
                wrapper.style.borderWidth = '10px';
                mobileBar.classList.remove('hidden');
                desktopBar.classList.add('hidden');
            } else if (mode === 'desktop') {
                wrapper.style.maxWidth = '100%';
                wrapper.style.height = '800px';
                wrapper.style.borderRadius = '1rem';
                wrapper.style.borderWidth = '2px';
                mobileBar.classList.add('hidden');
                desktopBar.classList.remove('hidden');
            }
        }

        // Reload Frame
        function reloadSimulatorFrame() {
            const iframe = document.getElementById('preview-frame');
            iframe.src = iframe.src;
            showToast('Simulator frame reloaded', 'info');
        }

        // Trigger remote widget toggle inside iframe
        function triggerRemoteWidgetToggle() {
            const iframe = document.getElementById('preview-frame');
            if (iframe && iframe.contentWindow) {
                iframe.contentWindow.postMessage({ action: 'toggleChat' }, '*');
                showToast('Triggered widget launcher!', 'success');
            }
        }

        // Listen for message events from iframe
        window.addEventListener('message', function(event) {
            if (event.data && event.data.action === 'triggerChat') {
                triggerRemoteWidgetToggle();
            }
        });

        // Copy Helper
        function copySnippet(elementId, btn) {
            const code = document.getElementById(elementId).innerText;
            navigator.clipboard.writeText(code).then(() => {
                const originalHtml = btn.innerHTML;
                btn.innerHTML = `
                    <svg class="h-3.5 w-3.5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                    </svg>
                    <span class="text-emerald-300">Copied!</span>
                `;
                setTimeout(() => { btn.innerHTML = originalHtml; }, 2000);
                showToast('Snippet copied to clipboard!', 'success');
            });
        }

        function copyToClipboard(text, btn) {
            navigator.clipboard.writeText(text).then(() => {
                if (btn) {
                    const orig = btn.innerText;
                    btn.innerText = 'Copied!';
                    setTimeout(() => { btn.innerText = orig; }, 2000);
                }
                showToast('API Key copied to clipboard!', 'success');
            });
        }

        function copyPrompt(text) {
            navigator.clipboard.writeText(text).then(() => {
                showToast(`Copied question: "${text}"`, 'info');
            });
        }

        // Toast Notification System
        function showToast(message, type = 'info') {
            const container = document.getElementById('toast-container');
            if (!container) return;

            const toast = document.createElement('div');
            const bgClass = type === 'success' ? 'bg-emerald-600' : 'bg-violet-600';
            toast.className = `pointer-events-auto flex items-center gap-2 rounded-2xl ${bgClass} text-white px-4 py-3 text-xs font-bold shadow-xl animate-in fade-in slide-in-from-bottom-3 duration-200`;
            toast.innerHTML = `
                <span>${message}</span>
            `;

            container.appendChild(toast);
            setTimeout(() => {
                toast.classList.add('opacity-0', 'transition-opacity');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }
    </script>
</body>
</html>
