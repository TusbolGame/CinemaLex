<style scoped>
    .vc-chrome {
        position: absolute;
        z-index: 1;
    }
</style>

<template>
    <div>
        <span class="exit" @click="$Helper.home()">
            <i class="fa fa-times-circle-o" aria-hidden="true"></i>
        </span>
        <!-- EXIT -->
        <div class="row">
            <div class="col-12 col-sm-3 col-lg-3 profile-menu">
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
                <div class="offset-lg-1">
                    <div class="row">
                        <div class="col-12 col-sm-4 col-lg-2 mt-1">
                            <select class="custom-select" v-model="caption_style.fontSize">
                                <option value="15px">25%</option>
                                <option value="20px">50%</option>
                                <option value="25px">75%</option>
                                <option value="30px">100%</option>
                            </select>
                        </div>
                        <div class="col-12 col-sm-4 col-lg-2 mt-1">
                            <select class="custom-select" v-model="caption_style.fontWeight">
                                <option value="500">500</option>
                                <option value="600">600</option>
                                <option value="700">700</option>
                                <option value="800">800</option>
                                <option value="900">900</option>
                                <option value="1000">1000</option>

                            </select>
                        </div>
                        <div class="col-12 col-sm-4 col-lg-2 mt-1">
                            <button class="btn btn-outline-primary" @click="SHOW_FONT_PICKER">Font color</button>
                            <chrome-picker v-if="show_font_picker" v-model="colors_font" />
                        </div>
                        <div class="col-12 col-sm-4 col-lg-2 mt-1">
                            <button class="btn btn-outline-primary" @click="SHOW_BACKGROUND_PICKER">Background color</button>
                            <chrome-picker v-if="show_background_picker" v-model="colors_background" />
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-12 col-lg-10 mt-5">
                    <img src="/themes/default/img/background_caption.jpg" alt="caption_cover" width="100%" style="box-shadow: 1px 2px 2px 1px #1f1f1f;">
                    <div class="col-12">
                        <div style="text-align: center;font-style: normal;font-variant-ligatures: normal;font-variant-caps: normal;font-weight: normal;font-stretch: normal;line-height: normal;font-family: sans-serif;white-space: pre-line;position: absolute;direction: ltr;writing-mode: horizontal-tb;left: 0px;bottom: 40px;right: 0px;height: 42px;background-color: rgba(0, 0, 0, 0);">
                            <p :style="caption_style">Do not go gentle into that good night. Rage, rage against the dying of the light</p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-12 col-lg-10 mt-5">
                    <button class="btn btn-outline-primary" @click="CHANGE">Update</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import BounceLoader from 'vue-spinner/src/BounceLoader.vue';
    import {
        Chrome
    } from 'vue-color'
    export default {
        components: {
            BounceLoader,
            'chrome-picker': Chrome,
        },
        data() {
            return {
                colors_font: {
                    hex: '#ffffff',
                    hsl: {
                        h: 150,
                        s: 0.5,
                        l: 0.2,
                        a: 1
                    },
                    hsv: {
                        h: 150,
                        s: 0.66,
                        v: 0.30,
                        a: 1
                    },
                    rgba: {
                        r: 25,
                        g: 77,
                        b: 51,
                        a: 1
                    },
                    a: 1
                },
                colors_background: {
                    hex: '#194d33',
                    hsl: {
                        h: 150,
                        s: 0.5,
                        l: 0.2,
                        a: 1
                    },
                    hsv: {
                        h: 150,
                        s: 0.66,
                        v: 0.30,
                        a: 1
                    },
                    rgba: {
                        r: 25,
                        g: 77,
                        b: 51,
                        a: 1
                    },
                    a: 1
                },
                show_font_picker: false,
                show_background_picker: false,
                caption_style: {
                    position: 'relative',
                    left: '0',
                    bottom: '0',
                    fontSize: '15px',
                    color: '#fff',
                    width: '90%',
                    display: 'inline',
                    backgroundColor: 'transparent',
                    fontWeight: '500',
                }
            }
        },
        watch: {
            colors_font() {
                this.caption_style.color = this.colors_font.hex;
            },
            colors_background() {
                this.caption_style.backgroundColor = 'rgba(' + this.colors_background.rgba.r + ',' + this.colors_background
                    .rgba.g + ',' + this.colors_background.rgba.b + ',' + this.colors_background.rgba.a + ')';
            },
        },

        methods: {
            CHANGE() {
                const caption = {
                    'font-size': this.caption_style.fontSize,
                    'background-color': this.caption_style.backgroundColor,
                    'font-weight': this.caption_style.fontWeight,
                    'color': this.caption_style.color
                };
                this.$store.dispatch('SET_CAPTION', caption);
            },

            SHOW_FONT_PICKER() {
                this.show_font_picker = !this.show_font_picker;
            },
            SHOW_BACKGROUND_PICKER() {
                this.show_background_picker = !this.show_background_picker;
            }
        }
    };
</script>