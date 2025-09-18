function getScriptQueryParam(param) {
    const scripts = document.getElementsByTagName('script');
    for (const script of scripts) {
        const src = script.getAttribute('src');
        if (src && src.includes(param)) {
            const urlParams = new URLSearchParams(src.split('?')[1]);
            return urlParams.get(param);
        }
    }
    return null;
}



var link2 = document.createElement("link");
link2.rel = "stylesheet";
link2.href = "https://ai-agent.rafusoft.com/assets/frontend/style.css";
document.head.appendChild(link2);


const link = document.createElement("link");
link.rel = "stylesheet";
link.href = "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css";
link.integrity = "sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==";
link.crossOrigin = "anonymous";
link.referrerPolicy = "no-referrer";

document.head.appendChild(link);



document.body.insertAdjacentHTML("beforeend", `
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
                <div class="messages" id="messages">
                    <div id="welcome_message_w" class="message bot-message"></div>
                </div>
            </div>
            <div class="chat-footer">
                <input type="text" id="chatInput" placeholder="Type your message..." onkeypress="sendMessage(event)">
                <button onclick="sendMessage()" id="send-button">
                <i class="fa-regular fa-paper-plane"></i>
                </button>
            </div>
            <div style="padding: 3px 5px; text-align: center;">
                <small class="text-[#]">
                    <a style="color: #7e7c7c;" href="https://rafusoft.com">Powered by Rafusoft AI Agent</a>
                </small>
            </div>
        </div>
    </div>
`);




// api kaey 

const API_KEY = getScriptQueryParam('api_key');




const getWidget = () => {
    fetch(`https://ai-agent.rafusoft.com/api/widget/${API_KEY}`)
        .then(res => res.json())
        .then(data => {
            document.getElementById('avatar').src = data.bot.avatar;
            document.getElementById('bot_name').innerText = data.bot.name;
            document.getElementById('welcome_message_w').innerText = data.bot.welcomeMessage;
            document.getElementById('widgetName').innerText = data.bot.widgetName;
            document.documentElement.style.setProperty('--primary-widget-color', data.bot.color);
        })
}
getWidget();

// Toggle chat box
const chatToggle = document.getElementById('chatToggle');
const chatBox = document.getElementById('chatBox');

chatToggle.addEventListener('click', () => {
    chatBox.classList.toggle('active');
});



async function sendMessage(event) {
    if (event && event.type === "keypress" && event.key !== "Enter") return;
    const input = document.getElementById("chatInput");
    const messageContainer = document.getElementById("messages");
    const userMessage = input.value.trim();

    if (userMessage) {
        // Add user's message
        const userMessageElement = document.createElement("div");
        userMessageElement.className = "message user-message";
        userMessageElement.textContent = userMessage;
        messageContainer.appendChild(userMessageElement);

        // Scroll to the bottom
        userMessageElement.scrollIntoView({ behavior: "smooth", block: "end" });

        input.value = "";

        // Add placeholder loading message
        const loadingMessageElement = document.createElement("div");
        loadingMessageElement.className = "message bot-message loading";
        loadingMessageElement.textContent = document.getElementById('bot_name').innerText + " is typing...";
        messageContainer.appendChild(loadingMessageElement);

        // Scroll to the loading message
        loadingMessageElement.scrollIntoView({ behavior: "smooth", block: "end" });

        try {
            // Fetch bot's reply
            const botReply = await fetchBotResponse(userMessage);

            // Remove loading message
            loadingMessageElement.remove();

            // Add bot's reply
            const botMessageElement = document.createElement("div");
            botMessageElement.className = "message bot-message";
            botMessageElement.textContent = botReply;
            messageContainer.appendChild(botMessageElement);

            // Scroll to the bot's reply
            botMessageElement.scrollIntoView({ behavior: "smooth", block: "end" });
        } catch (error) {
            // Remove loading message and show an error
            loadingMessageElement.remove();

            const errorMessageElement = document.createElement("div");
            errorMessageElement.className = "message bot-message";
            errorMessageElement.textContent = "Unable to connect. Please try again later.";
            messageContainer.appendChild(errorMessageElement);

            // Scroll to the error message
            errorMessageElement.scrollIntoView({ behavior: "smooth", block: "end" });
        }
    }
}

// Fetch response from server
async function fetchBotResponse(message) {
    try {
        const response = await fetch(`https://ai-agent.rafusoft.com/api/chat/${API_KEY}`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                message
            }),
        });
        const data = await response.json();
        return data.reply || "Sorry, something went wrong.";
    } catch (error) {
        console.error("Error fetching bot response:", error);
        return "Unable to connect. Please try again later.";
    }
}