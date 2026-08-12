<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
    $slug = $slug ?? ($businessUnit->slug ?? 'dariv');

    if ($slug === 'dariv') {
        $unitName = 'DARIV Waterproofing';
        $unitType = 'Residential & Roof Sealing';
        $unitIcon = '☔';
        $color = '#0ea5e9';
        $welcome = "Welcome to DARIV Waterproofing! ☔ Need assistance with roof, balcony, deck, or gutter waterproofing today?";
        $suggestions = ["Roof waterproofing cost", "How long does it take?", "Do you offer warranty?", "Talk to a human"];
    } elseif ($slug === 'hydroguard') {
        $unitName = 'HydroGuard Solutions';
        $unitType = 'Commercial & Foundations';
        $unitIcon = '🛡️';
        $color = '#0d9488';
        $welcome = "Hello from HydroGuard Solutions! 🛡️ How can we assist with basement sealing, elevator pits, or industrial waterproofing?";
        $suggestions = ["Basement leakage inspection", "Industrial service cost", "Do you offer site visits?", "Talk to a human"];
    } else {
        $slug = 'drymax';
        $unitName = 'DryMax Sealants';
        $unitType = 'Interior & Bathrooms';
        $unitIcon = '🚿';
        $color = '#7c3aed';
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
                    <button type="button" onclick="handleSuggestion('{{ $suggestion }}')" class="btn btn-xs rounded-full border border-gray-200 bg-white px-3 py-1.5 text-xs font-semibold text-gray-600 shadow-sm hover:border-violet-500 hover:bg-violet-50 hover:text-violet-600 normal-case transition-all">
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
            <form id="chat-form" class="flex items-center gap-2">
                @csrf
                <input id="prompt" name="prompt" type="text" placeholder="Type a message..." autocomplete="off" class="input input-bordered h-11 w-full rounded-2xl border-gray-200 bg-gray-50/50 text-sm focus:border-violet-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-violet-500 transition-all placeholder:text-gray-400" />
                
                <button type="submit" class="btn btn-square h-11 w-11 rounded-2xl bg-violet-600 text-white border-0 hover:bg-violet-700 active:scale-95 transition-all shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                    </svg>
                </button>
            </form>
            <p id="chat-status" class="mt-2 text-xs text-gray-400 text-center">Ask any question about our services.</p>
        </div>

    </div>

    <!-- Active Live Chat Script -->
    <script>
        const chatForm = document.getElementById('chat-form');
        const promptField = document.getElementById('prompt');
        const messageContainer = document.getElementById('chat-messages');
        const chatStatus = document.getElementById('chat-status');
        const typingIndicator = document.getElementById('typing-indicator');
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        // Extract user_id from query string or fallback to Blade value
        const urlParams = new URLSearchParams(window.location.search);
        const userId = urlParams.get('user_id') || '{{ $userId ?? "guest" }}';

        // Auto-scroll chat window
        function scrollToBottom() {
            messageContainer.scrollTop = messageContainer.scrollHeight;
        }

        // Send postMessage to parent iframe loader to toggle chat visibility
        function closeWidget() {
            window.parent.postMessage({ action: 'toggleChat' }, '*');
        }

        // Helper function to append bubbles to the DOM
        function appendBubble(message, isUser = false) {
            const wrapper = document.createElement('div');
            wrapper.className = isUser ? 'chat chat-end' : 'chat chat-start';

            const avatarWrapper = document.createElement('div');
            avatarWrapper.className = 'chat-image avatar';
            avatarWrapper.innerHTML = `<div class="w-8 rounded-full bg-gray-100 flex items-center justify-center border text-base">${isUser ? '👤' : '🤖'}</div>`;

            const bubble = document.createElement('div');
            bubble.className = isUser 
                ? 'chat-bubble shadow-sm text-sm leading-relaxed max-w-[85%]' 
                : 'chat-bubble bg-white text-gray-800 border border-gray-200/80 shadow-sm text-sm leading-relaxed max-w-[85%]';
            
            // Allow HTML formatting in AI responses
            bubble.innerHTML = message;

            wrapper.appendChild(avatarWrapper);
            wrapper.appendChild(bubble);
            messageContainer.appendChild(wrapper);

            scrollToBottom();
        }

        // Handle quick action suggestion buttons
        function handleSuggestion(suggestionText) {
            const suggestionsEl = document.getElementById('suggestions-container');
            if (suggestionsEl) suggestionsEl.style.display = 'none';

            promptField.value = suggestionText;
            chatForm.dispatchEvent(new Event('submit', { cancelable: true }));
        }

        // Form Submit Event Handler
        chatForm.addEventListener('submit', async (event) => {
            event.preventDefault();

            const prompt = promptField.value.trim();
            if (!prompt) return;

            // Hide initial suggestions if present
            const suggestionsEl = document.getElementById('suggestions-container');
            if (suggestionsEl) suggestionsEl.style.display = 'none';

            // 1. Render visitor prompt immediately
            appendBubble(prompt, true);
            promptField.value = '';
            
            // 2. Show UI loaders
            typingIndicator.classList.remove('hidden');
            scrollToBottom();
            chatStatus.textContent = 'Generating a response...';

            try {
                const askEndpoint = '{{ isset($businessUnit) ? route("chat.ask", ["businessUnit" => $businessUnit->id]) : "" }}';

                if (!askEndpoint) {
                    throw new Error('Business Unit endpoint route is missing.');
                }

                // 3. Make AJAX API call to backend
                const response = await fetch(askEndpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({ 
                        prompt: prompt,
                        user_id: userId
                    }),
                });

                const payload = await response.json();

                if (!response.ok) {
                    throw new Error(payload.message || 'Server error');
                }

                // 4. Render AI response
                appendBubble(payload.response ?? 'No response was returned.', false);
                chatStatus.textContent = 'Response generated.';
            } catch (error) {
                console.error('Chat submit error:', error);
                appendBubble('Something went wrong while sending your message.', false);
                chatStatus.textContent = 'The request could not be completed.';
            } finally {
                typingIndicator.classList.add('hidden');
                scrollToBottom();
            }
        });
    </script>
</body>
</html>