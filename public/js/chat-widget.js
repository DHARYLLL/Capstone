/**
 * Project RED Embeddable Chat Widget Loader
 * Add this script to any external website to render the chat widget:
 * <script src="http://localhost:8000/js/chat-widget.js" data-business="dariv"></script>
 */

// (function () {
//     // 1. Get the current script configuration
//     const scriptEl = document.querySelector('script[src*="chat-widget.js"]');
//     const businessSlug = scriptEl ? (scriptEl.getAttribute('data-business') || 'dariv') : 'dariv';
    
//     // Determine the base URL dynamically based on script source
//     let baseUrl = 'http://localhost:8000';
//     if (scriptEl && scriptEl.src) {
//         try {
//             const urlObj = new URL(scriptEl.src);
//             baseUrl = `${urlObj.protocol}//${urlObj.host}`;
//         } catch (e) {
//             console.error('Failed to parse script source URL for chat widget:', e);
//         }
//     }

//     // 2. Inject CSS styles for the launcher and container
//     const styleEl = document.createElement('style');
//     styleEl.innerHTML = `
//         .red-chat-widget-container {
//             position: fixed;
//             bottom: 24px;
//             right: 24px;
//             z-index: 999999;
//             font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
//             display: flex;
//             flex-direction: column;
//             align-items: flex-end;
//         }
        
//         .red-chat-iframe-wrapper {
//             position: absolute;
//             bottom: 84px;
//             right: 0;
//             width: 400px;
//             height: 600px;
//             box-shadow: 0 12px 40px rgba(0, 0, 0, 0.16);
//             border-radius: 24px;
//             overflow: hidden;
//             background: #ffffff;
//             transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
//             transform: translateY(20px) scale(0.9);
//             opacity: 0;
//             pointer-events: none;
//             border: 1px solid rgba(226, 232, 240, 0.8);
//         }
        
//         .red-chat-iframe-wrapper.active {
//             transform: translateY(0) scale(1);
//             opacity: 1;
//             pointer-events: auto;
//         }
        
//         .red-chat-iframe-wrapper iframe {
//             width: 100%;
//             height: 100%;
//             border: 0;
//             display: block;
//         }
        
//         .red-chat-launcher {
//             width: 60px;
//             height: 60px;
//             border-radius: 30px;
//             background: linear-gradient(135deg, #7c3aed, #6d28d9);
//             box-shadow: 0 4px 18px rgba(124, 58, 237, 0.35);
//             cursor: pointer;
//             display: flex;
//             align-items: center;
//             justify-content: center;
//             transition: all 0.25s ease;
//             color: #ffffff;
//         }
        
//         .red-chat-launcher:hover {
//             transform: scale(1.06);
//             box-shadow: 0 6px 22px rgba(124, 58, 237, 0.45);
//         }
        
//         .red-chat-launcher:active {
//             transform: scale(0.95);
//         }
        
//         .red-chat-launcher svg {
//             width: 26px;
//             height: 26px;
//             fill: currentColor;
//             transition: transform 0.3s ease;
//         }
        
//         .red-chat-launcher.active svg {
//             transform: rotate(90deg);
//         }

//         /* Mobile adaptation */
//         @media (max-width: 480px) {
//             .red-chat-iframe-wrapper {
//                 position: fixed;
//                 bottom: 0;
//                 right: 0;
//                 left: 0;
//                 width: 100%;
//                 height: 100%;
//                 border-radius: 0;
//                 box-shadow: none;
//                 transform: translateY(100%);
//             }
//             .red-chat-iframe-wrapper.active {
//                 transform: translateY(0);
//             }
//         }
//     `;
//     document.head.appendChild(styleEl);

//     // 3. Create DOM elements
//     const widgetContainer = document.createElement('div');
//     widgetContainer.className = 'red-chat-widget-container';

//     // Chat iframe wrapper
//     const iframeWrapper = document.createElement('div');
//     iframeWrapper.className = 'red-chat-iframe-wrapper';
    
//     const iframe = document.createElement('iframe');
//     // Lazy load the iframe when opened first time
//     iframe.src = 'about:blank';
//     iframeWrapper.appendChild(iframe);

//     // Launcher button
//     const launcher = document.createElement('div');
//     launcher.className = 'red-chat-launcher';
//     launcher.innerHTML = `
//         <svg id="red-chat-icon-open" viewBox="0 0 24 24">
//             <path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM6 9h12v2H6V9zm8 5H6v-2h8v2zm4-6H6V6h12v2z"/>
//         </svg>
//         <svg id="red-chat-icon-close" viewBox="0 0 24 24" style="display: none;">
//             <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
//         </svg>
//     `;

//     widgetContainer.appendChild(iframeWrapper);
//     widgetContainer.appendChild(launcher);
//     document.body.appendChild(widgetContainer);

//     let isIframeLoaded = false;

//     // 4. Toggle function
//     function toggleChat() {
//         const isActive = iframeWrapper.classList.contains('active');
//         const openIcon = document.getElementById('red-chat-icon-open');
//         const closeIcon = document.getElementById('red-chat-icon-close');
        
//         if (isActive) {
//             iframeWrapper.classList.remove('active');
//             launcher.classList.remove('active');
//             openIcon.style.display = 'block';
//             closeIcon.style.display = 'none';
//         } else {
//             // Lazy load the iframe URL if not loaded yet
//             if (!isIframeLoaded) {
//                 iframe.src = `${baseUrl}/chat/widget?business=${encodeURIComponent(businessSlug)}`;
//                 isIframeLoaded = true;
//             }
//             iframeWrapper.classList.add('active');
//             launcher.classList.add('active');
//             openIcon.style.display = 'none';
//             closeIcon.style.display = 'block';
//         }
//     }

//     // Bind event
//     launcher.addEventListener('click', toggleChat);

//     // 5. Handle postMessage signals from within the iframe
//     window.addEventListener('message', function (event) {
//         if (event.data && event.data.action === 'toggleChat') {
//             toggleChat();
//         }
//     });

// })();


(function () {
    const scriptEl = document.currentScript || Array.from(document.querySelectorAll('script[src*="chat-widget.js"]')).at(-1);
    const businessName = scriptEl?.getAttribute('data-business') || 'dariv';
    const storageKey = 'capstone_chat_user_id';

    let localUserId = localStorage.getItem(storageKey);
    if (!localUserId) {
        localUserId = 'guest_' + Math.random().toString(36).slice(2, 10) + '_' + Date.now();
        localStorage.setItem(storageKey, localUserId);
    }

    const baseUrl = (() => {
        try {
            if (scriptEl && scriptEl.src) {
                const url = new URL(scriptEl.src);
                return `${url.protocol}//${url.host}`;
            }
        } catch (error) {
            console.warn('Chat widget URL fallback triggered:', error);
        }

        return window.location.origin;
    })();

    const widgetRoot = document.createElement('div');
    widgetRoot.style.position = 'fixed';
    widgetRoot.style.right = '20px';
    widgetRoot.style.bottom = '20px';
    widgetRoot.style.zIndex = '2147483647';
    widgetRoot.style.fontFamily = 'Inter, Arial, sans-serif';

    const iframeWrapper = document.createElement('div');
    iframeWrapper.style.position = 'absolute';
    iframeWrapper.style.right = '0';
    iframeWrapper.style.bottom = '68px';
    iframeWrapper.style.width = '380px';
    iframeWrapper.style.height = '560px';
    iframeWrapper.style.maxHeight = '75vh';
    iframeWrapper.style.borderRadius = '22px';
    iframeWrapper.style.overflow = 'hidden';
    iframeWrapper.style.boxShadow = '0 24px 80px rgba(15, 23, 42, 0.22)';
    iframeWrapper.style.background = '#fff';
    iframeWrapper.style.border = '1px solid rgba(148, 163, 184, 0.3)';
    iframeWrapper.style.transform = 'translateY(12px) scale(0.98)';
    iframeWrapper.style.opacity = '0';
    iframeWrapper.style.pointerEvents = 'none';
    iframeWrapper.style.transition = 'all 0.22s ease';
    iframeWrapper.style.display = 'none';

    const iframe = document.createElement('iframe');
    iframe.title = 'Capstone customer chat';
    iframe.style.width = '100%';
    iframe.style.height = '100%';
    iframe.style.border = '0';
    iframe.style.display = 'block';
    iframe.allow = 'clipboard-write';
    iframeWrapper.appendChild(iframe);

    const launcher = document.createElement('button');
    launcher.type = 'button';
    launcher.textContent = '💬';
    launcher.style.width = '60px';
    launcher.style.height = '60px';
    launcher.style.border = '0';
    launcher.style.borderRadius = '50%';
    launcher.style.background = 'linear-gradient(135deg, #7c3aed, #4f46e5)';
    launcher.style.color = '#fff';
    launcher.style.fontSize = '26px';
    launcher.style.cursor = 'pointer';
    launcher.style.boxShadow = '0 14px 26px rgba(79, 70, 229, 0.35)';
    launcher.style.transition = 'transform 0.15s ease';

    widgetRoot.appendChild(iframeWrapper);
    widgetRoot.appendChild(launcher);
    document.body.appendChild(widgetRoot);

    let iframeLoaded = false;

    function toggleWidget() {
        const isVisible = iframeWrapper.style.display === 'block';

        if (isVisible) {
            iframeWrapper.style.display = 'none';
            iframeWrapper.style.opacity = '0';
            iframeWrapper.style.pointerEvents = 'none';
            iframeWrapper.style.transform = 'translateY(12px) scale(0.98)';
            launcher.style.transform = 'scale(1)';
            return;
        }

        if (!iframeLoaded) {
            iframe.src = `${baseUrl}/chat/widget?business=${encodeURIComponent(businessName)}&user_id=${encodeURIComponent(localUserId)}`;
            iframeLoaded = true;
        }

        iframeWrapper.style.display = 'block';
        iframeWrapper.style.opacity = '1';
        iframeWrapper.style.pointerEvents = 'auto';
        iframeWrapper.style.transform = 'translateY(0) scale(1)';
        launcher.style.transform = 'scale(1.04)';
    }

    launcher.addEventListener('click', toggleWidget);
    window.addEventListener('message', function (event) {
        if (event.data && event.data.action === 'toggleChat') {
            toggleWidget();
        }
    });
})();