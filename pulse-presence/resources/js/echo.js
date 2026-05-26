import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

// Only initialize Echo if the Reverb app key is configured.
// Without this guard, Pusher throws an uncaught error that crashes
// ALL JavaScript on the page (including Alpine.js camera/GPS code).
const reverbKey = import.meta.env.VITE_REVERB_APP_KEY;

if (reverbKey) {
    window.Echo = new Echo({
        broadcaster: 'reverb',
        key: reverbKey,
        wsHost: import.meta.env.VITE_REVERB_HOST,
        wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
        wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
        forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
        enabledTransports: ['ws', 'wss'],
    });
} else {
    console.warn('[Echo] VITE_REVERB_APP_KEY is not set. Real-time features are disabled.');
}
