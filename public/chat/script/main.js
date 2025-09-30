// ===== Utilities =====
function getScriptQueryParam(param) {
    const scripts = document.getElementsByTagName("script");
    for (const script of scripts) {
        const src = script.getAttribute("src");
        if (src && src.includes(param)) {
            const urlParams = new URLSearchParams(src.split("?")[1]);
            return urlParams.get(param);
        }
    }
    return null;
}

// Find this script tag to get origin
const THIS_SCRIPT = Array.from(document.getElementsByTagName("script")).find(
    (s) => (s.getAttribute("src") || "").includes("/chat/script/main.js")
);
const BASE = THIS_SCRIPT
    ? new URL(THIS_SCRIPT.src).origin
    : window.location.origin;

// ===== Styles =====
const widgetCss = document.createElement("link");
widgetCss.rel = "stylesheet";
widgetCss.href = `${BASE}/assets/frontend/style.css`;
document.head.appendChild(widgetCss);

const fa = document.createElement("link");
fa.rel = "stylesheet";
fa.href =
    "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css";
fa.integrity =
    "sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==";
fa.crossOrigin = "anonymous";
fa.referrerPolicy = "no-referrer";
document.head.appendChild(fa);

// Minimal styles for lead gate
const gateCss = document.createElement("style");
gateCss.textContent = `
  .lead-gate { padding: 12px; }
  .lead-form { display: grid; gap: 8px; grid-template-columns: 1fr; }
  .lead-form input { padding:.6rem .75rem; border:1px solid #ddd; border-radius:8px; }
  .lead-form button { padding:.6rem .75rem; border:0; border-radius:8px; background: var(--primary-widget-color,#f55); color:#fff; }
`;
document.head.appendChild(gateCss);

// ===== DOM =====
document.body.insertAdjacentHTML(
    "beforeend",
    `
  <div>
    <button class="chat-toggle" id="chatToggle">
      <i class="fa-solid fa-comment"></i>
    </button>

    <div class="chat-box" id="chatBox">
      <div class="chat-header">
        <div class="bars">
          <i class="fa-solid fa-bars"></i>
          <span id="widgetName"></span>
        </div>
      </div>
      <div class="chat-body">
        <div class="chat-bot">
          <img class="rounded" id="avatar" src="" alt="">
          <p id="bot_name"></p>
        </div>

        <!-- Lead Capture -->
        <div id="leadGate" class="lead-gate" style="display:none">
          <div class="lead-form">
            <input type="text"  id="leadName"   placeholder="Your name *">
            <input type="tel"   id="leadMobile" placeholder="Mobile *">
            <input type="email" id="leadEmail"  placeholder="Email (optional)">
            <button id="leadStartBtn" type="button">Start</button>
          </div>
        </div>

        <div class="messages" id="messages">
          <div id="welcome_message_w" class="message bot-message"></div>
        </div>
      </div>
      <div class="chat-footer">
        <input type="text" id="chatInput" placeholder="Type your message..." onkeypress="sendMessage(event)">
        <button id="send-button" type="button"><i class="fa-regular fa-paper-plane"></i></button>
      </div>
      <div style="padding: 3px 5px; text-align: center;">
        <small class="text-[#]"><a style="color: #7e7c7c;" href="https://rafusoft.com">Powered by Rafusoft AI Agent</a></small>
      </div>
    </div>
  </div>
`
);

// ===== State =====
const API_KEY = getScriptQueryParam("api_key");
const chatToggle = document.getElementById("chatToggle");
const chatBox = document.getElementById("chatBox");
const inputEl = document.getElementById("chatInput");
const sendBtn = document.getElementById("send-button");
const leadGate = document.getElementById("leadGate");
const leadBtn = document.getElementById("leadStartBtn");

let SESSION_ID = localStorage.getItem(`widget_session_${API_KEY}`) || null;
let REQUIRE_LEAD = true; // default true; server can override

// track which channels we've subscribed to (prevents duplicate listeners)
const _subscribed = new Set();

// ===== UI =====
chatToggle.addEventListener("click", () => chatBox.classList.toggle("active"));

function setChatEnabled(enabled) {
    inputEl.disabled = !enabled;
    sendBtn.disabled = !enabled;
}

function showLeadGate(show) {
    leadGate.style.display = show ? "block" : "none";
    setChatEnabled(!show);
}

// ===== Fetch widget config =====
function getWidget() {
    if (!API_KEY) return;
    fetch(`${BASE}/api/widget/${API_KEY}`)
        .then((res) => res.json())
        .then(({ bot }) => {
            if (!bot) return;
            document.getElementById("avatar").src = bot.avatar;
            document.getElementById("bot_name").innerText = bot.name;
            document.getElementById("welcome_message_w").innerText =
                bot.welcomeMessage;
            document.getElementById("widgetName").innerText = bot.widgetName;
            document.documentElement.style.setProperty(
                "--primary-widget-color",
                bot.color
            );

            // server may return requireLead / require_lead; support both keys
            REQUIRE_LEAD = bot.requireLead ?? bot.require_lead ?? true;

            if (REQUIRE_LEAD && !SESSION_ID) {
                showLeadGate(true);
            } else {
                showLeadGate(false);
            }
        })
        .catch(() => {
            // if widget fetch fails, keep chat disabled
            showLeadGate(true);
        });
}
getWidget();

// --- Dynamically load Pusher + Echo (so host pages don't need to) ---
function loadScript(src) {
    return new Promise((resolve, reject) => {
        const s = document.createElement("script");
        s.src = src;
        s.onload = resolve;
        s.onerror = reject;
        document.head.appendChild(s);
    });
}

const PUSHER_CFG = {
    key: "c87da674272b46d4bcd7",
    cluster: "ap2",
    useTLS: true,
    host: "",
    port: null,
};

// Bootstrap Echo (public channels -> no auth needed)
// --- keep your loadScript + PUSHER_CFG as-is ---

// Bootstrap Echo (public channels -> no auth needed)
(async function bootstrapEcho() {
    try {
        await loadScript("https://js.pusher.com/8.2/pusher.min.js");
        await loadScript(
            "https://cdn.jsdelivr.net/npm/laravel-echo@1/dist/echo.iife.js"
        );

        // Optional: dev logging
        if (window.Pusher) {
            window.Pusher.logToConsole = true; // remove in production
            window.Pusher = window.Pusher; // ensure global (sometimes not needed)
        }

        const cfg = PUSHER_CFG; // <-- use your literal config

        window.Echo = new Echo({
            broadcaster: "pusher",
            key: cfg.key,
            cluster: cfg.cluster || undefined,
            forceTLS: !!cfg.useTLS,
            enabledTransports: ["ws", "wss"], // no wsHost/wsPort for pusher.com
        });

        // If a session already exists, subscribe now
        if (SESSION_ID) subscribeToRealtime(SESSION_ID);
    } catch (e) {
        console.error("Echo bootstrap failed:", e);
    }
})();

// Real-time subscription for operator messages
function subscribeToRealtime(sessionId) {
    if (!window.Echo) {
        console.warn("Echo not loaded - cannot subscribe to operator messages");
        return;
    }

    const ch = `sessions.uuid.${sessionId}`;
    if (_subscribed.has(ch)) return; // ✅ prevent duplicate listeners
    _subscribed.add(ch);

    console.log("Public widget subscribing to:", ch);

    window.Echo.channel(ch)
        .listen(".message.created", (e) => {
            const p = e?.payload || e || {};
            console.log("Public widget received:", p);
            if (p.role === "user") return;

            const box = document.getElementById("messages");
            if (!box) return;

            const el = document.createElement("div");
            if (p.role === "operator") {
                el.className = "message operator-message";
                el.innerHTML = `<strong>Operator:</strong> ${p.content}`;
            } else {
                el.className = "message bot-message";
                el.textContent = p.content;
            }
            box.appendChild(el);
            el.scrollIntoView({ behavior: "smooth", block: "end" });
        })
        .error((err) =>
            console.error("Public widget subscription error:", err)
        );
}

leadBtn?.addEventListener("click", async () => {
    const name = document.getElementById("leadName").value.trim();
    const mobile = document.getElementById("leadMobile").value.trim();
    const email = document.getElementById("leadEmail").value.trim() || null;

    if (!name || !mobile) {
        alert("Name and Mobile are required.");
        return;
    }

    try {
        const res = await fetch(`${BASE}/api/chat/${API_KEY}/start`, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ name, mobile, email }),
        });
        const data = await res.json();
        if (data.session_id) {
            SESSION_ID = data.session_id;
            localStorage.setItem(`widget_session_${API_KEY}`, SESSION_ID);
            showLeadGate(false);

            // ✅ ADD THIS: Subscribe to real-time messages
            subscribeToRealtime(SESSION_ID);

            // Optional greeting
            // if (data.greeting) {
            //     const m = document.createElement("div");
            //     m.className = "message bot-message";
            //     m.textContent = data.greeting;
            //     document.getElementById("messages").appendChild(m);
            //     m.scrollIntoView({ behavior: "smooth", block: "end" });
            // }
        } else {
            alert("Could not start chat. Please try again.");
        }
    } catch (e) {
        console.error(e);
        alert("Network error. Please try again.");
    }
});

// ===== Chat =====
async function fetchBotResponse(message) {
    try {
        const res = await fetch(`${BASE}/api/chat/${API_KEY}`, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ message, session_id: SESSION_ID }),
        });
        // we don't render here anymore; just signal success/failure
        return { ok: res.ok };
    } catch (err) {
        // only true network failures come here
        return { ok: false, error: "network" };
    }
}

// // ===== Also subscribe if session already exists =====
// if (SESSION_ID) {
//     // Wait a bit for Echo to load, then subscribe
//     setTimeout(() => subscribeToRealtime(SESSION_ID), 1000);
// }

async function sendMessage(event) {
    if (event && event.type === "keypress" && event.key !== "Enter") return;
    if (REQUIRE_LEAD && !SESSION_ID) {
        showLeadGate(true);
        return;
    }

    const messageContainer = document.getElementById("messages");
    const userMessage = inputEl.value.trim();
    if (!userMessage) return;

    // Add user's message
    const userMessageElement = document.createElement("div");
    userMessageElement.className = "message user-message";
    userMessageElement.textContent = userMessage;
    messageContainer.appendChild(userMessageElement);
    userMessageElement.scrollIntoView({ behavior: "smooth", block: "end" });

    inputEl.value = "";

    // Loading message
    const loadingMessageElement = document.createElement("div");
    loadingMessageElement.className = "message bot-message loading";
    loadingMessageElement.textContent =
        (document.getElementById("bot_name").innerText || "Bot") +
        " is typing...";
    messageContainer.appendChild(loadingMessageElement);
    loadingMessageElement.scrollIntoView({ behavior: "smooth", block: "end" });

    try {
        const { ok } = await fetchBotResponse(userMessage);
        // remove the typing bubble either way; the real reply comes via broadcast
        loadingMessageElement.remove();
        // optional: log if the POST failed; do NOT add an error bubble
        if (!ok)
            console.warn("Chat API request failed; waiting for realtime...");
    } catch (err) {
        // true network error—still avoid polluting the chat timeline
        loadingMessageElement.remove();
        console.error("Network error sending message", err);
    }
}

// Wire click on the paper-plane button
sendBtn.addEventListener("click", () => sendMessage());
