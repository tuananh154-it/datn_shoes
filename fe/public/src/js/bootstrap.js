import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// Cấu hình Pusher
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: 'YOUR_PUSHER_KEY', // Lấy trong .env
    cluster: 'mt1',
    forceTLS: true
});
