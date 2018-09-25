import Vue from 'vue';
import router from '../../packages/Routes';
import swal from 'sweetalert';
const alertify = require("alertify.js");

const module = {
    state: {
        items: {},
        payment_update: {},
        plan: {},
        error: null,
        loading: true,
        button_loading: false,
    },
    actions: {

        /**
         *  Send request to oauth to check if the email and password is correct or return 401 error 
         * 
         * @param {*} commit 
         * @param {*} array Email And Password
         */
        LOGIN({commit}, {email,password}) {
            commit('BUTTON_LOAD');
            var data = {
                grant_type: 'password',
                client_id: 2,
                client_secret: Vue.helper.client_secret(),
                username: email,
                password: password,
                scope: '',
            };
            axios.post('/oauth/token', data)
                .then(response => {
                    axios.get('/api/user', {
                            headers: {
                                Authorization: 'Bearer ' + response.data.access_token
                            }
                    }).then(info => {
                        Vue.auth.setToken(response.data.access_token, response.data.expires_in, info.data.name, info.data.image, info.data.language); 
                        router.go('/')
                    });
                }, (error) => {
                    if (error.response.status === 401) {
                        commit('SET_ERROR', {
                            'error': true
                        })
                        commit('BUTTON_CLEAR')
                    }
                });
        },

                
        /**
         * Check hash 
         * 
         * @param {any} {commit} 
         * @param {hash} {code} 
         */

        CHECK_FORGET_CODE({commit}, code) {

            axios.post("/api/v1/register/forget/checkhash", { code: code }).then(response => {
                if (response.status === 200) {
                    // Message
                    // Not necessarily
                }
            }, error => {
                 router.push({ name: 'login'});
            });

        },
    
        
        /**
         *  Change password
         * 
         * @param {*} { commit }
         * @param {*} { email, hash, password, password }
         */

        CHANGE_FORGET_PASSWORD({ commit }, {code, password, password_confirmation }) {
            commit('BUTTON_LOAD');
            axios.post("/api/v1/update/register/password", {
                    code:  code,
                    password: password,
                    password_confirmation: password_confirmation
                })
                .then(response => {
                    if (response.status  === 200) {
                        router.push({ name: "login" });
                        alertify.logPosition("top right");
                        alertify.success(response.data.message)
                        commit('BUTTON_CLEAR');                             
                    }
                }, error => {
                    alertify.logPosition("top right");
                    alertify.error(error.response.data.message)
                    commit('BUTTON_CLEAR');                             
                });
        },

        /**
         *  Send message to email to reset password
         * 
         *  @param {email} email 
         */

        CHECK_EMAIL({commit}, email){
            commit('BUTTON_LOAD');
            axios.post('/api/v1/check/register/email', { email: email })
                .then(response => {
                    if (response.status === 200) {
                        router.push({ name: "login" });
                        alertify.logPosition("top right");
                        alertify.success(response.data.message);
                        commit('BUTTON_CLEAR');
                    }
                }, error => {
                    alertify.logPosition("top right");
                    alertify.error(error.response.data.message)
                    commit('BUTTON_CLEAR');
                });
        },

        /**
         * Change password of already user
         * 
         * @param {*} commit 
         * @param {*} password
         */
        UPDATE_PASSWORD(commit, { current_password, password, password_confirmation}) {
                    swal({
                        title: "Are you sure to change password ?",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    }).then((willDelete) => {
                        if (willDelete) {
                            axios.post('/api/v1/update/profile/password', {
                                current_password: current_password,
                                password: password,
                                password_confirmation: password_confirmation
                            }).then(response => {
                            if (response.status === 200) {
                                alertify.logPosition("top right");
                                alertify.success(response.data.message);
                                Vue.auth.destroyToken();
                                router.push({
                                    name: 'login'
                                });
                            }
                        }, error => {
                            alertify.logPosition("top right");
                            alertify.error(error.response.data.message);
                        });
                    }
                });
        },
        
        
        /**
         * Get payment details
         * 
         * @param {*}  
         */
        GET_PAYMENT({commit}) {
            commit('SPINER_LOAD');
            axios.get('/api/v1/get/profile/payment')
                .then(response => {
                    commit('SET_GET_PAYMENT', {
                        data: response.data
                    });
                    commit('SPINER_CLEAN');
                })
        },

        /**
         * Get billing details for one year
         * 
         * @param {*}  
         */
        GET_BILLING_DETAILS({
            commit
        }) {
            commit('SPINER_LOAD');
            axios.get('/api/v1/get/profile/payment/billing')
                .then(response => {
                    commit('SET_BILLING_DETAILS', {
                        data: response.data
                    });
                    commit('SPINER_CLEAN');
                })
        },

        /**
         *  Cancel membership 
         * 
         * @param {array} commit 
         */
        CANCEL_MEMBERSHIP({
            commit
        }) {
            swal({
                title: "Are you sure?",
                text: "Once Canceled, you will can resume your account Within 10 months and then will be deleted!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    commit('BUTTON_LOAD');
                    axios.get('/api/v1/update/profile/payment/cancel_membership').then(response => {
                        if (response.data.status === 'success') {
                            commit('SET_CANCEL_MEMBERSHIP', {
                                data: response.data
                            });
                            commit('BUTTON_CLEAR')
                        }
                    });
                    swal("The account is canceled!", {
                        icon: "success",
                    });
                }
            });
        },

        
            /**
             * Resume membership 
             * 
             * @param {any} {commit } 
             */
            
            RESUME_MEMBERSHIP({commit }) {
            commit('BUTTON_LOAD');
            axios.get('/api/v1/update/profile/payment/resume_membership').then(response => {
                if (response.data.status === 'success') {
                    alertify.logPosition("top right");
                    alertify.success("Successful resume your account");
                    commit('SET_RESUME_MEMBERSHIP', {
                        data: response.data
                    });
                    commit('BUTTON_CLEAR')
                }
            });
        },


       /**
         * Change plan 
         * 
         * @param {any} {commit } 
         */
        CHANGE_PLAN({
            commit
        }, plan_id) {
            swal({
                title: "Are you sure to change your plan?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    commit('BUTTON_LOAD')
                    axios.post('/api/v1/update/profile/payment/change_plan', {
                        plan_id: plan_id
                    }).then(response => {
                        if (response.data.status === 'success') {
                            commit('SET_PLAN', {
                                plan_id: plan_id
                            });
                            commit('BUTTON_CLEAR')
                        }
                    });
                }
            });
        },

        /**
         * Change default language 
         * 
         * @param {*} commit 
         * @param {*} lang 
         */
        SET_LANGUAGE({
            commit
        }, lang) {
            axios.post('/api/v1/update/profile/language', {
                language: lang
            }).then(response => {
                
                // Message
                // Not necessarily

            });
        },


       /**
        * Adujst caption 
        *
        * @param {*} commit
        * @param {*} caption 
        */
        SET_CAPTION({ commit }, caption) {
            axios.post('/api/v1/profile/caption', {caption: caption}).then(res => {
                alertify.logPosition("top right");
                alertify.success("Successful update");
            }, error => {
                alertify.logPosition("top right");
                alertify.error("Error");
            })
        },


            /**
             * Get viewing history 
             * 
             * @param {any} {commit } 
             */
            
            GET_VIEWING_HISTORY({commit }) {
                commit('SPINER_LOAD');
                axios.get('/api/v1/get/profile/viewing_history').then(response => {
                if (response.data.status === 'success') {
                    commit('SET_VIEWING_HISTORY', {
                        data: response.data.data
                    });
                    commit('SPINER_CLEAN');
                }
            });
        },

    },

    mutations: {

        SET_ERROR(state, error) {
            state.error = error.error;
        },

        SET_GET_PAYMENT(state, data) {
            state.loading = false;
            state.items = data.data;
            state.payment_update = data.data;
            state.plan = data.data;
        },

        SET_BILLING_DETAILS(state, data) {
            state.loading = false;
            state.items = data.data;
        },

        SET_CANCEL_MEMBERSHIP(state, data) {
            state.payment_update = data.data;
        },

        SET_RESUME_MEMBERSHIP(state, data) {
            state.payment_update = data.data;
        },

        SET_PLAN(state, data) {
            state.plan.subscription_plan = data.plan_id;
        },

        SET_VIEWING_HISTORY(state, data) {
            state.items = data;
        },

        // Spiner load
        SPINER_LOAD(state) {
            state.loading = true;
        },

        SPINER_CLEAN(state) {
            state.loading = false;
        },

        // BUTTON load
        BUTTON_LOAD(state) {
            state.button_loading = true;
        },

        BUTTON_CLEAR(state) {
            state.button_loading = false;
        },
    },

}
export default module;