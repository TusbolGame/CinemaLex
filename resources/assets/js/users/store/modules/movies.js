import Vue from 'vue';
import router from '../../packages/Routes';

const module = {
    state: {
        data: [],
        show: [],
        loading: false,
    },
    actions: {

        /**
         * Get all last movies 
         * 
         * @param {any} {commit} 
         */
        GET_MOVIES_LIST({
            commit
        }) {
            commit('SPINER_LOAD');
            axios.get('/api/v1/get/movies').then(response => {
                if (response.status === 200) {
                    const data = response.data.data;
                    commit('SET_MOVIES_LIST', data);
                    commit('SPINER_CLEAN');
                }
            });
        },

        /**
         * Get movies sort by trending or genre or twice
         * 
         * @param {*} commit 
         * @param {int,string} array {trending, genre} 
         */
        GET_MOVIES_SORT_BY_LIST({
            commit
        }, {
            trending,
            genre
        }) {
            commit('SPINER_LOAD');
            axios.post('/api/v1/get/movies/sort', {
                trending: trending,
                genre: genre
            }).then((response) => {
                if (response.status === 200) {
                    const data = response.data.data;
                    commit('SET_MOVIES_LIST', data);
                    commit('SPINER_CLEAN');
                }
            });
        },


        /**
         * Get movie details
         * 
         * @param {*} commit 
         * @param {*} id movie request 
         */
        GET_MOVIE_DETAILS({
            commit
        }, id) {
            commit('SPINER_LOAD');
            axios.get('/api/v1/get/movie/' + id).then((response) => {
                if (response.status === 200) {
                    const data = response.data.data;
                    commit('SET_MOVIE_DETAILS', data);
                    commit('SPINER_CLEAN');
                }
            }, error => {
                if(error.response.status === 404){
                    router.push({name: '404'});
                }else{
                    router.push('/');
                }
            });
        },
    },
    mutations: {
        SET_MOVIES_LIST(state, data) {
            state.data = data;
        },

        SET_MOVIE_DETAILS(state, data) {
            state.show = data;
        },

        // Spiner load
        SPINER_LOAD(state) {
            state.loading = true;
        },

        SPINER_CLEAN(state) {
            state.loading = false;
        }
    },
    getters: {}
};
export default module;