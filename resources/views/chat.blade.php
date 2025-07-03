<div id="messages"></div>
<input type="text" id="message" />
<button onclick="sendMessage()">Send</button>
<p>chat</p>

<script>
    // Get the authenticated user's ID from a backend variable
    const userId = {{ auth()->id() }};
    // Listen on the correct private channel and event name
    window.Echo.private('chat.' + userId)
        .listen('.message.sent', (e) => {
            document.getElementById('messages').innerHTML += `<p><strong>${e.user.name ?? e.sender_id}:</strong> ${e.message}</p>`;
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
