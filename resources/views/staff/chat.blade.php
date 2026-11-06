@extends('layouts.staff')

@section('page_title', 'Live Chat & Staff Handoff')
@section('breadcrumbs', 'Staff / Chat & Handoff')

@section('content')
    <div class="space-y-6">
        <!-- Minimal Top Control Header -->
        <div class="flex flex-col gap-4 rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-[#D8210B] to-[#EE4E56] text-lg font-black text-white shadow-xs shadow-[#D8210B]/20">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a.75.75 0 0 1-.974-.94 4.09 4.09 0 0 0 .546-2.127C3.308 16.326 2 14.307 2 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z" />
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-xl font-bold tracking-tight text-slate-900">Live Operator Desk</h1>
                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-600/20">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            Live Console
                        </span>
                    </div>
                    <p class="text-xs font-medium text-slate-400">Triage incoming escalations, claim waiting customer tickets, and provide instant live support</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <button type="button" id="sound-toggle-btn" onclick="toggleSoundAlerts()" 
                    class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition" title="Toggle audio alert on new chats">
                    <span id="sound-icon">🔔</span>
                    <span id="sound-label">Sound On</span>
                </button>
                <span id="header-waiting-badge" class="inline-flex items-center gap-1.5 rounded-xl border border-amber-200 bg-amber-50 px-3 py-1.5 text-xs font-bold text-amber-700">
                    <span class="h-2 w-2 rounded-full bg-amber-500"></span>
                    0 Waiting
                </span>
                <span id="header-active-badge" class="inline-flex items-center gap-1.5 rounded-xl border border-red-200 bg-red-50 px-3 py-1.5 text-xs font-bold text-[#D8210B]">
                    <span class="h-2 w-2 rounded-full bg-[#D8210B] animate-pulse"></span>
                    0 Active
                </span>
            </div>
        </div>

        <!-- 3-Column Operator Workspace -->
        <section class="grid gap-5 xl:grid-cols-[320px_1fr_280px]">
            
            <!-- Left Column: Queue List -->
            <div class="flex flex-col h-[700px] rounded-2xl border border-slate-200/80 bg-white shadow-xs overflow-hidden">
                <div class="p-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div class="flex items-center gap-2">
                        <h2 class="text-xs font-bold uppercase tracking-wider text-slate-500">Active Queue</h2>
                    </div>
                    <span id="queue-count" class="inline-flex items-center rounded-lg bg-slate-200/60 px-2 py-0.5 text-xs font-bold text-slate-700">0 chats</span>
                </div>
                
                <div class="flex-1 overflow-y-auto p-3 space-y-2" id="queue-list">
                    <!-- Populated dynamically via JS -->
                </div>
            </div>

            <!-- Center Column: Conversation Thread -->
            <div class="flex flex-col h-[700px] rounded-2xl border border-slate-200/80 bg-white shadow-xs overflow-hidden relative">
                
                <!-- Chat Top Bar -->
                <div class="p-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/60" id="chat-header">
                    <div class="min-w-0 flex items-center gap-3">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-red-100 text-xs font-bold text-[#D8210B]" id="active-client-avatar">
                            #
                        </div>
                        <div class="min-w-0">
                            <h3 class="font-bold text-slate-900 text-sm truncate" id="active-client-name">No Chat Selected</h3>
                            <p class="text-xs text-slate-400 truncate" id="active-client-status">Select a ticket from the queue to start responding</p>
                        </div>
                    </div>
                    <div id="session-badge" class="hidden"></div>
                </div>

                <!-- Empty State Screen -->
                <div id="chat-empty-state" class="absolute inset-0 flex flex-col items-center justify-center bg-white z-10 p-8 text-center space-y-3">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-red-50 text-[#D8210B]">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a.75.75 0 0 1-.974-.94 4.09 4.09 0 0 0 .546-2.127C3.308 16.326 2 14.307 2 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 text-sm">No Active Session Selected</h3>
                        <p class="text-xs text-slate-400 mt-1 max-w-sm">Pick an incoming customer inquiry from the left panel to review message history and take over live messaging.</p>
                    </div>
                </div>

                <!-- Chat Timeline Stream -->
                <div class="flex-1 overflow-y-auto p-5 space-y-4 bg-slate-50/40 relative" id="chat-timeline">
                    <!-- Dynamic chat messages injected here -->
                </div>

                <!-- Customer Typing Indicator Bar (Hidden by default) -->
                <div id="customer-typing-bar" class="hidden px-5 py-2 border-t border-slate-100 bg-slate-50/80 flex items-center gap-2">
                    <div class="flex gap-1 items-center">
                        <span class="h-1.5 w-1.5 rounded-full bg-[#D8210B] animate-bounce" style="animation-delay: -0.32s"></span>
                        <span class="h-1.5 w-1.5 rounded-full bg-[#D8210B] animate-bounce" style="animation-delay: -0.16s"></span>
                        <span class="h-1.5 w-1.5 rounded-full bg-[#D8210B] animate-bounce"></span>
                    </div>
                    <span class="text-[11px] font-semibold text-slate-500" id="customer-typing-label">Customer is typing...</span>
                </div>

                <!-- Reply Input Composer -->
                <div class="p-3.5 border-t border-slate-100 bg-white" id="input-container">
                    <form id="operator-reply-form" class="flex gap-2">
                        <input type="text" id="reply-input" disabled placeholder="Claim this chat to write a reply..." 
                            class="flex-1 rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm text-slate-800 placeholder:text-slate-400 focus:border-[#D8210B] focus:bg-white focus:ring-1 focus:ring-[#D8210B] focus:outline-none transition disabled:opacity-50">
                        <button type="submit" id="send-btn" disabled 
                            class="inline-flex items-center gap-1.5 rounded-xl bg-[#D8210B] px-5 py-2.5 text-xs font-semibold text-white shadow-xs shadow-[#D8210B]/20 hover:bg-[#EE4E56] transition disabled:opacity-50 disabled:pointer-events-none">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                            </svg>
                            Send
                        </button>
                    </form>
                </div>
            </div>

            <!-- Right Column: Operator Controls & Actions -->
            <div class="flex flex-col h-[700px] rounded-2xl border border-slate-200/80 bg-white shadow-xs overflow-hidden">
                <div class="p-4 border-b border-slate-100 bg-slate-50/50">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-slate-500">Handoff Actions</h2>
                </div>

                <div class="p-5 flex-1 flex flex-col justify-between" id="control-panel">
                    <div class="space-y-4">
                        <button onclick="claimActiveChat()" id="claim-btn" disabled 
                            class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-[#D8210B] px-4 py-3 text-xs font-semibold text-white shadow-xs shadow-[#D8210B]/20 hover:bg-[#EE4E56] transition disabled:opacity-50 disabled:pointer-events-none">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM4 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 10.374 21c-2.331 0-4.512-.645-6.374-1.765Z" />
                            </svg>
                            Claim & Take Over
                        </button>
                        
                        <div class="space-y-1.5">
                            <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Quick canned responses</label>
                            <div class="space-y-1.5" id="canned-responses-list">
                                <button type="button" onclick="insertCanned('Hello! I am reviewing your case now.')" 
                                    class="canned-item w-full text-left rounded-xl border border-slate-200/80 bg-slate-50/50 px-3 py-2 text-xs font-medium text-slate-700 hover:border-red-300 hover:bg-red-50/40 hover:text-[#D8210B] transition disabled:opacity-50" disabled>
                                    👋 Reviewing case now
                                </button>
                                <button type="button" onclick="insertCanned('We will schedule a site visit shortly.')" 
                                    class="canned-item w-full text-left rounded-xl border border-slate-200/80 bg-slate-50/50 px-3 py-2 text-xs font-medium text-slate-700 hover:border-red-300 hover:bg-red-50/40 hover:text-[#D8210B] transition disabled:opacity-50" disabled>
                                    📅 Schedule site visit
                                </button>
                                <button type="button" onclick="insertCanned('Could you upload or send a photo of the issue?')" 
                                    class="canned-item w-full text-left rounded-xl border border-slate-200/80 bg-slate-50/50 px-3 py-2 text-xs font-medium text-slate-700 hover:border-red-300 hover:bg-red-50/40 hover:text-[#D8210B] transition disabled:opacity-50" disabled>
                                    📸 Request photo / proof
                                </button>
                                <button type="button" onclick="insertCanned('Our team will email your final quotation today.')" 
                                    class="canned-item w-full text-left rounded-xl border border-slate-200/80 bg-slate-50/50 px-3 py-2 text-xs font-medium text-slate-700 hover:border-red-300 hover:bg-red-50/40 hover:text-[#D8210B] transition disabled:opacity-50" disabled>
                                    ✉️ Send email quote
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100">
                        <button onclick="resolveActiveChat()" id="resolve-btn" disabled 
                            class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-3 text-xs font-semibold text-white shadow-xs hover:bg-emerald-700 transition disabled:opacity-50 disabled:pointer-events-none">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                            </svg>
                            Mark As Resolved
                        </button>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script>
        const chatEndpoints = {
            sessions: @json(route('staff.chats.index')),
            messages: sessionId => @json(url('/staff/chats')) + `/${sessionId}/messages`,
            send: sessionId => @json(url('/staff/chats')) + `/${sessionId}/messages`,
            claim: sessionId => @json(url('/staff/chats')) + `/${sessionId}/claim`,
            resolve: sessionId => @json(url('/staff/chats')) + `/${sessionId}/resolve`,
            typing: sessionId => @json(url('/staff/chats')) + `/${sessionId}/typing`,
        };

        let sessions = [];
        let activeSessionId = null;
        let renderedMessageIds = new Set();
        let previousPendingCount = 0;
        let soundAlertsEnabled = localStorage.getItem('operator_sound_alerts') !== 'false';
        let originalPageTitle = document.title;
        let titleFlashInterval = null;
        let lastStaffTypingPing = 0;

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        if (csrfToken && window.axios) window.axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;

        // Initialize Audio Context for subtle pleasant notification chime
        function playChimeSound() {
            if (!soundAlertsEnabled) return;
            try {
                const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();
                osc.type = 'sine';
                osc.frequency.setValueAtTime(587.33, audioCtx.currentTime); // D5
                osc.frequency.exponentialRampToValueAtTime(880.00, audioCtx.currentTime + 0.15); // A5
                gain.gain.setValueAtTime(0.12, audioCtx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.35);
                osc.connect(gain);
                gain.connect(audioCtx.destination);
                osc.start();
                osc.stop(audioCtx.currentTime + 0.35);
            } catch (e) {
                console.warn('Audio alert not permitted:', e);
            }
        }

        function toggleSoundAlerts() {
            soundAlertsEnabled = !soundAlertsEnabled;
            localStorage.setItem('operator_sound_alerts', soundAlertsEnabled);
            updateSoundButton();
            if (soundAlertsEnabled) playChimeSound();
        }

        function updateSoundButton() {
            const icon = document.getElementById('sound-icon');
            const label = document.getElementById('sound-label');
            if (icon && label) {
                icon.textContent = soundAlertsEnabled ? '🔔' : '🔕';
                label.textContent = soundAlertsEnabled ? 'Sound On' : 'Muted';
            }
        }
        updateSoundButton();

        // Request browser system notification permission if needed
        if ('Notification' in window && Notification.permission === 'default') {
            setTimeout(() => Notification.requestPermission().catch(() => {}), 2000);
        }

        function triggerNewTicketNotification(count) {
            playChimeSound();
            
            // Tab Title Flashing
            if (titleFlashInterval) clearInterval(titleFlashInterval);
            let state = false;
            titleFlashInterval = setInterval(() => {
                document.title = state ? `(${count}) New Escalation! 💬` : originalPageTitle;
                state = !state;
            }, 1000);

            // Desktop system push notification
            if ('Notification' in window && Notification.permission === 'granted' && document.hidden) {
                new Notification('Customer Waiting for Support', {
                    body: `${count} ticket(s) waiting in your operator queue.`,
                    icon: '/favicon.ico',
                });
            }
        }

        function clearTitleAlert() {
            if (titleFlashInterval) {
                clearInterval(titleFlashInterval);
                titleFlashInterval = null;
                document.title = originalPageTitle;
            }
        }
        window.addEventListener('focus', clearTitleAlert);

        function sessionLabel(session) {
            return session.user_identifier || `Customer #${session.id}`;
        }

        function renderQueue() {
            const list = document.getElementById('queue-list');
            list.innerHTML = '';
            
            const currentPendingCount = sessions.filter(session => session.status === 'pending').length;
            
            // Alert operator when a new ticket enters queue
            if (currentPendingCount > previousPendingCount && previousPendingCount >= 0) {
                triggerNewTicketNotification(currentPendingCount);
            }
            previousPendingCount = currentPendingCount;

            if (sessions.length === 0) {
                list.innerHTML = `
                    <div class="text-center py-8 text-slate-400">
                        <p class="text-xs font-semibold">No chats in queue</p>
                        <p class="text-[11px] text-slate-400 mt-0.5">Waiting customer inquiries will appear here.</p>
                    </div>
                `;
            } else {
                sessions.forEach(session => {
                    const isSelected = session.id === activeSessionId;
                    const isPending = session.status === 'pending';
                    const item = document.createElement('button');
                    item.type = 'button';
                    item.className = `w-full p-3.5 rounded-xl border text-left transition ${
                        isSelected 
                            ? 'bg-red-50/60 border-red-300 ring-1 ring-red-300/40 shadow-xs' 
                            : 'bg-white border-slate-200/70 hover:bg-slate-50 hover:border-slate-300'
                    }`;

                    const statusBadgeClass = isPending 
                        ? 'bg-amber-50 text-amber-700 ring-amber-600/20' 
                        : 'bg-emerald-50 text-emerald-700 ring-emerald-600/20';

                    item.innerHTML = `
                        <div class="flex items-center justify-between gap-2">
                            <span class="font-bold text-slate-900 text-xs truncate session-title"></span>
                            <span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-[10px] font-semibold ring-1 ${statusBadgeClass}">
                                ${session.status}
                            </span>
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1 truncate">${session.business_unit?.name || 'Customer Conversation'}</p>
                    `;
                    item.querySelector('.session-title').textContent = sessionLabel(session);
                    item.addEventListener('click', () => selectChat(session.id));
                    list.appendChild(item);
                });
            }

            document.getElementById('queue-count').textContent = `${sessions.length} chats`;
            const waitingCount = sessions.filter(session => session.status === 'pending').length;
            const activeCount = sessions.filter(session => session.status === 'human_active').length;
            document.getElementById('header-waiting-badge').innerHTML = `<span class="h-2 w-2 rounded-full bg-amber-500"></span> ${waitingCount} Waiting`;
            document.getElementById('header-active-badge').innerHTML = `<span class="h-2 w-2 rounded-full bg-[#D8210B] animate-pulse"></span> ${activeCount} Active`;
        }

        function appendMessage(message) {
            if (renderedMessageIds.has(message.id)) return;
            renderedMessageIds.add(message.id);
            
            const isAgent = message.sender_type === 'agent';
            const isCustomer = message.sender_type === 'customer';
            const wrapper = document.createElement('div');
            wrapper.className = isAgent ? 'flex justify-end max-w-[85%] ml-auto' : 'flex justify-start max-w-[85%]';
            
            const bubble = document.createElement('div');
            bubble.className = isAgent 
                ? 'bg-gradient-to-br from-[#D8210B] to-[#EE4E56] text-white p-3.5 rounded-2xl rounded-tr-xs text-xs shadow-xs' 
                : 'bg-white border border-slate-200/80 p-3.5 rounded-2xl rounded-tl-xs text-xs text-slate-800 shadow-xs';
            
            const label = isAgent ? 'You (Operator)' : (isCustomer ? 'Customer' : 'Bot Assistant');
            const labelColor = isAgent ? 'text-red-200' : 'text-slate-400';
            const timeColor = isAgent ? 'text-red-200/70' : 'text-slate-400';

            bubble.innerHTML = `
                <p class="text-[10px] font-bold uppercase tracking-wider mb-1 ${labelColor}">${label}</p>
                <p class="message-body leading-relaxed"></p>
                <span class="block text-[10px] mt-1.5 text-right font-medium ${timeColor}"></span>
            `;
            bubble.querySelector('.message-body').textContent = message.message_text;
            bubble.querySelector('span').textContent = new Date(message.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            wrapper.appendChild(bubble);
            document.getElementById('chat-timeline').appendChild(wrapper);
        }

        async function loadSessions() {
            try {
                const response = await axios.get(chatEndpoints.sessions);
                sessions = response.data.data;
                renderQueue();
                if (activeSessionId && !sessions.some(session => session.id === activeSessionId)) resetChat();
            } catch (err) {
                console.error(err);
            }
        }

        async function loadMessages() {
            if (!activeSessionId) return;
            try {
                const response = await axios.get(chatEndpoints.messages(activeSessionId));
                response.data.data.forEach(appendMessage);
                
                // Toggle customer typing status bar
                const typingBar = document.getElementById('customer-typing-bar');
                if (typingBar) {
                    if (response.data.customer_typing) {
                        typingBar.classList.remove('hidden');
                    } else {
                        typingBar.classList.add('hidden');
                    }
                }

                const timeline = document.getElementById('chat-timeline');
                timeline.scrollTop = timeline.scrollHeight;
            } catch (err) {
                console.error(err);
            }
        }

        async function selectChat(sessionId) {
            activeSessionId = sessionId;
            renderedMessageIds = new Set();
            const session = sessions.find(item => item.id === sessionId);
            if (!session) return;

            document.getElementById('chat-empty-state').classList.add('hidden');
            document.getElementById('active-client-name').textContent = sessionLabel(session);
            document.getElementById('active-client-avatar').textContent = sessionLabel(session).substring(0, 2).toUpperCase();
            document.getElementById('active-client-status').textContent = session.business_unit?.name || 'Live customer session';
            
            const badge = document.getElementById('session-badge');
            badge.className = `inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-semibold ring-1 ${
                session.status === 'pending' ? 'bg-amber-50 text-amber-700 ring-amber-600/20' : 'bg-emerald-50 text-emerald-700 ring-emerald-600/20'
            }`;
            badge.textContent = session.status;
            badge.classList.remove('hidden');

            const isAssignedToMe = session.assigned_user_id;
            document.getElementById('claim-btn').disabled = Boolean(session.status === 'human_active' && isAssignedToMe);
            document.getElementById('resolve-btn').disabled = !isAssignedToMe;
            document.getElementById('reply-input').disabled = !isAssignedToMe;
            document.getElementById('send-btn').disabled = !isAssignedToMe;
            
            document.querySelectorAll('.canned-item').forEach(el => el.disabled = !isAssignedToMe);

            document.getElementById('chat-timeline').innerHTML = '';
            await loadMessages();
            renderQueue();
        }

        function resetChat() {
            activeSessionId = null;
            document.getElementById('chat-empty-state').classList.remove('hidden');
            document.getElementById('active-client-name').textContent = 'No Chat Selected';
            document.getElementById('active-client-avatar').textContent = '#';
            document.getElementById('active-client-status').textContent = 'Select a ticket from the queue to start responding';
            document.getElementById('session-badge').classList.add('hidden');
            const typingBar = document.getElementById('customer-typing-bar');
            if (typingBar) typingBar.classList.add('hidden');
            document.getElementById('chat-timeline').innerHTML = '';
            ['reply-input', 'send-btn', 'claim-btn', 'resolve-btn'].forEach(id => document.getElementById(id).disabled = true);
            document.querySelectorAll('.canned-item').forEach(el => el.disabled = true);
        }

        async function claimActiveChat() {
            const activeSession = sessions.find(session => String(session.id) === String(activeSessionId));
            const sessionId = activeSession?.id;
            if (sessionId === undefined || sessionId === null) {
                console.error('Cannot claim conversation: active session has no database ID.', {
                    activeSession,
                    activeSessionId
                });
                alert('Unable to claim this conversation because its session ID is missing.');
                return;
            }

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                await axios.post(
                    chatEndpoints.claim(sessionId),
                    { session_id: sessionId },
                    {
                        headers: {
                            'X-CSRF-TOKEN': csrfToken || '',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    }
                );
                await loadSessions();
                await selectChat(sessionId);
            } catch (error) {
                const message = error.response?.data?.message || 'Unable to claim this conversation.';
                console.error('Claim conversation failed', {
                    status: error.response?.status,
                    response: error.response?.data || error.message
                });
                console.error('Claim conversation failed:', error);
                alert(message);
            }
        }

        async function resolveActiveChat() {
            if (!activeSessionId) return;
            await axios.post(chatEndpoints.resolve(activeSessionId));
            await loadSessions();
            resetChat();
        }

        function insertCanned(text) {
            const input = document.getElementById('reply-input');
            if (input.disabled) return;
            input.value = text;
            input.focus();
        }

        // Notify customer widget when staff operator is typing
        const replyInputField = document.getElementById('reply-input');
        if (replyInputField) {
            replyInputField.addEventListener('input', () => {
                if (!activeSessionId) return;
                const now = Date.now();
                if (now - lastStaffTypingPing > 2500) {
                    lastStaffTypingPing = now;
                    axios.post(chatEndpoints.typing(activeSessionId)).catch(() => {});
                }
            });
        }

        document.getElementById('operator-reply-form').addEventListener('submit', async event => {
            event.preventDefault();
            const input = document.getElementById('reply-input');
            const message = input.value.trim();
            if (!message || !activeSessionId) return;
            const response = await axios.post(chatEndpoints.send(activeSessionId), { message });
            input.value = '';
            appendMessage(response.data.data);
            const timeline = document.getElementById('chat-timeline');
            timeline.scrollTop = timeline.scrollHeight;
        });

        loadSessions().catch(console.error);
        setInterval(() => loadSessions().then(loadMessages).catch(console.error), 3000);
    </script>
@endsection
