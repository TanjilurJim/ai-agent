<script src="{{ asset('assets/libs/huebee/huebee.pkgd.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/forms-advanced.js') }}"></script>

<script>
  // Helpers (null-safe)
  const $ = (id) => document.getElementById(id);

  // === Avatar preview ===
  const avatarInput = $('avatar');
  if (avatarInput) {
    avatarInput.addEventListener('change', (e) => {
      const file = e.target.files?.[0];
      if (!file) return;
      const reader = new FileReader();
      reader.onload = (ev) => {
        const src = ev.target?.result;
        if ($('avatar_preview')) $('avatar_preview').src = src;
        if ($('avatar_preview_w')) $('avatar_preview_w').src = src; // preview on the right
      };
      reader.readAsDataURL(file);
    });
  }

  // Clicking image opens file picker
  if ($('avatar_preview')) {
    $('avatar_preview').addEventListener('click', () => avatarInput?.click());
  }

  // === Live name / welcome / color ===
  const nameInput = $('name');
  if (nameInput) {
    nameInput.addEventListener('input', (e) => {
      const val = e.target.value || '';
      const el = $('widget_name'); // element in preview header
      if (el) el.innerText = val;
    });
  }

  const welcomeInput = $('welcomeMessage');
  if (welcomeInput) {
    welcomeInput.addEventListener('input', (e) => {
      const val = e.target.value || '';
      const el = $('welcome_message_w');
      if (el) el.innerText = val;
    });
  }

  const colorInput = $('color');
  if (colorInput) {
    const applyColor = (val) => {
      document.documentElement.style.setProperty('--primary-widget-color', val);
      // if your preview has a header you want to recolor directly, do it here too:
      const header = $('widget_header_w');
      if (header) header.style.background = val;
    };
    colorInput.addEventListener('input', (e) => applyColor(e.target.value || '#0A5'));
    colorInput.addEventListener('blur',  (e) => applyColor(e.target.value || '#0A5'));
    // apply initial
    applyColor(colorInput.value || '#0A5');
  }

  // === Chat preview ===
  const apiKey = @json($widget->api_key ?? '');
  const inputEl = $('chatInput');
  const sendBtn = $('chatSendBtn');

  // Disable chat in preview if we don't have an API key yet (create mode)
  const setChatEnabled = (enabled) => {
    if (inputEl) inputEl.disabled = !enabled;
    if (sendBtn) sendBtn.disabled = !enabled;
    const tip = $('chatDisabledTip');
    if (tip) tip.style.display = enabled ? 'none' : 'block';
  };
  setChatEnabled(!!apiKey);

  async function fetchBotResponse(message) {
    if (!apiKey) return "Save the widget first to enable chat preview.";
    try {
      const res = await fetch(`/api/chat/${apiKey}`, {
        method: "POST",
        headers: {"Content-Type":"application/json"},
        body: JSON.stringify({ message }),
      });
      const data = await res.json();
      return data.reply || "Sorry, something went wrong.";
    } catch (err) {
      console.error("chat error:", err);
      return "Unable to connect. Please try again later.";
    }
  }

  // Optional: expose sendMessage if not already defined elsewhere
  window.sendMessage ??= async function (event) {
    if (event && event.type === "keypress" && event.key !== "Enter") return;
    const messageContainer = $('messages');
    if (!inputEl || !messageContainer) return;
    const userMessage = inputEl.value.trim();
    if (!userMessage) return;

    // User message
    const userMessageElement = document.createElement("div");
    userMessageElement.className = "message user-message";
    userMessageElement.textContent = userMessage;
    messageContainer.appendChild(userMessageElement);
    userMessageElement.scrollIntoView({ behavior: "smooth", block: "end" });
    inputEl.value = "";

    // Loading
    const loading = document.createElement("div");
    loading.className = "message bot-message loading";
    loading.textContent = ( $('widget_name')?.innerText || 'Bot') + " is typing...";
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
</script>
