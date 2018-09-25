import Vue from 'vue';
import Vuex from 'vuex';
import home from './modules/home';
import player from './modules/player';
import auth from './modules/auth';
import casts from './modules/casts';
import search from './modules/search';
import collections from './modules/collections';
import movies from './modules/movies';
import series from './modules/series';
import kids from './modules/kids';
import tv from './modules/tv';
import event from './modules/event';

Vue.use(Vuex);

export default new Vuex.Store({
    namespaced: true,
    strict: false,
    modules: {
        auth,
        home,
        player,
        casts,
        search,
        collections,
        movies,
        series,
        kids,
        tv,
        event,
    }
});
