/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: import.meta.env.VITE_PUSHER_APP_KEY,
//     cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'mt1',
//     wsHost: window.location.hostname,
//     wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
//     wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
//     forceTLS: false,
//     enabledTransports: ['ws', 'wss'],
//     auth: {
//         headers: {
//             'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
//         }
//     }
// });


Pusher.logToConsole = true;

var pusher = new Pusher('63bb73ed80c7007c7360', {
    cluster: 'ap2'
});

var channel = pusher.subscribe('my-channel');
channel.bind('my-event', function (data) {
    alert(JSON.stringify(data));
});

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: "{{env(config('broadcasting.pusher.options.cluster'))}}",
    cluster: "{{config('broadcasting.pusher.key')}}",
    wsHost: window.location.hostname,
    wsPort: 6001,
    wssPort: 6001,
    disableStats: false,
    forceTLS: true,
    enabledTransports: [ 'wss'],
    auth: {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        }
    }
});
