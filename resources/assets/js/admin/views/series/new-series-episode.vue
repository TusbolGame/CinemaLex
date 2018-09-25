<template>
    <div>
        <div class="m-2" id="mangae-panel">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <router-link class="nav-link" :to="{name:'series_manage_id', params:{id:this.$route.params.id}}">
                        Manage
                    </router-link>
                </li>
                <li class="nav-item">
                    <router-link class="nav-link" :to="{name:'new_series_episode', params:{id:this.$route.params.id}}">
                        Episode
                    </router-link>
                </li>
            </ul>
        </div>

        <!-- END Manage Panel -->

        <div class="col-12">
            <div class="row">
                <div class="col-12 col-sm-12 col-md-6">
                    <div class="col-12 col-sm-12 ">
                        <div class="form-group">
                            <label for="Session">Session</label>
                            <select class="form-control" v-model="season">
                                <option v-for="(list, index) in listSession" :key="index">{{list}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12">
                        <div class="form-group">
                            <label for="Episode">Episode</label>
                            <select class="form-control" v-model="episode">
                                <option v-for="(list, index) in listEpiosde" :key="index">{{list}}</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-12 col-sm-12">
                        <input type="file" id="video" @change="infoShow('video','videoFileDetails')" class="inputfile">
                        <label id="videoLabel" for="video">Choose a episode video
                            <br>
                            <p id="videoFileDetails"></p>
                        </label>
                    </div>
                    <div class="form-group">
                        <div class="col-12 col-sm-4">
                            <label>Resolution</label>
                        </div>
                        <div class="col-12 col-sm-12">
                            <label class="custom-control custom-checkbox">
                                <input type="checkbox" v-model="resolution_360" class="custom-control-input" id="customCheck1">
                                <span class="custom-control-indicator"></span>
                                <span class="custom-control-description">360</span>
                            </label>


                            <label class="custom-control custom-checkbox">
                                <input type="checkbox" v-model="resolution_480" class="custom-control-input" id="customCheck1">
                                <span class="custom-control-indicator"></span>
                                <span class="custom-control-description">480</span>
                            </label>


                            <label class="custom-control custom-checkbox">
                                <input type="checkbox" v-model="resolution_720" class="custom-control-input" id="customCheck1">
                                <span class="custom-control-indicator"></span>
                                <span class="custom-control-description">720</span>
                            </label>

                            <label class="custom-control custom-checkbox">
                                <input type="checkbox" v-model="resolution_1080" class="custom-control-input" id="customCheck1">
                                <span class="custom-control-indicator"></span>
                                <span class="custom-control-description">1080</span>
                            </label>
                        </div>
                        <div class="form-group">
                            <div class="col-4">
                                <label>Episode
                                    <small class="form-text text-muted">External Link</small>
                                </label>
                            </div>
                            <div class="col-12 col-sm-12">
                                <input class="form-control" v-model="video_link" type="text" placeholder="Episode link" />
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-12">
                        <input type="file" id="subtitle" name="subtitle" v-validate="'mimes:application/x-subrip'" multiple size="10" @change="infoShow('subtitle','subtitleFileDetails')"
                            class="inputfile">
                        <label id="subtitleLabel" for="subtitle">Choose a subtitle
                            <br>
                            <small>The name must be of the same language name, exm: English.srt</small>
                            <p id="subtitleFileDetails"></p>
                        </label>
                        <span v-show="errors.has('subtitle')" class=" is-danger">{{ errors.first('subtitle')}}</span>
                    </div>
                    <div class="form-group">
                        <div class="col-12 col-sm-6">
                            <button class="btn btn-sm btn-outline-primary" @click="MOVIEDB_API()">Upload</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="upload-modal" v-if="showProgress">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-body p-4">

                            <div class="episodeApi" v-if="episodeApi">
                                <div class="row">
                                    <div class="svg ml-3">
                                        <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                            viewBox="0 0 514.4 514.4" style="enable-background:new 0 0 514.4 514.4;" xml:space="preserve"
                                            width="60">
                                            <g>
                                                <rect x="49.6" y="293.6" style="fill:#193651;" width="16" height="16" />
                                                <rect x="49.6" y="340.8" style="fill:#193651;" width="16" height="16" />
                                                <rect x="49.6" y="388.8" style="fill:#193651;" width="16" height="16" />
                                            </g>
                                            <path style="fill:#FFFFFF;" d="M179.2,311.2c-1.6,16.8,0,34.4,4,51.2L152,385.6l23.2,44.8l36.8-12c11.2,13.6,24.8,24.8,39.2,33.6
	                                        l-5.6,37.6l48,15.2l17.6-34.4c16.8,1.6,34.4,0,51.2-4l23.2,31.2l44.8-23.2l-12-36.8c13.6-11.2,24.8-24.8,32.8-39.2l38.4,5.6l15.2-48
	                                        l-34.4-18.4c1.6-16.8,0-34.4-4-51.2l31.2-23.2l-24-44.8l-36.8,12c-11.2-13.6-24.8-24.8-39.2-33.6l5.6-38.4l-48-15.2l-17.6,35.2
	                                        c-16.8-1.6-34.4,0-51.2,4l-23.2-31.2l-44.8,23.2l12,36.8c-13.6,11.2-24.8,24.8-32.8,39.2l-38.4-5.6l-15.2,48L179.2,311.2z"
                                            />
                                            <ellipse style="fill:#FDBF5E;" cx="324.21" cy="324.75" rx="116" ry="116" />
                                            <g>
                                                <path style="fill:#193651;" d="M292.8,366.4h-12v-59.2c0-10.4-8-18.4-18.4-18.4s-18.4,8-18.4,18.4v59.2h-12v-59.2
                                                    c0-16.8,13.6-30.4,30.4-30.4s30.4,13.6,30.4,30.4V366.4z" />
                                                <path style="fill:#193651;" d="M320.8,366.4h-12v-89.6h32c12.8,0,23.2,10.4,23.2,23.2v10.4c0,12.8-10.4,23.2-23.2,23.2h-20V366.4z
                                                    M320.8,321.6h20c6.4,0,11.2-4.8,11.2-11.2V300c0-6.4-4.8-11.2-11.2-11.2h-20V321.6z"
                                                />
                                                <polygon style="fill:#193651;" points="411.2,288.8 411.2,276.8 376,276.8 376,288.8 388,288.8 388,360 376,360 376,372 411.2,372 
                                                    411.2,360 400,360 400,288.8 	" />
                                                <rect x="237.6" y="316" style="fill:#193651;" width="48.8" height="12" />
                                            </g>
                                            <path style="fill:#FFFFFF;" d="M276.8,85.6c0-0.8,0-1.6,0-2.4c0-40.8-33.6-74.4-74.4-74.4c-28,0-52,15.2-64.8,37.6
                                            c-8.8-8-20.8-12.8-34.4-12.8c-28.8,0-52.8,24-52.8,52.8v0.8C25.6,96.8,8,121.6,8,150.4c0,37.6,30.4,67.2,67.2,67.2h182.4
                                            c37.6,0,67.2-30.4,67.2-67.2C326.4,120,305.6,93.6,276.8,85.6z" />
                                            <path style="fill:#193651;" d="M478.4,332.8c0.8-14.4,0-28.8-4-43.2l32-24l-29.6-56.8l-38.4,12.8c-9.6-11.2-20.8-20-33.6-28l6.4-40
                                            l-60.8-19.2l-17.6,36c-0.8,0-1.6,0-1.6,0c1.6-6.4,2.4-12.8,2.4-20c0-31.2-20-60-48.8-70.4c-1.6-44-37.6-79.2-82.4-79.2
                                            c-26.4,0-51.2,12.8-67.2,34.4c-9.6-5.6-20.8-8.8-32-8.8c-32,0-58.4,24.8-60.8,56C16,95.2,0,120.8,0,150.4
                                            c0,41.6,33.6,75.2,75.2,75.2h129.6c-4,5.6-8,10.4-12,16.8l-40-6.4l-18.4,61.6l36,17.6c-0.8,14.4,0,28.8,4,43.2l-32,24l29.6,56.8
                                            l38.4-12.8c9.6,11.2,20.8,20,33.6,28l-6.4,40l60.8,19.2l17.6-36c14.4,0.8,28.8-0.8,43.2-4l24,32L440,476l-12.8-38.4
                                            c11.2-9.6,20-20.8,28-33.6l40,6.4l19.2-61.6L478.4,332.8z M16.8,150.4c0-24.8,14.4-46.4,37.6-55.2l4.8-3.2v-5.6
                                            c0-24.8,20-44.8,44.8-44.8c10.4,0,20.8,4,28.8,10.4l7.2,6.4l5.6-8c12-20.8,33.6-33.6,57.6-33.6c36.8,0,66.4,29.6,66.4,66.4V92
                                            l5.6,1.6c25.6,7.2,43.2,31.2,43.2,57.6c0,32.8-26.4,59.2-59.2,59.2H76C43.2,210.4,16.8,183.2,16.8,150.4z M484,394.4l-36.8-5.6
                                            l-3.2,4.8c-8,14.4-18.4,27.2-31.2,37.6l-4,3.2l12,35.2L388,486.4L365.6,456l-5.6,1.6c-16,4-32,5.6-48.8,4l-5.6-0.8l-16,34.4
                                            L254.4,484l5.6-36.8l-4.8-2.4c-14.4-8-26.4-18.4-37.6-31.2l-3.2-4l-35.2,12l-16.8-32.8l30.4-22.4l-1.6-5.6c-4-16-5.6-32-4-48.8
                                            l0.8-5.6l-34.4-16.8l11.2-35.2l36.8,5.6l2.4-4.8c6.4-10.4,13.6-20.8,22.4-28.8h31.2c28.8,0,53.6-16,66.4-40c4,0,8,0,12,0.8l5.6,0.8
                                            l16.8-33.6l35.2,11.2l-4.8,36l4.8,2.4c14.4,8,26.4,18.4,37.6,31.2l3.2,4l35.2-12l16.8,32.8L456,282.4l1.6,5.6c4,16,5.6,32,4,48.8
                                            l-0.8,5.6l33.6,16.8L484,394.4z" />
                                            <polygon style="fill:#2EA2DB;" points="195.2,154.4 236,154.4 236,113.6 224,125.6 176,76.8 159.2,93.6 208,142.4 " />
                                            <polygon style="fill:#F16051;" points="134.4,100.8 93.6,100.8 93.6,141.6 106.4,129.6 154.4,178.4 171.2,161.6 122.4,112.8 "
                                            />
                                        </svg>

                                    </div>
                                    <div class="title">
                                        <h6>
                                            <strong>Upload movie details</strong>
                                        </h6>
                                    </div>
                                </div>
                                <div class="progress mt-2">
                                    <div v-if="percentEpisodeApi !== 100" class="progress-bar" role="progressbar" :style="{width: percentEpisodeApi + '%', height:'6px' }"
                                        :aria-valuenow="percentEpisodeApi" aria-valuemin="0" aria-valuemax="100"></div>
                                    <div v-else>
                                        <i class="fa fa-circle-o-notch fa-spin"></i> Prepare</div>
                                </div>
                                <p class="is-danger">{{error_message_api}}</p>
                                <p class="is-success">{{success_message_api}}</p>
                                <hr>
                            </div>

                            <!-- END moviepai -->

                            <div class="movievideos3 mt-1" v-if="episode_video_s3">
                                <div class="row">
                                    <div class="svg ml-3">

                                        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                            viewBox="0 0 488.013 488.013" style="enable-background:new 0 0 488.013 488.013;"
                                            xml:space="preserve" width="60px">
                                            <path style="fill:#007AF4;" d="M422.413,260.806c13.6-31.2,16.8-69.6-8.8-94.4c-24.8-24.8-60.8-30.4-91.2-18.4
                                            c-0.8-45.6-37.6-82.4-84-82.4c-45.6-0.8-83.2,36.8-83.2,83.2l0,0c-8-3.2-13.6-4.8-21.6-4.8c-27.2,0-40,27.2-40,55.2
                                            c0,7.2-8.8,8-5.6,14.4c-1.6,0-3.2-0.8-5.6-0.8c-17.6,0-21.6,19.2-21.6,36.8c0,5.6-8.8,5.6-6.4,10.4c-32,12-54.4,44.8-54.4,80.8
                                            c0,46.4,22.4,85.6,68.8,93.6h350.4c46.4-8,68.8-47.2,68.8-93.6C487.213,304.006,455.213,272.006,422.413,260.806z"
                                            />
                                            <path style="fill:#00A6F9;" d="M430.413,248.806c13.6-31.2,8-68.8-16.8-93.6s-60.8-30.4-91.2-18.4c-0.8-45.6-37.6-82.4-84-82.4
                                            c-45.6,0-83.2,37.6-83.2,84l0,0c-8-3.2-13.6-4.8-21.6-4.8c-27.2,0-49.6,22.4-49.6,49.6c0,7.2,1.6,13.6,4,19.2
                                            c-1.6,0-3.2-0.8-5.6-0.8c-17.6,0-32,14.4-32,32c0,5.6,1.6,10.4,4,15.2c-32,12-54.4,41.6-54.4,77.6c-0.8,47.2,36.8,84,83.2,84h320
                                            c46.4,0,84-36.8,84-83.2C487.213,290.406,463.213,260.006,430.413,248.806z" />
                                            <circle style="fill:#00B3F9;" cx="355.213" cy="89.606" r="19.2" />
                                            <path style="fill:#007AF4;" d="M390.413,116.006c4.8,0,8.8-4,8.8-8.8s-4-8.8-8.8-8.8s-8.8,4-8.8,8.8
                                                C381.613,112.006,385.613,116.006,390.413,116.006z" />
                                            <g>
                                                <path style="fill:#0093F7;" d="M399.213,76.006c2.4,0,4.8-2.4,4.8-4.8s-2.4-4.8-4.8-4.8s-4.8,2.4-4.8,4.8
                                                        C395.213,73.606,396.813,76.006,399.213,76.006z" />
                                                <path style="fill:#0093F7;" d="M487.213,327.206c0-36.8-24-67.2-56.8-79.2c13.6-31.2,8-68-16.8-93.6
                                                c-24.8-24.8-60.8-30.4-91.2-18.4c-0.8-45.6-37.6-82.4-84-82.4c-45.6,0.8-83.2,38.4-83.2,84.8l0,0c-8-3.2-13.6-4.8-21.6-4.8
                                                c-27.2,0-49.6,22.4-49.6,49.6c0,6.4,0.8,11.2,3.2,16.8l211.2,210.4h104.8C449.613,410.406,487.213,373.606,487.213,327.206z"
                                                />
                                            </g>
                                            <g>
                                                <path style="fill:#007AF4;" d="M243.213,211.206l73.6,73.6c8.8,8.8,8.8,23.2,0,32s-23.2,8.8-32,0l-41.6-40.8l-40.8,40.8
                                                c-8.8,8.8-23.2,8.8-32,0s-8.8-23.2,0-32L243.213,211.206z" />
                                                <path style="fill:#007AF4;" d="M243.213,243.206c12.8,0,24,10.4,24,23.2v104c0,12.8-11.2,23.2-24,23.2c-12.8,0-24-10.4-24-23.2
                                                v-104C219.213,253.606,230.413,243.206,243.213,243.206z" />
                                            </g>
                                            <g>
                                                <path style="fill:#FFFFFF;" d="M243.213,194.406l73.6,73.6c8.8,8.8,8.8,23.2,0,32s-23.2,8.8-32,0l-41.6-40.8l-40.8,40.8
                                                    c-8.8,8.8-23.2,8.8-32,0s-8.8-23.2,0-32L243.213,194.406z" />
                                                <path style="fill:#FFFFFF;" d="M243.213,227.206c12.8,0,24,10.4,24,23.2v104c0,12.8-11.2,23.2-24,23.2c-12.8,0-24-10.4-24-23.2
                                                    v-104C219.213,236.806,230.413,227.206,243.213,227.206z" />
                                            </g>
                                        </svg>

                                    </div>
                                    <div class="title">
                                        <h6>
                                            <strong>Upload and transcode video </strong>
                                        </h6>
                                    </div>
                                </div>
                                <div class="progress">
                                    <div v-if="percentVideoUpload !== 100" class="progress-bar" role="progressbar" :style="{width: percentVideoUpload + '%', height:'6px' }"
                                        :aria-valuenow="percentVideoUpload" aria-valuemin="0" aria-valuemax="100"></div>
                                    <div v-else>
                                        <i class="fa fa-circle-o-notch fa-spin"></i> Prepare</div>
                                </div>
                                <p class="is-danger">{{error_message_video}}</p>
                                <p class="is-success">{{success_message_video}}</p>
                                <hr>
                            </div>

                            <!-- END movievideos3 -->

                            <div class="subtitlemovie mt-1" v-if="subtitle_video">
                                <div class="row">
                                    <div class="svg ml-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px"
                                            viewBox="0 0 384 384" style="enable-background:new 0 0 384 384;" xml:space="preserve"
                                            width="50px" class="">
                                            <g>
                                                <g>
                                                    <g>
                                                        <path d="M341.333,21.333H42.667C19.093,21.333,0,40.427,0,64v256c0,23.573,19.093,42.667,42.667,42.667h298.667    C364.907,362.667,384,343.573,384,320V64C384,40.427,364.907,21.333,341.333,21.333z M170.667,170.667h-32V160H96v64h42.667    v-10.667h32v21.333c0,11.733-9.493,21.333-21.333,21.333h-64C73.493,256,64,246.4,64,234.667v-85.333    C64,137.6,73.493,128,85.333,128h64c11.84,0,21.333,9.6,21.333,21.333V170.667z M320,170.667h-32V160h-42.667v64H288v-10.667h32    v21.333C320,246.4,310.507,256,298.667,256h-64c-11.84,0-21.333-9.6-21.333-21.333v-85.333c0-11.733,9.493-21.333,21.333-21.333    h64c11.84,0,21.333,9.6,21.333,21.333V170.667z"
                                                            data-original="#000000" class="active-path" data-old_color="#00A6F9"
                                                            fill="#00A6F9" />
                                                    </g>
                                                </g>
                                            </g>
                                        </svg>

                                    </div>
                                    <div class="title">
                                        <h6>
                                            <strong>Upload and convert srt </strong>
                                        </h6>
                                    </div>
                                </div>
                                <div class="progress">
                                    <div v-if="percentSubtitleUpload !== 100" class="progress-bar" role="progressbar" :style="{width: percentSubtitleUpload + '%', height:'6px' }"
                                        :aria-valuenow="percentSubtitleUpload" aria-valuemin="0" aria-valuemax="100"></div>
                                    <div v-else>
                                        <i class="fa fa-circle-o-notch fa-spin"></i> Prepare</div>
                                </div>
                                <p class="is-danger">{{error_message_subtitle}}</p>
                                <p class="is-success">{{success_message_subtitle}}</p>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
import { mapState } from "vuex";
const alertify = require("alertify.js");
export default {
  data() {
    return {
      id: "",
      video_link: "",
      resolution_360: false,
      resolution_480: false,
      resolution_720: false,
      resolution_1080: false,
      showProgress: false,
      percentEpisodeApi: 0,
      percentVideoUpload: 0,
      percentSubtitleUpload: 0,
      age: null,
      episode_name: "",
      season: "",
      episode: "",
      listSession: [],
      listEpiosde: [],
      episodeApi: false,
      episode_video_s3: false,
      subtitle_video: false,
      error_message_api: "",
      success_message_api: "",
      error_message_video: "",
      success_message_video: "",
      error_message_subtitle: "",
      success_message_subtitle: ""
    };
  },
  computed: mapState({
    data: state => state.series.data,
    data_search: state => state.series.data_search,
    button_loading: state => state.series.button_loading,
    spinner_loading: state => state.series.spinner_loading
  }),
  created() {
    this.$store.dispatch("GET_SERIES_INFO", this.$route.params.id);
    for (var i = 1; i < 50; i++) {
      this.listSession.push(i);
    }
    for (var i = 1; i < 2000; i++) {
      this.listEpiosde.push(i);
    }
  },
  methods: {
    MOVIEDB_API() {
      const formData = new FormData();
      formData.append("episode_name", this.episode_name);
      formData.append("season_number", this.season);
      formData.append("episode_number", this.episode);
      formData.append("series_id", this.$route.params.id);
      const progress = {
        headers: {
          "content-type": "multipart/form-data"
        },
        onUploadProgress: progressEvent => {
          this.percentEpisodeApi = Math.round(
            progressEvent.loaded * 100.0 / progressEvent.total
          );
        }
      };
      // Store data
      this.$validator.validateAll().then(result => {
        if (result) {
          this.showProgress = true;
          this.episodeApi = true;
          axios
            .post("/api/admin/new/series/episode/details", formData, progress)
            .then(
              response => {
                if (response.status === 200) {
                  this.success_message_api = response.data.message;
                  this.MOVIEVIDEO_S3(response.data.id);
                }
              },
              error => {
                this.error_message_api = error.response.data.message;
              }
            );
        }
      });
    },

    MOVIEVIDEO_S3(id) {
      // Upload video
      const video = document.querySelector("#video");
      const video_link = document.querySelector("#video_link");
      const formData = new FormData();
      formData.append("video", video.files[0]);
      formData.append("id", id);
      formData.append("video_link", this.video_link);
      formData.append("resolution_360", this.resolution_360);
      formData.append("resolution_480", this.resolution_480);
      formData.append("resolution_720", this.resolution_720);
      formData.append("resolution_1080", this.resolution_1080);

      // Progress
      this.episode_video_s3 = true;
      const progress = {
        headers: {
          "content-type": "multipart/form-data"
        },
        onUploadProgress: progressEvent => {
          this.percentVideoUpload = Math.round(
            progressEvent.loaded * 100.0 / progressEvent.total
          );
        }
      };
      // Store data
      axios
        .post("/api/admin/new/series/episode/video", formData, progress)
        .then(
          response => {
            if (response.status === 200) {
              this.success_message_video = response.data.message;
              const sub = document.getElementById("subtitle").files.length;
              if (sub > 0) {
                this.SUBTITLE_S3(response.data.id);
              } else {
                this.$route.go(-1);
                alertify.logPosition("top right");
                alertify.success("Successful upload");
              }
            }
          },
          error => {
            this.error_message_video = error.response.data.message;
          }
        );
    },

    SUBTITLE_S3(id) {
      const formData = new FormData();
      const sub = document.getElementById("subtitle").files.length;
      for (var x = 0; x < sub; x++) {
        formData.append(
          "subtitleUpload[]",
          document.getElementById("subtitle").files[x]
        );
      }
      formData.append("id", id);

      // Progress
      this.subtitle_video = true;
      const progress = {
        headers: {
          "content-type": "multipart/form-data"
        },
        onUploadProgress: progressEvent => {
          this.percentSubtitleUpload = Math.round(
            progressEvent.loaded * 100.0 / progressEvent.total
          );
        }
      };
      // Store data
      axios
        .post("/api/admin/new/series/episode/subtitle", formData, progress)
        .then(
          response => {
            if (response.status === 200) {
              this.success_message_subtitle = response.data.message;
              this.$route.go(-1);
              alertify.logPosition("top right");
              alertify.success("Successful upload");
            }
          },
          error => {
            this.error_message_subtitle = error.response.data.message;
          }
        );
    },

    // Show file name and size
    infoShow(idFiles, idDetails) {
      var x = document.getElementById(idFiles);
      var txt = "";
      if ("files" in x) {
        for (var i = 0; i < x.files.length; i++) {
          txt += "<br><strong>" + (i + 1) + ". file</strong><br>";
          var file = x.files[i];
          if ("name" in file) {
            txt += "name: " + file.name + "<br>";
          }
          if ("size" in file) {
            if (file.size < 1048576)
              txt += "size:" + (file.size / 1024).toFixed(3) + "KB<br>";
          }
        }
      }
      document.getElementById(idDetails).innerHTML = txt;
    }
  }
};
</script>