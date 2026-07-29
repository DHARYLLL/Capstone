<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Chat Widget</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
    <style>
        /* Custom scrollbar styling */
        ::-webkit-scrollbar {
            width: 5px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        
        .chat-bubble {
            border-radius: 1.25rem;
        }
        .chat-start .chat-bubble {
            border-bottom-left-radius: 0.25rem;
        }
        .chat-end .chat-bubble {
            border-bottom-right-radius: 0.25rem;
            background-color: #7c3aed;
            color: #ffffff;
        }
        
        /* Pulse dot animation for typing indicator */
        .typing-dot {
            animation: typingBounce 1.4s infinite ease-in-out both;
        }
        .typing-dot:nth-child(1) { animation-delay: -0.32s; }
        .typing-dot:nth-child(2) { animation-delay: -0.16s; }
        
        @keyframes typingBounce {
            0%, 80%, 100% { transform: scale(0); }
            40% { transform: scale(1); }
        }
    </style>
</head>
@php
    if ($slug === 'dariv') {
        $unitName = 'DARIV Waterproofing';
        $unitType = 'Residential & Roof Sealing';
        $unitIcon = '☔';
        $color = '#0ea5e9'; // Sky blue
        $welcome = "Welcome to DARIV Waterproofing! ☔ Need assistance with roof, balcony, deck, or gutter waterproofing today?";
        $suggestions = ["Roof waterproofing cost", "How long does it take?", "Do you offer warranty?", "Talk to a human"];
    } elseif ($slug === 'hydroguard') {
        $unitName = 'HydroGuard Solutions';
        $unitType = 'Commercial & Foundations';
        $unitIcon = '🛡️';
        $color = '#0d9488'; // Teal
        $welcome = "Hello from HydroGuard Solutions! 🛡️ How can we assist with basement sealing, elevator pits, or industrial waterproofing?";
        $suggestions = ["Basement leakage inspection", "Industrial service cost", "Do you offer site visits?", "Talk to a human"];
    } else {
        $slug = 'drymax';
        $unitName = 'DryMax Sealants';
        $unitType = 'Interior & Bathrooms';
        $unitIcon = '🚿';
        $color = '#7c3aed'; // Violet
        $welcome = "Hi! Welcome to DryMax Sealants. 🚿 How can we help you with bathroom floor sealing, tile regrouting, or minor leaks today?";
        $suggestions = ["Bathroom sealing cost", "Tile regrouting rates", "Do you offer free estimates?", "Talk to a human"];
    }
@endphp
<body class="bg-transparent font-sans text-gray-800 antialiased h-screen flex flex-col justify-end overflow-hidden">

    <!-- Chat Container -->
    <div class="flex h-full w-full flex-col overflow-hidden bg-white shadow-2xl border border-gray-100 rounded-t-3xl md:rounded-3xl">
        
        <!-- Header -->
        <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4 text-white shadow-sm" style="background: linear-gradient(135deg, {{ $color }}, #1e293b);">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-white/20 p-2 backdrop-blur-md shadow-sm">
                    @if ($slug === 'dariv')
                        <img src="{{ asset('images/logo.svg') }}" alt="DARIV Logo" class="h-full w-full object-contain filter invert">
                    @else
                        <span class="text-xl">{{ $unitIcon }}</span>
                    @endif
                </div>
                <div>
                    <h2 class="text-sm font-extrabold tracking-tight">{{ $unitName }}</h2>
                    <div class="flex items-center gap-1.5 text-xs text-white/80">
                        <span class="inline-block h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        AI assistant online
                    </div>
                </div>
            </div>
            
            <!-- Close Button (for desktop integrations where user clicks close) -->
            <button onclick="closeWidget()" class="rounded-xl p-1.5 text-white/80 hover:bg-white/10 hover:text-white transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Chat Messages Area -->
        <div id="chat-messages" class="flex-1 overflow-y-auto p-5 space-y-4 bg-gray-50/50">
            
            <!-- Welcome Message -->
            <div class="chat chat-start">
                <div class="chat-image avatar">
                    <div class="w-8 rounded-full bg-gray-100 flex items-center justify-center border text-base">
                        🤖
                    </div>
                </div>
                <div class="chat-bubble bg-white text-gray-800 border border-gray-200/80 shadow-sm text-sm leading-relaxed max-w-[85%]">
                    {{ $welcome }}
                </div>
            </div>

            <!-- Suggestions list -->
            <div id="suggestions-container" class="flex flex-wrap gap-2 pt-1 pl-10">
                @foreach($suggestions as $suggestion)
                    <button onclick="handleSuggestion('{{ $suggestion }}')" class="btn btn-xs rounded-full border border-gray-200 bg-white px-3 py-1.5 text-xs font-semibold text-gray-600 shadow-sm hover:border-violet-500 hover:bg-violet-50 hover:text-violet-600 normal-case transition-all">
                        {{ $suggestion }}
                    </button>
                @endforeach
            </div>

        </div>

        <!-- Typing Indicator (Hidden by default) -->
        <div id="typing-indicator" class="hidden px-5 py-3 bg-gray-50/50 border-t border-gray-100">
            <div class="flex items-center gap-2 text-xs text-gray-400">
                <span class="inline-block text-base">🤖</span>
                <span class="font-medium text-gray-500">AI is thinking</span>
                <div class="flex gap-1 items-center ml-1">
                    <span class="typing-dot h-1.5 w-1.5 rounded-full bg-gray-400"></span>
                    <span class="typing-dot h-1.5 w-1.5 rounded-full bg-gray-400"></span>
                    <span class="typing-dot h-1.5 w-1.5 rounded-full bg-gray-400"></span>
                </div>
            </div>
        </div>

        <!-- Footer Input Area -->
        <div class="border-t border-gray-100 bg-white p-4">
            <form onsubmit="sendMessage(event)" class="flex gap-2">
                <input id="message-input" type="text" placeholder="Type a message..." autocomplete="off" class="input input-bordered h-11 w-full rounded-2xl border-gray-200 bg-gray-50/50 text-sm focus:border-violet-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-violet-500 transition-all placeholder:text-gray-400" />
                <button type="submit" class="btn btn-square h-11 w-11 rounded-2xl bg-violet-600 text-white border-0 hover:bg-violet-700 active:scale-95 transition-all shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                    </svg>
                </button>
            </form>
        </div>

    </div>

    <!-- Script logic for simulated interactivity -->
    <script>
        const slug = "{{ $slug }}";
        const businessName = "{{ $unitName }}";
        let isHumanSession = false;
        
        // Auto-scroll to bottom of chat
        function scrollToBottom() {
            const chatDiv = document.getElementById('chat-messages');
            chatDiv.scrollTop = chatDiv.scrollHeight;
        }

        // Notify parent window to close the widget frame
        function closeWidget() {
            window.parent.postMessage({ action: 'toggleChat' }, '*');
        }

        // Add a message bubble to the chat
        function addMessage(sender, text, isAI = false, operatorName = 'System') {
            const container = document.getElementById('chat-messages');
            
            const chatWrapper = document.createElement('div');
            chatWrapper.className = sender === 'visitor' ? 'chat chat-end' : 'chat chat-start';

            // Avatar setup
            const avatarWrapper = document.createElement('div');
            avatarWrapper.className = 'chat-image avatar';
            const avatarInner = document.createElement('div');
            avatarInner.className = 'w-8 rounded-full flex items-center justify-center border text-base bg-gray-100';
            
            if (sender === 'visitor') {
                avatarInner.innerHTML = '👤';
            } else if (isAI) {
                avatarInner.innerHTML = '🤖';
            } else {
                avatarInner.innerHTML = '👨‍💼';
            }
            avatarWrapper.appendChild(avatarInner);
            chatWrapper.appendChild(avatarWrapper);

            // Bubble content
            const bubble = document.createElement('div');
            bubble.className = sender === 'visitor' 
                ? 'chat-bubble shadow-sm text-sm leading-relaxed max-w-[85%]' 
                : 'chat-bubble bg-white text-gray-800 border border-gray-200/80 shadow-sm text-sm leading-relaxed max-w-[85%]';
            
            // Text content
            bubble.innerHTML = text;
            chatWrapper.appendChild(bubble);

            // Header labels for operators/time
            if (sender !== 'visitor' && !isAI) {
                const header = document.createElement('div');
                header.className = 'chat-header text-[10px] text-gray-400 mb-1 pl-1';
                header.textContent = operatorName;
                chatWrapper.insertBefore(header, bubble);
            }

            container.appendChild(chatWrapper);
            scrollToBottom();
        }

        // Handle suggestion button click
        function handleSuggestion(text) {
            // Hide suggestions container after selection to keep it clean
            document.getElementById('suggestions-container').style.display = 'none';
            addMessage('visitor', text);
            triggerBotResponse(text);
        }

        // Send a custom message from input field
        function sendMessage(e) {
            e.preventDefault();
            const input = document.getElementById('message-input');
            const text = input.value.trim();
            if (!text) return;
            
            // Hide suggestions
            document.getElementById('suggestions-container').style.display = 'none';

            addMessage('visitor', text);
            input.value = '';

            if (isHumanSession) {
                // Mock human operator response
                triggerOperatorResponse(text);
            } else {
                // AI response
                triggerBotResponse(text);
            }
        }

        // Trigger bot AI response simulation
        function triggerBotResponse(userMsg) {
            const msgLower = userMsg.toLowerCase();
            const typingIndicator = document.getElementById('typing-indicator');
            typingIndicator.classList.remove('hidden');
            scrollToBottom();

            setTimeout(() => {
                typingIndicator.classList.add('hidden');
                let responseText = '';

                // Matching responses depending on business
                if (msgLower.includes('human') || msgLower.includes('agent') || msgLower.includes('operator') || msgLower.includes('staff') || msgLower.includes('person') || msgLower.includes('talk')) {
                    responseText = `Sure! I am routing you to a human operator. Please stand by a moment... 📞`;
                    addMessage('bot', responseText, true);
                    triggerHumanHandoff();
                    return;
                }

                if (slug === 'dariv') {
                    if (msgLower.includes('rate') || msgLower.includes('price') || msgLower.includes('cost') || msgLower.includes('estimate') || msgLower.includes('quote')) {
                        responseText = `Our DARIV residential waterproofing rates are estimated based on area size:<br>• <b>Roof Deck Waterproofing</b>: Starts at 450 PHP / sqm<br>• <b>Balcony & Terrace Sealing</b>: Starts at 500 PHP / sqm<br>• <b>Gutter Leak Repair</b>: Custom quotation. All works include a <b>5-year warranty</b>!`;
                    } else if (msgLower.includes('time') || msgLower.includes('duration') || msgLower.includes('long')) {
                        responseText = `A standard residential roof deck project takes about <b>3 to 5 sunny days</b> to complete, allowing proper curing time between coats.`;
                    } else if (msgLower.includes('warrant') || msgLower.includes('guarante')) {
                        responseText = `Yes! We provide a full <b>5-year warranty</b> on all our roof and balcony waterproofing services against any leakage.`;
                    } else {
                        responseText = `Thanks for asking! I'm DARIV's AI assistant. Ask me about our roof deck waterproofing costs, project duration, warranties, or locations. Type "Talk to human" to reach our estimators.`;
                    }
                } else if (slug === 'hydroguard') {
                    if (msgLower.includes('inspection') || msgLower.includes('visit') || msgLower.includes('check') || msgLower.includes('look')) {
                        responseText = `We conduct professional on-site inspections for commercial basements and foundation leaks. Inspections in Cebu area are <b>free of charge</b>!`;
                    } else if (msgLower.includes('rate') || msgLower.includes('price') || msgLower.includes('cost') || msgLower.includes('quote')) {
                        responseText = `Commercial and basement sealing rates depend on leakage severity and structure type. We offer polyurethane injection and bentonite clay membrane coatings. Site inspection is required for a final quote.`;
                    } else if (msgLower.includes('basement') || msgLower.includes('pit') || msgLower.includes('foundat')) {
                        responseText = `We specialize in heavy-duty commercial foundation systems: pressure grouting, bentonite membrane application, and negative-side crystalline waterproofing for elevator pits and basements.`;
                    } else {
                        responseText = `Hello! I'm HydroGuard's AI assistant. Ask me about commercial inspections, basement leakage repairs, elevator pit waterproofing, or site visits. Type "Talk to human" to speak to our project engineer!`;
                    }
                } else { // drymax
                    if (msgLower.includes('bathroom') || msgLower.includes('shower') || msgLower.includes('toilet')) {
                        responseText = `For leaking bathrooms, we perform our signature <b>DryMax Tile-Over waterproofing</b> starting at 8,500 PHP per bathroom. No major hacking of tiles needed!`;
                    } else if (msgLower.includes('grout') || msgLower.includes('re-grout') || msgLower.includes('tile')) {
                        responseText = `We offer premium epoxy tile regrouting to prevent water seepage. Rates start at 1,500 PHP per toilet floor.`;
                    } else if (msgLower.includes('estimate') || msgLower.includes('cost') || msgLower.includes('price')) {
                        responseText = `We provide free online estimates! Simply send us photos of the leaking bathroom floor, tile cracks, or wall seepage, and we will send a rough quote right away.`;
                    } else {
                        responseText = `Hi! I'm DryMax Sealants' AI assistant. Ask me about bathroom waterproofing, epoxy regrouting, free estimates, or window sealing. You can also request to chat with our technical staff directly.`;
                    }
                }

                addMessage('bot', responseText, true);
            }, 1200);
        }

        // Simulate human handoff sequence
        function triggerHumanHandoff() {
            isHumanSession = true;
            
            setTimeout(() => {
                const systemMsg = document.createElement('div');
                systemMsg.className = 'my-2 flex items-center justify-center';
                systemMsg.innerHTML = `<span class="rounded-full bg-violet-50 border border-violet-100 px-3.5 py-1 text-[11px] font-bold text-violet-600 shadow-sm">⚡ System: Routed to Live Operator</span>`;
                document.getElementById('chat-messages').appendChild(systemMsg);
                scrollToBottom();

                // Mock Operator joins 2 seconds later
                setTimeout(() => {
                    const typingIndicator = document.getElementById('typing-indicator');
                    typingIndicator.querySelector('span.font-medium').textContent = 'Dave (Staff) is typing';
                    typingIndicator.classList.remove('hidden');
                    scrollToBottom();

                    setTimeout(() => {
                        typingIndicator.classList.add('hidden');
                        addMessage('staff', `Hi there! I am Dave from ${businessName} staff. I see you want to talk to an operator. How can I assist you today?`, false, 'Dave · Operator');
                    }, 1500);
                }, 2000);
            }, 1500);
        }

        // Mock response from operator
        function triggerOperatorResponse(userMsg) {
            const typingIndicator = document.getElementById('typing-indicator');
            typingIndicator.querySelector('span.font-medium').textContent = 'Dave (Staff) is typing';
            typingIndicator.classList.remove('hidden');
            scrollToBottom();

            setTimeout(() => {
                typingIndicator.classList.add('hidden');
                addMessage('staff', `Thanks for that. I am checking the records details for you right now regarding your request. Can you give me one moment?`, false, 'Dave · Operator');
            }, 2000);
        }

        // Initial scroll
        window.addEventListener('load', scrollToBottom);
    </script>
</body>
</html>
