import Vue from 'vue';
import router from '../../routes';
const alertify = require('alertify.js');

const module = {
    state: {
        data: [],
        data_search: {},
        button_loading: false,
        spinner_loading: false,
    },
    actions: {

        /**
         * Get all SERIES
         * 
         * @param {*} commit object 
         * @param id int id 
         */
        GET_ALL_SERIES({commit}) {
            commit('SPINER_LOAD');
            axios.get('/api/admin/get/series').then(response => {
                // if status code 200
                if(response.status === 200){                    
                    commit('SET_SERIES', response.data.data);
                    commit('SPINER_CLEAN');
               }
            });
        },

        GET_SERIES({commit}, id) {
            commit('SPINER_LOAD');
            axios.get('/api/admin/get/series/' + id).then(response => {
                // if status code 200
                if(response.status === 200){                    
                    commit('SET_SERIES', response.data.data);
                    commit('SPINER_CLEAN');
               }
            });
        },

        /**
         * Get SERIES by pagination
         * 
         * @param {*} commit object 
         * @param id int id 
         */

        GET_SERIES_PAGINATION({commit}, path_url) {
            commit('SPINER_LOAD');
            axios.get(path_url).then(response => {
                // if status code 200
                if(response.status === 200){                    
                    commit('SET_SERIES', response.data.data);
                    commit('SPINER_CLEAN');
               }
            });
        },

        /**
         * Delete SERIES
         * 
         * @param {*} id  uuid
         * @param {*} key int
         */
        DELETE_SERIES({commit}, {id, key}) {
            commit('BUTTON_LOAD', id);
            axios.delete('/api/admin/delete/series/' + id).then(response => {
              if (response.status === 200) {
                alertify.logPosition('top right');
                alertify.success('Successful Delete');  
                commit('DELETE_SERIES', key);
                commit('BUTTON_CLEAN');
              }
            },error => {
                alertify.logPosition('top right');
                alertify.error(error.response.data.message);
                commit('BUTTON_CLEAN');
            });
        },

        /**
         * Search SERIES
         * 
         * @param {string} string query 
         */

        GET_SERIES_SEARCH({commit}, query) {
            commit('SPINER_LOAD');
            axios.post('/api/admin/get/series/search', {query: query} ).then(response => {
                // if status code 200
                if(response.status === 200){                    
                    commit('SET_SEARCH_SERIES', response.data.data);
                    commit('SPINER_CLEAN');
               }
            });
        },

        ADD_SERIES_TO_TOP({commit}, {id,key}) {
            axios.post('/api/admin/new/series/top', {id: id}).then(response => {
              if (response.status === 200) {
                alertify.logPosition('top right');
                alertify.success(response.data.message); 
                commit('ADD_SERIES_TO_TOP',{id,key});
              }
            },error => {
                alertify.logPosition('top right');
                alertify.error(error.response.data.message);
            });
        },

        /**
         * Get season
         * 
         * @param {*} commit object 
         * @param id int id 
         */

        GET_ALL_SEASON({commit}, id) {
            commit('SPINER_LOAD');
            axios.get('/api/admin/get/series/season/' + id).then(response => {
                if(response.status === 200){                    
                    commit('SET_SERIES', response.data.data);
                    commit('SPINER_CLEAN');
               }
            });
        },

        /**
         * Get season
         * 
         * @param {*} commit object 
         * @param id int id 
         */

        GET_SERIES_INFO({commit}, id) {
            commit('SPINER_LOAD');
            axios.get('/api/admin/get/series/info/' + id).then(response => {
                if(response.status === 200){                    
                    commit('SET_SERIES', response.data.data);
                    commit('SPINER_CLEAN');
               }
            });
        },

       /**
         * Delete Episode
         * 
         * @param {*} id  uuid
         * @param {*} key int
         */
        DELETE_EPISODE({commit}, {id, key}) {
            commit('BUTTON_LOAD', id);
            axios.delete('/api/admin/delete/series/episode/' + id).then(response => {
              if (response.status === 200) {
                alertify.logPosition('top right');
                alertify.success('Successful Delete');  
                commit('DELETE_SERIES', key);
                commit('BUTTON_CLEAN');
              }
            },error => {
                alertify.logPosition('top right');
                alertify.error(error.response.data.message);
                commit('BUTTON_CLEAN');
            });
        },
    },

    mutations: {

        SET_SERIES(state, data) {
            state.data = data;
        },

        SET_SEARCH_SERIES(state, data) {
            state.data_search = data;
        },

        DELETE_SERIES(state, key) {
            state.data.series.data.splice(key, 1);
        },

        ADD_SERIES_TO_TOP(state, data) {
            state.data.series.data[data.key].series_id = data.id;
        },

        BUTTON_LOAD(state, data) {
            state.button_loading = data;
        },

        BUTTON_CLEAN(state) {
            state.button_loading = false;
        },

        SPINER_LOAD(state) {
            state.spinner_loading = true;
        },

        SPINER_CLEAN(state) {
            state.spinner_loading = false;
        }
    },
    getters: {}
};
export default module;