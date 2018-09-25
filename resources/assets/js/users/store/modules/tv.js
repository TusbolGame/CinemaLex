import Vue from 'vue';

const module = {
    state: {
        data: [],
        loading: false,
    },
    actions: {

        /**
         * Get all channels
         * 
         * @param {*} param0 
         */
        LOAD_TV_LIST({
            commit
        }) {
            commit('SPINER_LOAD');
            axios.get('/api/v1/get/tv')
                .then((response) => {
                    if (response.status === 200) {
                        commit('SET_TV_LIST', response.data.data);
                        commit('SPINER_CLEAN');
                    }
                });

        },
    },
    mutations: {
        SET_TV_LIST(state, data) {
            state.data = data;
        },

        SPINER_LOAD(state) {
            state.loading = true;
        },

        SPINER_CLEAN(state) {
            state.loading = false;
        }
    },
};
export default module;