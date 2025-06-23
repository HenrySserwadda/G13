<div id="messages"></div>
<input type="text" id="message" />
<button onclick="sendMessage()">Send</button>
<p>chat</p>

<script>
    Echo.channel('chat-channel')
        .listen('.message.sent', (e) => {
            const messagesDiv = document.getElementById('messages');
            const messageHtml = `
                <div class="message ${e.user.id === currentUserId ? 'sent' : 'received'}">
                    ${e.user.id !== currentUserId ? 
                        `<div class="message-avatar">
                            <img src="${e.user.avatar || 'https://via.placeholder.com/30'}" alt="User Avatar">
                        </div>` : ''}
                    <div class="message-content">
                        <p>${e.message}</p>
                        <span class="message-time">${new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</span>
                    </div>
                </div>`;
            messagesDiv.innerHTML += messageHtml;
            messagesDiv.scrollTop = messagesDiv.scrollHeight;
        });

    function sendMessage() {
        const messageInput = document.getElementById('message');
        if (messageInput.value.trim() === '') return;
        
        fetch('/send-message', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ 
                message: messageInput.value,
                receiver_id: currentChatUserId // You'll need to set this when a chat is selected
            })
        }).then(() => {
            messageInput.value = '';
        });
    }
    
    // You'll need to add logic for:
    // 1. Setting currentUserId (logged in user)
    // 2. Setting currentChatUserId when a contact is clicked
    // 3. Loading previous messages for a chat
    // 4. Handling contact list dynamically from your backend
</script>