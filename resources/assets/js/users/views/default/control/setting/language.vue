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
                        <div class="col-3 col-sm-4 col-md-4 col-lg-3 col-xl-2 mt-4 ml-2 ml-sm-3 p-1 text-center flag" :class="{active:active === 'en'}"
                            @click="SET_LANGUAGE('en')">
                        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                            viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve" width="100%">
                        <circle style="fill:#F0F0F0;" cx="256" cy="256" r="256"/>
                        <g>
                            <path style="fill:#D80027;" d="M244.87,256H512c0-23.106-3.08-45.49-8.819-66.783H244.87V256z"/>
                            <path style="fill:#D80027;" d="M244.87,122.435h229.556c-15.671-25.572-35.708-48.175-59.07-66.783H244.87V122.435z"/>
                            <path style="fill:#D80027;" d="M256,512c60.249,0,115.626-20.824,159.356-55.652H96.644C140.374,491.176,195.751,512,256,512z"/>
                            <path style="fill:#D80027;" d="M37.574,389.565h436.852c12.581-20.529,22.338-42.969,28.755-66.783H8.819
                                C15.236,346.596,24.993,369.036,37.574,389.565z"/>
                        </g>
                        <path style="fill:#0052B4;" d="M118.584,39.978h23.329l-21.7,15.765l8.289,25.509l-21.699-15.765L85.104,81.252l7.16-22.037
                            C73.158,75.13,56.412,93.776,42.612,114.552h7.475l-13.813,10.035c-2.152,3.59-4.216,7.237-6.194,10.938l6.596,20.301l-12.306-8.941
                            c-3.059,6.481-5.857,13.108-8.372,19.873l7.267,22.368h26.822l-21.7,15.765l8.289,25.509l-21.699-15.765l-12.998,9.444
                            C0.678,234.537,0,245.189,0,256h256c0-141.384,0-158.052,0-256C205.428,0,158.285,14.67,118.584,39.978z M128.502,230.4
                            l-21.699-15.765L85.104,230.4l8.289-25.509l-21.7-15.765h26.822l8.288-25.509l8.288,25.509h26.822l-21.7,15.765L128.502,230.4z
                            M120.213,130.317l8.289,25.509l-21.699-15.765l-21.699,15.765l8.289-25.509l-21.7-15.765h26.822l8.288-25.509l8.288,25.509h26.822
                            L120.213,130.317z M220.328,230.4l-21.699-15.765L176.93,230.4l8.289-25.509l-21.7-15.765h26.822l8.288-25.509l8.288,25.509h26.822
                            l-21.7,15.765L220.328,230.4z M212.039,130.317l8.289,25.509l-21.699-15.765l-21.699,15.765l8.289-25.509l-21.7-15.765h26.822
                            l8.288-25.509l8.288,25.509h26.822L212.039,130.317z M212.039,55.743l8.289,25.509l-21.699-15.765L176.93,81.252l8.289-25.509
                            l-21.7-15.765h26.822l8.288-25.509l8.288,25.509h26.822L212.039,55.743z"/>
                        </svg>
                            <strong>English</strong>
                        </div>
                        <div class="col-3 col-sm-4 col-md-4 col-lg-3 col-xl-2 mt-4 ml-2 ml-sm-3 p-1 text-center flag" :class="{active:active === 'fr'}"
                            @click="SET_LANGUAGE('fr')">
                            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve" width="100%">
                            <circle style="fill:#F0F0F0;" cx="256" cy="256" r="256"/>
                            <path style="fill:#D80027;" d="M512,256c0-110.071-69.472-203.906-166.957-240.077v480.155C442.528,459.906,512,366.071,512,256z"/>
                            <path style="fill:#0052B4;" d="M0,256c0,110.071,69.473,203.906,166.957,240.077V15.923C69.473,52.094,0,145.929,0,256z"/>

                            </svg>
                            <strong>French</strong>
                        </div>
                        <div class="col-3 col-sm-4 col-md-4 col-lg-3 col-xl-2 mt-4 ml-2 ml-sm-3 p-1 text-center flag" :class="{active:active === 'de'}"
                            @click="SET_LANGUAGE('de')">
                            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve" width="100%">
                            <path style="fill:#FFDA44;" d="M15.923,345.043C52.094,442.527,145.929,512,256,512s203.906-69.473,240.077-166.957L256,322.783
                                L15.923,345.043z"/>
                            <path d="M256,0C145.929,0,52.094,69.472,15.923,166.957L256,189.217l240.077-22.261C459.906,69.472,366.071,0,256,0z"/>
                            <path style="fill:#D80027;" d="M15.923,166.957C5.633,194.69,0,224.686,0,256s5.633,61.31,15.923,89.043h480.155
                                C506.368,317.31,512,287.314,512,256s-5.632-61.31-15.923-89.043H15.923z"/>
                            </svg>
                            <strong>German</strong>
                        </div>
                        <div class="col-3 col-sm-4 col-md-4 col-lg-3 col-xl-2 mt-4 ml-2 ml-sm-3 p-1 text-center flag" :class="{active:active === 'in'}"
                            @click="SET_LANGUAGE('in')">
                            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve" width="100%">
                            <circle style="fill:#F0F0F0;" cx="256" cy="256" r="256"/>
                            <path style="fill:#FF9811;" d="M256,0C154.506,0,66.81,59.065,25.402,144.696h461.195C445.19,59.065,357.493,0,256,0z"/>
                            <path style="fill:#6DA544;" d="M256,512c101.493,0,189.19-59.065,230.598-144.696H25.402C66.81,452.935,154.506,512,256,512z"/>
                            <circle style="fill:#0052B4;" cx="256" cy="256" r="89.043"/>
                            <circle style="fill:#F0F0F0;" cx="256" cy="256" r="55.652"/>
                            <polygon style="fill:#0052B4;" points="256,187.326 273.169,226.264 315.473,221.663 290.337,256 315.473,290.337 273.169,285.736 
                                256,324.674 238.831,285.736 196.527,290.336 221.663,256 196.527,221.663 238.831,226.264 "/>
                            </svg>
                            <strong>Hindi</strong>
                        </div>
                        <div class="col-3 col-sm-4 col-md-4 col-lg-3 col-xl-2 mt-4 ml-2 ml-sm-3 p-1 text-center flag" :class="{active:active === 'es'}"
                            @click="SET_LANGUAGE('es')">
                                <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                    viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve" width="100%">
                                <path style="fill:#FFDA44;" d="M0,256c0,31.314,5.633,61.31,15.923,89.043L256,367.304l240.077-22.261
                                    C506.367,317.31,512,287.314,512,256s-5.633-61.31-15.923-89.043L256,144.696L15.923,166.957C5.633,194.69,0,224.686,0,256z"/>
                                <g>
                                    <path style="fill:#D80027;" d="M496.077,166.957C459.906,69.473,366.071,0,256,0S52.094,69.473,15.923,166.957H496.077z"/>
                                    <path style="fill:#D80027;" d="M15.923,345.043C52.094,442.527,145.929,512,256,512s203.906-69.473,240.077-166.957H15.923z"/>
                                </g>
                                </svg>
                            <strong>Spanish</strong>
                        </div>
                        <div class="col-3 col-sm-4 col-md-4 col-lg-3 col-xl-2 mt-4 ml-2 ml-sm-3 p-1 text-center flag" :class="{active:active === 'tr'}"
                            @click="SET_LANGUAGE('tr')">
                            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve" width="100%">
                            <circle style="fill:#D80027;" cx="256" cy="256" r="256"/>
                            <g>
                                <polygon style="fill:#F0F0F0;" points="245.518,209.186 266.523,238.131 300.54,227.101 279.502,256.021 300.504,284.965 
                                    266.499,273.893 245.462,302.813 245.484,267.052 211.478,255.98 245.496,244.95 	"/>
                                <path style="fill:#F0F0F0;" d="M188.194,328.348c-39.956,0-72.348-32.392-72.348-72.348s32.392-72.348,72.348-72.348
                                    c12.458,0,24.18,3.151,34.414,8.696c-16.055-15.702-38.012-25.392-62.24-25.392c-49.178,0-89.043,39.866-89.043,89.043
                                    s39.866,89.043,89.043,89.043c24.23,0,46.186-9.691,62.24-25.392C212.374,325.197,200.652,328.348,188.194,328.348z"/>
                            </g>
                            </svg>
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
