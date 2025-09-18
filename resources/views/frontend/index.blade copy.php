<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="html template">
    <meta name="keywords" content="HTML, CSS, JavaScript">
    <meta name="author" content="Asad">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ai agent</title>
    <link rel="stylesheet" href="{{ asset('assets/frontend/style.css') }}">
    <script src="https://kit.fontawesome.com/90623e284f.js" crossorigin="anonymous"></script>
</head>

<body>
    <div class="chat-box" id="chatBox">
        <div class="chat-header">
            <div class="">
                <i class="fa-solid fa-bars"></i>
               <span id="widgetName" ></span>
            </div>
        </div>
        <div class="chat-body">
            <div class="chat-bot">
                <img class="rounded" id="avatar"  src="" alt="">
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
        <div style="padding: 3px 5px; text-align: center;"><small class="text-[#]"><a style="color: #7e7c7c;" href="http://bot.test/">Powered by Rafusoft AI Agent</a></small></div>
    </div>

    <script>
        const getWidget = () => {
            fetch(`http://bot.test/api/widget/XiEErW4upbO4CPXDfBuY`)
                .then(res => res.json())
                .then(data => {
                    document.getElementById('avatar').src = data.bot.avatar;
                    document.getElementById('bot_name').innerText = data.bot.name;
                    document.getElementById('welcome_message_w').innerText = data.bot.welcomeMessage;
                    document.getElementById('widgetName').innerText = data.bot.widgetName;
                    document.documentElement.style.setProperty('--primary-color', data.bot.botColor);
                })
        }
        getWidget();
 </script>




<script>
    // chating function 

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





    // 

    // Fetch response from server
    async function fetchBotResponse(message) {
        try {
            const response = await fetch(`/api/chat/XiEErW4upbO4CPXDfBuY`, {
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
</script>



</body>
</html>
