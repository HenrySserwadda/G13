<div class="chat-container">
    <!-- Left sidebar - Contacts list -->
    <div class="contacts-list">
        <div class="chat-header">
            <h3>Chats</h3>
        </div>
        <div class="search-box">
            <input type="text" placeholder="Search or start new chat">
        </div>
        <div class="contacts">
            <!-- Sample contacts - you'll replace with dynamic data -->
            <div class="contact active">
                <div class="contact-avatar">
                    <img src="https://via.placeholder.com/40" alt="User Avatar">
                </div>
                <div class="contact-info">
                    <h4>John Doe</h4>
                    <p>Hello there!</p>
                </div>
                <div class="contact-time">
                    <span>12:30 PM</span>
                </div>
            </div>
            <div class="contact">
                <div class="contact-avatar">
                    <img src="https://via.placeholder.com/40" alt="User Avatar">
                </div>
                <div class="contact-info">
                    <h4>Jane Smith</h4>
                    <p>See you tomorrow</p>
                </div>
                <div class="contact-time">
                    <span>Yesterday</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Right side - Chat area -->
    <div class="chat-area">
        <div class="chat-header">
            <div class="chat-user">
                <div class="user-avatar">
                    <img src="https://via.placeholder.com/40" alt="User Avatar">
                </div>
                <div class="user-info">
                    <h3>John Doe</h3>
                    <p>Online</p>
                </div>
            </div>
        </div>
        
        <div class="messages" id="messages">
            <!-- Messages will appear here -->
            <div class="message received">
                <div class="message-avatar">
                    <img src="https://via.placeholder.com/30" alt="User Avatar">
                </div>
                <div class="message-content">
                    <p>Hey there!</p>
                    <span class="message-time">10:30 AM</span>
                </div>
            </div>
            <div class="message sent">
                <div class="message-content">
                    <p>Hi! How are you?</p>
                    <span class="message-time">10:32 AM</span>
                </div>
            </div>
        </div>
        
        <div class="message-input">
            <input type="text" id="message" placeholder="Type a message">
            <button onclick="sendMessage()">
                <svg viewBox="0 0 24 24" width="24" height="24">
                    <path fill="currentColor" d="M1.101 21.757L23.8 12.028 1.101 2.3l.011 7.912 13.623 1.816-13.623 1.817-.011 7.912z"></path>
                </svg>
            </button>
        </div>
    </div>
</div>

<style>
    /* WhatsApp-like styling */
    .chat-container {
        display: flex;
        height: 80vh;
        max-width: 1200px;
        margin: 20px auto;
        border: 1px solid #e1e1e1;
        box-shadow: 0 1px 1px rgba(0,0,0,0.08);
        font-family: 'Segoe UI', Helvetica, Arial, sans-serif;
    }
    
    .contacts-list {
        width: 30%;
        border-right: 1px solid #e1e1e1;
        background: #f7f7f7;
        display: flex;
        flex-direction: column;
    }
    
    .chat-area {
        width: 70%;
        display: flex;
        flex-direction: column;
        background: #e5ddd5;
        background-image: url('https://web.whatsapp.com/img/bg-chat-tile-light_a4be512e7195b6b733d9110b408f075d.png');
    }
    
    .chat-header, .search-box {
        padding: 10px 15px;
        background: #ededed;
        border-bottom: 1px solid #e1e1e1;
    }
    
    .search-box input {
        width: 100%;
        padding: 8px 10px;
        border: none;
        border-radius: 20px;
        background: white;
    }
    
    .contacts {
        flex: 1;
        overflow-y: auto;
    }
    
    .contact {
        display: flex;
        padding: 10px 15px;
        border-bottom: 1px solid #e1e1e1;
        cursor: pointer;
        align-items: center;
    }
    
    .contact:hover, .contact.active {
        background: #e9e9e9;
    }
    
    .contact-avatar img, .user-avatar img, .message-avatar img {
        border-radius: 50%;
        margin-right: 10px;
    }
    
    .contact-info {
        flex: 1;
    }
    
    .contact-info h4 {
        margin: 0;
        font-size: 16px;
    }
    
    .contact-info p {
        margin: 3px 0 0;
        font-size: 13px;
        color: #777;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .contact-time {
        font-size: 12px;
        color: #999;
    }
    
    .chat-user {
        display: flex;
        align-items: center;
    }
    
    .user-info p {
        margin: 3px 0 0;
        font-size: 13px;
        color: #777;
    }
    
    .messages {
        flex: 1;
        padding: 20px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
    }
    
    .message {
        max-width: 70%;
        margin-bottom: 15px;
        display: flex;
    }
    
    .message.received {
        align-self: flex-start;
    }
    
    .message.sent {
        align-self: flex-end;
        flex-direction: row-reverse;
    }
    
    .message-content {
        padding: 8px 12px;
        border-radius: 7.5px;
        position: relative;
    }
    
    .message.received .message-content {
        background: white;
        margin-left: 10px;
    }
    
    .message.sent .message-content {
        background: #dcf8c6;
        margin-right: 10px;
    }
    
    .message-time {
        font-size: 11px;
        color: #777;
        display: block;
        text-align: right;
        margin-top: 5px;
    }
    
    .message-input {
        display: flex;
        padding: 10px;
        background: #f0f0f0;
        align-items: center;
    }
    
    .message-input input {
        flex: 1;
        padding: 10px 15px;
        border: none;
        border-radius: 20px;
        margin-right: 10px;
    }
    
    .message-input button {
        background: none;
        border: none;
        cursor: pointer;
        color: #919191;
    }
    
    .message-input button:hover {
        color: #4fc3f7;
    }
</style>

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