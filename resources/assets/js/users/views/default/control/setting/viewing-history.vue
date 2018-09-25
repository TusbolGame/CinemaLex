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

                <div class="preload hidden-xs-down" v-if="loading">
                    <bounce-loader color="#6b707c" size="80px" style="position: absolute;left: 50%;top: 40%;"></bounce-loader>
                </div>
                <div class="preload hidden-md-up" v-if="loading">
                    <bounce-loader color="#6b707c" size="80px" style="position: absolute;left: 50%;top: 40%;"></bounce-loader>
                </div>
                <table class="table table-inverse">
                    <thead>
                        <tr>
                            <th>{{$t('setting.name')}}</th>
                            <th>{{$t('setting.watch_date')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item,key) in viewing_history.data" :key="key">
                            <td v-if="item.type === 'movie'">
                           <router-link :to="{name: 'movie-show', params:{id: item.id }}">    
                            {{item.name}}
                            </router-link>
                            </td>
                            <td v-if="item.type === 'series'">
                             <router-link :to="{name: 'series-show', params:{id: item.id }}"> 
                              {{ item.name + ' ('+ $t('show.episode ') + item.episode_number + ')' }}
                             </router-link>
                             </td>
                            <td>{{item.created_at}}</td>
                        </tr>
                    </tbody>
                </table>
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
            viewing_history: state => state.auth.items
        }),
        mounted() {
                this.$store.dispatch("GET_VIEWING_HISTORY");
        },
    }
</script>

<style scoped>
.table-inverse a {
    color: #2196F3 !important;
}
</style>
