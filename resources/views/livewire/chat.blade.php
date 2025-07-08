<div>
    @php
        use Illuminate\Support\Str;
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
                                @if(!empty($message['file_path']))
                                    @if(Str::startsWith($message['file_type'] ?? '', 'image/'))
                                        <a href="{{ asset('storage/' . $message['file_path']) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $message['file_path']) }}" alt="Image" style="max-width: 200px; max-height: 200px; border-radius: 8px; margin-bottom: 4px;">
                                        </a>
                                    @else
                                        <a href="{{ asset('storage/' . $message['file_path']) }}" download>
                                            <span class="file-icon" style="margin-right: 6px;">📎</span>
                                            {{ $message['original_file_name'] ?? basename($message['file_path']) }}
                                        </a>
                                    @endif
                                @endif
                                @if(!empty($message['message']))
                                    <p>{{ $message['message'] }}</p>
                                @endif
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
                        <form wire:submit="submit" enctype="multipart/form-data">
                            <div class="input-actions">
                                <button type="button" class="emoji-btn">
                                    <svg viewBox="0 0 24 24" width="24" height="24">
                                        <path fill="currentColor" d="M12 20a8 8 0 0 0 8-8 8 8 0 0 0-8-8 8 8 0 0 0-8 8 8 8 0 0 0 8 8zm0-18a10 10 0 0 1 10 10 10 10 0 0 1-10 10A10 10 0 0 1 2 12 10 10 0 0 1 12 2zm0 13a1 1 0 0 1 1 1 1 1 0 0 1-1 1 1 1 0 0 1-1-1 1 1 0 0 1 1-1zm-3.5-3a1.5 1.5 0 0 1 1.5 1.5 1.5 1.5 0 0 1-1.5 1.5A1.5 1.5 0 0 1 7 12.5 1.5 1.5 0 0 1 8.5 11zm7 0a1.5 1.5 0 0 1 1.5 1.5 1.5 1.5 0 0 1-1.5 1.5 1.5 1.5 0 0 1-1.5-1.5 1.5 1.5 0 0 1 1.5-1.5z"></path>
                                    </svg>
                                </button>
                                <label class="attachment-btn" style="cursor:pointer;">
                                    <svg viewBox="0 0 24 24" width="24" height="24">
                                        <path fill="currentColor" d="M16.5 6v11.5c0 2.21-1.79 4-4 4s-4-1.79-4-4V5a2.5 2.5 0 0 1 5 0v10.5c0 .55-.45 1-1 1s-1-.45-1-1V6H10v9.5a2.5 2.5 0 0 0 5 0V5c0-2.21-1.79-4-4-4S7 2.79 7 5v12.5c0 3.04 2.46 5.5 5.5 5.5s5.5-2.46 5.5-5.5V6h-1.5z"></path>
                                    </svg>
                                    <input type="file" wire:model="newFile" style="display:none;">
                                </label>
                            </div>
                            @if($newFile)
                                <div class="file-preview" style="margin-top: 8px; display: flex; align-items: center; gap: 10px;">
                                    @if(substr($newFile->getMimeType(), 0, 6) === 'image/')
                                        <img src="{{ $newFile->temporaryUrl() }}" alt="Preview" style="max-width: 100px; max-height: 100px; border-radius: 6px; border: 1px solid #ccc;">
                                    @endif
                                    <span>{{ $newFile->getClientOriginalName() }}</span>
                                    <button type="button" wire:click="$set('newFile', null)" style="background: none; border: none; color: #e3342f; font-size: 18px; cursor: pointer;">&times;</button>
                                </div>
                            @endif
                            <input type="text" 
                                   wire:model="newMessage" 
                                   placeholder="Type a message...." 
                                   class="message-field"
                                   >
                            <button type="submit" class="send-btn">
                                <svg viewBox="0 0 24 24" width="24" height="24">
                                    <path fill="currentColor" d="M1.101 21.757L23.8 12.028 1.101 2.3l.011 7.912 13.623 1.816-13.623 1.817-.011 7.912z"></path>
                                </svg>
                            </button>
                        </form>
                        @error('newFile') <span class="text-danger">{{ $message }}</span> @enderror
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
    body {
        font-family: 'Inter', 'Segoe UI', Helvetica, Arial, sans-serif;
        background: #181c23;
    }
    .chat-wrapper {
        min-height: 400px;
        width: 100%;
        background: #181c23;
        border-radius: 18px;
        overflow: hidden;
    }
    .chat-container {
        display: flex;
        width: 100%;
        max-width: 100%;
        margin: 0;
        font-family: 'Segoe UI', Helvetica, Arial, sans-serif;
        border-radius: 18px;
        overflow: hidden;
    }
    .contacts-list {
        width: 30%;
        border-right: 1px solid #232733;
        background: #232733;
        display: flex;
        flex-direction: column;
    }
    .chat-area {
        width: 70%;
        display: flex;
        flex-direction: column;
        background: #181c23;
        position: relative;
    }
    .chat-header {
        padding: 10px 15px;
        background: #232733;
        border-bottom: 1px solid #232733;
    }
    .header-user {
        display: flex;
        align-items: center;
    }
    .header-user h3 {
        margin-left: 10px;
        color: #f3f4f6;
    }
    .search-box {
        background: #232733;
        padding: 10px 0 10px 0;
        border-bottom: 1px solid #313543;
    }
    .search-box input {
        width: 100%;
        padding: 8px 10px;
        border: none;
        border-radius: 20px;
        background: #181c23;
        color: #f3f4f6;
    }
    .contacts {
        flex: 1;
        overflow-y: auto;
    }
    .contact {
        display: flex;
        padding: 10px 15px;
        border-bottom: 1px solid #232733;
        background: transparent;
        cursor: pointer;
        transition: background 0.2s;
    }
    .contact.active, .contact:hover {
        background: #2d3340;
    }
    .contact-avatar img {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 10px;
    }
    .contact-info h4 {
        margin: 0 0 2px 0;
        font-size: 16px;
        font-weight: 500;
        color: #f3f4f6;
    }
    .contact-info p {
        margin: 0;
        font-size: 13px;
        color: #b0b3b8;
    }
    .contact-time span {
        font-size: 12px;
        color: #7a7e87;
    }
    .chat-user .user-avatar img {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        object-fit: cover;
    }
    .user-info h3 {
        margin: 0;
        font-size: 18px;
        font-weight: 600;
        color: #f3f4f6;
    }
    .user-info p {
        margin: 0;
        font-size: 13px;
        color: #b0b3b8;
    }
    .status-indicator {
        margin-top: 2px;
        font-size: 12px;
        color: #38b2ac;
        display: flex;
        align-items: center;
    }
    .status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #38b2ac;
        display: inline-block;
        margin-right: 5px;
    }
    .chat-actions .action-btn {
        background: none;
        border: none;
        color: #b0b3b8;
        margin-left: 10px;
        cursor: pointer;
        transition: color 0.2s;
    }
    .chat-actions .action-btn:hover {
        color: #38b2ac;
    }
    .messages {
        flex: 1;
        padding: 20px;
        height: 400px;
        max-height: 400px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 10px;
        background: transparent;
        scroll-behavior: smooth;
    }
    .message {
        max-width: 65%;
        margin-bottom: 15px;
        display: flex;
        position: relative;
    }
    .message.sent {
        align-self: flex-end;
        flex-direction: row-reverse;
    }
    .message.received {
        align-self: flex-start;
    }
    .message-avatar {
        align-self: flex-end;
        margin-right: 8px;
    }
    .avatar-img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
    }
    .message-content {
        padding: 10px 16px;
        border-radius: 12px;
        background: #232733;
        color: #f3f4f6;
        font-size: 15px;
        position: relative;
        word-wrap: break-word;
        max-width: 100%;
        box-shadow: 0 1px 2px 0 rgba(0,0,0,0.10);
        border: 1px solid #232733;
    }
    .message.sent .message-content {
        background: #2563eb;
        color: #fff;
        border: 1px solid #2563eb;
    }
    .message-content img {
        max-width: 120px;
        max-height: 120px;
        border-radius: 8px;
        margin-bottom: 4px;
        border: 1px solid #232733;
        display: block;
    }
    .message-content a {
        color: #7ab8ff;
        text-decoration: underline;
        font-size: 14px;
        word-break: break-all;
        font-weight: 600;
    }
    .message.sent .message-content a {
        color: #fff;
        font-weight: 600;
        text-shadow: 0 1px 2px rgba(0,0,0,0.10);
    }
    .message-time {
        font-size: 12px;
        color: #b0b3b8;
        display: inline-block;
        margin-left: 8px;
    }
    .message-status {
        margin-left: 4px;
        color: #38b2ac;
        vertical-align: middle;
    }
    .message-input {
        padding: 10px;
        background: #232733;
        border-top: 1px solid #232733;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .message-input form {
        display: flex;
        align-items: center;
        width: 100%;
        gap: 8px;
    }
    .input-actions {
        display: flex;
        margin-right: 10px;
        align-items: center;
        gap: 4px;
    }
    .emoji-btn, .attachment-btn {
        background: none;
        border: none;
        color: #b0b3b8;
        font-size: 22px;
        cursor: pointer;
        transition: color 0.2s;
        padding: 0 4px;
    }
    .emoji-btn:hover, .attachment-btn:hover {
        color: #38b2ac;
    }
    .message-field {
        flex: 1;
        padding: 10px 15px;
        border: none;
        border-radius: 20px;
        background: #181c23;
        font-size: 15px;
        color: #f3f4f6;
        outline: none;
    }
    .send-btn {
        background: #2563eb;
        border: none;
        border-radius: 50%;
        width: 44px;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 22px;
        cursor: pointer;
        margin-left: 10px;
        transition: background 0.2s;
    }
    .send-btn:hover {
        background: #174bbd;
    }
    .file-preview {
        margin-top: 0;
        margin-right: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
        background: #232733;
        border-radius: 8px;
        padding: 4px 10px;
        box-shadow: 0 1px 2px 0 rgba(0,0,0,0.10);
        animation: fadeIn 0.3s;
        font-size: 13px;
    }
    .file-preview img {
        max-width: 60px;
        max-height: 60px;
        border-radius: 6px;
        border: 1px solid #232733;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: none; }
    }
    .no-chat-selected {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
        min-height: 400px;
        background: transparent;
    }
    .empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        width: 100%;
    }
    .empty-icon {
        margin-bottom: 20px;
    }
    .empty-state h3 {
        font-size: 24px;
        color: #f3f4f6;
        margin-bottom: 8px;
    }
    .empty-state p {
        color: #b0b3b8;
        font-size: 14px;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('livewire:load', function () {
        Livewire.hook('message.processed', (message, component) => {
            const messages = document.getElementById('messages');
            if (messages) {
                messages.scrollTo({ top: messages.scrollHeight, behavior: 'smooth' });
            }
        });
        Livewire.on('chat-selected', () => {
            setTimeout(() => {
                const messages = document.getElementById('messages');
                if (messages) {
                    messages.scrollTo({ top: messages.scrollHeight, behavior: 'smooth' });
                }
            }, 100);
        });
    });
</script>
@endpush