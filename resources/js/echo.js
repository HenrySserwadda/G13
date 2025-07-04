// Echo is initialized globally in bootstrap.js. Do not re-initialize here.
// You can add custom Echo listeners or helpers here if needed, but do not set window.Echo again.

// Always use window.Echo, not Echo directly.
if (typeof window.Echo !== 'undefined') {
    // Example: window.Echo.private('some-channel').listen('SomeEvent', (e) => { /* ... */ });
    // Add your custom listeners here
} else {
    console.warn('window.Echo is not defined. Make sure bootstrap.js is loaded first.');
}

/*import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});*/
window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT || 8080,  // Ensure fallback to 8080
    wssPort: import.meta.env.VITE_REVERB_PORT || 8080, // Same for wss
    forceTLS: false, // Force to false since you're using http
    enabledTransports: ['ws', 'wss'],
    authorizer: (channel, options) => {
        return {
            authorize: (socketId, callback) => {
                axios.post('/broadcasting/auth', {
                    socket_id: socketId,
                    channel_name: channel.name
                })
                .then(response => {
                    callback(false, response.data);
                })
                .catch(error => {
                    callback(true, error);
                });
            }
        };
    }
});
