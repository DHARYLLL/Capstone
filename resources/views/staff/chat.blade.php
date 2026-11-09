@extends('layouts.staff')

@section('page_title', 'Live Chat & Staff Handoff')
@section('breadcrumbs', 'Staff / Chat & Handoff')

@section('content')
    <div class="space-y-6">
        <!-- Minimal Top Control Header -->
        <div class="flex flex-col gap-4 rounded-2xl border border-[#232934] bg-[#14181F] p-5 shadow-sm sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-[#D8210B] to-[#EE4E56] text-lg font-black text-white shadow-sm shadow-[#D8210B]/20">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a.75.75 0 0 1-.974-.94 4.09 4.09 0 0 0 .546-2.127C3.308 16.326 2 14.307 2 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z" />
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-xl font-bold tracking-tight text-[#F0EDE8]">Live Operator Desk</h1>
                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-500/10 border border-emerald-500/30 px-2.5 py-0.5 text-xs font-semibold text-emerald-400">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            Live Console
                        </span>
                    </div>
                    <p class="text-xs font-medium text-[#6B7280]">Triage incoming escalations, claim waiting customer tickets, and provide instant live support</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <button type="button" id="sound-toggle-btn" onclick="toggleSoundAlerts()" 
                    class="inline-flex items-center gap-1.5 rounded-xl border border-[#232934] bg-[#1A1F29] px-3 py-1.5 text-xs font-semibold text-[#F0EDE8] hover:border-[#2B313D] transition" title="Toggle audio alert on new chats">
                    <span id="sound-icon">🔔</span>
                    <span id="sound-label">Sound On</span>
                </button>
                <span id="header-waiting-badge" class="inline-flex items-center gap-1.5 rounded-xl border border-[#F2D742]/30 bg-[#F2D742]/10 px-3 py-1.5 text-xs font-bold text-[#F2D742]">
                    <span class="h-2 w-2 rounded-full bg-[#F2D742]"></span>
                    0 Waiting
                </span>
                <span id="header-active-badge" class="inline-flex items-center gap-1.5 rounded-xl border border-[#D8210B]/30 bg-[#D8210B]/10 px-3 py-1.5 text-xs font-bold text-[#EE4E56]">
                    <span class="h-2 w-2 rounded-full bg-[#D8210B] animate-pulse"></span>
                    0 Active
                </span>
            </div>
        </div>

        <!-- 3-Column Operator Workspace -->
        <section class="grid gap-5 xl:grid-cols-[320px_1fr_280px]">
            
            <!-- Left Column: Queue List -->
            <div class="flex flex-col h-[700px] rounded-2xl border border-[#232934] bg-[#14181F] shadow-sm overflow-hidden">
                <div class="p-4 border-b border-[#232934] flex items-center justify-between bg-[#14181F]">
                    <div class="flex items-center gap-2">
                        <h2 class="text-xs font-bold uppercase tracking-wider text-[#F0EDE8]">Active Queue</h2>
                    </div>
                    <span id="queue-count" class="inline-flex items-center rounded-lg bg-[#1A1F29] border border-[#232934] px-2 py-0.5 text-xs font-bold text-[#9AA0AB]">0 chats</span>
                </div>
                
                <div class="flex-1 overflow-y-auto p-3 space-y-2" id="queue-list">
                    <!-- Populated dynamically via JS -->
                </div>
            </div>

            <!-- Center Column: Active Chat Session Timeline & Composer -->
            <div class="flex flex-col h-[700px] rounded-2xl border border-[#232934] bg-[#14181F] shadow-sm overflow-hidden relative">
                
                <!-- Chat Window Header -->
                <div class="p-4 border-b border-[#232934] bg-[#14181F] flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3 min-w-0">
                        <div id="active-client-avatar" class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#D8210B]/20 border border-[#D8210B]/40 text-[#EE4E56] font-bold text-sm">
                            💬
                        </div>
                        <div class="min-w-0">
                            <div class="flex items-center gap-2">
                                <h3 class="font-bold text-[#F0EDE8] text-sm truncate" id="active-client-name">No Active Conversation</h3>
                                <span id="session-badge" class="hidden rounded-full px-2 py-0.5 text-[10px] font-extrabold uppercase tracking-wide"></span>
                            </div>
                            <p class="text-xs text-[#6B7280] truncate mt-0.5" id="active-client-status">Select a conversation from the left queue to start operator handling</p>
                        </div>
                    </div>

                    <!-- Fast Status Toggles in Header -->
                    <div class="flex items-center gap-2 shrink-0">
                        <button onclick="claimActiveChat()" id="header-claim-btn" disabled 
                            class="inline-flex items-center gap-1.5 rounded-xl bg-[#D8210B] px-3.5 py-1.5 text-xs font-bold text-white shadow-sm shadow-[#D8210B]/20 hover:bg-[#EE4E56] disabled:opacity-40 transition cursor-pointer disabled:cursor-not-allowed">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                            </svg>
                            <span id="header-claim-label">Claim Chat</span>
                        </button>
                        <button onclick="resolveActiveChat()" id="header-resolve-btn" disabled 
                            class="inline-flex items-center gap-1.5 rounded-xl border border-emerald-500/40 bg-emerald-500/10 px-3.5 py-1.5 text-xs font-bold text-emerald-400 hover:bg-emerald-500/20 disabled:opacity-40 transition cursor-pointer disabled:cursor-not-allowed">
                            ✓ Mark Resolved
                        </button>
                    </div>
                </div>

                <!-- Empty State Prompt -->
                <div id="chat-empty-state" class="absolute inset-0 top-[69px] flex flex-col items-center justify-center bg-[#14181F]/95 z-10 p-8 text-center space-y-3">
                    <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-[#1A1F29] border border-[#232934] text-[#EE4E56] text-3xl shadow-sm">
                        💬
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-[#F0EDE8]">Select a Ticket</h3>
                        <p class="text-xs text-[#6B7280] mt-1 max-w-xs">Pick an incoming customer inquiry from the left queue to monitor or handle live.</p>
                    </div>
                </div>

                <!-- Chat Message Timeline -->
                <div class="flex-1 overflow-y-auto p-5 space-y-4 bg-[#14181F]" id="chat-timeline">
                    <!-- Dynamic Messages Injected here -->
                </div>

                <!-- Live Typing Indicator -->
                <div id="typing-indicator" class="hidden px-5 py-2 text-xs text-[#9AA0AB] bg-[#14181F] flex items-center gap-2 border-t border-[#232934]">
                    <span class="inline-flex gap-1">
                        <span class="h-1.5 w-1.5 rounded-full bg-[#6B7280] animate-bounce"></span>
                        <span class="h-1.5 w-1.5 rounded-full bg-[#6B7280] animate-bounce [animation-delay:0.2s]"></span>
                        <span class="h-1.5 w-1.5 rounded-full bg-[#6B7280] animate-bounce [animation-delay:0.4s]"></span>
                    </span>
                    <span class="font-medium">Client is typing a response...</span>
                </div>

                <!-- Quick Response Chips -->
                <div class="px-4 py-2 border-t border-[#232934] bg-[#1A1F29] flex items-center gap-1.5 overflow-x-auto no-scrollbar" id="canned-chips">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-[#6B7280] shrink-0 mr-1">Quick:</span>
                    <button type="button" onclick="insertCanned('Hello! I am reviewing your case now.')" 
                        class="shrink-0 rounded-full border border-[#232934] bg-[#14181F] px-2.5 py-1 text-[11px] font-medium text-[#9AA0AB] hover:border-[#D8210B] hover:text-[#EE4E56] transition">
                        👋 Reviewing case
                    </button>
                    <button type="button" onclick="insertCanned('Could you upload or send a photo of the area?')" 
                        class="shrink-0 rounded-full border border-[#232934] bg-[#14181F] px-2.5 py-1 text-[11px] font-medium text-[#9AA0AB] hover:border-[#D8210B] hover:text-[#EE4E56] transition">
                        📸 Request photos
                    </button>
                    <button type="button" onclick="insertCanned('We can schedule an on-site inspection for you.')" 
                        class="shrink-0 rounded-full border border-[#232934] bg-[#14181F] px-2.5 py-1 text-[11px] font-medium text-[#9AA0AB] hover:border-[#D8210B] hover:text-[#EE4E56] transition">
                        📅 Schedule inspection
                    </button>
                    <button type="button" onclick="insertCanned('Our estimator will email your official quotation today.')" 
                        class="shrink-0 rounded-full border border-[#232934] bg-[#14181F] px-2.5 py-1 text-[11px] font-medium text-[#9AA0AB] hover:border-[#D8210B] hover:text-[#EE4E56] transition">
                        ✉️ Send email quote
                    </button>
                </div>

                <!-- Message Input Form -->
                <div class="p-3 border-t border-[#232934] bg-[#14181F]" id="input-container">
                    <form id="operator-reply-form" class="flex items-center gap-2">
                        <input type="text" id="reply-input" disabled placeholder="Claim this chat to write a reply..." 
                            class="flex-1 rounded-xl border border-[#232934] bg-[#1A1F29] px-4 py-2.5 text-xs text-[#F0EDE8] placeholder-[#6B7280] focus:border-[#D8210B] focus:outline-none transition">
                        <button type="submit" id="send-btn" disabled 
                            class="inline-flex items-center gap-1.5 rounded-xl bg-[#D8210B] px-4 py-2.5 text-xs font-bold text-white shadow-sm shadow-[#D8210B]/20 hover:bg-[#EE4E56] disabled:opacity-40 transition cursor-pointer disabled:cursor-not-allowed">
                            <span>Send</span>
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Right Column: Context & Metadata Panel -->
            <div class="flex flex-col h-[700px] rounded-2xl border border-[#232934] bg-[#14181F] shadow-sm overflow-hidden">
                <div class="p-4 border-b border-[#232934] bg-[#14181F]">
                    <h2 class="text-xs font-bold text-[#F0EDE8] uppercase tracking-wider">Session Details</h2>
                </div>

                <div class="p-4 flex-1 flex flex-col justify-between overflow-y-auto space-y-4" id="control-panel">
                    
                    <!-- Session Profile Card -->
                    <div class="space-y-3">
                        <div class="rounded-xl border border-[#232934] bg-[#1A1F29] p-3.5">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-[#6B7280] block mb-1">Customer Profile</span>
                            <div class="text-sm font-bold text-[#F0EDE8]" id="dossier-name">Anonymous Client</div>
                            <div class="text-xs text-[#9AA0AB] mt-0.5" id="dossier-session-id">Session #—</div>
                        </div>

                        <!-- Organization & Channel -->
                        <div class="rounded-xl border border-[#232934] bg-[#1A1F29] p-3.5 space-y-2 text-xs">
                            <div class="flex justify-between items-center">
                                <span class="text-[#6B7280]">Tenant:</span>
                                <span class="font-bold text-[#F0EDE8]">DARIV Waterproofing</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-[#6B7280]">Channel:</span>
                                <span class="font-semibold text-[#F0EDE8]">Web Chat Widget</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-[#6B7280]">Assigned To:</span>
                                <span class="font-semibold text-[#EE4E56]" id="dossier-operator">Unassigned</span>
                            </div>
                        </div>

                        <!-- Routing Summary -->
                        <div class="rounded-xl border border-[#D8210B]/30 bg-[#D8210B]/10 p-3.5">
                            <div class="flex items-center gap-1.5 text-[#EE4E56] text-xs font-bold mb-1">
                                <span>🤖</span>
                                <span>AI Escalation Reason</span>
                            </div>
                            <p class="text-xs text-[#9AA0AB] leading-relaxed" id="dossier-ai-summary">
                                Customer requested human operator support. AI answered prior questions using uploaded knowledge docs.
                            </p>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="space-y-2 pt-4 border-t border-[#232934]">
                        <button onclick="claimActiveChat()" id="claim-btn" disabled 
                            class="w-full rounded-xl bg-[#D8210B] px-4 py-2.5 text-xs font-bold text-white shadow-sm shadow-[#D8210B]/20 hover:bg-[#EE4E56] disabled:opacity-40 transition flex items-center justify-center gap-2 cursor-pointer disabled:cursor-not-allowed">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM4 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 10.374 21c-2.331 0-4.512-.645-6.374-1.765Z" />
                            </svg>
                            <span id="claim-label">Claim Conversation</span>
                        </button>
                        
                        <button onclick="resolveActiveChat()" id="resolve-btn" disabled 
                            class="w-full rounded-xl border border-[#232934] bg-[#1A1F29] px-4 py-2.5 text-xs font-bold text-[#F0EDE8] hover:border-emerald-500/50 hover:text-emerald-400 disabled:opacity-40 transition flex items-center justify-center gap-2 cursor-pointer disabled:cursor-not-allowed">
                            <svg class="h-4 w-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            Mark as Resolved
                        </button>
                    </div>
                </div>
            </div>

        </section>
    </div>

    <!-- Script for Live Queue Polling & Handling -->
    <script>
        const chatRoutes = {
            sessions: @json(route('staff.chats.index')),
            messages: @json(route('staff.chats.messages', ['session' => '__SESSION__'])),
            claim: @json(route('staff.chats.claim', ['session' => '__SESSION__'])),
            resolve: @json(route('staff.chats.resolve', ['session' => '__SESSION__'])),
            typing: @json(route('staff.chats.typing', ['session' => '__SESSION__']))
        };

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        let activeId = null;
        let activeSession = null;
        let soundAlerts = true;
        let previousPendingCount = 0;

        function sessionUrl(template, id) {
            return template.replace('__SESSION__', id);
        }

        async function requestJson(url, options = {}) {
            const response = await fetch(url, {
                ...options,
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    ...(options.headers || {})
                }
            });
            if (!response.ok) {
                const error = await response.json().catch(() => ({}));
                throw new Error(error.message || 'Request failed.');
            }
            return response.json();
        }

        function toggleSoundAlerts() {
            soundAlerts = !soundAlerts;
            document.getElementById('sound-icon').innerText = soundAlerts ? '🔔' : '🔕';
            document.getElementById('sound-label').innerText = soundAlerts ? 'Sound On' : 'Sound Off';
        }

        function playDing() {
            if (!soundAlerts) return;
            try {
                const ctx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.type = 'sine';
                osc.frequency.setValueAtTime(800, ctx.currentTime);
                osc.frequency.exponentialRampToValueAtTime(400, ctx.currentTime + 0.15);
                gain.gain.setValueAtTime(0.15, ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.15);
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.start();
                osc.stop(ctx.currentTime + 0.15);
            } catch(e) {}
        }

        async function refreshQueue() {
            try {
                const payload = await requestJson(chatRoutes.sessions);
                const sessions = payload.data || [];
                
                if (payload.pending_count > previousPendingCount) {
                    playDing();
                }
                previousPendingCount = payload.pending_count;

                document.getElementById('header-waiting-badge').innerHTML = `<span class="h-2 w-2 rounded-full bg-[#F2D742]"></span>${payload.pending_count} Waiting`;
                document.getElementById('header-active-badge').innerHTML = `<span class="h-2 w-2 rounded-full bg-[#D8210B] animate-pulse"></span>${payload.active_count} Active`;
                document.getElementById('queue-count').innerText = `${sessions.length} chats`;

                renderQueue(sessions);

                if (activeId) {
                    activeSession = sessions.find(s => s.id === activeId) || activeSession;
                    if (activeSession) {
                        updateHeader();
                        updateControls();
                        updateDossier();
                    }
                }
            } catch (err) {
                console.error(err);
            }
        }

        function renderQueue(sessions = []) {
            const list = document.getElementById('queue-list');
            list.innerHTML = '';

            if (sessions.length === 0) {
                list.innerHTML = `
                    <div class="text-center py-12 text-[#6B7280]">
                        <div class="text-3xl mb-2">🎉</div>
                        <p class="text-xs font-bold text-[#F0EDE8]">Queue is completely clear!</p>
                        <p class="text-[11px] text-[#6B7280] mt-0.5">No escalated tickets waiting for response.</p>
                    </div>
                `;
                return;
            }

            sessions.forEach(session => {
                const item = document.createElement('div');
                const waiting = session.status === 'waiting';
                const isSelected = session.id === activeId;
                const initials = (session.customer_name || 'CL').substring(0, 2).toUpperCase();

                item.className = `p-3 rounded-xl border transition-all duration-150 cursor-pointer text-left ${
                    isSelected 
                        ? 'bg-[#1A1F29] border-[#D8210B] shadow-sm ring-1 ring-[#D8210B]' 
                        : 'bg-[#1A1F29] border-[#232934] hover:border-[#2B313D] hover:bg-[#202632]'
                }`;
                item.onclick = () => selectChat(session.id);

                item.innerHTML = `
                    <div class="flex items-start gap-2.5">
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg ${waiting ? 'bg-[#F2D742]/20 border border-[#F2D742]/40 text-[#F2D742]' : 'bg-blue-500/20 border border-blue-500/40 text-blue-400'} text-xs font-bold">
                            ${initials}
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex justify-between items-center gap-1">
                                <span class="font-bold text-[#F0EDE8] text-xs truncate">${session.customer_name}</span>
                                <span class="inline-flex rounded-full px-1.5 py-0.5 text-[9px] font-bold ${
                                    waiting ? 'bg-[#F2D742]/10 border border-[#F2D742]/30 text-[#F2D742]' : 'bg-blue-500/10 border border-blue-500/30 text-blue-400'
                                }">
                                    ${waiting ? 'WAITING' : 'ACTIVE'}
                                </span>
                            </div>
                            <p class="text-[11px] text-[#6B7280] truncate mt-0.5">${session.latest_message || 'No messages yet'}</p>
                            ${!waiting && session.assigned_user_name ? `<p class="text-[10px] font-semibold text-blue-400 truncate mt-1">Assigned: ${session.assigned_user_name}</p>` : ''}
                        </div>
                    </div>
                `;
                list.appendChild(item);
            });
        }

        async function selectChat(id) {
            activeId = id;
            document.getElementById('chat-empty-state').classList.add('hidden');
            
            try {
                const [queue, messages] = await Promise.all([
                    requestJson(chatRoutes.sessions),
                    requestJson(sessionUrl(chatRoutes.messages, id))
                ]);
                activeSession = queue.data.find(s => s.id === id);
                renderQueue(queue.data);
                updateHeader();
                renderTimeline(messages.data);
                updateControls();
                updateDossier();
            } catch (err) {
                console.error(err);
            }
        }

        function updateHeader() {
            if (!activeSession) return;
            document.getElementById('active-client-name').innerText = activeSession.customer_name;
            document.getElementById('active-client-status').innerText = activeSession.latest_message || 'Active conversation';
            
            const initials = (activeSession.customer_name || 'CL').substring(0, 2).toUpperCase();
            document.getElementById('active-client-avatar').innerText = initials;

            const badge = document.getElementById('session-badge');
            badge.classList.remove('hidden');
            const isWaiting = activeSession.status === 'waiting';
            badge.className = `rounded-full px-2 py-0.5 text-[9px] font-extrabold uppercase tracking-wide ${
                isWaiting ? 'bg-[#F2D742]/10 border border-[#F2D742]/30 text-[#F2D742]' : 'bg-blue-500/10 border border-blue-500/30 text-blue-400'
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
            document.getElementById('header-claim-label').textContent = claimed ? 'Assigned' : 'Claim Chat';
            document.getElementById('claim-label').textContent = claimed ? 'Assigned' : 'Claim Conversation';
            document.getElementById('reply-input').disabled = !claimed;
            document.getElementById('send-btn').disabled = !claimed;
            document.getElementById('header-claim-btn').disabled = !activeSession || claimed;
            document.getElementById('header-resolve-btn').disabled = !claimed;
            document.getElementById('claim-btn').disabled = !activeSession || claimed;
            document.getElementById('resolve-btn').disabled = !claimed;
            document.getElementById('reply-input').placeholder = claimed ? 'Type a live message (Enter to send)...' : 'Claim this conversation to reply...';
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
                    element.innerHTML = `<span class="bg-[#1A1F29] border border-[#232934] text-[10px] font-bold text-[#9AA0AB] uppercase tracking-wider px-3 py-1 rounded-full">${message.message_text}</span>`;
                } else if (sender === 'bot' || sender === 'ai') {
                    element.className = 'flex justify-start items-end gap-2.5 max-w-[85%]';
                    element.innerHTML = `
                        <div class="h-7 w-7 rounded-lg bg-[#D8210B]/20 border border-[#D8210B]/40 flex items-center justify-center text-xs shrink-0 shadow-sm">🤖</div>
                        <div class="bg-[#1A1F29] border border-[#232934] p-3.5 rounded-2xl rounded-bl-xs text-xs text-[#F0EDE8] shadow-sm leading-relaxed">
                            <p class="text-[10px] font-extrabold uppercase tracking-widest text-[#EE4E56] mb-1">RED AI Assistant</p>
                            <div class="message-text"></div>
                            <span class="block text-[9px] text-[#6B7280] mt-1.5 text-right">${time}</span>
                        </div>
                    `;
                    element.querySelector('.message-text').textContent = message.message_text;
                } else if (sender === 'operator' || sender === 'staff') {
                    element.className = 'flex justify-end gap-2.5 max-w-[85%] ml-auto';
                    element.innerHTML = `
                        <div class="bg-gradient-to-br from-[#D8210B] to-[#EE4E56] text-white p-3.5 rounded-2xl rounded-br-xs text-xs shadow-sm leading-relaxed">
                            <p class="text-[10px] font-extrabold uppercase tracking-widest text-white/90 mb-1">Operator (You)</p>
                            <div class="message-text"></div>
                            <span class="block text-[9px] text-white/70 mt-1.5 text-right">${time}</span>
                        </div>
                    `;
                    element.querySelector('.message-text').textContent = message.message_text;
                } else {
                    element.className = 'flex justify-start items-end gap-2.5 max-w-[85%]';
                    element.innerHTML = `
                        <div class="h-7 w-7 rounded-lg bg-[#232934] text-[#F0EDE8] flex items-center justify-center text-xs font-bold shrink-0 border border-[#2B313D]">CL</div>
                        <div class="bg-[#1A1F29] border border-[#232934] p-3.5 rounded-2xl rounded-tl-xs text-xs text-[#F0EDE8] leading-relaxed">
                            <p class="text-[10px] font-extrabold uppercase tracking-widest text-[#9AA0AB] mb-1">Customer</p>
                            <div class="message-text"></div>
                            <span class="block text-[9px] text-[#6B7280] mt-1.5">${time}</span>
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
            document.getElementById('active-client-name').innerText = 'No Active Conversation';
            document.getElementById('active-client-status').innerText = 'Select a conversation from the left queue to start operator handling';
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
            try {
                await requestJson(sessionUrl(chatRoutes.claim, activeId), { method: 'POST' });
                await selectChat(activeId);
            } catch (err) {
                alert(err.message);
            }
        }

        async function resolveActiveChat() {
            if (!activeId) return;
            const id = activeId;
            try {
                await requestJson(sessionUrl(chatRoutes.resolve, id), { method: 'POST' });
                resetChat();
                await refreshQueue();
            } catch (err) {
                alert(err.message);
            }
        }

        function insertCanned(text) {
            const input = document.getElementById('reply-input');
            input.value = text;
            input.focus();
        }

        document.getElementById('operator-reply-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            const input = document.getElementById('reply-input');
            const message = input.value.trim();
            if (!message || !activeId) return;

            input.value = '';
            try {
                await requestJson(sessionUrl(chatRoutes.messages, activeId), {
                    method: 'POST',
                    body: JSON.stringify({ message })
                });
                const response = await requestJson(sessionUrl(chatRoutes.messages, activeId));
                renderTimeline(response.data);
            } catch (err) {
                alert(err.message);
            }
        });

        // Initialize and poll every 3 seconds
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
