import Vue from 'vue';
import VueRouter from 'vue-router';
import VeeValidate from 'vee-validate';

try {
    window.$ = window.jQuery = require('jquery');
    require('bootstrap');

} catch (e) {}


window.axios = require('axios');

Vue.use(VueRouter);
Vue.use(VeeValidate);

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo'

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: 'your-pusher-key'
// });