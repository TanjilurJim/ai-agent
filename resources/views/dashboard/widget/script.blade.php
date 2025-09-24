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
  if (nameInput) nameInput.addEventListener('input', (e) => { ($('widget_name')||{}).innerText = e.target.value || ''; });

  const welcomeInput = $('welcomeMessage');
  if (welcomeInput) welcomeInput.addEventListener('input', (e) => { ($('welcome_message_w')||{}).innerText = e.target.value || ''; });

  const colorInput = $('color');
  if (colorInput) {
    const applyColor = (val) => {
      document.documentElement.style.setProperty('--primary-widget-color', val);
      const header = $('widget_header_w'); if (header) header.style.background = val;
    };
    colorInput.addEventListener('input', (e) => applyColor(e.target.value || '#0A5'));
    colorInput.addEventListener('blur',  (e) => applyColor(e.target.value || '#0A5'));
    applyColor(colorInput.value || '#0A5');
  }

  // === Chat preview with session ===
  const apiKey = @json($widget->api_key ?? '');
  const inputEl = $('chatInput');
  const sendBtn = $('send-button');     // FIXED id
  const leadGate = $('leadGate');
  const leadStartBtn = $('leadStartBtn');

  // Persist a session per widget in localStorage (preview namespace)
  const storageKey = apiKey ? `preview_session_${apiKey}` : null;
  let sessionId = storageKey ? localStorage.getItem(storageKey) : null;

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
  showLead(!!apiKey && !sessionId);  // gate only when apiKey exists but session doesn't

  // Start (create session via API)
  leadStartBtn?.addEventListener('click', async () => {
    const name   = ($('leadName')?.value || '').trim();
    const mobile = ($('leadMobile')?.value || '').trim();
    const email  = ($('leadEmail')?.value || '').trim() || null;

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
        headers: {'Content-Type':'application/json'},
        body: JSON.stringify({ name, mobile, email })
      });
      const data = await res.json();
      if (data.session_id) {
        sessionId = data.session_id;
        if (storageKey) localStorage.setItem(storageKey, sessionId);
        showLead(false);

        // Optional greeting
        if (data.greeting) {
          const m = document.createElement('div');
          m.className = 'message bot-message';
          m.textContent = data.greeting;
          ($('messages')||document.body).appendChild(m);
          m.scrollIntoView({ behavior:'smooth', block:'end' });
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
    if (!apiKey) return "Save the widget first to enable chat preview.";
    try {
      const res = await fetch(`/api/chat/${apiKey}`, {
        method: "POST",
        headers: {"Content-Type":"application/json"},
        body: JSON.stringify({ message, session_id: sessionId }),   // include session
      });
      const data = await res.json();
      return data.reply || "Sorry, something went wrong.";
    } catch (err) {
      console.error("chat error:", err);
      return "Unable to connect. Please try again later.";
    }
  }

  // Expose sendMessage for the preview button + Enter
  window.sendMessage ??= async function (event) {
    if (event && event.type === "keypress" && event.key !== "Enter") return;
    if (!apiKey) { alert('Save the widget first.'); return; }

    // if gating and no session yet, show form
    if (!sessionId) { showLead(true); return; }

    const messageContainer = $('messages');
    const userMessage = (inputEl?.value || '').trim();
    if (!inputEl || !messageContainer || !userMessage) return;

    const userEl = document.createElement("div");
    userEl.className = "message user-message";
    userEl.textContent = userMessage;
    messageContainer.appendChild(userEl);
    userEl.scrollIntoView({ behavior: "smooth", block: "end" });
    inputEl.value = "";

    const loading = document.createElement("div");
    loading.className = "message bot-message loading";
    loading.textContent = (($('widget_name')?.innerText) || 'Bot') + " is typing...";
    messageContainer.appendChild(loading);
    loading.scrollIntoView({ behavior: "smooth", block: "end" });

    const reply = await fetchBotResponse(userMessage);
    loading.remove();

    const botEl = document.createElement("div");
    botEl.className = "message bot-message";
    botEl.textContent = reply;
    messageContainer.appendChild(botEl);
    botEl.scrollIntoView({ behavior: "smooth", block: "end" });
  };

  // Also wire the click on the button
  sendBtn?.addEventListener('click', () => window.sendMessage());
</script>
