<template>
    <div>
        <div class="login p-1 p-sm-2 p-md-5 p-lg-5 p-xl-5">
            <div class="col-12 col-sm-8 col-md-8 col-lg-8 offset-sm-2 offset-lg-2 p-xs-5 login-form">
                <div class="row">
                    <div class="hidden-sm-down col-md-6 col-lg-6 side-cover">
                        <img src="/themes/default/img/background.jpg" alt="cover" class="hidden-lg-down" width="110%">
                        <img class="hidden-xl-up" src="/themes/default/img/background.jpg" alt="cover" width="160%">
                    </div>

                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 p-lg-5 pt-sm-5 p-3">
                        <div class="form-group">
                            <label class="col-12 control-label">
                                <h2>Password Reset.</h2>
                                Please enter your email address, We'll send you an email with a link to reset your password.
                            </label>
                            <div class="col-12">
                                <input class="form-control" name="email" v-validate="'required|email|max:50'" :class="{'input': true, 'input-danger': errors.has('email') }"
                                    type="email" v-model="email" placeholder="Email Address">
                                <span v-show="errors.has('email')" class="help is-danger">{{errors.first('email')}}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-12">
                                <button v-if="!button_loading" class="btn btn-outline-primary" @click="CHECK_EMAIL">SEND</button>
                                <button v-if="button_loading" class="btn btn-outline-primary" disabled>
                                    <i class="fa fa-circle-o-notch fa-spin"></i> Loading</button>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-12">
                                <p>If you still need help, contact
                                    <router-link :to="{name: 'contactus'}"> {{$t('app_name')}} Support</router-link>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {
        mapState
    } from "vuex";

    export default {
        data() {
            return {
                email: '',
            }
        },

        computed: mapState({
            data: state => state.auth.data,
            button_loading: state => state.auth.button_loading
        }),
        methods: {
            CHECK_EMAIL() {
                this.$validator.validateAll().then((result) => {
                    if (result) {
                        this.$store.dispatch('CHECK_EMAIL', this.email)
                    }
                })
            },

        }
    }
</script>