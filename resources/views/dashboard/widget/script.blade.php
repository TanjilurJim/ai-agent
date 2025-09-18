<script src="{{ asset('assets/libs/huebee/huebee.pkgd.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/forms-advanced.js') }}"></script>

<script>
    // set avatar  
    const avatarInput = document.getElementById('avatar');
    avatarInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                const image = document.getElementById('avatar_preview');
                const imageW = document.getElementById('avatar_preview_w');
                image.src = event.target.result;
                imageW.src = event.target.result;
            };
            reader.readAsDataURL(file);
        }
    });


    // set name  
    const nameInput = document.getElementById('name');
    nameInput.addEventListener('keyup', function(e) {
        const name = e.target.value;
        document.getElementById('widget_name').innerText = name;
    })

    // set name  welcome Message
    const welcomeMessage = document.getElementById('welcomeMessage');
    welcomeMessage.addEventListener('keyup', function(e) {
        const name = e.target.value;
        document.getElementById('welcome_message_w').innerText = name;
    })

    // set name  welcome Message
    const color = document.getElementById('color');
    color.addEventListener('blur', function(e) {
        const mewcolor = e.target.value;
        console.log(mewcolor)
        document.documentElement.style.setProperty('--primary-widget-color', mewcolor);
    })






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
        loadingMessageElement.textContent = document.getElementById('widget_name').innerText + " is typing...";
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
            const response = await fetch(`/api/chat/{{ $widget->api_key ?? '' }}`, {
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
