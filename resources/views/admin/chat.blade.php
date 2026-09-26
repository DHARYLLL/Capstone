@extends('layouts.admin')

@section('page_title', 'Live Chat & Staff Handoff')
@section('breadcrumbs', 'Admin / Chat & Handoff')

@section('content')
    <div class="space-y-8">
        <!-- Dashboard Header -->
        <section class="rounded-[2rem] border border-[#e2e8f0] bg-white p-6 shadow-sm lg:p-8">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <h1 class="text-3xl font-black tracking-tight text-gray-900 font-sans">Live Chat & Staff Handoff</h1>
                    <p class="mt-2 max-w-3xl text-sm leading-6 text-gray-500">Operate a split-screen console for active queues, conversation timelines, and live handoff actions.</p>
                </div>
                <div class="flex gap-2">
                    <span id="header-waiting-badge" class="badge badge-warning badge-outline font-bold px-3 py-2.5 text-xs rounded-full">{{ $waitingCount }} Waiting</span>
                    <span id="header-active-badge" class="badge badge-info badge-outline font-bold px-3 py-2.5 text-xs rounded-full">{{ $activeCount }} Active</span>
                </div>
            </div>
        </section>

        <!-- Main Console Layout -->
        <section class="grid gap-6 xl:grid-cols-[300px_1fr_280px]">
            
            <!-- Left Column: Client Queue -->
            <div class="card bg-base-100 shadow-sm border border-gray-100 rounded-3xl h-[650px] overflow-hidden flex flex-col">
                <div class="p-5 border-b border-gray-100 flex items-center justify-between bg-slate-50/30">
                    <h2 class="text-xs font-bold text-gray-900 uppercase tracking-wider">Active Queue</h2>
                    <span id="queue-count" class="badge bg-slate-100 border-0 text-gray-700 font-bold px-2 py-1.5 text-[10px]">{{ $sessions->count() }} chats</span>
                </div>
                <div class="flex-1 overflow-y-auto p-4 space-y-3" id="queue-list">
                    <!-- Queue items populated via JS -->
                </div>
            </div>

            <!-- Center Column: Conversation View -->
            <div class="card bg-base-100 shadow-sm border border-gray-100 rounded-3xl h-[650px] flex flex-col justify-between overflow-hidden relative">
                <!-- Active Chat Header -->
                <div class="p-5 border-b border-gray-100 flex items-center justify-between bg-slate-50/50" id="chat-header">
                    <div class="min-w-0">
                        <h3 class="font-extrabold text-gray-900 text-base truncate" id="active-client-name">No Chat Selected</h3>
                        <p class="text-xs text-gray-400 mt-0.5 truncate" id="active-client-status">Select a conversation from the queue to start reply</p>
                    </div>
                    <div id="session-badge" class="badge badge-sm font-bold border-0 text-[10px] uppercase tracking-wider py-2 hidden"></div>
                </div>

                <!-- Empty State Placeholder -->
                <div id="chat-empty-state" class="absolute inset-0 flex flex-col items-center justify-center bg-white z-10 p-8 text-center space-y-4">
                    <div class="h-16 w-16 bg-slate-50 rounded-2xl flex items-center justify-center text-3xl">💬</div>
                    <div>
                        <h3 class="font-bold text-gray-800">No Chat Selected</h3>
                        <p class="text-xs text-gray-400 mt-1">Select a waiting client from the active queue to take over the session.</p>
                    </div>
                </div>

                <!-- Chat Timeline Container -->
                <div class="flex-1 overflow-y-auto p-5 space-y-4 bg-slate-50/20" id="chat-timeline">
                    <!-- Messages injected dynamically -->
                </div>

                <!-- Message Input Bar -->
                <div class="p-4 border-t border-gray-100 bg-white" id="input-container">
                    <form id="operator-reply-form" class="flex gap-2">
                        <input type="text" id="reply-input" disabled placeholder="Claim this chat to write a reply..." 
                            class="input input-bordered flex-1 rounded-2xl border-gray-200 bg-slate-50 text-sm text-gray-800 focus:border-indigo-500 focus:bg-white focus:outline-none">
                        <button type="submit" id="send-btn" disabled 
                            class="btn rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-6 py-3 h-auto">
                            Send
                        </button>
                    </form>
                </div>
            </div>

            <!-- Right Column: Operator Controls -->
            <div class="card bg-base-100 shadow-sm border border-gray-100 rounded-3xl h-[650px] flex flex-col overflow-hidden">
                <div class="p-5 border-b border-gray-100 bg-slate-50/30">
                    <h2 class="text-xs font-bold text-gray-900 uppercase tracking-wider">Handoff Actions</h2>
                </div>
                <div class="p-5 flex-1 flex flex-col justify-between" id="control-panel">
                    <div class="space-y-4">
                        <button onclick="claimActiveChat()" id="claim-btn" disabled 
                            class="btn w-full rounded-2xl border-0 bg-indigo-600 hover:bg-indigo-700 text-white font-bold h-auto py-3.5">
                            👋 Claim & Take Over
                        </button>
                        
                        <div class="dropdown w-full">
                            <button id="canned-btn" tabindex="0" role="button" disabled 
                                class="btn w-full rounded-2xl border border-gray-200 bg-white hover:bg-slate-50 text-gray-700 font-bold text-left justify-between h-auto py-3 text-xs">
                                <span>💬 Quick Responses</span>
                                <span>▼</span>
                            </button>
                            <ul tabindex="0" class="dropdown-content z-30 menu p-2 shadow-lg bg-white border border-gray-200 rounded-2xl w-full mt-1 space-y-1 text-xs">
                                <li><a onclick="insertCanned('Hello! I am reviewing your case now.')">Reviewing case now</a></li>
                                <li><a onclick="insertCanned('We will schedule a site visit shortly.')">Schedule site visit</a></li>
                                <li><a onclick="insertCanned('Could you upload a photo of the leakage?')">Ask for photos</a></li>
                                <li><a onclick="insertCanned('Our estimator will email your final quote today.')">Send email quote</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="space-y-3 pt-6 border-t border-gray-100">
                        <button onclick="resolveActiveChat()" id="resolve-btn" disabled 
                            class="btn w-full rounded-2xl border-0 bg-emerald-600 hover:bg-emerald-700 text-white font-bold h-auto py-3.5">
                            ✓ Mark Resolved
                        </button>
                    </div>
                </div>
            </div>
        </section>
    </div>

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
            sessions.forEach(session => {
                const item = document.createElement('div');
                const waiting = ['waiting', 'pending', 'queued'].includes(session.raw_status);
                item.className = `p-4 rounded-2xl border transition cursor-pointer text-left ${String(session.id) === String(activeId) ? 'bg-indigo-50/50 border-indigo-200 shadow-sm ring-1 ring-indigo-150' : 'bg-white border-gray-100 hover:bg-slate-50'}`;
                item.addEventListener('click', () => selectChat(session.id));
                const heading = document.createElement('div');
                heading.className = 'flex justify-between items-start';
                heading.appendChild(textElement('span', 'font-bold text-gray-900 text-sm', session.customer_name));
                heading.appendChild(textElement('span', `badge badge-xs font-bold border-0 px-2 py-1 text-[9px] uppercase tracking-wide ${waiting ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700'}`, waiting ? 'WAITING' : 'ACTIVE'));
                item.appendChild(heading);
                item.appendChild(textElement('p', 'text-xs text-gray-500 mt-1 truncate', session.latest_message));
                if (!waiting && session.assigned_user_name) {
                    item.appendChild(textElement('p', 'text-xs text-blue-600 mt-1 truncate', `Active • Assigned to ${session.assigned_user_name}`));
                }
                list.appendChild(item);
            });
        }

        async function refreshQueue() {
            try {
                const payload = await requestJson(chatRoutes.sessions);
                renderQueue(payload.data);
                document.getElementById('queue-count').innerText = `${payload.data.length} chats`;
                document.getElementById('header-waiting-badge').innerText = `${payload.waiting_count} Waiting`;
                document.getElementById('header-active-badge').innerText = `${payload.active_count} Active`;
                if (activeId && !payload.data.some(session => String(session.id) === String(activeId))) resetChat();
                if (activeId) {
                    activeSession = payload.data.find(session => String(session.id) === String(activeId)) || activeSession;
                    if (activeSession) {
                        updateHeader();
                        updateControls();
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
                activeSession = queue.data.find(session => String(session.id) === String(id));
                renderQueue(queue.data);
                updateHeader();
                renderTimeline(messages.data);
                updateControls();
            } catch (error) { console.error(error); }
        }

        function updateHeader() {
            if (!activeSession) return;
            document.getElementById('active-client-name').innerText = activeSession.customer_name;
            document.getElementById('active-client-status').innerText = activeSession.latest_message;
            const badge = document.getElementById('session-badge');
            badge.className = `badge badge-sm font-bold border-0 text-[10px] uppercase tracking-wider py-2 ${activeSession.status === 'waiting' ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700'}`;
            badge.innerText = activeSession.status === 'waiting' ? 'WAITING' : 'ACTIVE';
        }

        function updateControls() {
            const claimed = activeSession?.assigned_user_id !== null && activeSession?.assigned_user_id !== undefined;
            const waiting = activeSession?.status === 'waiting';
            document.getElementById('reply-input').disabled = !claimed;
            document.getElementById('send-btn').disabled = !claimed;
            document.getElementById('claim-btn').disabled = !activeSession;
            document.getElementById('canned-btn').disabled = !claimed;
            document.getElementById('resolve-btn').disabled = !claimed;
            document.getElementById('reply-input').placeholder = claimed ? 'Type a message to reply live...' : 'Claim this chat to write a reply...';
            document.getElementById('reply-input').classList.toggle('bg-slate-50', !claimed);
        }

        function renderTimeline(messages) {
            const timeline = document.getElementById('chat-timeline');
            timeline.innerHTML = '';
            messages.forEach(message => {
                const time = message.created_at ? new Date(message.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) : '';
                const sender = message.sender_type;
                const element = document.createElement('div');
                if (sender === 'system') {
                    element.className = 'flex justify-center my-2';
                    element.appendChild(textElement('span', 'bg-slate-100 border border-slate-200/50 text-[10px] font-bold text-slate-500 uppercase tracking-wider px-3 py-1 rounded-full', message.message_text));
                } else if (sender === 'bot' || sender === 'ai') {
                    element.className = 'flex justify-start items-end gap-2.5 max-w-[85%]';
                    element.innerHTML = '<div class="h-8 w-8 rounded-lg bg-indigo-50 border border-indigo-150 flex items-center justify-center text-sm shrink-0">🤖</div><div class="bg-white border border-gray-150 p-4 rounded-[1.5rem] rounded-bl-none text-sm text-gray-800 shadow-sm leading-relaxed"><p class="text-[10px] font-extrabold uppercase tracking-widest text-indigo-600 mb-1">AI Assistant</p><span class="message-text"></span><span class="block text-[9px] text-gray-400 mt-2 text-right"></span></div>';
                    element.querySelector('.message-text').textContent = message.message_text;
                    element.querySelector('span:last-child').textContent = time;
                } else if (sender === 'operator' || sender === 'staff' || sender === 'agent') {
                    element.className = 'flex justify-end gap-2.5 max-w-[85%] ml-auto';
                    element.innerHTML = '<div class="bg-indigo-600 text-white p-4 rounded-[1.5rem] rounded-br-none text-sm shadow-sm leading-relaxed"><p class="text-[10px] font-extrabold uppercase tracking-widest text-white/80 mb-1">You (Operator)</p><span class="message-text"></span><span class="block text-[9px] text-white/60 mt-2 text-right"></span></div>';
                    element.querySelector('.message-text').textContent = message.message_text;
                    element.querySelector('span:last-child').textContent = time;
                } else {
                    element.className = 'flex justify-start gap-2.5 max-w-[85%]';
                    element.innerHTML = '<div class="bg-slate-100 border border-slate-200/60 p-4 rounded-[1.5rem] rounded-tl-none text-sm text-gray-800 leading-relaxed"><p class="text-[10px] font-extrabold uppercase tracking-widest text-slate-500 mb-1">Client User</p><span class="message-text"></span><span class="block text-[9px] text-gray-400 mt-2"></span></div>';
                    element.querySelector('.message-text').textContent = message.message_text;
                    element.querySelector('span:last-child').textContent = time;
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
            document.getElementById('session-badge').classList.add('hidden');
            document.getElementById('chat-timeline').innerHTML = '';
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
            document.getElementById('reply-input').value = text;
            document.getElementById('reply-input').focus();
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
            await selectChat(activeId);
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