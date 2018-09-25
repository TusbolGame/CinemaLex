<template>
    <div>
        <span class="exit" @click="$Helper.home()">
            <i class="fa fa-times-circle-o" aria-hidden="true"></i>
        </span>
        <!-- EXIT -->
        <div class="row">
            <div class="col-12 col-sm-3 col-lg-3  profile-menu">
                <div class="col-lg-8 float-lg-right mt-5 h-100">
                    <div class="mb-2">
                        <b>{{$t('setting.user_setting')}}</b>
                    </div>
                    <div class="list-group">
                        <router-link class="list-group-item list-group-item-action" :to="{name: 'profile'}">{{$t('setting.profile')}}</router-link>
                        <router-link class="list-group-item list-group-item-action" :to="{name: 'security'}" exact>{{$t('setting.security')}}</router-link>
                    </div>

                    <div class="mb-2">
                        <b>{{$t('setting.biling')}}</b>
                    </div>
                    <div class="list-group">
                        <router-link class="list-group-item list-group-item-action" :to="{name: 'payment-update'}">{{$t('setting.update_payment')}}</router-link>
                        <router-link class="list-group-item list-group-item-action" :to="{name: 'billing-details'}" exact>{{$t('setting.billing_details')}}</router-link>
                        <router-link class="list-group-item list-group-item-action" :to="{name: 'change-plan'}" exact>{{$t('setting.change_plan')}}</router-link>
                    </div>

                    <div class="mb-2">
                        <b>{{$t('setting.app_setting')}}</b>
                    </div>
                    <div class="list-group">
                        <router-link class="list-group-item list-group-item-action" :to="{name: 'language'}">
                            {{$t('setting.language')}}
                        </router-link>
                    </div>

                    <div class="list-group">
                        <router-link class="list-group-item list-group-item-action" :to="{name: 'adjust-subtitles'}">
                            {{$t('setting.adjust_subtitles')}}
                        </router-link>
                    </div>
                    <div class="list-group">
                        <router-link class="list-group-item list-group-item-action" :to="{name: 'viewing-history'}">
                            {{$t('setting.viewing_history')}}
                        </router-link>
                    </div>
                    <hr>
                    <button class="btn btn-outline-danger w-100" @click="$auth.destroyToken()">{{$t('nav.logout')}}</button>
                </div>
            </div>

            <div class="col-12 col-sm-9 col-lg-9 offset-sm-3 offset-lg-3 p-sm-3 mt-5 profile-edit h-100">
                <div class="col-sm-10 col-lg-8 offset-sm-1 offset-lg-2">
                    <div class="form-group">
                        <label class="col-8 control-label">{{$t('setting.current')}}</label>
                        <div class="col-12">
                            <input class="form-control" type="password" name="current" v-validate="'min:8|required'" :class="{'input': true, 'input-danger': errors.has('current') }"
                                v-model="current" :placeholder="$t('setting.current')">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-8 control-label">{{$t('setting.password')}}</label>
                        <div class="col-12">
                            <input class="form-control" type="password" name="confirm-field" v-validate="'min:8|required'" :class="{'input': true, 'input-danger': errors.has('password') }"
                                v-model="password" :placeholder="$t('setting.password')">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-8 control-label">{{$t('setting.password_confirm')}}</label>
                        <div class="col-12">
                            <input class="form-control" type="password" name="password" v-validate="'min:8|required|confirmed:confirm-field'" :class="{'input': true, 'input-danger': errors.has('password') }"
                                v-model="confirm" :placeholder="$t('setting.password_confirm')" data-vv-as="password">
                            <span v-show="errors.has('password')" class="help is-danger">{{ errors.first('password')}}</span>
                        </div>
                    </div>
                    <div class="form-group" v-if="success">
                        <div class="col-12">
                            <span class="is-success">{{$t('setting.successful_update')}}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-12">
                            <button class="btn btn-outline-primary" @click="CHANGE_PASSWORD">{{$t('setting.update')}}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import BounceLoader from "vue-spinner/src/BounceLoader.vue";
    export default {
        data() {
            return {
                current: "",
                password: "",
                confirm: "",
                success: ""
            };
        },
        methods: {
            CHANGE_PASSWORD() {
                this.$validator.validateAll().then(result => {
                    if (result) {
                        this.$store.dispatch("UPDATE_PASSWORD", {
                            current_password: this.current,
                            password: this.password,
                            password_confirmation: this.confirm
                        });
                    }
                });
            }
        }
    };
</script>