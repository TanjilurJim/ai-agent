// ====== Rafusoft Widget: CDN Embed (v2025-10-05a) ======

// ----- Utilities -----
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

// ----- Styles (external + a few inline rules) -----
const widgetCss = document.createElement("link");
widgetCss.rel = "stylesheet";
widgetCss.href = BASE + "/assets/frontend/style.css";
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

const inlineCss = document.createElement("style");

const miniCss = document.createElement("style");
miniCss.textContent = `
  .chat-close-btn{ margin-left:auto; cursor:pointer; opacity:.8 }
  .chat-close-btn:hover{ opacity:1 }

  /* when minimized, hide chat, show restore bubble */
  .rs-widget.minimized .chat-box{ display:none }

  .chat-restore{
    display:none; position:fixed; right:20px; bottom:20px;
    width:60px; height:60px; border-radius:50%;
    background:var(--primary-widget-color,#f55); color:#fff;
    border:0; box-shadow:0 4px 10px rgba(0,0,0,.2);
    cursor:pointer; z-index:2147483647;
  }
  .chat-restore::before{
    content:'💬'; font-size:28px; line-height:60px; display:block; text-align:center;
  }
  .rs-widget.minimized .chat-restore{ display:block }
`;
document.head.appendChild(miniCss);

// Typing indicator (3 dots) like Messenger
var typingCss = document.createElement("style");
typingCss.textContent = `
  .message.bot-message.typing { display:flex; }
  .typing-bubble {
    display:inline-flex; align-items:center; justify-content:center;
    min-width: 44px; height: 28px; padding: 6px 10px;
    border-radius: 14px; background: #f1f1f1;
  }
  .typing-indicator { display:inline-flex; gap: 6px; }
  .typing-indicator span {
    width: 6px; height: 6px; border-radius: 50%;
    background: #9aa0a6; opacity: .4;
    animation: typingPulse 1.2s infinite ease-in-out;
  }
  .typing-indicator span:nth-child(2) { animation-delay: .15s; }
  .typing-indicator span:nth-child(3) { animation-delay: .30s; }

  @keyframes typingPulse {
    0%, 80%, 100% { transform: translateY(0); opacity: .4; }
    40%          { transform: translateY(-3px); opacity: 1; }
  }

  /* Respect reduced motion */
  @media (prefers-reduced-motion: reduce) {
    .typing-indicator span { animation: none; }
  }
`;
document.head.appendChild(typingCss);

// put this right after you append inlineCss
var actionsThemeCss = document.createElement("style");
actionsThemeCss.textContent =
    ".chat-actions button{color:var(--action-accent,#abc8d1)}" +
    ".chat-actions button i{color:inherit}" +
    ".chat-actions button:hover{background:var(#abc8d1);}";
document.head.appendChild(actionsThemeCss);

// (optional) set the accent dynamically, or let it default to #2563eb
document.documentElement.style.setProperty("--action-accent", "#2563eb");

inlineCss.textContent =
    ".lead-gate{padding:12px}.lead-form{display:grid;gap:8px;grid-template-columns:1fr}.lead-form input{padding:.6rem .75rem;border:1px solid #ddd;border-radius:8px}.lead-form button{padding:.6rem .75rem;border:0;border-radius:8px;background:var(--primary-widget-color,#f55);color:#fff}" +
    ".chat-header{position:relative;overflow:visible}#chatMenuBtn{cursor:pointer;padding:.35rem .5rem;border-radius:.375rem}#chatMenuBtn:hover{background:rgba(0,0,0,.06)}" +
    ".chat-actions{position:absolute;top:100%;left:0;margin-top:.25rem;min-width:180px;background:#fff;border:1px solid rgba(0,0,0,.1);border-radius:.5rem;box-shadow:0 10px 25px rgba(0,0,0,.08);z-index:9999;padding:.25rem}" +
    ".chat-footer{display:flex;gap:.5rem;align-items:center}" +
    ".chat-footer .attach-btn{display:inline-flex;align-items:center;justify-content:center;width:38px;height:38px;border:0;border-radius:8px;cursor:pointer}" +
    ".chat-footer " +
    ".chat-actions button{width:100%;text-align:left;border:0;background:transparent;padding:.5rem .75rem;border-radius:.375rem;font-size:.925rem}.chat-actions }";
document.head.appendChild(inlineCss);

// ----- DOM (insert widget markup) -----
document.body.insertAdjacentHTML(
    "beforeend",
    [
        '<div id="rs-widget" class="rs-widget">',
        '  <button class="chat-toggle" id="chatToggle"><i class="fa-solid fa-comment"></i></button>',
        '  <div class="chat-box" id="chatBox">',
        '    <div class="chat-header">',
        '      <div class="bars">',
        '        <i class="fa-solid fa-bars" id="chatMenuBtn" aria-haspopup="true" aria-expanded="false"></i>',
        '        <span id="widgetName"></span>',
        '           <i class="fa-solid fa-xmark chat-close-btn" id="chatCloseBtn" title="Minimize"></i>',
        "      </div>",
        '      <div id="chatActions" class="chat-actions" role="menu" aria-hidden="true" style="display:none">',
        '        <button id="downloadTxtBtn" type="button" role="menuitem"><i class="fa-regular fa-file-lines" style="margin-right:.5rem"></i> Download chat (.txt)</button>',
        '        <button id="printChatBtn" type="button" role="menuitem"><i class="fa-solid fa-print" style="margin-right:.5rem"></i> Print chat</button>',
        '        <button id="emailChatBtn" type="button" role="menuitem"><i class="fa-regular fa-envelope" style="margin-right:.5rem"></i> Email chat</button>',

        "      </div>",
        "    </div>",
        '    <div class="chat-body">',
        '      <div class="chat-bot">',
        '        <img class="rounded" id="avatar" src="" alt="">',
        '        <p id="bot_name"></p>',
        "      </div>",
        '      <div id="leadGate" class="lead-gate" style="display:none">',
        '        <div class="lead-form">',
        '          <input type="text"  id="leadName"   placeholder="Your name *">',
        '          <input type="tel"   id="leadMobile" placeholder="Mobile *">',
        '          <input type="email" id="leadEmail"  placeholder="Email (optional)">',
        '          <button id="leadStartBtn" type="button">Start</button>',
        "        </div>",
        "      </div>",
        '      <div class="messages" id="messages">',
        '        <div id="welcome_message_w" class="message bot-message"></div>',
        "      </div>",
        "    </div>",
        '    <div class="chat-footer">',
        '      <button id="fileBtn" class="attach-btn" type="button" title="Attach files" aria-label="Attach files">',
        '        <i class="fa-solid fa-paperclip"></i>',
        "      </button>",
        '      <input type="file" id="chatFile" accept="image/*,.pdf,.doc,.docx,.xls,.xlsx,.txt" multiple style="display:none">',
        '      <input type="text" id="chatInput" placeholder="Type your message..." onkeypress="sendMessage(event)">',
        '      <button id="send-button" type="button"><i class="fa-regular fa-paper-plane"></i></button>',
        "    </div>",
        '    <div style="padding:3px 5px;text-align:center;"><small class="text-[#]"><a style="color:#7e7c7c;" href="https://rafusoft.com">Powered by Rafusoft AI Agent</a></small></div>',
        "  </div>",

        '  <button id="chatRestoreBtn" class="chat-restore" type="button" aria-label="Open chat" title="Open chat"></button>',
        "</div>",
    ].join("")
);

// ----- State -----
const API_KEY = getScriptQueryParam("api_key");
const SESSION_KEY = "widget_session_" + API_KEY;

const WIDGET = document.getElementById("rs-widget");
const closeBtn = document.getElementById("chatCloseBtn");
const restoreBtn = document.getElementById("chatRestoreBtn");

const chatToggle = document.getElementById("chatToggle");
const chatBox = document.getElementById("chatBox");
const inputEl = document.getElementById("chatInput");
const sendBtn = document.getElementById("send-button");
const fileBtn = document.getElementById("fileBtn");
const fileInput = document.getElementById("chatFile");
const leadGate = document.getElementById("leadGate");
const leadBtn = document.getElementById("leadStartBtn");

let SESSION_ID = localStorage.getItem(SESSION_KEY) || null;
let REQUIRE_LEAD = true; // server can override

const _subscribed = new Set(); // avoid duplicate Echo listeners

// ----- UI -----
chatToggle.addEventListener("click", function () {
    // if user clicks the launcher, always un-minimize first
    if (WIDGET.classList.contains("minimized")) {
        WIDGET.classList.remove("minimized");
    }
    chatBox.classList.toggle("active");
});

// Minimize from the header X
closeBtn?.addEventListener("click", function (e) {
    e.stopPropagation(); // don't let any parent click handlers run
    WIDGET.classList.add("minimized"); // hide chat-box, show restore bubble
    chatBox.classList.remove("active"); // also collapse if it was open
});

// Restore from the floating bubble
restoreBtn?.addEventListener("click", function () {
    WIDGET.classList.remove("minimized");
    chatBox.classList.add("active"); // open on restore (nice UX)
});

function setChatEnabled(enabled) {
    inputEl.disabled = !enabled;
    sendBtn.disabled = !enabled;
}
function showLeadGate(show) {
    leadGate.style.display = show ? "block" : "none";
    setChatEnabled(!show);
}

// ----- Fetch widget config -----
function getWidget() {
    if (!API_KEY) return;
    fetch(BASE + "/api/widget/" + API_KEY)
        .then(function (res) {
            return res.json();
        })
        .then(function (data) {
            var bot = data && data.bot;
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

            REQUIRE_LEAD =
                bot.requireLead != null
                    ? bot.requireLead
                    : bot.require_lead != null
                    ? bot.require_lead
                    : true;
            showLeadGate(REQUIRE_LEAD && !SESSION_ID);
        })
        .catch(function () {
            showLeadGate(true);
        });
}
getWidget();

// ----- Load Pusher + Echo -----
function loadScript(src) {
    return new Promise(function (resolve, reject) {
        var s = document.createElement("script");
        s.src = src;
        s.onload = resolve;
        s.onerror = reject;
        document.head.appendChild(s);
    });
}

var PUSHER_CFG = { key: "c87da674272b46d4bcd7", cluster: "ap2", useTLS: true };

(function bootstrapEcho() {
    Promise.resolve()
        .then(function () {
            return loadScript("https://js.pusher.com/8.2/pusher.min.js");
        })
        .then(function () {
            return loadScript(
                "https://cdn.jsdelivr.net/npm/laravel-echo@1/dist/echo.iife.js"
            );
        })
        .then(function () {
            if (window.Pusher) window.Pusher.logToConsole = true; // dev only
            window.Echo = new Echo({
                broadcaster: "pusher",
                key: PUSHER_CFG.key,
                cluster: PUSHER_CFG.cluster,
                forceTLS: !!PUSHER_CFG.useTLS,
                enabledTransports: ["ws", "wss"],
            });
            if (SESSION_ID) subscribeToRealtime(SESSION_ID);
        })
        .catch(function (e) {
            console.error("Echo bootstrap failed:", e);
        });
})();

// ----- Realtime subscription -----
function subscribeToRealtime(sessionId) {
    if (!window.Echo) {
        console.warn("Echo not loaded - cannot subscribe");
        return;
    }
    var ch = "sessions.uuid." + sessionId;
    if (_subscribed.has(ch)) return;
    _subscribed.add(ch);

    window.Echo.channel(ch)
        .listen(".message.created", function (e) {
            // support either {payload:{...}} or flat {...}
            var p = (e && (e.payload || e)) || {};
            if (!p || !p.role) return;

            var box = document.getElementById("messages");
            if (!box) return;

            // text bubble (bot/operator only; ignore user echoes)
            if (p.role !== "user" && (p.content || "").trim() !== "") {
                var el = document.createElement("div");
                el.className =
                    "message " +
                    (p.role === "operator"
                        ? "operator-message"
                        : "bot-message");
                if (p.role === "operator") {
                    el.innerHTML = "<strong>Operator:</strong> " + p.content;
                } else {
                    el.textContent = p.content;
                }
                box.appendChild(el);
                el.scrollIntoView({ behavior: "smooth", block: "end" });
            }

            // --- NEW: render attachments (images & other files)
            if (Array.isArray(p.attachments) && p.attachments.length) {
                p.attachments.forEach(function (att) {
                    if (!att || !att.url) return;

                    var wrap = document.createElement("div");
                    wrap.className =
                        "message " +
                        (p.role === "operator"
                            ? "operator-message"
                            : "bot-message");

                    if ((att.mime || "").startsWith("image/")) {
                        // clickable image (opens full size)
                        var a = document.createElement("a");
                        a.href = att.url;
                        a.target = "_blank";
                        a.rel = "noopener";

                        var img = new Image();
                        img.src = att.url;
                        img.alt = att.filename || "attachment";
                        img.style.maxWidth = "220px";
                        img.style.borderRadius = "8px";
                        img.style.display = "block";

                        a.appendChild(img);
                        wrap.appendChild(a);
                    } else {
                        // fallback: link to file
                        var link = document.createElement("a");
                        link.href = att.url;
                        link.target = "_blank";
                        link.rel = "noopener";
                        link.textContent = att.filename || att.url;
                        wrap.appendChild(link);
                    }

                    box.appendChild(wrap);
                    wrap.scrollIntoView({ behavior: "smooth", block: "end" });
                });
            }
        })
        .error(function (err) {
            console.error("Public widget subscription error:", err);
        });
}

// ----- Lead gate start -----
if (leadBtn) {
    leadBtn.addEventListener("click", function () {
        var name = (document.getElementById("leadName").value || "").trim();
        var mobile = (document.getElementById("leadMobile").value || "").trim();
        var email =
            (document.getElementById("leadEmail").value || "").trim() || null;

        if (!name || !mobile) {
            alert("Name and Mobile are required.");
            return;
        }

        fetch(BASE + "/api/chat/" + API_KEY + "/start", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ name: name, mobile: mobile, email: email }),
        })
            .then(function (r) {
                return r.json();
            })
            .then(function (data) {
                if (data && data.session_id) {
                    SESSION_ID = data.session_id;
                    localStorage.setItem(SESSION_KEY, SESSION_ID);
                    showLeadGate(false);
                    subscribeToRealtime(SESSION_ID);
                } else {
                    alert("Could not start chat. Please try again.");
                }
            })
            .catch(function () {
                alert("Network error. Please try again.");
            });
    });
}

if (fileBtn && fileInput) {
    fileBtn.addEventListener("click", function () {
        if (REQUIRE_LEAD && !SESSION_ID) {
            showLeadGate(true);
            alert("Please start the chat first.");
            return;
        }
        fileInput.click();
    });

    fileInput.addEventListener("change", function (e) {
        const files = e.target.files;
        if (!files.length) return;

        // Validate file count (your backend allows max 5 images)
        if (files.length > 5) {
            alert("Maximum 5 images allowed at once.");
            fileInput.value = "";
            return;
        }

        // Show uploading indicator
        const messageContainer = document.getElementById("messages");
        const uploadingMsg = document.createElement("div");
        uploadingMsg.className = "message user-message";
        uploadingMsg.textContent = `📎 Uploading ${files.length} file(s)...`;
        messageContainer.appendChild(uploadingMsg);
        uploadingMsg.scrollIntoView({ behavior: "smooth", block: "end" });

        // Create FormData - your backend expects 'images[]' and 'session_id'
        const formData = new FormData();
        formData.append("session_id", SESSION_ID);
        formData.append("message", ""); // optional text with images

        // Your backend validates 'images.*' so use 'images[]'
        for (let i = 0; i < files.length; i++) {
            formData.append("images[]", files[i]);
        }

        // Show typing indicator
        const loading = document.createElement("div");
        loading.className = "message bot-message typing";
        loading.setAttribute("role", "status");
        loading.setAttribute("aria-live", "polite");
        loading.innerHTML = `
          <div class="typing-bubble" aria-label="Bot is typing">
            <div class="typing-indicator"><span></span><span></span><span></span></div>
          </div>
        `;
        messageContainer.appendChild(loading);
        loading.scrollIntoView({ behavior: "smooth", block: "end" });

        // Upload to your existing chat endpoint
        // In your file upload code (replace the fetch section):
        fetch(BASE + "/api/chat/" + API_KEY, {
            method: "POST",
            body: formData,
        })
            .then(async (res) => {
                const contentType = res.headers.get("content-type");

                // If response is not JSON, it's an error page
                if (!contentType || !contentType.includes("application/json")) {
                    const text = await res.text();
                    console.error(
                        "Server returned HTML instead of JSON:",
                        text
                    );
                    throw new Error(`Server error: ${res.status}`);
                }

                if (!res.ok) {
                    const errorData = await res.json();
                    console.error("API error:", errorData);
                    throw new Error(
                        errorData.message || `Server error: ${res.status}`
                    );
                }

                return res.json();
            })
            .then((data) => {
                uploadingMsg.remove();
                loading.remove();
                fileInput.value = "";
                console.log("Upload success:", data);
            })
            .catch((err) => {
                console.error("Upload failed:", err);
                uploadingMsg.textContent = "❌ Upload failed: " + err.message;
                uploadingMsg.style.color = "#ef4444";
                loading.remove();
                fileInput.value = "";
            });
    });
}

// ----- Chat -----
function fetchBotResponse(message) {
    return fetch(BASE + "/api/chat/" + API_KEY, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ message: message, session_id: SESSION_ID }),
    })
        .then(function (res) {
            return { ok: res.ok };
        })
        .catch(function () {
            return { ok: false, error: "network" };
        });
}

function sendMessage(event) {
    if (event && event.type === "keypress" && event.key !== "Enter") return;
    if (REQUIRE_LEAD && !SESSION_ID) {
        showLeadGate(true);
        return;
    }

    var messageContainer = document.getElementById("messages");
    var userMessage = (inputEl.value || "").trim();
    if (!userMessage) return;

    var userEl = document.createElement("div");
    userEl.className = "message user-message";
    userEl.textContent = userMessage;
    messageContainer.appendChild(userEl);
    userEl.scrollIntoView({ behavior: "smooth", block: "end" });
    inputEl.value = "";

    const loading = document.createElement("div");
    loading.className = "message bot-message typing";
    loading.setAttribute("role", "status");
    loading.setAttribute("aria-live", "polite");
    loading.innerHTML = `
  <div class="typing-bubble" aria-label="Bot is typing">
    <div class="typing-indicator"><span></span><span></span><span></span></div>
  </div>
`;
    messageContainer.appendChild(loading);
    loading.scrollIntoView({ behavior: "smooth", block: "end" });

    fetchBotResponse(userMessage)
        .then(function (r) {
            loading.remove();
            if (!r.ok)
                console.warn("Chat POST failed; waiting for realtime...");
        })
        .catch(function (err) {
            loading.remove();
            console.error("Network error sending message", err);
        });
}
sendBtn.addEventListener("click", function () {
    sendMessage();
});
window.sendMessage = sendMessage; // for onkeypress

// ----- Header menu: toggle + Download/Print -----
(function setupHeaderActions() {
    var menuBtn = document.getElementById("chatMenuBtn");
    var emailBtn = document.getElementById("emailChatBtn");
    var menuPanel = document.getElementById("chatActions");
    var dlBtn = document.getElementById("downloadTxtBtn");
    var printBtn = document.getElementById("printChatBtn");

    if (!menuBtn || !menuPanel || !dlBtn || !printBtn) {
        console.warn("[widget] header actions: elements not found");
        return;
    }

    menuPanel.style.display = "none";

    function toggleMenu(open) {
        var willOpen =
            typeof open === "boolean"
                ? open
                : menuPanel.style.display === "none" ||
                  menuPanel.style.display === "";
        menuPanel.style.display = willOpen ? "block" : "none";
        menuBtn.setAttribute("aria-expanded", String(willOpen));
        menuPanel.setAttribute("aria-hidden", String(!willOpen));
    }

    menuBtn.addEventListener("click", function (e) {
        e.stopPropagation();
        toggleMenu();
    });

    document.addEventListener("click", function (e) {
        if (menuPanel.style.display !== "block") return;
        if (menuPanel.contains(e.target) || menuBtn.contains(e.target)) return;
        toggleMenu(false);
    });

    // ===== EMAIL MODAL FUNCTIONS =====
    function createEmailModal() {
        // Remove existing modal if any
        var existing = document.getElementById("emailModal");
        if (existing) existing.remove();

        var modal = document.createElement("div");
        modal.id = "emailModal";
        modal.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 999999;
            animation: fadeIn 0.2s ease;
        `;

        modal.innerHTML = `
            <div style="
                background: white;
                border-radius: 16px;
                padding: 24px;
                max-width: 400px;
                width: 90%;
                box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);
                animation: slideUp 0.3s ease;
            ">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h3 style="margin: 0; font-size: 18px; font-weight: 600; color: #111827;">Email Chat Transcript</h3>
                    <button id="closeEmailModal" style="
                        background: none;
                        border: none;
                        font-size: 24px;
                        color: #9ca3af;
                        cursor: pointer;
                        padding: 0;
                        width: 32px;
                        height: 32px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        border-radius: 4px;
                        transition: all 0.2s;
                    " onmouseover="this.style.background='#f3f4f6'; this.style.color='#111827'" 
                       onmouseout="this.style.background='none'; this.style.color='#9ca3af'">×</button>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 500; color: #374151;">
                        Email Address
                    </label>
                    <input 
                        type="email" 
                        id="emailInput" 
                        placeholder="Enter email address"
                        style="
                            width: 100%;
                            padding: 10px 12px;
                            border: 1px solid #d1d5db;
                            border-radius: 8px;
                            font-size: 14px;
                            transition: all 0.2s;
                            box-sizing: border-box;
                        "
                        onfocus="this.style.borderColor='#3b82f6'; this.style.outline='none'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)'"
                        onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none'"
                    />
                    <p id="emailError" style="
                        margin: 8px 0 0 0;
                        font-size: 13px;
                        color: #ef4444;
                        display: none;
                    "></p>
                </div>

                <div id="emailStatus" style="
                    margin-bottom: 16px;
                    padding: 12px;
                    border-radius: 8px;
                    font-size: 14px;
                    display: none;
                "></div>

                <div style="display: flex; gap: 12px; justify-content: flex-end;">
                    <button id="cancelEmailBtn" style="
                        padding: 10px 20px;
                        border: 1px solid #d1d5db;
                        border-radius: 8px;
                        background: white;
                        color: #374151;
                        font-size: 14px;
                        font-weight: 500;
                        cursor: pointer;
                        transition: all 0.2s;
                    " onmouseover="this.style.background='#f9fafb'" 
                       onmouseout="this.style.background='white'">Cancel</button>
                    
                    <button id="confirmEmailBtn" style="
                        padding: 10px 20px;
                        border: none;
                        border-radius: 8px;
                        background: #3b82f6;
                        color: white;
                        font-size: 14px;
                        font-weight: 500;
                        cursor: pointer;
                        transition: all 0.2s;
                        display: flex;
                        align-items: center;
                        gap: 8px;
                    " onmouseover="this.style.background='#2563eb'" 
                       onmouseout="this.style.background='#3b82f6'">
                        <span>Send</span>
                    </button>
                </div>
            </div>
        `;

        // Add animations
        var style = document.createElement("style");
        style.textContent = `
            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }
            @keyframes slideUp {
                from { transform: translateY(20px); opacity: 0; }
                to { transform: translateY(0); opacity: 1; }
            }
        `;
        document.head.appendChild(style);

        document.body.appendChild(modal);
        return modal;
    }

    function closeEmailModal() {
        var modal = document.getElementById("emailModal");
        if (modal) {
            modal.style.animation = "fadeOut 0.2s ease";
            setTimeout(function () {
                modal.remove();
            }, 200);
        }
    }

    function showEmailStatus(message, type) {
        var status = document.getElementById("emailStatus");
        if (!status) return;

        var colors = {
            sending: { bg: "#fef3c7", text: "#92400e", icon: "⏳" },
            success: { bg: "#d1fae5", text: "#065f46", icon: "✅" },
            error: { bg: "#fee2e2", text: "#991b1b", icon: "❌" },
        };

        var color = colors[type] || colors.error;
        status.style.background = color.bg;
        status.style.color = color.text;
        status.style.display = "block";
        status.innerHTML = `${color.icon} ${message}`;
    }

    function showEmailError(message) {
        var error = document.getElementById("emailError");
        if (error) {
            error.textContent = message;
            error.style.display = "block";
        }
    }

    function hideEmailError() {
        var error = document.getElementById("emailError");
        if (error) {
            error.style.display = "none";
        }
    }

    // EMAIL BUTTON CLICK
    emailBtn.addEventListener("click", async function () {
        toggleMenu(false);

        var modal = createEmailModal();
        var emailInput = document.getElementById("emailInput");
        var confirmBtn = document.getElementById("confirmEmailBtn");
        var cancelBtn = document.getElementById("cancelEmailBtn");
        var closeBtn = document.getElementById("closeEmailModal");

        emailInput.focus();

        // Close handlers
        closeBtn.onclick = closeEmailModal;
        cancelBtn.onclick = closeEmailModal;
        modal.onclick = function (e) {
            if (e.target === modal) closeEmailModal();
        };

        // Enter key to submit
        emailInput.onkeypress = function (e) {
            if (e.key === "Enter") {
                confirmBtn.click();
            }
        };

        // Confirm button
        confirmBtn.onclick = async function () {
            var email = emailInput.value.trim();
            hideEmailError();

            // Validate email
            if (!email) {
                showEmailError("Please enter an email address");
                emailInput.focus();
                return;
            }

            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                showEmailError("Please enter a valid email address");
                emailInput.focus();
                return;
            }

            // Disable inputs while sending
            confirmBtn.disabled = true;
            cancelBtn.disabled = true;
            emailInput.disabled = true;
            confirmBtn.style.opacity = "0.6";
            confirmBtn.style.cursor = "not-allowed";
            confirmBtn.innerHTML = "<span>Sending...</span>";

            showEmailStatus("Sending transcript...", "sending");

            try {
                var transcript = buildTxtTranscript();
                var res = await fetch(
                    BASE + "/api/chat/" + API_KEY + "/email",
                    {
                        method: "POST",
                        headers: { "Content-Type": "application/json" },
                        body: JSON.stringify({
                            to: email,
                            transcript: transcript,
                            session_id:
                                localStorage.getItem(SESSION_KEY) || "preview",
                        }),
                    }
                );

                var data = await res.json().catch(function () {
                    return {};
                });

                if (res.ok && data.ok) {
                    showEmailStatus(
                        `Transcript sent successfully to ${email}!`,
                        "success"
                    );
                    setTimeout(closeEmailModal, 2000);
                } else {
                    showEmailStatus(
                        "Failed to send email. Please try again.",
                        "error"
                    );
                    // Re-enable button
                    confirmBtn.disabled = false;
                    cancelBtn.disabled = false;
                    emailInput.disabled = false;
                    confirmBtn.style.opacity = "1";
                    confirmBtn.style.cursor = "pointer";
                    confirmBtn.innerHTML = "<span>Send</span>";
                }
            } catch (e) {
                console.error(e);
                showEmailStatus(
                    "Network error. Please check your connection.",
                    "error"
                );
                // Re-enable button
                confirmBtn.disabled = false;
                cancelBtn.disabled = false;
                emailInput.disabled = false;
                confirmBtn.style.opacity = "1";
                confirmBtn.style.cursor = "pointer";
                confirmBtn.innerHTML = "<span>Send</span>";
            }
        };
    });

    function collectChatMessages() {
        var box = document.getElementById("messages");
        var items = [];
        if (!box) return items;
        var nodes = box.querySelectorAll(".message");
        for (var i = 0; i < nodes.length; i++) {
            var el = nodes[i];
            var text = (el.textContent || "").trim();
            if (!text) continue;
            var role = "bot";
            if (el.classList.contains("user-message")) role = "user";
            else if (el.classList.contains("operator-message"))
                role = "operator";
            items.push({ role: role, content: text });
        }
        return items;
    }

    // emailBtn.addEventListener("click", async function () {
    //     var to = (prompt("Send transcript to email address:") || "").trim();
    //     if (!to) return;

    //     var transcript = buildTxtTranscript(); // reuses your collector
    //     try {
    //         const res = await fetch(BASE + "/api/chat/" + API_KEY + "/email", {
    //             method: "POST",
    //             headers: { "Content-Type": "application/json" },
    //             body: JSON.stringify({
    //                 to: to,
    //                 transcript: transcript,
    //                 session_id: localStorage.getItem(SESSION_KEY) || "preview",
    //             }),
    //         });
    //         const data = await res.json().catch(() => ({}));
    //         if (res.ok && data.ok) {
    //             alert("Transcript sent to " + to);
    //         } else {
    //             alert("Could not send email. Please try again.");
    //             console.warn("email error", data);
    //         }
    //     } catch (e) {
    //         console.error(e);
    //         alert("Network error while sending email.");
    //     }

    //     toggleMenu(false);
    // });

    function downloadTextFile(filename, text) {
        var blob = new Blob([text], { type: "text/plain;charset=utf-8" });
        var url = URL.createObjectURL(blob);
        var a = document.createElement("a");
        a.href = url;
        a.download = filename;
        document.body.appendChild(a);
        a.click();
        a.remove();
        URL.revokeObjectURL(url);
    }

    function esc(s) {
        return String(s).replace(/[&<>"']/g, function (c) {
            return {
                "&": "&amp;",
                "<": "&lt;",
                ">": "&gt;",
                '"': "&quot;",
                "'": "&#39;",
            }[c];
        });
    }

    function buildTxtTranscript() {
        var messages = collectChatMessages();
        var header =
            "Widget: " +
            (document.getElementById("widgetName").innerText || "Widget") +
            "\n" +
            "Session: " +
            (localStorage.getItem(SESSION_KEY) || "preview") +
            "\n" +
            "Exported: " +
            new Date().toLocaleString() +
            "\n\n";
        var lines = messages.map(function (m) {
            var who =
                m.role === "user"
                    ? "User"
                    : m.role === "operator"
                    ? "Operator"
                    : "Bot";
            return "[" + who + "] " + m.content;
        });
        return header + lines.join("\n");
    }

    function buildPrintableHtml() {
        var messages = collectChatMessages();
        var avatar =
            document.getElementById("avatar").getAttribute("src") || "";
        var wname = document.getElementById("widgetName").innerText || "Widget";
        var bubbles = messages
            .map(function (m) {
                var cls = m.role === "user" ? "user" : "bot";
                return (
                    '<div class="row ' +
                    cls +
                    '"><div class="bubble">' +
                    esc(m.content) +
                    "</div></div>"
                );
            })
            .join("");
        var head =
            '<!doctype html><html><head><meta charset="utf-8"><title>' +
            esc(wname) +
            " - Chat Transcript</title>" +
            "<style>@media print{@page{margin:12mm}}body{font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;margin:16px}.header{display:flex;align-items:center;gap:12px;margin-bottom:12px}.header img{width:48px;height:48px;border-radius:10px;object-fit:cover}.sub{color:#666;font-size:12px}.row{display:flex;margin:8px 0}.row.user{justify-content:flex-end}.row.bot{justify-content:flex-start}.bubble{max-width:70ch;padding:10px 12px;border-radius:14px;background:#f1f1f1;white-space:pre-wrap;word-wrap:break-word}.row.user .bubble{background:#e8f0ff}hr{border:0;height:1px;background:#e5e5e5;margin:12px 0}</style></head><body>";
        var headerHtml =
            '<div class="header">' +
            (avatar ? '<img src="' + avatar + '" alt="">' : "") +
            "<div><div><strong>" +
            esc(wname) +
            "</strong></div>" +
            '<div class="sub">Session: ' +
            esc(localStorage.getItem(SESSION_KEY) || "preview") +
            "</div>" +
            '<div class="sub">Exported: ' +
            esc(new Date().toLocaleString()) +
            "</div>" +
            "</div></div><hr>";
        var tail = "</body></html>";
        return head + headerHtml + (bubbles || "<em>No messages.</em>") + tail;
    }

    dlBtn.addEventListener("click", function () {
        var base =
            (document.getElementById("widgetName").innerText || "widget")
                .toLowerCase()
                .replace(/[^a-z0-9]+/g, "-")
                .replace(/^-+|-+$/g, "")
                .substring(0, 50) || "widget";
        var stamp = new Date().toISOString().replace(/[:.]/g, "-");
        var filename =
            base +
            "-chat-" +
            (localStorage.getItem(SESSION_KEY) || "preview") +
            "-" +
            stamp +
            ".txt";
        downloadTextFile(filename, buildTxtTranscript());
        toggleMenu(false);
    });

    printBtn.addEventListener("click", function () {
        const html = buildPrintableHtml();

        // create hidden iframe
        const iframe = document.createElement("iframe");
        iframe.style.position = "fixed";
        iframe.style.right = "0";
        iframe.style.bottom = "0";
        iframe.style.width = "0";
        iframe.style.height = "0";
        iframe.style.border = "0";
        iframe.style.visibility = "hidden";
        document.body.appendChild(iframe);

        // write the printable HTML
        const doc = iframe.contentWindow.document;
        doc.open();
        doc.write(html);
        doc.close();

        // try to print when ready
        const tryPrint = () => {
            try {
                iframe.contentWindow.focus();
                iframe.contentWindow.print();
                // clean up after a short delay
                setTimeout(() => document.body.removeChild(iframe), 500);
            } catch (e) {
                // if the document isn't ready yet, retry shortly
                setTimeout(tryPrint, 100);
            }
        };

        iframe.onload = tryPrint;
        setTimeout(tryPrint, 250); // extra nudge in case onload doesn't fire

        // close the menu
        (function toggleMenu(open) {
            const menuPanel = document.getElementById("chatActions");
            const menuBtn = document.getElementById("chatMenuBtn");
            const willOpen = typeof open === "boolean" ? open : false;
            menuPanel.style.display = willOpen ? "block" : "none";
            menuBtn.setAttribute("aria-expanded", String(willOpen));
            menuPanel.setAttribute("aria-hidden", String(!willOpen));
        })(false);
    });

    function toggleMenu(open) {
        var willOpen =
            typeof open === "boolean"
                ? open
                : menuPanel.style.display === "none" ||
                  menuPanel.style.display === "";
        menuPanel.style.display = willOpen ? "block" : "none";
        menuBtn.setAttribute("aria-expanded", String(willOpen));
        menuPanel.setAttribute("aria-hidden", String(!willOpen));
    }
})();
