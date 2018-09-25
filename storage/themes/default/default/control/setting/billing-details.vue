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

            <!-- END LIST -->

            <div class="col-12 col-sm-9 col-lg-9 offset-sm-3 offset-lg-3 p-sm-3 mt-5 profile-edit h-100">

                <div class="spinner-load" v-if="loading">
                    <div class="hidden-sm-up phone">
                        <div id="main">

                            <span class="spinner"></span>

                        </div>
                    </div>

                    <div class="hidden-xs-down other">
                        <div id="main">

                            <span class="spinner"></span>

                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-12">
                        <small>{{$t('setting.next_plan')}}</small>
                        <h6 v-if="info.name === 'Monthly'">{{info.name}} for ${{info.amount/100}}/mo</h6>
                        <h6 v-if="info.name === 'Yearly'"> {{info.name}} for ${{info.amount/100}}/y</h6>
                        <hr>
                    </div>
                </div>
                <table class="table table-inverse">
                    <thead>
                        <tr>
                            <th>{{$t('setting.description')}}</th>
                            <th>{{$t('setting.start_period')}}</th>
                            <th>{{$t('setting.end_period')}}</th>
                            <th>{{$t('setting.total')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item,key) in info.invoices" :key="key">
                            <td>{{$t('app_name')}}rine service</td>
                            <td>{{item.start}}</td>
                            <td>{{item.end}}</td>
                            <td>${{item.total/100}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>

</template>

<script>
    import {
        mapState
    } from "vuex";
    const alertify = require("alertify.js");
    import BounceLoader from 'vue-spinner/src/BounceLoader.vue';
    export default {
        components: {
            BounceLoader
        },
        data() {
            return {
                showModelError: false,
                error: null
            };
        },
        computed: mapState({
            loading: state => state.auth.loading,
            info: state => state.auth.items
        }),
        mounted() {
            if (this.$auth.isAuthenticated()) {
                this.$store.dispatch("GET_BILLING_DETAILS");
            }
        },
    }
</script>