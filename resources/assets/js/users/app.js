require('../bootstrap');
import Vue from 'vue';
import Helper from './packages/Helper';
import VueProgressBar from 'vue-progressbar';
import store from './store/store.js';
import router from './packages/Routes';
import i18n from './packages/Language';
import Auth from './packages/Auth';
Vue.use(Auth);
// Get theme name to render a folder of theme 
Vue.use(Helper);
let themeName = Vue.helper.current_theme();
let themePath = require('./views/' + themeName + '/app.vue');

axios.defaults.headers.common['Authorization'] = 'Bearer ' + Vue.auth.getToken();

const options = {
    color: '#03A9F4',
    failedColor: '#F44336',
    thickness: '3px',
    transition: {
        speed: '6s',
        opacity: '1',
        termination: 500
    },
    autoRevert: true,
    location: 'top',
    inverse: false
};
Vue.use(VueProgressBar, options);

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.userAuth)) {
        if (Vue.auth.isAuthenticated()) {
            next({
                path: '/v1/app'
            });
        } else {
            next();
        }
    } else {
        next(); // make sure to always call next()!
    }
});

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.userNotAuth)) {
        if (!Vue.auth.isAuthenticated()) {
            next({
                path: '/v1'
            });
        } else {
            next();
        }
    } else {
        next(); // make sure to always call next()!
    }
});

router.beforeEach((to, from, next) => {
    if (!to.matched.length) {
        next('/v1/404');
    } else {
        next();
    }
});

new Vue({
    el: '.' + themeName,
    i18n,
    store,
    router,
    render: h => h(themePath)
});