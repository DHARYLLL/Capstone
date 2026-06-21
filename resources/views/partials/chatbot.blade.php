{{-- filepath: c:\Users\dhary\Desktop\Capstone\capstone1\resources\views\partials\chatbot.blade.php --}}
@php
    $chatBusiness   = $business ?? null;
    $chatBizName    = data_get($chatBusiness, 'name', 'us');
    $chatGreeting   = data_get($chatBusiness, 'chat_greeting', "Hello! I'm the AI assistant for {$chatBizName}. How can I help you today?");
    $chatPrompts    = collect(data_get($chatBusiness, 'chat_prompts') ?? [
        ['icon' => '📋', 'label' => 'What services do you offer?',    'question' => 'What services do you offer?'],
        ['icon' => '📍', 'label' => 'Where are you located?',         'question' => 'Where are you located?'],
        ['icon' => '⏰', 'label' => 'What are your operating hours?', 'question' => 'What are your operating hours?'],
        ['icon' => '📞', 'label' => 'Show me contact details',        'question' => 'Show me contact details'],
    ]);
    $chatPlaceholder = data_get($chatBusiness, 'chat_placeholder', "Ask about {$chatBizName}...");
    $chatTitle       = data_get($chatBusiness, 'chat_title', $chatBizName . ' AI Assistant');
@endphp

<div id="chatbot" class="fixed bottom-4 right-4 z-50">
    <input id="chatbot-toggle" type="checkbox" class="peer hidden" checked>

    <div class="absolute bottom-16 right-0 hidden w-[calc(100vw-2rem)] max-w-sm overflow-hidden rounded-[1.75rem] border border-[#eadfce] bg-white shadow-2xl peer-checked:block sm:w-[22rem]">
        <!-- Chatbot Header -->
        <div class="flex items-center justify-between bg-[#5A3E2B] px-4 py-4 text-white">
            <div>
                <div class="font-bold flex items-center gap-2">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h8M8 14h5m-7 7l-4 1 1-4A9 9 0 1118 6 9 9 0 016 21z" />
                    </svg>
                    {{ $chatTitle }}
                </div>
                <div class="mt-1 flex items-center gap-2 text-xs text-white/80">
                    <span class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    Online &amp; Active
                </div>
            </div>
            <label for="chatbot-toggle" class="cursor-pointer text-white/90 hover:text-white" aria-label="Close chat">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </label>
        </div>

        <!-- Chat messages container -->
        <div id="chat-messages-container" class="h-[24rem] overflow-y-auto p-4 space-y-4 bg-gray-50/50">
            <div class="chat-message-item max-w-[85%] rounded-2xl rounded-tl-md bg-white border border-gray-100 p-4 text-sm leading-6 text-gray-700 shadow-sm">
                {{ $chatGreeting }}
            </div>

            <!-- Starter buttons wrapper -->
            <div id="starter-buttons-wrapper" class="space-y-2 pt-2">
                @foreach ($chatPrompts as $prompt)
                    <button class="btn btn-sm w-full justify-start border border-[#d8c6b0] bg-white text-gray-700 hover:border-[#5A3E2B] hover:bg-[#f7efe4] hover:text-gray-700 text-xs font-semibold rounded-xl chat-prompt-btn"
                            data-question="{{ $prompt['question'] }}">
                        {{ $prompt['icon'] ?? '💬' }} {{ $prompt['label'] }}
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Chat Input field -->
        <div class="border-t border-[#eadfce] p-3 bg-white">
            <form id="chat-input-form" class="join w-full">
                <input type="text" id="chat-input-field" class="input join-item w-full border-[#d8c6b0] bg-white focus:border-[#5A3E2B] focus:outline-none text-sm" placeholder="{{ $chatPlaceholder }}" autocomplete="off">
                <button type="submit" class="btn join-item border-0 bg-[#5A3E2B] text-white hover:bg-[#4a3223]">
                    <svg class="h-4 w-4 fill-current" viewBox="0 0 24 24">
                        <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z" />
                    </svg>
                </button>
            </form>
        </div>
    </div>

    <!-- Toggle button floating -->
    <label for="chatbot-toggle" class="btn btn-circle h-14 w-14 border-0 bg-[#5A3E2B] text-white shadow-2xl hover:bg-[#4a3223] transition-all hover:scale-105 flex items-center justify-center cursor-pointer" aria-label="Toggle chat">
        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h8M8 14h5m-7 7l-4 1 1-4A9 9 0 1118 6 9 9 0 016 21z" />
        </svg>
    </label>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const container = document.querySelector('#chat-messages-container');
        const promptButtons = document.querySelectorAll('.chat-prompt-btn');
        const chatForm = document.querySelector('#chat-input-form');
        const inputField = document.querySelector('#chat-input-field');
        const starterWrapper = document.querySelector('#starter-buttons-wrapper');

        // Scroll to bottom helper
        const scrollToBottom = () => {
            container.scrollTop = container.scrollHeight;
        };

        // Append Message helper
        const appendMessage = (text, isUser = false) => {
            const msg = document.createElement('div');
            msg.className = isUser 
                ? 'chat-message-item max-w-[85%] rounded-2xl rounded-tr-md p-4 text-sm leading-6 bg-[#5A3E2B] text-white shadow-sm ml-auto text-right'
                : 'chat-message-item max-w-[85%] rounded-2xl rounded-tl-md p-4 text-sm leading-6 bg-white border border-gray-100 text-gray-700 shadow-sm mr-auto';
            msg.innerHTML = text;
            container.appendChild(msg);
            scrollToBottom();
        };

        // Show typing indicator
        const appendTypingIndicator = () => {
            const indicator = document.createElement('div');
            indicator.id = 'typing-indicator';
            indicator.className = 'chat-message-item max-w-[85%] rounded-2xl rounded-tl-md p-3 bg-white border border-gray-100 text-gray-400 shadow-sm mr-auto flex items-center gap-1';
            indicator.innerHTML = `
                <span class="h-1.5 w-1.5 rounded-full bg-gray-400 animate-bounce" style="animation-delay: 0ms"></span>
                <span class="h-1.5 w-1.5 rounded-full bg-gray-400 animate-bounce" style="animation-delay: 150ms"></span>
                <span class="h-1.5 w-1.5 rounded-full bg-gray-400 animate-bounce" style="animation-delay: 300ms"></span>
            `;
            container.appendChild(indicator);
            scrollToBottom();
            return indicator;
        };

        // Responses repository
        const getBotResponse = (query) => {
            const q = query.toLowerCase();

            if (q.includes('carmelita') || q.includes('room') || q.includes('accommodation') || q.includes('stay') || q.includes('rate') || q.includes('pricing') || q.includes('suit') || q.includes('deluxe') || q.includes('standard')) {
                return `
                    <div class="space-y-3">
                        <p class="font-bold text-gray-900">🏨 Villa Carmelita Accommodations</p>
                        <p class="text-xs text-gray-600">Here are our current room types, nightly pricing, and live room numbers availability:</p>
                        <div class="space-y-2 text-xs">
                            <div class="p-2 bg-gray-50 rounded-xl border border-gray-100">
                                <div class="flex justify-between font-bold text-gray-800">
                                    <span>Standard Room</span>
                                    <span class="text-[#5A3E2B]">PHP 1,800</span>
                                </div>
                                <div class="text-[10px] text-gray-500 mt-0.5">Rooms: RM 310, 312, 315 <span class="text-emerald-600 font-semibold">(3 vacant)</span> | RM 314 <span class="text-rose-500">(1 booked)</span></div>
                            </div>
                            <div class="p-2 bg-gray-50 rounded-xl border border-gray-100">
                                <div class="flex justify-between font-bold text-gray-800">
                                    <span>Junior Suite</span>
                                    <span class="text-[#5A3E2B]">PHP 1,950</span>
                                </div>
                                <div class="text-[10px] text-gray-500 mt-0.5">Rooms: RM 301 <span class="text-emerald-600 font-semibold">(1 vacant)</span> | RM 308 <span class="text-rose-500">(1 booked)</span></div>
                            </div>
                            <div class="p-2 bg-gray-50 rounded-xl border border-gray-100">
                                <div class="flex justify-between font-bold text-gray-800">
                                    <span>Deluxe Twin</span>
                                    <span class="text-[#5A3E2B]">PHP 2,250</span>
                                </div>
                                <div class="text-[10px] text-gray-500 mt-0.5">Rooms: RM 302, 303, 305, 307, 309 <span class="text-emerald-600 font-semibold">(5 vacant)</span> | RM 306, 311 <span class="text-rose-500">(2 booked)</span> | RM 304 <span class="text-amber-600">(1 maintenance)</span></div>
                            </div>
                            <div class="p-2 bg-gray-50 rounded-xl border border-gray-100">
                                <div class="flex justify-between font-bold text-gray-800">
                                    <span>Family Suite</span>
                                    <span class="text-[#5A3E2B]">PHP 3,500</span>
                                </div>
                                <div class="text-[10px] text-gray-500 mt-0.5">Rooms: RM 201, 206 <span class="text-emerald-600 font-semibold">(2 vacant)</span> | RM 202 <span class="text-rose-500">(1 booked)</span> | RM 207 <span class="text-amber-600">(1 maintenance)</span></div>
                            </div>
                            <div class="p-2 bg-gray-50 rounded-xl border border-gray-100">
                                <div class="flex justify-between font-bold text-gray-800">
                                    <span>Super Deluxe Room</span>
                                    <span class="text-[#5A3E2B]">PHP 3,000</span>
                                </div>
                                <div class="text-[10px] text-gray-500 mt-0.5">Rooms: RM 203, 205 <span class="text-emerald-600 font-semibold">(2 vacant)</span> | RM 204 <span class="text-rose-500">(1 booked)</span></div>
                            </div>
                        </div>
                        <div class="pt-1">
                            <button onclick="if(window.room_availability_modal) room_availability_modal.showModal()" class="btn btn-xs rounded-full border-0 bg-[#5A3E2B] text-white hover:bg-[#453020] px-3 w-full">
                                💻 Open Rooms Availability Grid
                            </button>
                        </div>
                    </div>
                `;
            } else if (q.includes('dakong') || q.includes('balay') || q.includes('restaurant') || q.includes('eat') || q.includes('food') || q.includes('dine') || q.includes('menu')) {
                return `
                    <div class="space-y-2">
                        <p class="font-bold text-gray-900">🍽️ Dakong Balay Restaurant</p>
                        <p>Enjoy our warm family hospitality and mouthwatering Filipino cuisine!</p>
                        <p class="text-xs font-semibold text-gray-650">Our Best Sellers:</p>
                        <ul class="list-disc list-inside text-xs space-y-1 text-gray-600">
                            <li><strong>Chicken Inasal Meal</strong> (PHP 220)</li>
                            <li><strong>Pork Sisig Platter</strong> (PHP 340)</li>
                            <li><strong>Family Set Menu</strong> (PHP 1,250)</li>
                            <li><strong>Private Dining Service</strong> (PHP 800)</li>
                        </ul>
                    </div>
                `;
            } else if (q.includes('monclaire') || q.includes('pool') || q.includes('swim') || q.includes('cottage') || q.includes('gazebo')) {
                return `
                    <div class="space-y-2">
                        <p class="font-bold text-gray-900">🏊 Monclaire Pool</p>
                        <p>Relax, swim, and rent our poolside gazebos for day retreats or evening celebrations!</p>
                        <p class="text-xs font-semibold text-gray-650">Rates & Passes:</p>
                        <ul class="list-disc list-inside text-xs space-y-1 text-gray-600">
                            <li><strong>Adult Day Pass</strong>: PHP 150</li>
                            <li><strong>Child Day Pass</strong>: PHP 100</li>
                            <li><strong>Private Gazebo Rental</strong>: PHP 800</li>
                            <li><strong>Exclusive Pool Rental</strong>: PHP 5,000</li>
                        </ul>
                    </div>
                `;
            } else if (q.includes('contact') || q.includes('call') || q.includes('phone') || q.includes('number') || q.includes('email') || q.includes('details')) {
                return `
                    <div class="space-y-2">
                        <p class="font-bold text-gray-900">📞 Contact Details</p>
                        <p class="text-xs">Feel free to reach out directly to our support and booking agents:</p>
                        <div class="text-xs space-y-1 text-gray-600">
                            <p>🏨 <strong>Villa Carmelita</strong>: +63 912 345 6789 | stay@villacarmelita.com</p>
                            <p>🍽️ <strong>Dakong Balay</strong>: +63 987 654 3210 | dine@dakongbalay.com</p>
                            <p>🏊 <strong>Monclaire Pool</strong>: +63 911 222 3333 | swim@monclairepool.com</p>
                        </div>
                    </div>
                `;
            }

            // Default fallback
            return `
                <div class="space-y-1">
                    <p>I'm here to assist you with Dakong Balay, Monclaire Pool, and Villa Carmelita.</p>
                    <p class="text-xs text-gray-500">Try asking: <i>"Do you have rooms at Villa Carmelita?"</i>, <i>"Show me Dakong Balay menu"</i>, or <i>"Pool ticket prices"</i>.</p>
                </div>
            `;
        };

        // Handle user prompt selection
        const handleInteraction = (questionText) => {
            // Remove starters wrapper after first interaction to keep chat clean (or hide it)
            if (starterWrapper) {
                starterWrapper.style.display = 'none';
            }

            appendMessage(questionText, true);
            const indicator = appendTypingIndicator();

            setTimeout(() => {
                indicator.remove();
                const reply = getBotResponse(questionText);
                appendMessage(reply, false);
            }, 600);
        };

        // Bind starter prompt buttons
        promptButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                const question = btn.getAttribute('data-question');
                handleInteraction(question);
            });
        });

        // Form submission handling
        chatForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const text = inputField.value.trim();
            if (!text) return;

            inputField.value = '';
            handleInteraction(text);
        });
    });
</script>