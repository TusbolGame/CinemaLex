require('../bootstrap');
import Vue from 'vue';
import app from './views/app.vue';
import store from './store/store.js';
import router from './routes';
import VeeValidate from 'vee-validate';
import swal from 'sweetalert';
import Auth from './packages/Auth';

Vue.use(Auth);
Vue.use(VeeValidate);


new Vue({
    el: '#administrator',
    store,
    router,
    swal,
    render: h => h(app)
});