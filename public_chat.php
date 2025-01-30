<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Public Chat</title>
    <style>
        .chat-bubble { padding: 10px; margin: 5px; border-radius: 10px; max-width: 70%; }
        .mine { background-color: #dcf8c6; text-align: right; margin-left: auto; }
        .other { background-color: #f1f0f0; }
        .chat-container { max-height: 400px; overflow-y: auto; }
    </style>
</head>
<body>
    <div class="chat-container" id="chat-container"></div>
    <form id="chat-form" enctype="multipart/form-data">
        <input type="text" name="message" id="message" placeholder="Type a message" required>
        <input type="file" name="image" id="image">
        <button type="submit">Send</button>
    </form>

    <script>
        const chatContainer = document.getElementById('chat-container');
        const chatForm = document.getElementById('chat-form');
        const messageInput = document.getElementById('message');

        async function loadChats() {
            const response = await fetch('chat_sys.php');
            const chats = await response.json();
            chatContainer.innerHTML = chats.map(chat => `
                <div class="chat-bubble ${chat.user_id == <?= $user_id ?> ? 'mine' : 'other'}">
                    <strong>${chat.user_id == <?= $user_id ?> ? 'You' : chat.username}:</strong>
                    <p>${chat.message}</p>
                    ${chat.image_url ? `<img src="${chat.image_url}" style="max-width: 100px;">` : ''}
                </div>
            `).join('');
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }

        chatForm.addEventListener('submit', async e => {
            e.preventDefault();
            const formData = new FormData(chatForm);
            await fetch('chat_sys.php', { method: 'POST', body: formData });
            messageInput.value = '';
            loadChats();
        });

        setInterval(loadChats, 3000);
        loadChats();
    </script>
</body>
</html>
