<template>
  <div>

    <div class="spinner-load" v-if="spinner_loading">
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

    <!-- END spinner load -->

    <div class="m-2" id="manage-panel">
      <ul class="nav nav-tabs">
        <li class="nav-item">
          <router-link class="nav-link" :to="{name:'series_manage_id', params:{id:this.$route.params.id}}">Manage</router-link>
        </li>
        <li class="nav-item">
          <router-link class="nav-link" :to="{name:'new_series_episode', params:{id:this.$route.params.id}}">Episode</router-link>
        </li>
        <li class="col-lg-3 col-sm-4 col-12 offset-lg-6 offset-sm-3">
          <div class="form-inline my-lg-0" id="search">
            <input class="form-control mr-sm-2" type="text" placeholder="Search">
          </div>
        </li>
      </ul>
    </div>

    <!-- END Control Panel -->

    <div class="subtitles-modal">
      <div class="modal fade" id="GetSubtitleModal" tabindex="-1" role="dialog" aria-labelledby="GetSubtitleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="GetSubtitleModalLabel">Subtitles</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">

              <div class="spinner-load" v-if="subtitle_spinner_loading">
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

              <!-- END Spinner -->

              <div class="col-12">
                <input type="file" id="subtitle" name="subtitle" multiple size="10" @change="SHOW_FILES_INFO('subtitle','subtitleFileDetails')"
                  class="inputfile" v-validate="'mimes:application/x-subrip'">
                <label id="subtitleLabel" for="subtitle">Add New Subtitles
                  <br>
                  <small>The name most be of the same language exm: English.srt</small>
                  <p id="subtitleFileDetails"></p>
                </label>
                <span v-show="errors.has('subtitle')" class=" is-danger">{{ errors.first('subtitle')}}</span>
              </div>

              <div class="subtitle-progress mt-1 p-4" v-if="subtitle_video">
                <div class="row">
                  <div class="svg ml-3">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px"
                      viewBox="0 0 384 384" style="enable-background:new 0 0 384 384;" xml:space="preserve" width="50px" class="">
                      <g>
                        <g>
                          <g>
                            <path d="M341.333,21.333H42.667C19.093,21.333,0,40.427,0,64v256c0,23.573,19.093,42.667,42.667,42.667h298.667    C364.907,362.667,384,343.573,384,320V64C384,40.427,364.907,21.333,341.333,21.333z M170.667,170.667h-32V160H96v64h42.667    v-10.667h32v21.333c0,11.733-9.493,21.333-21.333,21.333h-64C73.493,256,64,246.4,64,234.667v-85.333    C64,137.6,73.493,128,85.333,128h64c11.84,0,21.333,9.6,21.333,21.333V170.667z M320,170.667h-32V160h-42.667v64H288v-10.667h32    v21.333C320,246.4,310.507,256,298.667,256h-64c-11.84,0-21.333-9.6-21.333-21.333v-85.333c0-11.733,9.493-21.333,21.333-21.333    h64c11.84,0,21.333,9.6,21.333,21.333V170.667z"
                              data-original="#000000" class="active-path" data-old_color="#00A6F9" fill="#00A6F9" />
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

              <hr>

              <!-- END Subttile Upload -->

              <div class="table-responsive" v-if="subtitles.subtitles !== null">
                <div class="table table-hover">
                  <thead>
                    <th>Name</th>
                    <th>Language</th>
                    <th>Created date</th>
                    <th>Control</th>
                  </thead>
                  <tbody>
                    <tr v-for="(item, index) in subtitles.subtitles" :key="index">
                      <td>{{item.name}}</td>
                      <td>{{item.language }}</td>
                      <td>{{item.created_at}}</td>
                      <td>
                        <div class="btn-group" role="group">
                          <button v-if="!button_loading" class="btn btn-sm btn-outline-danger btn-sm mr-2" @click="DELETE_SUBTITLE(item.id,index)">Delete</button>
                          <button v-if="button_loading === item.id" class="btn btn-sm btn-outline-danger btn-sm mr-2" disabled>
                            <i class="fa fa-circle-o-notch fa-spin"></i> Loading</button>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </div>
              </div>

              <div v-else class="text-center">
                <h4>There is no subtitles</h4>
              </div>
            </div>

            <!-- END Table -->


            <div class="modal-footer">
              <button type="button" class="btn btn-sm btn-outline-primary" :class="{disabled: disable_button}" @click="UPLOAD_SUBTITLE()">Upload Subtitle</button>
            </div>
          </div>
        </div>
      </div>

    </div>


    <div v-if="data.season !== null ">
      <div class="table-responisve m-2">
        <div class="table table-hover">
          <thead>
            <th>Name</th>
            <th>Session</th>
            <th>Epiosde</th>
            <th>Created date</th>
            <th>Updated date</th>
            <th>Control</th>
          </thead>
          <tbody>
            <tr v-for="(item, index) in data.season.data" :key="index">
              <td>{{item.name}}</td>
              <td>Session {{item.season_number}}</td>
              <td>Episode {{item.episode_number}}</td>
              <td>{{item.created_at}}</td>
              <td>{{item.updated_at}}</td>
              <td>
                <div class="btn-group" role="group">
                  <button class="btn btn-sm btn-outline-primary btn-sm mr-2" @click="GET_SUBTITLE(item.id,index)" data-toggle="modal" data-target="#GetSubtitleModal">Get Subtitles</button>
                  <button v-if="!button_loading" class="btn btn-sm btn-outline-danger btn-sm mr-2" @click="DELETE(item.id,index)" :id="item.t_id">Delete</button>
                  <button v-if="button_loading === item.e_id" class="btn btn-sm btn-outline-danger btn-sm mr-2" disabled>
                    <i class="fa fa-circle-o-notch fa-spin"></i> Loading</button>
                </div>
              </td>
            </tr>
          </tbody>
        </div>
      </div>

      <!-- END Table -->

      <nav aria-label="pagination">
        <ul class="pagination justify-content-center">
          <li class="page-item" id="end">
            <a class="page-link" @click="PAGINATION('/api/admin/get/series/season/'+ $route.params.id )">Begin</a>
          </li>
          <li class="page-item" id="prev" :class="{disabled: data.season.prev_page_url === null}">
            <a class="page-link" @click="PAGINATION(data.season.prev_page_url)">Previous</a>
          </li>
          <li class="page-item">
            <a class="page-link">{{data.season.current_page}}/{{data.season.last_page}}</a>
          </li>
          <li class="page-item" id="next" :class="{disabled: data.season.next_page_url === null}">
            <a class="page-link" @click="PAGINATION(data.season.next_page_url)">Next</a>
          </li>
          <li class="page-item" id="end">
            <a class="page-link" @click="PAGINATION('/api/admin/get/series/season/'+ $route.params.id +'?page='+data.season.last_page)">End</a>
          </li>
        </ul>
      </nav>

      <!-- END Nav -->

    </div>
    <div v-else>
      <div class="text-center mt-5 mr-5">
        <h4>Sorry no result were found</h4>
      </div>

    </div>
  </div>
</template>

<script>
const alertify = require("alertify.js");
import { mapState } from "vuex";
export default {
  data() {
    return {
      show_subtitle_modal: false,
      percentSubtitleUpload: 0,
      subtitle_video: false,
      subtitle_movie_id: null,
      error_message_subtitle: "",
      success_message_subtitle: "",
      disable_button: false
    };
  },
  computed: mapState({
    data: state => state.series.data,
    button_loading: state => state.series.button_loading,
    spinner_loading: state => state.series.spinner_loading,
    subtitles: state => state.subtitles.data,
    subtitle_spinner_loading: state => state.subtitles.spinner_loading
  }),

  created() {
    this.$store.dispatch("GET_ALL_SEASON", this.$route.params.id);
  },
  methods: {
    DELETE(id, key) {
      swal({
        title: "Are you sure to delete ?",
        icon: "warning",
        text: "All videos and subtitles will removed!",
        buttons: true,
        dangerMode: true
      }).then(willDelete => {
        if (willDelete) {
          this.$store.dispatch("DELETE_EPISODE", {
            id,
            key
          });
        }
      });
    },

    PAGINATION(path_url) {
      this.$store.dispatch("GET_SERIES_PAGINATION", path_url);
    },

    GET_SUBTITLE(id, key) {
      this.show_subtitle_modal = true;
      this.subtitle_movie_id = id;
      this.$store.dispatch("GET_EPISODE_SUBTITLES", id);
    },

    UPLOAD_SUBTITLE(id) {
      const formData = new FormData();
      const sub = document.getElementById("subtitle").files.length;
      for (var x = 0; x < sub; x++) {
        formData.append(
          "subtitleUpload[]",
          document.getElementById("subtitle").files[x]
        );
      }

      formData.append("id", this.subtitle_movie_id);

      // Progress
      this.subtitle_video = true;
      this.disable_button = true;
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

      axios
        .post("/api/admin/new/series/episode/subtitle", formData, progress)
        .then(
          response => {
            if (response.status === 200) {
              this.success_message_subtitle = response.data.message;
              this.subtitles.subtitles.push(response.data.data);
              setTimeout(() => {
                this.subtitle_video = false;
              }, 500);
              this.disable_button = false;
            }
          },
          error => {
            this.disable_button = false;
            this.error_message_subtitle = error.response.data.message;
            setTimeout(() => {
              this.subtitle_video = false;
            }, 1500);
          }
        );
    },

    DELETE_SUBTITLE(id, key) {
      swal({
        title: "Are you sure to delete ?",
        icon: "warning",
        buttons: true,
        dangerMode: true
      }).then(willDelete => {
        if (willDelete) {
          this.$store.dispatch("DELETE_SUBTITLE", {
            id,
            key
          });
        }
      });
    },

    SHOW_FILES_INFO(idFiles, idDetails) {
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