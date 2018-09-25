<template>
    <div>

        <div class="login p-1 p-sm-2 p-md-5 p-lg-5 p-xl-5">
            <div class="col-12 col-sm-8 col-md-8 col-lg-8 offset-sm-2 offset-lg-2 p-xs-5 login-form">
                <div class="row">
                <div class="hidden-sm-down col-md-6 col-lg-6 side-cover">
                    <img src="themes/default/img/background.jpg" alt="cover" class="hidden-lg-down" width="110%">
                    <img class="hidden-xl-up" src="themes/default/img/background.jpg" alt="cover" width="160%">
                </div>

                <div class="col-12 col-sm-12 col-md-6 col-lg-6 p-lg-5 pt-sm-5 p-3" @keyup.enter="login">
                    <div class="form-group">
                        <label class="col-8 control-label">E-mail</label>
                        <div class="col-12">
                            <input class="form-control" name="email" v-validate="'required|email|max:50'" :class="{'input': true, 'input-danger': errors.has('email') }"
                                type="email" v-model="email" placeholder="E-mail">
                            <span v-show="errors.has('email')" class="help is-danger">{{errors.first('email')}}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-8 control-label">Password</label>
                        <div class="col-12">
                            <input class="form-control" type="password" name="password" v-validate="'required|min:6|max:100'" :class="{'input': true, 'input-danger': errors.has('password') }"
                                v-model="password" placeholder="Password">
                            <span v-show="errors.has('password')" class="help is-danger">{{errors.first('password')}}</span>
                        </div>
                    </div>
                    <div class="form-group" v-show="error">
                        <div class="col-12">
                            <div class="help is-danger">E-mail or password is incorrect</div>
                        </div>
                    </div>
                    <div class="form-group form-inline">
                        <div class="col-12 forget-password">
                            <router-link :to="{name: 'forget_password'}">Forget password ?</router-link>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-12">
                            <button class="btn btn-outline-primary" @click="login" v-if="!button_loading">LOGIN</button>
                            <button class="btn btn-outline-primary" v-if="button_loading">
                                <i class="fa fa-circle-o-notch fa-spin"></i> Loading</button>
                        </div>
                    </div>
                    <div class="my-5 text-center">
                        Don't have an account?
                        <router-link :to="{name: 'plan'}">Signup</router-link>
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
    } from 'vuex';

    export default {
        data() {
            return {
                email: '',
                password: '',
            };
        },
        computed: mapState({
            error: state => state.auth.error,
            button_loading: state => state.auth.button_loading
        }),
        methods: {
            login() {
                this.$validator.validateAll().then((result) => {
                    if (result) {
                        this.$store.dispatch('LOGIN', {
                            'email': this.email,
                            'password': this.password
                        });
                    }
                })
            }
        }
    }
</script>