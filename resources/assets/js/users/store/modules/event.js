import Vue from 'vue';

const module = {
    state: {
        sort: {},
    },

    mutations: {
        SET_SORT_BY(state, {trending, genre}) {
            state.sort = {trending, genre}
        }
    },

    getter: {}
};
export default module;