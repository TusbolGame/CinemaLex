<template>
    <div>
        <div class="report-modal" v-if="show_report">
            <div class="col-12 col-sm-8 col-lg-6 offset-sm-2 offset-lg-3" id="modal">
                <div class="header">
                    <span id="close" @click="CLOSE_REPORT()">
                        <i class="fa fa-times-circle-o" aria-hidden="true"></i>
                    </span>
                </div>
                <div class="body">
                    <h1>{{$t('report.what_happening')}}
                        <b style="color:dodgerblue">?</b>
                    </h1>
                    <div class="form-check">
                        <label class="form-check-label">
                            <input class="custom-control-input" name="radio_group_1" v-validate="'required|in:1,2,3,4'" type="radio" value="1" v-model="report_problem_type">
                            <span class="custom-control-indicator"></span>
                            <h4>{{$t('report.labeling_problem')}}</h4>
                        </label>
                    </div>
                    <div class="form-check">
                        <label class="form-check-label">
                            <input class="custom-control-input" name="radio_group_1" type="radio" value="2" v-model="report_problem_type">
                            <span class="custom-control-indicator"></span>
                            <h4>{{$t('report.video_problem')}}</h4>
                        </label>
                    </div>
                    <div class="form-check">
                        <label class="form-check-label">
                            <input class="custom-control-input" name="radio_group_1" type="radio" value="3" v-model="report_problem_type">
                            <span class="custom-control-indicator"></span>
                            <h4>{{$t('report.sound_problem')}}</h4>
                        </label>
                    </div>
                    <div class="form-check">
                        <label class="form-check-label">
                            <input class="custom-control-input" name="radio_group_1" type="radio" value="4" v-model="report_problem_type">
                            <span class="custom-control-indicator"></span>
                            <h4>{{$t('report.caption_problem')}}</h4>
                        </label>
                    </div>
                    <span class="help is-danger" v-show="errors.has('radio_group_1')">Please Choose The Problem</span>

                    <div class="textarea-details">
                        <h3>{{$t('report.more_details')}}</h3>
                        <textarea v-model="report_details" :placeholder="$t('report.more_details')" cols="40" rows="6"></textarea>
                    </div>
                    <div class="my-2">
                        <button class="btn btn-outline-primary" @click="SEND_REPORT">{{$t('report.send')}}</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- REPORT -->

        <div class="col-12 row" style="padding:0; margin: 0; height: 100%;">
            <p id="movie-title">{{movie_title}}</p>
            <div class="col-12" id="video-box">
                <div id="player">
                    <video id='my-player' class="video-js vjs-default-skin"></video>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    window.videojs = require('video.js')
    videojs = videojs.default || videojs
    require('videojs-resolution-switcher');
    import {
        mapState
    } from 'vuex';

    export default {
        name: 'movie-player',
        data() {
            return {
                movie_title: '',
                active: '',
                show_suggestion: false,
                show_report_modal: false,
                report_problem_type: null,
                report_details: null,
                show_report_body: true,
            }
        },
        computed: mapState({
            url: state => state.player.url,
            suggestion: state => state.player.suggestion,
            show_report: state => state.player.show_report,
            next: state => state.player.next,

        }),
        mounted() {
            this.VIDEOJS_DISPOSE();
            this.$store.dispatch('LOAD_MOVIE_PLAYER', this.$route.params.id);
        },
        watch: {
            next(val) {
                if (val === 'movie') {
                    this.VIDEOJS_DISPOSE();
                    this.$store.dispatch('LOAD_MOVIE_PLAYER', this.suggestion.id);
                }
            },
        },
        methods: {
            // Change movie
            CHANGE_MOVIE(id) {
                this.$store.dispatch('LOAD_MOVIE_PLAYER', id);
            },

            // Report
            SHOW_REPORT() {
                this.show_report_modal = true;
                const myPlayer = videojs("my-player");
                myPlayer.pause();
            },
            CLOSE_REPORT() {
                this.show_report_modal = false;
                const myPlayer = videojs("my-player");
                myPlayer.play();
            },
            SEND_REPORT() {
                this.$validator.validateAll().then((result) => {
                    if (result) {
                        axios.post('/api/v1/create/report/movie', {
                            type: this.report_problem_type,
                            details: this.report_details,
                            id: this.$route.params.id
                        }).then((res) => {
                            if (res.data.status === 'success') {
                                this.show_report_body = false;
                                setTimeout(() => {
                                    this.CLOSE_REPORT();
                                    this.show_report_body = true;
                                }, 4000)
                            }
                        }, (error) => {
                            //
                        })
                    }
                })
            },


            // VideoJs dispose when user exit from player  
            VIDEOJS_DISPOSE() {
                const oldPlayer = document.getElementById('my-player');
                videojs(oldPlayer).dispose();
                const element = document.createElement("video")
                element.setAttribute("id", "my-player");
                element.setAttribute("class", "video-js vjs-default-skin");
                document.getElementById('player').appendChild(element);
            },



            // When Colse video re-play video 
            CLOSE_REPORT() {
                this.$store.commit('CLOSE_REPORT')
                const myPlayer = videojs("my-player");
                myPlayer.play();
            },
        },
    }
</script>