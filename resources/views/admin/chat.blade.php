@extends('layouts.admin')

@section('page_title', 'Live Chat Console')
@section('breadcrumbs', 'Admin / Live Chat')

@section('content')
    <div class="space-y-5">
        
        <!-- Low-Profile Status & Control Bar -->
        <div class="flex flex-col gap-4 rounded-2xl border border-slate-200/80 bg-white p-4 shadow-xs sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-gradient-to-tr from-violet-600 to-indigo-600 text-white shadow-xs">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-base font-black tracking-tight text-slate-900 leading-tight">Live Operator Console</h1>
                    <p class="text-xs text-slate-400">Manage real-time escalations, customer inquiries, and AI handoffs</p>
                </div>
            </div>

            <!-- Queue Counter Badges -->
            <div class="flex flex-wrap items-center gap-2">
                <span id="header-waiting-badge" class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-3 py-1 text-xs font-bold text-amber-700 ring-1 ring-amber-500/20">
                    <span class="h-1.5 w-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                    {{ $waitingCount }} Waiting Handoff
                </span>
                <span id="header-active-badge" class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 px-3 py-1 text-xs font-bold text-blue-700 ring-1 ring-blue-500/20">
                    <span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span>
                    {{ $activeCount }} Active Chats
                </span>
                <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                    Live Sync
                </span>
            </div>
        </div>

        <!-- 3-Column Modern Unified Workbench -->
        <div class="grid gap-5 xl:grid-cols-[320px_1fr_300px] h-[calc(100vh-190px)] min-h-[600px]">
            
            <!-- 1. Left Column: Queue & Triage -->
            <div class="flex flex-col rounded-2xl border border-slate-200/80 bg-white shadow-xs overflow-hidden">
                <div class="p-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-700">Active Queue</span>
                        <span id="queue-count" class="rounded-md bg-violet-100 px-1.5 py-0.5 text-[10px] font-bold text-violet-700">
                            {{ $sessions->count() }}
                        </span>
                    </div>
                    <button onclick="refreshQueue()" class="text-slate-400 hover:text-violet-600 transition p-1 hover:rotate-180 duration-300" title="Refresh Queue">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                        </svg>
                    </button>
                </div>

                <!-- Queue Filter / Search -->
                <div class="p-3 border-b border-slate-100">
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                        <input type="text" id="queue-search" oninput="filterQueue(this.value)" placeholder="Filter conversations..." 
                               class="w-full pl-8 pr-3 py-1.5 rounded-xl border border-slate-200 bg-slate-50 text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:border-violet-500 focus:outline-none transition">
                    </div>
                </div>

                <!-- Scrollable Queue List -->
                <div class="flex-1 overflow-y-auto p-3 space-y-2" id="queue-list">
                    <!-- Javascript populates queue cards -->
                </div>
            </div>

            <!-- 2. Center Column: Live Conversation Timeline & Composer -->
            <div class="flex flex-col rounded-2xl border border-slate-200/80 bg-white shadow-xs overflow-hidden relative">
                
                <!-- Chat Header -->
                <div class="p-4 border-b border-slate-100 bg-slate-50/60 flex items-center justify-between gap-3" id="chat-header">
                    <div class="flex items-center gap-3 min-w-0">
                        <div id="active-client-avatar" class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-violet-100 text-violet-700 text-xs font-bold">
                            💬
                        </div>
                        <div class="min-w-0">
                            <div class="flex items-center gap-2">
                                <h3 class="font-bold text-slate-900 text-sm truncate" id="active-client-name">No Chat Selected</h3>
                                <span id="session-badge" class="hidden rounded-full px-2 py-0.5 text-[9px] font-extrabold uppercase tracking-wide"></span>
                            </div>
                            <p class="text-xs text-slate-400 truncate mt-0.5" id="active-client-status">Select an active conversation to begin live assistance</p>
                        </div>
                    </div>

                    <!-- Header Fast Action Buttons -->
                    <div class="flex items-center gap-2 shrink-0">
                        <button onclick="claimActiveChat()" id="header-claim-btn" disabled 
                                class="inline-flex items-center gap-1.5 rounded-xl bg-violet-600 px-3 py-1.5 text-xs font-bold text-white shadow-xs hover:bg-violet-700 disabled:opacity-40 transition cursor-pointer disabled:cursor-not-allowed">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                            </svg>
                            <span id="header-claim-label">Claim Session</span>
                        </button>
                        <button onclick="resolveActiveChat()" id="header-resolve-btn" disabled 
                                class="inline-flex items-center gap-1.5 rounded-xl border border-emerald-300 bg-emerald-50 px-3 py-1.5 text-xs font-bold text-emerald-700 hover:bg-emerald-100 disabled:opacity-40 transition cursor-pointer disabled:cursor-not-allowed">
                            ✓ Resolve
                        </button>
                    </div>
                </div>

                <!-- Empty State Placeholder -->
                <div id="chat-empty-state" class="absolute inset-0 top-[65px] flex flex-col items-center justify-center bg-white/95 z-10 p-8 text-center space-y-3">
                    <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-tr from-violet-100 to-indigo-100 text-violet-600 text-2xl shadow-xs">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a.75.75 0 0 1-.974-.94 4.09 4.09 0 0 0 .546-2.127C3.308 16.326 2 14.307 2 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-800">Select a Conversation</h3>
                        <p class="text-xs text-slate-400 mt-1 max-w-xs">Pick a waiting client inquiry from the queue on the left to monitor or take over live.</p>
                    </div>
                </div>

                <!-- Chat Timeline Stream -->
                <div class="flex-1 overflow-y-auto p-5 space-y-4 bg-slate-50/30" id="chat-timeline">
                    <!-- Messages injected dynamically -->
                </div>

                <!-- Quick Response Action Chips -->
                <div class="px-4 py-2 border-t border-slate-100 bg-white flex items-center gap-1.5 overflow-x-auto no-scrollbar" id="canned-chips">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 shrink-0 mr-1">Quick:</span>
                    <button type="button" onclick="insertCanned('Hello! I am reviewing your case now.')" 
                            class="shrink-0 rounded-full border border-slate-200 bg-slate-50 px-2.5 py-1 text-[11px] font-medium text-slate-600 hover:border-violet-300 hover:bg-violet-50 hover:text-violet-700 transition">
                        👋 Reviewing case
                    </button>
                    <button type="button" onclick="insertCanned('Could you upload or send a photo of the area?')" 
                            class="shrink-0 rounded-full border border-slate-200 bg-slate-50 px-2.5 py-1 text-[11px] font-medium text-slate-600 hover:border-violet-300 hover:bg-violet-50 hover:text-violet-700 transition">
                        📸 Request photos
                    </button>
                    <button type="button" onclick="insertCanned('We can schedule an on-site inspection for you.')" 
                            class="shrink-0 rounded-full border border-slate-200 bg-slate-50 px-2.5 py-1 text-[11px] font-medium text-slate-600 hover:border-violet-300 hover:bg-violet-50 hover:text-violet-700 transition">
                        📅 Schedule inspection
                    </button>
                    <button type="button" onclick="insertCanned('Our estimator will email your official quotation today.')" 
                            class="shrink-0 rounded-full border border-slate-200 bg-slate-50 px-2.5 py-1 text-[11px] font-medium text-slate-600 hover:border-violet-300 hover:bg-violet-50 hover:text-violet-700 transition">
                        ✉️ Send email quote
                    </button>
                </div>

                <!-- Message Composer Input Bar -->
                <div class="p-3 border-t border-slate-100 bg-white" id="input-container">
                    <form id="operator-reply-form" class="flex items-center gap-2">
                        <input type="text" id="reply-input" disabled placeholder="Claim this chat to write a reply..." 
                               class="flex-1 rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:border-violet-500 focus:outline-none transition">
                        <button type="submit" id="send-btn" disabled 
                                class="inline-flex items-center gap-1.5 rounded-xl bg-violet-600 px-4 py-2.5 text-xs font-bold text-white shadow-xs hover:bg-violet-700 disabled:opacity-40 transition cursor-pointer disabled:cursor-not-allowed">
                            <span>Send</span>
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

            <!-- 3. Right Column: Customer Dossier & AI Context Panel -->
            <div class="flex flex-col rounded-2xl border border-slate-200/80 bg-white shadow-xs overflow-hidden">
                <div class="p-4 border-b border-slate-100 bg-slate-50/50">
                    <h2 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Session Dossier</h2>
                </div>

                <div class="p-4 flex-1 flex flex-col justify-between overflow-y-auto space-y-4" id="control-panel">
                    
                    <!-- Customer Details Card -->
                    <div class="space-y-3">
                        <div class="rounded-xl border border-slate-200/70 bg-slate-50/60 p-3.5">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Customer Profile</span>
                            <div class="text-sm font-bold text-slate-800" id="dossier-name">Anonymous Client</div>
                            <div class="text-xs text-slate-500 mt-0.5" id="dossier-session-id">Session #—</div>
                        </div>

                        <!-- Tenant & Channel -->
                        <div class="rounded-xl border border-slate-200/70 bg-slate-50/60 p-3.5 space-y-2 text-xs">
                            <div class="flex justify-between items-center">
                                <span class="text-slate-400">Tenant:</span>
                                <span class="font-bold text-slate-700">DARIV Waterproofing</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-slate-400">Channel:</span>
                                <span class="font-semibold text-slate-700">Web Chat Widget</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-slate-400">Assigned To:</span>
                                <span class="font-semibold text-violet-700" id="dossier-operator">Unassigned</span>
                            </div>
                        </div>

                        <!-- AI Context Card -->
                        <div class="rounded-xl border border-violet-100 bg-violet-50/40 p-3.5">
                            <div class="flex items-center gap-1.5 text-violet-700 text-xs font-bold mb-1">
                                <span>🤖</span>
                                <span>AI Assistant Routing</span>
                            </div>
                            <p class="text-xs text-slate-600 leading-relaxed" id="dossier-ai-summary">
                                Customer requested human operator support. AI answered prior questions using uploaded knowledge docs.
                            </p>
                        </div>
                    </div>

                    <!-- Primary Action Controls -->
                    <div class="space-y-2 pt-4 border-t border-slate-100">
                        <button onclick="claimActiveChat()" id="claim-btn" disabled 
                                class="w-full rounded-xl bg-violet-600 px-4 py-2.5 text-xs font-bold text-white shadow-xs hover:bg-violet-700 disabled:opacity-40 transition flex items-center justify-center gap-2 cursor-pointer disabled:cursor-not-allowed">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM4 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 10.374 21c-2.331 0-4.512-.645-6.374-1.765Z" />
                            </svg>
                            <span id="claim-label">Claim Conversation</span>
                        </button>
                        
                        <button onclick="resolveActiveChat()" id="resolve-btn" disabled 
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 disabled:opacity-40 transition flex items-center justify-center gap-2 cursor-pointer disabled:cursor-not-allowed">
                            <svg class="h-4 w-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            Mark as Resolved
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Script: Realtime Queue & Chat Logic -->
    <script>
        const chatRoutes = {
            sessions: @json(route('admin.chat.sessions')),
            messages: @json(route('admin.chat.messages', ['session' => '__SESSION__'])),
            claim: @json(route('admin.chat.claim', ['session' => '__SESSION__'])),
            reply: @json(route('admin.chat.reply', ['session' => '__SESSION__'])),
            resolve: @json(route('admin.chat.resolve', ['session' => '__SESSION__']))
        };
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
        let activeId = null;
        let activeSession = null;
        let allSessions = [];

        function sessionUrl(template, id) {
            return template.replace('__SESSION__', id);
        }

        async function requestJson(url, options = {}) {
            const response = await fetch(url, {
                ...options,
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken, ...(options.headers || {}) }
            });
            if (!response.ok) throw new Error('Chat request failed.');
            return response.json();
        }

        function textElement(tag, className, text) {
            const element = document.createElement(tag);
            element.className = className;
            element.textContent = text;
            return element;
        }

        function renderQueue(sessions = []) {
            const list = document.getElementById('queue-list');
            list.innerHTML = '';
            
            if (sessions.length === 0) {
                list.innerHTML = `
                    <div class="text-center py-10 text-slate-400">
                        <svg class="mx-auto h-8 w-8 text-slate-300 mb-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.76c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.076-4.076a1.526 1.526 0 0 1 1.037-.443 48.282 48.282 0 0 0 5.68-.494c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                        </svg>
                        <p class="text-xs font-bold text-slate-600">Queue is Clear</p>
                        <p class="text-[11px] text-slate-400 mt-0.5">No active handoffs waiting.</p>
                    </div>
                `;
                return;
            }

            sessions.forEach(session => {
                const item = document.createElement('div');
                const waiting = ['waiting', 'pending', 'queued'].includes(session.raw_status);
                const isSelected = String(session.id) === String(activeId);
                const initials = (session.customer_name || 'CL').substring(0, 2).toUpperCase();

                item.className = `p-3 rounded-xl border transition-all duration-150 cursor-pointer text-left ${
                    isSelected 
                        ? 'bg-violet-50/70 border-violet-300 shadow-xs ring-1 ring-violet-200' 
                        : 'bg-white border-slate-200/80 hover:border-slate-300 hover:bg-slate-50/70'
                }`;
                item.addEventListener('click', () => selectChat(session.id));

                item.innerHTML = `
                    <div class="flex items-start gap-2.5">
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg ${waiting ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700'} text-xs font-bold">
                            ${initials}
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex justify-between items-center gap-1">
                                <span class="font-bold text-slate-800 text-xs truncate">${session.customer_name}</span>
                                <span class="inline-flex rounded-full px-1.5 py-0.5 text-[9px] font-bold ${
                                    waiting ? 'bg-amber-50 text-amber-700 ring-1 ring-amber-500/20' : 'bg-blue-50 text-blue-700 ring-1 ring-blue-500/20'
                                }">
                                    ${waiting ? 'WAITING' : 'ACTIVE'}
                                </span>
                            </div>
                            <p class="text-[11px] text-slate-400 truncate mt-0.5">${session.latest_message || 'No messages yet'}</p>
                            ${!waiting && session.assigned_user_name ? `<p class="text-[10px] font-semibold text-blue-600 truncate mt-1">Assigned: ${session.assigned_user_name}</p>` : ''}
                        </div>
                    </div>
                `;
                list.appendChild(item);
            });
        }

        function filterQueue(query) {
            if (!query.trim()) {
                renderQueue(allSessions);
                return;
            }
            const filtered = allSessions.filter(s => 
                (s.customer_name || '').toLowerCase().includes(query.toLowerCase()) ||
                (s.latest_message || '').toLowerCase().includes(query.toLowerCase())
            );
            renderQueue(filtered);
        }

        async function refreshQueue() {
            try {
                const payload = await requestJson(chatRoutes.sessions);
                allSessions = payload.data || [];
                
                const searchInput = document.getElementById('queue-search');
                if (searchInput && searchInput.value.trim()) {
                    filterQueue(searchInput.value);
                } else {
                    renderQueue(allSessions);
                }

                document.getElementById('queue-count').innerText = String(allSessions.length);
                document.getElementById('header-waiting-badge').innerHTML = `<span class="h-1.5 w-1.5 rounded-full bg-amber-500 animate-pulse"></span>${payload.waiting_count} Waiting Handoff`;
                document.getElementById('header-active-badge').innerHTML = `<span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span>${payload.active_count} Active Chats`;

                if (activeId && !allSessions.some(session => String(session.id) === String(activeId))) {
                    resetChat();
                }
                if (activeId) {
                    activeSession = allSessions.find(session => String(session.id) === String(activeId)) || activeSession;
                    if (activeSession) {
                        updateHeader();
                        updateControls();
                        updateDossier();
                    }
                }
            } catch (error) { console.error(error); }
        }

        async function selectChat(id) {
            activeId = id;
            activeSession = null;
            document.getElementById('chat-empty-state').classList.add('hidden');
            try {
                const [queue, messages] = await Promise.all([
                    requestJson(chatRoutes.sessions),
                    requestJson(sessionUrl(chatRoutes.messages, id))
                ]);
                allSessions = queue.data || [];
                activeSession = allSessions.find(session => String(session.id) === String(id));
                renderQueue(allSessions);
                updateHeader();
                renderTimeline(messages.data);
                updateControls();
                updateDossier();
            } catch (error) { console.error(error); }
        }

        function updateHeader() {
            if (!activeSession) return;
            document.getElementById('active-client-name').innerText = activeSession.customer_name;
            document.getElementById('active-client-status').innerText = activeSession.latest_message || 'Active conversation';
            
            const initials = (activeSession.customer_name || 'CL').substring(0, 2).toUpperCase();
            document.getElementById('active-client-avatar').innerText = initials;

            const badge = document.getElementById('session-badge');
            badge.classList.remove('hidden');
            const isWaiting = activeSession.status === 'waiting' || ['waiting', 'pending', 'queued'].includes(activeSession.raw_status);
            badge.className = `rounded-full px-2 py-0.5 text-[9px] font-extrabold uppercase tracking-wide ${
                isWaiting ? 'bg-amber-50 text-amber-700 ring-1 ring-amber-500/20' : 'bg-blue-50 text-blue-700 ring-1 ring-blue-500/20'
            }`;
            badge.innerText = isWaiting ? 'WAITING HANDOFF' : 'OPERATOR ACTIVE';
        }

        function updateDossier() {
            if (!activeSession) return;
            document.getElementById('dossier-name').innerText = activeSession.customer_name;
            document.getElementById('dossier-session-id').innerText = `Session #${activeSession.id}`;
            document.getElementById('dossier-operator').innerText = activeSession.assigned_user_name || 'Unassigned (Waiting)';
        }

        function updateControls() {
            const claimed = activeSession?.assigned_user_id !== null && activeSession?.assigned_user_id !== undefined;
            document.getElementById('header-claim-label').textContent = claimed ? 'Assigned' : 'Claim Session';
            document.getElementById('claim-label').textContent = claimed ? 'Assigned' : 'Claim Conversation';
            document.getElementById('reply-input').disabled = !claimed;
            document.getElementById('send-btn').disabled = !claimed;
            document.getElementById('header-claim-btn').disabled = !activeSession || claimed;
            document.getElementById('header-resolve-btn').disabled = !claimed;
            document.getElementById('claim-btn').disabled = !activeSession || claimed;
            document.getElementById('resolve-btn').disabled = !claimed;
            document.getElementById('reply-input').placeholder = claimed ? 'Type a live message (Enter to send)...' : 'Claim this conversation to reply...';
            document.getElementById('reply-input').classList.toggle('bg-slate-50', !claimed);
        }

        function renderTimeline(messages = []) {
            const timeline = document.getElementById('chat-timeline');
            timeline.innerHTML = '';
            messages.forEach(message => {
                const time = message.created_at ? new Date(message.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) : '';
                const sender = message.sender_type;
                const element = document.createElement('div');

                if (sender === 'system') {
                    element.className = 'flex justify-center my-2';
                    element.innerHTML = `<span class="bg-slate-100 border border-slate-200/60 text-[10px] font-bold text-slate-500 uppercase tracking-wider px-3 py-1 rounded-full">${message.message_text}</span>`;
                } else if (sender === 'bot' || sender === 'ai') {
                    element.className = 'flex justify-start items-end gap-2.5 max-w-[85%]';
                    element.innerHTML = `
                        <div class="h-7 w-7 rounded-lg bg-indigo-50 border border-indigo-100 flex items-center justify-center text-xs shrink-0 shadow-2xs">🤖</div>
                        <div class="bg-white border border-slate-200/80 p-3.5 rounded-2xl rounded-bl-xs text-xs text-slate-800 shadow-xs leading-relaxed">
                            <p class="text-[10px] font-extrabold uppercase tracking-widest text-indigo-600 mb-1">RED AI Assistant</p>
                            <div class="message-text"></div>
                            <span class="block text-[9px] text-slate-400 mt-1.5 text-right">${time}</span>
                        </div>
                    `;
                    element.querySelector('.message-text').textContent = message.message_text;
                } else if (sender === 'operator' || sender === 'staff' || sender === 'agent') {
                    element.className = 'flex justify-end gap-2.5 max-w-[85%] ml-auto';
                    element.innerHTML = `
                        <div class="bg-gradient-to-tr from-violet-600 to-indigo-600 text-white p-3.5 rounded-2xl rounded-br-xs text-xs shadow-xs leading-relaxed">
                            <p class="text-[10px] font-extrabold uppercase tracking-widest text-white/80 mb-1">Operator (You)</p>
                            <div class="message-text"></div>
                            <span class="block text-[9px] text-white/60 mt-1.5 text-right">${time}</span>
                        </div>
                    `;
                    element.querySelector('.message-text').textContent = message.message_text;
                } else {
                    element.className = 'flex justify-start items-end gap-2.5 max-w-[85%]';
                    element.innerHTML = `
                        <div class="h-7 w-7 rounded-lg bg-slate-200 text-slate-700 flex items-center justify-center text-xs font-bold shrink-0 shadow-2xs">CL</div>
                        <div class="bg-slate-100 border border-slate-200/60 p-3.5 rounded-2xl rounded-tl-xs text-xs text-slate-800 leading-relaxed">
                            <p class="text-[10px] font-extrabold uppercase tracking-widest text-slate-500 mb-1">Customer</p>
                            <div class="message-text"></div>
                            <span class="block text-[9px] text-slate-400 mt-1.5">${time}</span>
                        </div>
                    `;
                    element.querySelector('.message-text').textContent = message.message_text;
                }
                timeline.appendChild(element);
            });
            timeline.scrollTop = timeline.scrollHeight;
        }

        function resetChat() {
            activeId = null;
            activeSession = null;
            document.getElementById('chat-empty-state').classList.remove('hidden');
            document.getElementById('active-client-name').innerText = 'No Chat Selected';
            document.getElementById('active-client-status').innerText = 'Select a conversation from the queue to start reply';
            document.getElementById('active-client-avatar').innerText = '💬';
            document.getElementById('session-badge').classList.add('hidden');
            document.getElementById('chat-timeline').innerHTML = '';
            document.getElementById('dossier-name').innerText = 'Anonymous Client';
            document.getElementById('dossier-session-id').innerText = 'Session #—';
            document.getElementById('dossier-operator').innerText = 'Unassigned';
            updateControls();
        }

        async function claimActiveChat() {
            if (!activeId) return;
            await requestJson(sessionUrl(chatRoutes.claim, activeId), { method: 'POST' });
            await selectChat(activeId);
        }

        async function resolveActiveChat() {
            if (!activeId) return;
            const id = activeId;
            await requestJson(sessionUrl(chatRoutes.resolve, id), { method: 'POST' });
            resetChat();
            await refreshQueue();
        }

        function insertCanned(text) {
            const input = document.getElementById('reply-input');
            input.value = text;
            input.focus();
        }

        document.getElementById('operator-reply-form').addEventListener('submit', async function (event) {
            event.preventDefault();
            const input = document.getElementById('reply-input');
            const text = input.value.trim();
            if (!text || !activeId) return;
            await requestJson(sessionUrl(chatRoutes.reply, activeId), {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ message: text })
            });
            input.value = '';
            const messages = await requestJson(sessionUrl(chatRoutes.messages, activeId));
            renderTimeline(messages.data);
        });

        refreshQueue();
        setInterval(async () => {
            await refreshQueue();
            if (activeId) {
                const response = await requestJson(sessionUrl(chatRoutes.messages, activeId));
                renderTimeline(response.data);
            }
        }, 3000);
    </script>
@endsection