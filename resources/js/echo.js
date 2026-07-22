import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

// Only initialize Echo if the Reverb app key is configured.
// Without this guard, Pusher throws an uncaught error that crashes
// ALL JavaScript on the page (including Alpine.js camera/GPS code).
const reverbKey = import.meta.env.VITE_REVERB_APP_KEY;

if (reverbKey) {
    const wsHost = import.meta.env.VITE_REVERB_HOST || window.location.hostname;
    const rawPort = import.meta.env.VITE_REVERB_PORT;
    const wsPort = rawPort ? Number(rawPort) : 80;
    const wssPort = rawPort ? Number(rawPort) : 443;
    const scheme = import.meta.env.VITE_REVERB_SCHEME || (window.location.protocol === 'https:' ? 'https' : 'http');
    const forceTLS = scheme === 'https';

    window.Echo = new Echo({
        broadcaster: 'reverb',
        key: reverbKey,
        wsHost: wsHost,
        wsPort: wsPort,
        wssPort: wssPort,
        forceTLS: forceTLS,
        enabledTransports: ['ws', 'wss'],
    });
} else {
    console.warn('[Echo] VITE_REVERB_APP_KEY is not set. Real-time features are disabled.');
}
