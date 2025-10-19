// Impor Axios terlebih dahulu
import axios from 'axios';
window.axios = axios;

// Set default header untuk permintaan AJAX Laravel
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// ======================================================
// Konfigurasi Laravel Echo dan Pusher
// ======================================================
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,      // ✅ gunakan VITE_ jika pakai Laravel 9+
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true
});
