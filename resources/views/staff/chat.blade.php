@extends('layouts.staff')

@section('page_title', 'Live Chat & Staff Handoff')
@section('breadcrumbs', 'Staff / Chat & Handoff')

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
                    <span id="header-waiting-badge" class="badge badge-warning badge-outline font-bold px-3 py-2.5 text-xs rounded-full">2 Waiting</span>
                    <span id="header-active-badge" class="badge badge-info badge-outline font-bold px-3 py-2.5 text-xs rounded-full">1 Active</span>
                </div>
            </div>
        </section>

        <!-- Main Console Layout -->
        <section class="grid gap-6 xl:grid-cols-[300px_1fr_280px]">
            
            <!-- Left Column: Client Queue -->
            <div class="card bg-base-100 shadow-sm border border-gray-100 rounded-3xl h-[650px] overflow-hidden flex flex-col">
                <div class="p-5 border-b border-gray-100 flex items-center justify-between bg-slate-50/30">
                    <h2 class="text-xs font-bold text-gray-900 uppercase tracking-wider">Active Queue</h2>
                    <span id="queue-count" class="badge bg-slate-100 border-0 text-gray-700 font-bold px-2 py-1.5 text-[10px]">3 chats</span>
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

    <!-- Client-side Interactive Session State Simulation -->
    <script>
        // Initial dataset representing databases
        const chats = {
            'maria-d': {
                name: 'Maria D.',
                meta: 'Roof leak repair quote',
                status: 'waiting',
                claimed: false,
                messages: [
                    { sender: 'client', text: 'How much does it cost to waterproof a residential roof deck?', time: '10:14 AM' },
                    { sender: 'bot', text: 'Our DARIV residential waterproofing rates start at PHP 450 per sqm. Curing takes 3-5 days. All works include a 5-year warranty!', time: '10:14 AM' },
                    { sender: 'client', text: 'Can I speak to an operator to get a site visit scheduled?', time: '10:15 AM' },
                    { sender: 'system', text: 'Handoff triggered. Conversation routed to waiting operator queue.', time: '10:15 AM' }
                ]
            },
            'john-p': {
                name: 'John P.',
                meta: 'Downpayment invoice questions',
                status: 'waiting',
                claimed: false,
                messages: [
                    { sender: 'client', text: 'I received the quote but where do I pay the 30% downpayment?', time: '09:40 AM' },
                    { sender: 'bot', text: 'Hello! I can transfer you to our manager to send downpayment invoices or links. Please type "Talk to human" or wait a moment.', time: '09:41 AM' },
                    { sender: 'client', text: 'Talk to human please.', time: '09:41 AM' },
                    { sender: 'system', text: 'Handoff triggered. Routed reason: Downpayment/billing inquiry.', time: '09:41 AM' }
                ]
            },
            'aya-r': {
                name: 'Aya R.',
                meta: 'Balcony sealing warranty',
                status: 'active',
                claimed: true,
                messages: [
                    { sender: 'client', text: 'Is balcony sealing also covered by the 5-year warranty?', time: '09:02 AM' },
                    { sender: 'bot', text: 'Yes! We provide a full 5-year warranty on all our roof and balcony waterproofing services against any leakage.', time: '09:03 AM' },
                    { sender: 'system', text: 'Operator Mae S. claimed this chat session.', time: '09:05 AM' },
                    { sender: 'operator', text: 'Hello Aya! Yes, balcony sealing is fully covered. Would you like us to inspect the balcony size first?', time: '09:06 AM' }
                ]
            }
        };

        let activeId = null;

        // Render queue listings on page load
        function renderQueue() {
            const list = document.getElementById('queue-list');
            list.innerHTML = '';
            
            let count = 0;
            let waitingCount = 0;
            let activeCount = 0;

            for (const [id, chat] of Object.entries(chats)) {
                count++;
                if (chat.status === 'waiting') waitingCount++;
                if (chat.status === 'active') activeCount++;

                const isActive = (id === activeId);
                const badgeColor = chat.status === 'waiting' ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700';
                
                const item = document.createElement('div');
                item.onclick = () => selectChat(id);
                item.className = `p-4 rounded-2xl border transition cursor-pointer text-left ${isActive ? 'bg-indigo-50/50 border-indigo-200 shadow-sm ring-1 ring-indigo-150' : 'bg-white border-gray-100 hover:bg-slate-50'}`;
                item.innerHTML = `
                    <div class="flex justify-between items-start">
                        <span class="font-bold text-gray-900 text-sm">${chat.name}</span>
                        <span class="badge badge-xs font-bold border-0 px-2 py-1 text-[9px] uppercase tracking-wide ${badgeColor}">${chat.status}</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 truncate">${chat.meta}</p>
                `;
                list.appendChild(item);
            }

            // Update top counters
            document.getElementById('queue-count').innerText = `${count} chats`;
            document.getElementById('header-waiting-badge').innerText = `${waitingCount} Waiting`;
            document.getElementById('header-active-badge').innerText = `${activeCount} Active`;
        }

        // Active chat selection
        function selectChat(id) {
            activeId = id;
            const chat = chats[id];
            
            // Remove empty state placeholder
            document.getElementById('chat-empty-state').classList.add('hidden');
            
            // Header content
            document.getElementById('active-client-name').innerText = chat.name;
            document.getElementById('active-client-status').innerText = chat.meta;
            
            const badge = document.getElementById('session-badge');
            badge.classList.remove('hidden');
            badge.innerText = chat.status;
            if (chat.status === 'waiting') {
                badge.className = 'badge badge-sm font-bold border-0 text-[10px] uppercase tracking-wider py-2 bg-amber-100 text-amber-700';
            } else {
                badge.className = 'badge badge-sm font-bold border-0 text-[10px] uppercase tracking-wider py-2 bg-blue-100 text-blue-700';
            }

            // Load message timelines
            renderTimeline();

            // Toggle controls status
            const inputsDisabled = !chat.claimed;
            document.getElementById('reply-input').disabled = inputsDisabled;
            document.getElementById('send-btn').disabled = inputsDisabled;
            
            document.getElementById('claim-btn').disabled = chat.claimed;
            document.getElementById('canned-btn').disabled = inputsDisabled;
            document.getElementById('resolve-btn').disabled = !chat.claimed;

            if (inputsDisabled) {
                document.getElementById('reply-input').placeholder = "Claim this chat to write a reply...";
                document.getElementById('reply-input').classList.add('bg-slate-50');
            } else {
                document.getElementById('reply-input').placeholder = "Type a message to reply live...";
                document.getElementById('reply-input').classList.remove('bg-slate-50');
            }

            renderQueue();
        }

        // Render message thread list
        function renderTimeline() {
            const timeline = document.getElementById('chat-timeline');
            timeline.innerHTML = '';
            const chat = chats[activeId];

            chat.messages.forEach(msg => {
                const el = document.createElement('div');
                
                if (msg.sender === 'system') {
                    // System logs
                    el.className = 'flex justify-center my-2';
                    el.innerHTML = `<span class="bg-slate-100 border border-slate-200/50 text-[10px] font-bold text-slate-500 uppercase tracking-wider px-3 py-1 rounded-full">${msg.text}</span>`;
                } else if (msg.sender === 'bot') {
                    // Bot responses
                    el.className = 'flex justify-start items-end gap-2.5 max-w-[85%]';
                    el.innerHTML = `
                        <div class="h-8 w-8 rounded-lg bg-indigo-50 border border-indigo-150 flex items-center justify-center text-sm shrink-0">🤖</div>
                        <div class="bg-white border border-gray-150 p-4 rounded-[1.5rem] rounded-bl-none text-sm text-gray-800 shadow-sm leading-relaxed">
                            <p class="text-[10px] font-extrabold uppercase tracking-widest text-indigo-600 mb-1">AI Assistant</p>
                            ${msg.text}
                            <span class="block text-[9px] text-gray-400 mt-2 text-right">${msg.time}</span>
                        </div>
                    `;
                } else if (msg.sender === 'operator') {
                    // Operator answers
                    el.className = 'flex justify-end gap-2.5 max-w-[85%] ml-auto';
                    el.innerHTML = `
                        <div class="bg-indigo-600 text-white p-4 rounded-[1.5rem] rounded-br-none text-sm shadow-sm leading-relaxed">
                            <p class="text-[10px] font-extrabold uppercase tracking-widest text-white/80 mb-1">You (Operator)</p>
                            ${msg.text}
                            <span class="block text-[9px] text-white/60 mt-2 text-right">${msg.time}</span>
                        </div>
                    `;
                } else {
                    // Customer client text
                    el.className = 'flex justify-start gap-2.5 max-w-[85%]';
                    el.innerHTML = `
                        <div class="bg-slate-100 border border-slate-200/60 p-4 rounded-[1.5rem] rounded-tl-none text-sm text-gray-800 leading-relaxed">
                            <p class="text-[10px] font-extrabold uppercase tracking-widest text-slate-500 mb-1">Client User</p>
                            ${msg.text}
                            <span class="block text-[9px] text-gray-400 mt-2">${msg.time}</span>
                        </div>
                    `;
                }
                timeline.appendChild(el);
            });

            // Auto-scroll timeline to bottom
            timeline.scrollTop = timeline.scrollHeight;
        }

        // Claim Chat
        function claimActiveChat() {
            if (!activeId) return;
            const chat = chats[activeId];
            chat.claimed = true;
            chat.status = 'active';
            chat.messages.push({ sender: 'system', text: 'You claimed this chat session.', time: 'Just now' });
            
            selectChat(activeId);
        }

        // Resolve Chat
        function resolveActiveChat() {
            if (!activeId) return;
            delete chats[activeId];
            activeId = null;

            // Reset back to empty placeholder screen
            document.getElementById('chat-empty-state').classList.remove('hidden');
            document.getElementById('active-client-name').innerText = "No Chat Selected";
            document.getElementById('active-client-status').innerText = "Select a conversation from the queue to start reply";
            document.getElementById('session-badge').classList.add('hidden');
            document.getElementById('chat-timeline').innerHTML = '';
            
            document.getElementById('reply-input').disabled = true;
            document.getElementById('send-btn').disabled = true;
            document.getElementById('claim-btn').disabled = true;
            document.getElementById('canned-btn').disabled = true;
            document.getElementById('resolve-btn').disabled = true;

            renderQueue();
        }

        // Quick responses insertion
        function insertCanned(text) {
            document.getElementById('reply-input').value = text;
            document.getElementById('reply-input').focus();
        }

        // Submit replies
        document.getElementById('operator-reply-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const input = document.getElementById('reply-input');
            const text = input.value.trim();
            if (!text || !activeId) return;

            const chat = chats[activeId];
            const now = new Date();
            const timeStr = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

            chat.messages.push({ sender: 'operator', text: text, time: timeStr });
            input.value = '';
            renderTimeline();
        });

        // Bootstrap on page load
        renderQueue();
    </script>
@endsection
