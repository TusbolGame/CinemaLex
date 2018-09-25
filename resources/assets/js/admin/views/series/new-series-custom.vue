<template>
    <div>
        <div class="m-2" id="mangae-panel">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <router-link class="nav-link" :to="{name: 'series-manage'}">Manage</router-link>
                </li>
                <li class="nav-item">
                    <router-link class="nav-link" :to="{name: 'series-api'}">Api</router-link>
                </li>
                <li class="nav-item">
                    <router-link class="nav-link" :to="{name: 'series-custom'}">Custom</router-link>
                </li>
            </ul>
        </div>
        <div class="col-12">
            <div class="row">

                <div class="col-6">
                    <div class="form-group">
                        <div class="col-2">
                            <label for="movie-name">Name</label>
                        </div>
                        <div class="col-10">
                            <input v-validate="'required|max:30'" name="name" class="form-control" v-model="name" type="text" placeholder="Name" />
                            <span v-show="errors.has('name')" class="is-danger">{{ errors.first('name') }}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-2">
                            <label for="movie-name">Years</label>
                        </div>
                        <div class="col-10">
                            <input v-validate="'required|numeric|max:4'" name="year" class="form-control" v-model="year" type="text" placeholder="Years"
                            />
                            <span v-show="errors.has('year')" class="is-danger">{{ errors.first('year') }}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-2">
                            <label for="movie-name">Genres</label>
                        </div>
                        <div class="col-10">
                            <div class="form-control-feedback" v-if="name === false">Name of movie.</div>
                            <select multiple class="form-control" v-validate="'required'" name="genres" v-model="genres" id="exampleSelect2">
                                <option>Adventure</option>
                                <option>Animation</option>
                                <option>Biography</option>
                                <option>Comedy</option>
                                <option>Crime</option>
                                <option>Documentary</option>
                                <option> Drama</option>
                                <option>Family</option>
                                <option>Fantasy</option>
                                <option>Film Noir</option>
                                <option>History </option>
                                <option>Horror</option>
                                <option>Music</option>
                                <option>Musical</option>
                                <option> Mystery</option>
                                <option>Romance</option>
                                <option> Sci-Fi</option>
                                <option> Sport</option>
                                <option> Superhero</option>
                                <option> Thriller</option>
                                <option> War</option>
                                <option> Western</option>
                            </select>
                        </div>
                        <span v-show="errors.has('genres')" class="is-danger">{{ errors.first('genres') }}</span>
                    </div>
                    <div class="form-group">
                        <div class="col-2">
                            <label for="movie-name">Overview</label>
                        </div>
                        <div class="col-10">
                            <textarea v-validate="'required'" name="overview" class="form-control" v-model="overview" type="text" placeholder="Overview"
                            />
                            <span v-show="errors.has('overview')" class="is-danger">{{ errors.first('overview') }}
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-2">
                            <label for="movie-name">Rate</label>
                        </div>
                        <div class="col-10">
                            <input v-validate="'required|decimal|max:3'" name="rate" class="form-control" v-model="rate" type="text" placeholder="Rate"
                            />
                            <span v-show="errors.has('rate')" class="is-danger">{{ errors.first('rate') }}</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-2">
                            <label for="movie-name">Trailer</label>
                        </div>
                        <div class="col-10">
                            <input v-validate="'required|url|max:300'" name="youtube" class="form-control" v-model="youtube" type="text" placeholder="Trailer"
                            />
                            <span v-show="errors.has('youtube')" class="is-danger">{{ errors.first('youtube') }}
                            </span>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-12 col-sm-4">
                            <label>Poster</label>
                        </div>
                        <div class="col-12 col-sm-12">
                            <input type="file" id="poster" name="poster" v-validate="'image'" @change="readImage('poster','posterFileImage')" class="inputfile">
                            <label id="posterLabel" for="poster">Choose a poster
                                <br>
                            </label>
                            <img src="" id="posterFileImage" width="200" style="display: none;">
                            <span v-show="errors.has('poster')" class="is-danger">{{ errors.first('poster') }}
                            </span>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-12 col-sm-4">
                            <label>Backdrop</label>
                        </div>
                        <div class="col-12 col-sm-12">
                            <input type="file" id="backdrop" name="backdrop" v-validate="'image'" @change="readImage('backdrop','backdropFileImage')"
                                class="inputfile">
                            <label id="backdropLabel" for="backdrop">Choose a backdrop
                                <br>
                            </label>
                            <img src="" id="backdropFileImage" width="200" style="display: none;">
                            <span v-show="errors.has('backdrop')" class="is-danger">{{ errors.first('backdrop')}}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-12 col-sm-12">
                            <label for="genres">Rating system</label>
                            <select class="form-control" v-validate="'required'" name="age" v-model="age" id="age">
                                <option>G</option>
                                <option>PG</option>
                                <option>PG-13</option>
                                <option>R</option>
                                <option>X</option>
                            </select>
                        </div>
                        <span v-show="errors.has('age')" class=" is-danger">{{ errors.first('age') }}</span>
                    </div>
                    <div class="form-group">
                        <div class="col-6">
                            <button class="btn btn-sm btn-outline-primary" @click="addItem(name)">Upload</button>
                        </div>
                    </div>
                </div>


                <div class="col-12 col-sm-6">
                    <form class="cover__form" id="form">
                        <div class="form-group">
                            <div class="col-12 col-sm-4">
                                <label>Cast (1)</label>
                            </div>
                            <div class="col-12 col-sm-12">
                                <input v-validate="'required|max:50'" name="name1" class="form-control" v-model="cast1" type="text" placeholder="Name" />
                                <br>
                                <span v-show="errors.has('name1')" class="is-danger">{{ errors.first('name4') }}
                                </span>

                                <input type="file" id="cast1" name="cast4" v-validate="'image'" @change="readImage('cast1','cast1FileImage')" class="inputfile">
                                <label id="cast1Label" for="cast1">Choose a image
                                    <br>
                                </label>
                                <img src="" id="cast1FileImage" width="200" style="display: none;">
                                <span v-show="errors.has('cast1')" class="is-danger">{{ errors.first('cast1')}}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-12 col-sm-4">
                                <label>Cast (2)</label>
                            </div>
                            <div class="col-12 col-sm-12">
                                <input v-validate="'required|max:50'" name="name2" class="form-control" v-model="cast2" type="text" placeholder="Name" />
                                <br>
                                <span v-show="errors.has('name2')" class="is-danger">{{ errors.first('name2') }}
                                </span>


                                <input type="file" id="cast2" name="cast2" v-validate="'image'" @change="readImage('cast2','cast2FileImage')" class="inputfile">
                                <label id="cast2Label" for="cast2">Choose a image
                                    <br>
                                </label>
                                <img src="" id="cast2FileImage" width="200" style="display: none;">
                                <span v-show="errors.has('cast2')" class="is-danger">{{ errors.first('cast2')}}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-12 col-sm-4">
                                <label>Cast (3)</label>
                            </div>
                            <div class="col-12 col-sm-12">
                                <input v-validate="'required|max:50'" name="name3" class="form-control" v-model="cast3" type="text" placeholder="Name" />
                                <br>
                                <span v-show="errors.has('name3')" class="is-danger">{{ errors.first('name3') }}
                                </span>


                                <input type="file" id="cast3" name="cast3" v-validate="'image'" @change="readImage('cast3','cast3FileImage')" class="inputfile">
                                <label id="cast3Label" for="cast3">Choose a image
                                    <br>
                                </label>
                                <img src="" id="cast3FileImage" width="200" style="display: none;">
                                <span v-show="errors.has('cast3')" class="is-danger">{{ errors.first('cast3')}}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-12 col-sm-4">
                                <label>Cast (4)</label>
                            </div>
                            <div class="col-12 col-sm-12">
                                <input v-validate="'required|max:50'" name="name4" class="form-control" v-model="cast4" type="text" placeholder="Name" />
                                <br>
                                <span v-show="errors.has('name4')" class="is-danger">{{ errors.first('name4') }}
                                </span>


                                <input type="file" id="cast4" name="cast4" v-validate="'image'" @change="readImage('cast4','cast4FileImage')" class="inputfile">
                                <label id="cast4Label" for="cast4">Choose a image
                                    <br>
                                </label>
                                <img src="" id="cast4FileImage" width="200" style="display: none;">
                                <span v-show="errors.has('cast4')" class="is-danger">{{ errors.first('cast4')}}</span>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="upload-modal" v-if="showProgress">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-body p-4">

                                <div class="movieapi">
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
                                        <div class="progress-bar" role="progressbar" v-if="percentSeriesApi !== 100" :style="{width: percentSeriesApi + '%', height:'6px' }"
                                            :aria-valuenow="percentSeriesApi" aria-valuemin="0" aria-valuemax="100"></div>
                                        <div v-else>
                                            <i class="fa fa-circle-o-notch fa-spin"></i> Prepare</div>
                                    </div>
                                    <p class="is-danger">{{error_message_api}}</p>
                                    <p class="is-success">{{success_message_api}}</p>
                                    <hr>
                                </div>

                                <!-- END moviepai -->

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
const alertify = require("alertify.js");
export default {
  data() {
    return {
      name: "",
      year: "",
      genres: "",
      overview: "",
      runtime: "",
      rate: "",
      youtube: "",
      video_link: "",
      count: "",
      age: null,
      showProgress: false,
      percentSeriesApi: 0
    };
  },

  methods: {
    addItem(name) {
      const fileInput1 = document.querySelector("#cast1");
      const fileInput2 = document.querySelector("#cast2");
      const fileInput3 = document.querySelector("#cast3");
      const fileInput4 = document.querySelector("#cast4");
      const poster = document.querySelector("#poster");
      const backdrop = document.querySelector("#backdrop");
      const formData = new FormData();
      formData.append("image1", fileInput1.files[0]);
      formData.append("image2", fileInput2.files[0]);
      formData.append("image3", fileInput3.files[0]);
      formData.append("image4", fileInput4.files[0]);
      formData.append("cast1", this.cast1);
      formData.append("cast2", this.cast2);
      formData.append("cast3", this.cast3);
      formData.append("cast4", this.cast4);
      formData.append("name", this.name);
      formData.append("year", this.year);
      formData.append("genres", this.genres);
      formData.append("overview", this.overview);
      formData.append("rate", this.rate);
      formData.append("youtube", this.youtube);
      formData.append("poster", poster.files[0]);
      formData.append("backdrop", backdrop.files[0]);
      formData.append("age", this.age);

      this.$validator.validateAll().then(result => {
        if (result) {
          // Progress
          var progress = {
            headers: {
              "content-type": "multipart/form-data"
            },
            onUploadProgress: progressEvent => {
              this.percentMovieApi = Math.round(
                progressEvent.loaded * 100.0 / progressEvent.total
              );
            }
          };
          this.showProgress = true;
          axios
            .post("/api/admin/new/series/customupload", formData, progress)
            .then(
              response => {
                if (response.status === 200) {
                  this.success_message_api = response.data.message;
                  setTimeout(() => {
                    this.$router.push({
                      name: "series-manage"
                    });
                    alertify.logPosition("top right");
                    alertify.success("Successful upload");
                  }, 1500);
                }
              },
              error => {
                this.error_message_api = error.response.data.message;
              }
            );
        }
      });
    },
    readImage(id, outImage) {
      var img = document.getElementById(id);
      var tgt = img.target || window.event.srcElement,
        files = tgt.files;

      // FileReader support
      if (FileReader && files && files.length) {
        var fr = new FileReader();
        fr.onload = function() {
          var srcImage = document.getElementById(outImage);
          srcImage.style.display = "block";
          srcImage.src = fr.result;
        };
        fr.readAsDataURL(files[0]);
      } else {
        // Not supported
        // fallback -- perhaps submit the input to an iframe and temporarily store
        // them on the server until the user's session ends.
      }
    }
  }
};
</script>