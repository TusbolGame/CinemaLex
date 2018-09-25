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
      <div class="col-12" id="video-box">
        <div id="player">
          <video id='my-player' class="video-js vjs-default-skin"></video>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
  window.videojs = require("video.js");
  videojs = videojs.default || videojs;
  require("videojs-resolution-switcher");
  import {
    mapState
  } from "vuex";
  export default {

    name: 'series-player-current',

    data() {
      return {
        episode_title: "",
        series_title: "",
        url_subtitle: "",
        seasonHide: true,
        active: "",
        activeSeason: "",
        report_problem_type: null,
        report_details: null
      };
    },

    computed: mapState({
      data: state => state.player.data,
      season: state => state.player.season,
      show_report: state => state.player.show_report,
      next_episode: state => state.player.next_episode,
      next_season: state => state.player.next_season,
      next: state => state.player.next,
      next_is: state => state.player.next_is,
      next_playlist: state => state.player.next_playlist,
      season_playlist_active: state => state.player.season_playlist_active
    }),

    mounted() {

      // Create element Vidoejs
      this.VIDEOJS_DISPOSE();

      // Run video
      this.$store.dispatch("LOAD_SERIES_PLAYER", {
        type: 'cur',
        series_id: this.$route.params.series_id
      });

      if (this.next === "episode") {
        this.$store.dispatch("LOAD_SERIES_PLAYER", {
          type: 'cur',
          series_id: this.$route.params.series_id
        });
      }
      if (this.next === "season") {
        this.$store.dispatch("LOAD_SERIES_PLAYER", {
          type: 'cur',
          series_id: this.$route.params.series_id
        });
      }

    },

    watch: {

      // timeing change
      next(val) {
        if (val === "episode") {
          setTimeout(() => {
            if (this.next === 'episode') {
              this.VIDEOJS_DISPOSE();
              this.$store.dispatch("LOAD_SERIES_PLAYER", {
                episode_id: this.next_episode.id,
                type: 'sp',
                series_id: this.next_episode.series_id
              });
            }
          }, 100000);
        }
        if (val === "season") {
          setTimeout(() => {
            if (this.next === 'season') {
              this.VIDEOJS_DISPOSE();
              this.$store.dispatch("LOAD_SERIES_PLAYER", {
                series_number: this.next_season.id,
                type: 'sp',
                series_id: this.next_season.series_id
              });
            }
          }, 100000);
        }
      },

      // onclick change

      next_is(val) {
        if (val === "episode") {
          this.VIDEOJS_DISPOSE();
          this.$store.dispatch("LOAD_SERIES_PLAYER", {
            episode_id: this.next_episode.id,
            type: 'sp',
            series_id: this.next_episode.series_id
          });
        } else if (val === "season") {
          this.VIDEOJS_DISPOSE();
          this.$store.dispatch("LOAD_SERIES_PLAYER", {
            episode_id: this.next_season.id,
            type: 'sp',
            series_id: this.next_season.series_id
          });
        } else if (val.number !== null || val.number !== undefined) {}
      },

      // Onclick  Playlist
      next_playlist(val) {
        for (var index = 0; index < this.season[this.season_playlist_active].length; index++) {
          if (this.season[this.season_playlist_active][index].id == val) {
            // Create element Vidoejs
            this.VIDEOJS_DISPOSE();
            this.$store.dispatch("LOAD_SERIES_PLAYER", {
              episode_id: this.season[this.season_playlist_active][index].id,
              type: 'sp',
              series_id: this.season[this.season_playlist_active][index].series_id
            });
          }
        }
      },

    },
    methods: {
      CHANGE_SERIES(episode_id) {
        this.$store.dispatch("LOAD_SERIES_PLAYER", {
          episode_id: episode_id,
          type: 'sp'
        });
      },
      CLOSE_REPORT() {
        this.$store.commit("CLOSE_REPORT");
        const myPlayer = videojs("my-player");
        myPlayer.play();
      },

      SEND_REPORT() {
        this.$validator.validateAll().then(result => {
          if (result) {
            axios
              .post("/api/v1/create/report/series", {
                type: this.report_problem_type,
                details: this.report_details,
                episode_id: this.data.id,
                series_id: this.data.series_id
              })
              .then(
                res => {
                  if (res.data.status === "success") {
                    this.show_report_body = false;
                    setTimeout(() => {
                      this.CLOSE_REPORT();
                      this.show_report_body = true;
                    }, 4000);
                  }
                },
                error => {
                  //
                }
              );
          }
        });
      },

      VIDEOJS_DISPOSE() {
        const oldPlayer = document.getElementById("my-player");
        videojs(oldPlayer).dispose();
        const element = document.createElement("video");
        element.setAttribute("id", "my-player");
        element.setAttribute("class", "video-js vjs-default-skin");
        document.getElementById("player").appendChild(element);
      },
    }
  };
</script>