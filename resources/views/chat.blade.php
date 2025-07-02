<div id="messages"></div>
<input type="text" id="message" />
<button onclick="sendMessage()">Send</button>
<p>chat</p>

<script>
    Echo.channel('chat-channel')
        .listen('.message.sent', (e) => {
            document.getElementById('messages').innerHTML += `<p><strong>${e.user.name}:</strong> ${e.message}</p>`;
        });

    function sendMessage() {
        fetch('/send-message', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ message: document.getElementById('message').value })
        });
    }
</script>
