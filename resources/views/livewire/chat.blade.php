<div>
    @php
        function generateAvatar($name, $size = 40) {
            $colors = ['#FF5733', '#33FF57', '#3357FF', '#F333FF', '#FF33A8', '#33FFF5'];
            $initials = '';
            $words = explode(' ', $name);
            foreach ($words as $word) {
                $initials .= strtoupper(substr($word, 0, 1));
                if (strlen($initials) >= 2) break;
            }
            $colorIndex = crc32($name) % count($colors);
            $bgColor = $colors[$colorIndex];
            return "data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='$size' height='$size' viewBox='0 0 100 100'><rect width='100' height='100' rx='50' fill='$bgColor'/><text x='50' y='60' font-size='40' text-anchor='middle' fill='white'>$initials</text></svg>";
        }
    @endphp

    <div class="chat-wrapper">
        <div class="chat-container">
            <!-- Left sidebar - Contacts list -->
            <div class="contacts-list">
                <div class="chat-header">
                    <div class="header-user">
                        <div class="user-avatar">
                            @livewire('avatar-upload', ['user' => auth()->user()], key(auth()->id()))
                        </div>
                        <h3>Chats</h3>
                    </div>
                </div>
                <div class="search-box">
                    <input type="text" wire:model.live="searchTerm" placeholder="Search or start new chat">
                </div>
                <div class="contacts">
                    @foreach($users as $user)
                    <div class="contact {{ $selectedUserId == $user->id ? 'active' : '' }}"
                         wire:click="selectUser({{ $user->id }})">
                        <div class="contact-avatar">
                            <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : generateAvatar($user->name, 32) }}" alt="User Avatar" class="contact-avatar-img">
                        </div>
                        <div class="contact-info">
                            <h4>{{ $user->name }}</h4>
                            <p>{{ $user->email }}</p>
                        </div>
                        <div class="contact-time">
                            <span>{{ $user->last_message_time }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Right side - Chat area -->
            <div class="chat-area">
                @if($selectedUserId && isset($selectedUser) && $selectedUser)
                    <!-- Your existing chat UI when a user is selected -->
                    <div class="chat-header">
                        <div class="chat-user">
                            <div class="user-avatar">
                                <img src="{{ $selectedUser->avatar ? asset('storage/' . $selectedUser->avatar) : generateAvatar($selectedUser->name) }}" class="avatar-img" alt="Avatar">
                            </div>
                            <div class="user-info">
                                <h3>{{ $selectedUser->name }}</h3>
                                <p>{{ $selectedUser->email }}</p>
                                <p class="status-indicator">
                                    <span class="status-dot online"></span>
                                    Online
                                </p>
                            </div>
                            <div class="chat-actions">
                                <button class="action-btn">
                                    <svg viewBox="0 0 24 24" width="20" height="20">
                                        <path fill="currentColor" d="M12 7a2 2 0 1 0-.001-4.001A2 2 0 0 0 12 7zm0 2a2 2 0 1 0-.001 3.999A2 2 0 0 0 12 9zm0 6a2 2 0 1 0-.001 3.999A2 2 0 0 0 12 15z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="messages" id="messages">
                        @foreach($messages as $message)
                        <div class="message {{ $message['sender_id'] == auth()->id() ? 'sent' : 'received' }}">
                            @if($message['sender_id'] != auth()->id())
                            <div class="message-avatar">
                                <img src="{{ isset($message['sender']['avatar']) && $message['sender']['avatar'] ? asset('storage/' . $message['sender']['avatar']) : generateAvatar($message['sender']['name'] ?? 'User', 28) }}" alt="User Avatar" class="avatar-img">
                            </div>
                            @endif
                            <div class="message-content">
                                <p>{{ $message['message'] }}</p>
                                <span class="message-time">
                                    {{ \Carbon\Carbon::parse($message['created_at'])->format('h:i A') }}
                                    @if($message['sender_id'] == auth()->id())
                                    <span class="message-status">
                                        <svg viewBox="0 0 24 24" width="16" height="16">
                                            <path fill="currentColor" d="M18 7l-1.41-1.41-6.34 6.34 1.41 1.41L18 7zm4.24-1.41L11.66 16.17 7.48 12l-1.41 1.41 4.24 4.24 8.49-8.48z"></path>
                                        </svg>
                                    </span>
                                    @endif
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="message-input">
                        <form wire:submit="submit">
                            <div class="input-actions">
                                <button type="button" class="emoji-btn">
                                    <svg viewBox="0 0 24 24" width="24" height="24">
                                        <path fill="currentColor" d="M12 20a8 8 0 0 0 8-8 8 8 0 0 0-8-8 8 8 0 0 0-8 8 8 8 0 0 0 8 8zm0-18a10 10 0 0 1 10 10 10 10 0 0 1-10 10A10 10 0 0 1 2 12 10 10 0 0 1 12 2zm0 13a1 1 0 0 1 1 1 1 1 0 0 1-1 1 1 1 0 0 1-1-1 1 1 0 0 1 1-1zm-3.5-3a1.5 1.5 0 0 1 1.5 1.5 1.5 1.5 0 0 1-1.5 1.5A1.5 1.5 0 0 1 7 12.5 1.5 1.5 0 0 1 8.5 11zm7 0a1.5 1.5 0 0 1 1.5 1.5 1.5 1.5 0 0 1-1.5 1.5 1.5 1.5 0 0 1-1.5-1.5 1.5 1.5 0 0 1 1.5-1.5z"></path>
                                    </svg>
                                </button>
                                <button type="button" class="attachment-btn">
                                    <svg viewBox="0 0 24 24" width="24" height="24">
                                        <path fill="currentColor" d="M16.5 6v11.5c0 2.21-1.79 4-4 4s-4-1.79-4-4V5a2.5 2.5 0 0 1 5 0v10.5c0 .55-.45 1-1 1s-1-.45-1-1V6H10v9.5a2.5 2.5 0 0 0 5 0V5c0-2.21-1.79-4-4-4S7 2.79 7 5v12.5c0 3.04 2.46 5.5 5.5 5.5s5.5-2.46 5.5-5.5V6h-1.5z"></path>
                                    </svg>
                                </button>
                            </div>
                            <input type="text" 
                                   wire:model="newMessage" 
                                   placeholder="Type a message...." 
                                   class="message-field"
                                   required>
                            <button type="submit" class="send-btn">
                                <svg viewBox="0 0 24 24" width="24" height="24">
                                    <path fill="currentColor" d="M1.101 21.757L23.8 12.028 1.101 2.3l.011 7.912 13.623 1.816-13.623 1.817-.011 7.912z"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                @else
                    <!-- Empty state when no chat is selected -->
                    <div class="no-chat-selected">
                        <div class="empty-state">
                            <div class="empty-icon">
                                <svg viewBox="0 0 24 24" width="64" height="64">
                                    <path fill="#aebac1" d="M19.005 3.175H4.674C3.642 3.175 3 3.789 3 4.821V21.02l3.544-3.514h12.461c1.033 0 2.064-1.06 2.064-2.093V4.821c-.001-1.032-1.032-1.646-2.064-1.646zm-4.989 9.869H7.041V11.1h6.975v1.944zm3-4H7.041V7.1h9.975v1.944z"></path>
                                </svg>
                            </div>
                            <h3>G13 Web Chat</h3>
                            <p>Select a chat to start messaging</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .chat-wrapper {
        min-height: 400px;
        width: 100%;
    }
    .chat-container {
        display: flex;
        width: 100%;
        max-width: 100%;
        margin: 0;
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
        position: relative;
    }
    .chat-header {
        padding: 10px 15px;
        background: #ededed;
        border-bottom: 1px solid #e1e1e1;
    }
    .header-user {
        display: flex;
        align-items: center;
    }
    .header-user h3 {
        margin-left: 10px;
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
    .contact-avatar {
        position: relative;
        margin-right: 10px;
    }
    .contact-avatar-img {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        object-fit: cover;
    }
    .user-avatar img, .message-avatar img {
        border-radius: 50%;
        object-fit: cover;
    }
    .contact-info {
        flex: 1;
        min-width: 0; /* Allows text truncation */
    }
    .contact-info h4 {
        margin: 0;
        font-size: 15px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .contact-info p {
        margin: 3px 0 0;
        font-size: 12px;
        color: #777;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .contact-time {
        font-size: 11px;
        color: #999;
        white-space: nowrap;
    }
    /* Chat header specific styles */
    .chat-area .chat-header {
        padding: 10px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .chat-user {
        display: flex;
        align-items: center;
        flex: 1;
    }
    .user-avatar {
        margin-right: 15px;
    }
    .avatar-img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
    }
    .user-info {
        flex: 1;
    }
    .user-info h3 {
        margin: 0;
        font-size: 16px;
        font-weight: 500;
        color: #333;
    }
    .status-indicator {
        margin: 2px 0 0;
        font-size: 13px;
        color: #667781;
        display: flex;
        align-items: center;
    }
    .status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        margin-right: 6px;
    }
    .status-dot.online {
        background-color: #4ad504;
    }
    .chat-actions {
        display: flex;
    }
    .action-btn {
        background: none;
        border: none;
        color: #54656f;
        cursor: pointer;
        padding: 8px;
        margin-left: 10px;
    }
    .messages {
        flex: 1;
        padding: 20px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        background-image: url('https://web.whatsapp.com/img/bg-chat-tile-light_a4be512e7195b6b733d9110b408f075d.png');
        background-repeat: repeat;
    }
    .message {
        max-width: 65%;
        margin-bottom: 15px;
        display: flex;
        position: relative;
    }
    .message.received {
        align-self: flex-start;
    }
    .message.sent {
        align-self: flex-end;
        flex-direction: row-reverse;
    }
    .message-avatar {
        align-self: flex-end;
        margin-right: 8px;
    }
    .message-avatar img {
        width: 28px;
        height: 28px;
    }
    .message-content {
        padding: 8px 12px;
        border-radius: 7.5px;
        position: relative;
        word-wrap: break-word;
        max-width: 100%;
    }
    .message.received .message-content {
        background: white;
        margin-left: 10px;
        border-top-left-radius: 0;
    }
    .message.sent .message-content {
        background: #dcf8c6;
        margin-right: 10px;
        border-top-right-radius: 0;
    }
    .message-time {
        font-size: 11px;
        color: #667781;
        display: inline-block;
        margin-left: 8px;
        margin-top: 4px;
        clear: both;
    }
    .message-status {
        margin-left: 4px;
        vertical-align: middle;
    }
    .message-input {
        display: flex;
        padding: 10px;
        background: #f0f0f0;
        align-items: center;
        border-top: 1px solid #e1e1e1;
    }
    .input-actions {
        display: flex;
        margin-right: 10px;
    }
    .emoji-btn, .attachment-btn {
        background: none;
        border: none;
        color: #54656f;
        cursor: pointer;
        padding: 8px;
    }
    .message-field {
        flex: 1;
        padding: 10px 15px;
        border: none;
        border-radius: 20px;
        background: white;
        font-size: 15px;
        outline: none;
    }
    .send-btn {
        background: none;
        border: none;
        cursor: pointer;
        color: #54656f;
        margin-left: 10px;
        padding: 8px;
    }
    .no-chat-selected {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
        background: #f8f9fa;
    }
    .empty-state {
        text-align: center;
        padding: 20px;
    }
    .empty-icon {
        margin-bottom: 20px;
    }
    .empty-state h3 {
        font-size: 24px;
        color: #41525d;
        margin-bottom: 8px;
    }
    .empty-state p {
        color: #667781;
        font-size: 14px;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('message-sent', () => {
            const messagesDiv = document.getElementById('messages');
            messagesDiv.scrollTop = messagesDiv.scrollHeight;
        });
        
        Livewire.on('chat-selected', () => {
            setTimeout(() => {
                const messagesDiv = document.getElementById('messages');
                messagesDiv.scrollTop = messagesDiv.scrollHeight;
            }, 100);
        });
    });
</script>
@endpush