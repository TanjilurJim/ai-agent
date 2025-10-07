<script src="{{ asset('assets/libs/huebee/huebee.pkgd.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/forms-advanced.js') }}"></script>

<script>
    const $ = (id) => document.getElementById(id);

    // === Avatar preview (unchanged) ===
    const avatarInput = $('avatar');
    if (avatarInput) {
        avatarInput.addEventListener('change', (e) => {
            const file = e.target.files?.[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = (ev) => {
                const src = ev.target?.result;
                if ($('avatar_preview')) $('avatar_preview').src = src;
                if ($('avatar_preview_w')) $('avatar_preview_w').src = src;
            };
            reader.readAsDataURL(file);
        });
    }
    if ($('avatar_preview')) $('avatar_preview').addEventListener('click', () => avatarInput?.click());

    // === Live fields (unchanged) ===
    const nameInput = $('name');
    if (nameInput) nameInput.addEventListener('input', (e) => {
        ($('widget_name') || {}).innerText = e.target.value || '';
    });

    const welcomeInput = $('welcomeMessage');
    if (welcomeInput) welcomeInput.addEventListener('input', (e) => {
        ($('welcome_message_w') || {}).innerText = e.target.value || '';
    });

    const colorInput = $('color');
    if (colorInput) {
        const applyColor = (val) => {
            document.documentElement.style.setProperty('--primary-widget-color', val);
            const header = $('widget_header_w');
            if (header) header.style.background = val;
        };
        colorInput.addEventListener('input', (e) => applyColor(e.target.value || '#0A5'));
        colorInput.addEventListener('blur', (e) => applyColor(e.target.value || '#0A5'));
        applyColor(colorInput.value || '#0A5');
    }

    // === Chat preview with session ===
    const apiKey = @json($widget->api_key ?? '');
    const inputEl = $('chatInput');
    const sendBtn = $('send-button'); // FIXED id
    const leadGate = $('leadGate');
    const leadStartBtn = $('leadStartBtn');

    // Persist a session per widget in localStorage (preview namespace)
    const storageKey = apiKey ? `preview_session_${apiKey}` : null;
    let sessionId = storageKey ? localStorage.getItem(storageKey) : null;

    if (sessionId) {
        subscribeToRealtime(sessionId);
    }

    function subscribeToRealtime(sessionId) {
        if (!window.Echo) {
            console.warn("Echo not loaded.");
            return;
        }

        console.log("Subscribing to:", `sessions.uuid.${sessionId}`);
        window.Echo.private(`sessions.uuid.${sessionId}`)
            .listen('.message.created', (e) => {
                console.log("Realtime event:", e);

                // Ignore messages already sent by the visitor
                if (e.role === 'user') return;

                const messageContainer = $('messages');
                if (!messageContainer) return;

                const msgEl = document.createElement("div");
                msgEl.className = "message bot-message";
                msgEl.textContent = e.content;
                messageContainer.appendChild(msgEl);
                msgEl.scrollIntoView({
                    behavior: "smooth",
                    block: "end"
                });
            });
    }


    function setChatEnabled(enabled) {
        if (inputEl) inputEl.disabled = !enabled;
        if (sendBtn) sendBtn.disabled = !enabled;
    }

    function showLead(show) {
        if (!leadGate) return;
        leadGate.style.display = show ? 'block' : 'none';
        setChatEnabled(!show);
    }

    // If no apiKey yet (create form), keep chat disabled
    setChatEnabled(!!apiKey);
    showLead(!!apiKey && !sessionId); // gate only when apiKey exists but session doesn't

    // Start (create session via API)
    leadStartBtn?.addEventListener('click', async () => {
        const name = ($('leadName')?.value || '').trim();
        const mobile = ($('leadMobile')?.value || '').trim();
        const email = ($('leadEmail')?.value || '').trim() || null;

        if (!name || !mobile) {
            alert('Name and Mobile are required.');
            return;
        }
        if (!apiKey) {
            alert('Save the widget first to generate API key.');
            return;
        }

        try {
            const res = await fetch(`/api/chat/${apiKey}/start`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    name,
                    mobile,
                    email
                })
            });
            const data = await res.json();
            if (data.session_id) {

                sessionId = data.session_id;
                if (storageKey) localStorage.setItem(storageKey, sessionId);
                subscribeToRealtime(sessionId);
                showLead(false);

                // Optional greeting
                if (data.greeting) {
                    const m = document.createElement('div');
                    m.className = 'message bot-message';
                    m.textContent = data.greeting;
                    ($('messages') || document.body).appendChild(m);
                    m.scrollIntoView({
                        behavior: 'smooth',
                        block: 'end'
                    });
                }
            } else {
                alert('Could not start chat. Please try again.');
            }
        } catch (e) {
            console.error('start error:', e);
            alert('Network error. Please try again.');
        }
    });

    async function fetchBotResponse(message) {
        if (!apiKey) return {
            reply: "Save the widget first to enable chat preview.",
            role: 'bot'
        };
        try {
            const res = await fetch(`/api/chat/${apiKey}`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    message,
                    session_id: sessionId
                }),
            });
            const data = await res.json();
            return {
                reply: data.reply || "Sorry, something went wrong.",
                role: (data.role || 'bot').toLowerCase() // normalize role
            };
        } catch (err) {
            console.error("chat error:", err);
            return {
                reply: "Unable to connect. Please try again later.",
                role: 'bot'
            };
        }
    }



    // Expose sendMessage for the preview button + Enter
    window.sendMessage ??= async function(event) {
        if (event && event.type === "keypress" && event.key !== "Enter") return;
        if (!apiKey) {
            alert('Save the widget first.');
            return;
        }

        // if gating and no session yet, show form
        if (!sessionId) {
            showLead(true);
            return;
        }

        const messageContainer = $('messages');
        const userMessage = (inputEl?.value || '').trim();
        if (!inputEl || !messageContainer || !userMessage) return;

        const userEl = document.createElement("div");
        userEl.className = "message user-message";
        userEl.textContent = userMessage;
        messageContainer.appendChild(userEl);
        userEl.scrollIntoView({
            behavior: "smooth",
            block: "end"
        });
        inputEl.value = "";

        const loading = document.createElement("div");
        loading.className = "message bot-message loading";
        loading.textContent = (($('widget_name')?.innerText) || 'Bot') + " is typing...";
        messageContainer.appendChild(loading);
        loading.scrollIntoView({
            behavior: "smooth",
            block: "end"
        });

        const {
            reply,
            role
        } = await fetchBotResponse(userMessage);
        loading.remove();

        const botEl = document.createElement("div");

        if (role === 'operator' || role === 'assistant' || role === 'staff') {
            botEl.className = "message bot-message"; // same style as before, looks like staff
        } else {
            botEl.className = "message bot-message"; // could use different style if needed
        }

        // botEl.className = "message bot-message";
        botEl.textContent = reply;
        messageContainer.appendChild(botEl);
        botEl.scrollIntoView({
            behavior: "smooth",
            block: "end"
        });
    };

    // Also wire the click on the button
    sendBtn?.addEventListener('click', () => window.sendMessage());
</script>
<script>
    // ===== Header action menu toggle =====
    const chatMenuBtn = document.getElementById('chatMenuBtn');
    const chatActions = document.getElementById('chatActions');

    function toggleMenu(open) {
        if (!chatActions) return;
        const willOpen = (open !== undefined) ? open : !chatActions.classList.contains('show');
        chatActions.classList.toggle('show', willOpen);
        if (chatMenuBtn) chatMenuBtn.setAttribute('aria-expanded', String(willOpen));
        if (chatActions) chatActions.setAttribute('aria-hidden', String(!willOpen));
    }

    chatMenuBtn?.addEventListener('click', (e) => {
        e.stopPropagation();
        toggleMenu();
    });

    // Close when clicking outside
    document.addEventListener('click', (e) => {
        if (!chatActions?.classList.contains('show')) return;
        if (chatActions.contains(e.target) || chatMenuBtn.contains(e.target)) return;
        toggleMenu(false);
    });

    // ===== Helpers to collect chat and name =====
    function getWidgetSafeName() {
        const name = (document.getElementById('widget_name')?.innerText || 'widget').trim();
        return name.toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '')
            .substring(0, 50) || 'widget';
    }

    function collectChatMessages() {
        const container = document.getElementById('messages');
        const items = [];
        if (!container) return items;

        container.querySelectorAll('.message').forEach(el => {
            const text = (el.textContent || '').trim();
            if (!text) return;
            let role = 'bot';
            if (el.classList.contains('user-message')) role = 'user';
            else if (el.classList.contains('bot-message')) role = 'bot';
            items.push({
                role,
                content: text
            });
        });
        return items;
    }

    // ===== Download as .txt =====
    function downloadTextFile(filename, text) {
        const blob = new Blob([text], {
            type: 'text/plain;charset=utf-8'
        });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = filename;
        document.body.appendChild(a);
        a.click();
        a.remove();
        URL.revokeObjectURL(url);
    }

    function buildTxtTranscript() {
        const messages = collectChatMessages();
        const header = [
            `Widget: ${document.getElementById('widget_name')?.innerText || 'Widget'}`,
            `Session: ${sessionId || 'preview'}`,
            `Exported: ${new Date().toLocaleString()}`,
            ''
        ].join('\n');

        const lines = messages.map(m => {
            const who = (m.role === 'user') ? 'User' : 'Bot';
            return `[${who}] ${m.content}`;
        });

        return header + lines.join('\n');
    }

    document.getElementById('downloadTxtBtn')?.addEventListener('click', () => {
        const base = getWidgetSafeName();
        const stamp = new Date().toISOString().replace(/[:.]/g, '-');
        const name = `${base}-chat-${sessionId || 'preview'}-${stamp}.txt`;
        downloadTextFile(name, buildTxtTranscript());
        toggleMenu(false);
    });

    // ===== Print chat =====
    function buildPrintableHtml() {
        const messages = collectChatMessages();
        const avatar = document.getElementById('avatar_preview_w')?.getAttribute('src') || '';
        const wname = document.getElementById('widget_name')?.innerText || 'Widget';

        const bubbles = messages.map(m => {
            const cls = (m.role === 'user') ? 'user' : 'bot';
            return `<div class="row ${cls}"><div class="bubble">${escapeHtml(m.content)}</div></div>`;
        }).join('');

        return `
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>${escapeHtml(wname)} – Chat Transcript</title>
<style>
  @media print { @page { margin: 12mm; } }
  body { font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif; margin: 16px; }
  .header { display:flex; align-items:center; gap:12px; margin-bottom: 12px; }
  .header img { width:48px; height:48px; border-radius:10px; object-fit:cover; }
  .sub { color:#666; font-size: 12px; }
  .row { display:flex; margin: 8px 0; }
  .row.user { justify-content: flex-end; }
  .row.bot  { justify-content: flex-start; }
  .bubble {
    max-width: 70ch;
    padding: 10px 12px;
    border-radius: 14px;
    background: #f1f1f1;
    white-space: pre-wrap;
    word-wrap: break-word;
  }
  .row.user .bubble { background: #e8f0ff; }
  hr { border: 0; height: 1px; background: #e5e5e5; margin: 12px 0; }
</style>
</head>
<body>
  <div class="header">
    ${avatar ? `<img src="${avatar}" alt="">` : ''}
    <div>
      <div><strong>${escapeHtml(wname)}</strong></div>
      <div class="sub">Session: ${escapeHtml(String(sessionId || 'preview'))}</div>
      <div class="sub">Exported: ${escapeHtml(new Date().toLocaleString())}</div>
    </div>
  </div>
  <hr>
  ${bubbles || '<em>No messages.</em>'}
  <script>window.onload = () => { window.print(); setTimeout(() => window.close(), 300); }<\/script>
</body>
</html>`;
    }

    function escapeHtml(s) {
        return String(s).replace(/[&<>"']/g, c => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#39;'
        } [c]));
    }

    document.getElementById('printChatBtn')?.addEventListener('click', () => {
        const html = buildPrintableHtml();
        const w = window.open('', '_blank', 'noopener,noreferrer');
        if (!w) {
            alert('Popup blocked. Please allow popups to print.');
            return;
        }
        w.document.open('text/html');
        w.document.write(html);
        w.document.close();
        toggleMenu(false);
    });


    // === Widget minimize / restore ===
    // === Widget minimize / restore ===
    const chatWidget = document.getElementById('chat-widget');
    const chatCloseBtn = document.getElementById('chatCloseBtn');

    // Minimize from the X (stop bubbling so container listener doesn't instantly restore)
    chatCloseBtn?.addEventListener('click', (e) => {
        e.stopPropagation();
        chatWidget?.classList.add('minimized');
    });

    // Restore by clicking the floating bubble area (the ::after pseudo-element)
    chatWidget?.addEventListener('click', () => {
        if (chatWidget.classList.contains('minimized')) {
            chatWidget.classList.remove('minimized');
        }
    });
</script>
