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
                <div class="language">
                    <b>{{$t('setting.select_language')}}</b>
                    <hr>
                    <div class="row">
                        <div class="col-3 col-sm-4 col-md-4 col-lg-3 col-xl-2 mt-4 ml-2 ml-sm-3 ml-md-3 ml-lg-3 ml-xl-3 p-1 text-center flag" :class="{active:active === 'en'}"
                            @click="SET_LANGUAGE('en')">
                            <img src="/themes/default/img/flag/united-states.svg" alt="united-states" width="100%">
                            <strong>English</strong>
                        </div>
                        <div class="col-3 col-sm-4 col-md-4 col-lg-3 col-xl-2 mt-4 ml-2 ml-sm-3 ml-md-3 ml-lg-3 ml-xl-3 p-1 text-center flag" :class="{active:active === 'fr'}"
                            @click="SET_LANGUAGE('fr')">
                            <img src="/themes/default/img/flag/france.svg" alt="france" width="100%">
                            <strong>French</strong>
                        </div>
                        <div class="col-3 col-sm-4 col-md-4 col-lg-3 col-xl-2 mt-4 ml-2 ml-sm-3 ml-md-3 ml-lg-3 ml-xl-3 p-1 text-center flag" :class="{active:active === 'de'}"
                            @click="SET_LANGUAGE('de')">
                            <img src="/themes/default/img/flag/germany.svg" alt="germany" width="100%">
                            <strong>German</strong>
                        </div>
                        <div class="col-3 col-sm-4 col-md-4 col-lg-3 col-xl-2 mt-4 ml-2 ml-sm-3 ml-md-3 ml-lg-3 ml-xl-3 p-1 text-center flag" :class="{active:active === 'in'}"
                            @click="SET_LANGUAGE('in')">
                            <img src="/themes/default/img/flag/india.svg" alt="india" width="100%">
                            <strong>Hindi</strong>
                        </div>
                        <div class="col-3 col-sm-4 col-md-4 col-lg-3 col-xl-2 mt-4 ml-2 ml-sm-3 ml-md-3 ml-lg-3 ml-xl-3 p-1 text-center flag" :class="{active:active === 'es'}"
                            @click="SET_LANGUAGE('es')">
                            <img src="/themes/default/img/flag/spain.svg" alt="spain" width="100%">
                            <strong>Spanish</strong>
                        </div>
                        <div class="col-3 col-sm-4 col-md-4 col-lg-3 col-xl-2 mt-4 ml-2 ml-sm-3 ml-md-3 ml-lg-3 ml-xl-3 p-1 text-center flag" :class="{active:active === 'tr'}"
                            @click="SET_LANGUAGE('tr')">
                            <img src="/themes/default/img/flag/turkey.svg" alt="spain" width="100%">
                            <strong>Turkish</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

</template>

<script>
    export default {
        data() {
            return {
                active: null,
            }
        },
        mounted() {
            const lang = localStorage.getItem('language');
            if (lang !== null) {
                this.$i18n.locale = lang;
                this.active = lang;
            } else {
                this.active = 'en';
                this.$i18n.locale = 'en';
            }
        },
        methods: {
            SET_LANGUAGE(lang) {
                this.$i18n.locale = lang;
                this.active = lang;
                localStorage.setItem('language', lang);
                this.$store.dispatch("SET_LANGUAGE", lang);
            },
        }
    };
</script>