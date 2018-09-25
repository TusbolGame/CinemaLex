<template>
    <div>
        <div class="col-12">


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

            <!-- END Spinner -->


            <collection-modal @hideModalCollectionCancel="HIDE_COLLECTION_MODAL_CANCEL" @hideModalCollectionSave="HIDE_COLLECTION_MODAL_SAVE"
                :id="collection.id" :poster="collection.poster" :name="collection.name" :type="collection.type" :index="collection.index"></collection-modal>

            <!-- END Collection component -->

            <div class="col-12 p-0 film-list" v-if="data.movies !== null">

                <div class="row">
                    <div class="col-6 col-sm-4 col-md-3 col-lg-3 col-xl-2 p-2 mb-3 poster" v-for="(item, index) in data.movies" :key="index"
                        @mouseover="active = item.id">

                        <router-link :to="{name: 'movie-show', params: {id: item.id }}">
                            <div class="poster_img">

                                <img :src="data.cdn.cdn_poster+item.poster" :alt="item.name" width="100%">

                                <div class="poster_overlay_info" v-if="active === item.id">

                                    <div class="header">


                                        <div class="col-5 col-sm-4 col-md-4 pull-left my-list p-1 p-md-2 p-lg-2 p-xl-2">

                                            <div class="add text-left" v-if="! item.is_favorite">

                                                <svg @click.prevent="SHOW_COLLECTION_MODAL(item.id, data.cdn.cdn_poster+item.poster, item.name, 'movie', index)" xmlns="http://www.w3.org/2000/svg"
                                                    xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px"
                                                    y="0px" viewBox="0 0 300.003 300.003" style="enable-background:new 0 0 300.003 300.003;"
                                                    xml:space="preserve" width="6vh">
                                                    <g>
                                                        <g>
                                                            <g>
                                                                <path d="M150,0C67.159,0,0.001,67.159,0.001,150c0,82.838,67.157,150.003,149.997,150.003S300.002,232.838,300.002,150    C300.002,67.159,232.839,0,150,0z M213.281,166.501h-48.27v50.469c-0.003,8.463-6.863,15.323-15.328,15.323    c-8.468,0-15.328-6.86-15.328-15.328v-50.464H87.37c-8.466-0.003-15.323-6.863-15.328-15.328c0-8.463,6.863-15.326,15.328-15.328    l46.984,0.003V91.057c0-8.466,6.863-15.328,15.326-15.328c8.468,0,15.331,6.863,15.328,15.328l0.003,44.787l48.265,0.005    c8.466-0.005,15.331,6.86,15.328,15.328C228.607,159.643,221.742,166.501,213.281,166.501z"
                                                                    data-original="#000000" class="active-path" data-old_color="#ffffff"
                                                                    fill="#ffffff" />
                                                            </g>
                                                        </g>
                                                    </g>
                                                </svg>

                                            </div>

                                            <div class="remove text-left" v-if="item.is_favorite">

                                                <svg @click.prevent="DELETE_FROM_COLLECTION(item.id, 'movie', index)" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                                    version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 52 52" style="enable-background:new 0 0 52 52;"
                                                    xml:space="preserve" width="6vh">
                                                    <g>
                                                        <g>
                                                            <path d="M26,0C11.664,0,0,11.663,0,26s11.664,26,26,26s26-11.663,26-26S40.336,0,26,0z M40.495,17.329l-16,18   C24.101,35.772,23.552,36,22.999,36c-0.439,0-0.88-0.144-1.249-0.438l-10-8c-0.862-0.689-1.002-1.948-0.312-2.811   c0.689-0.863,1.949-1.003,2.811-0.313l8.517,6.813l14.739-16.581c0.732-0.826,1.998-0.9,2.823-0.166   C41.154,15.239,41.229,16.503,40.495,17.329z"
                                                                data-original="#000000" class="active-path" data-old_color="#5FC4E9"
                                                                fill="#03A9F4" />
                                                        </g>
                                                    </g>
                                                </svg>

                                            </div>


                                        </div>


                                        <!-- END My List -->


                                        <div class="col-5 col-sm-4 pull-right likes p-1 p-md-2 p-lg-2 p-xl-2">

                                            <div class="add text-right" v-if="! item.is_like">

                                                <svg @click.prevent="ADD_NEW_LIKE(item.id, 'movie', index, 'add')" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                                    version="1.1" id="Capa_1" x="0px" y="0px" width="6vh" viewBox="0 0 512 512"
                                                    style="enable-background:new 0 0 512 512;" xml:space="preserve">
                                                    <g>
                                                        <g>
                                                            <g>
                                                                <g>
                                                                    <path d="M344,288c4.781,0,9.328,0.781,13.766,1.922C373.062,269.562,384,245.719,384,218.625C384,177.422,351.25,144,310.75,144     c-21.875,0-41.375,10.078-54.75,25.766C242.5,154.078,223,144,201.125,144C160.75,144,128,177.422,128,218.625     C128,312,256,368,256,368s14-6.203,32.641-17.688C288.406,348.203,288,346.156,288,344C288,313.125,313.125,288,344,288z"
                                                                        data-original="#000000" class="active-path" data-old_color="#ffffff"
                                                                        fill="#ffffff" />
                                                                    <path d="M256,0C114.609,0,0,114.609,0,256c0,141.391,114.609,256,256,256c141.391,0,256-114.609,256-256     C512,114.609,397.391,0,256,0z M256,472c-119.297,0-216-96.703-216-216S136.703,40,256,40s216,96.703,216,216     S375.297,472,256,472z"
                                                                        data-original="#000000" class="active-path" data-old_color="#ffffff"
                                                                        fill="#ffffff" />
                                                                </g>
                                                                <path d="M344,304c-22.094,0-40,17.906-40,40s17.906,40,40,40s40-17.906,40-40S366.094,304,344,304z M368,352h-16v16h-16v-16h-16    v-16h16v-16h16v16h16V352z"
                                                                    data-original="#000000" class="active-path" data-old_color="#ffffff"
                                                                    fill="#ffffff" />
                                                            </g>
                                                        </g>
                                                    </g>
                                                </svg>
                                            </div>


                                            <div class="remove text-right" v-if="item.is_like">

                                                <svg @click.prevent="ADD_NEW_LIKE(item.id, 'movie', index, 'delete')" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                                    version="1.1" id="Capa_1" x="0px" y="0px" width="6vh" viewBox="0 0 512 512"
                                                    style="enable-background:new 0 0 512 512;" xml:space="preserve">
                                                    <g>
                                                        <g>
                                                            <g>
                                                                <g>
                                                                    <g>
                                                                        <path d="M344,288c4.781,0,9.328,0.781,13.766,1.922C373.062,269.562,384,245.719,384,218.625C384,177.422,351.25,144,310.75,144      c-21.875,0-41.375,10.078-54.75,25.766C242.5,154.078,223,144,201.125,144C160.75,144,128,177.422,128,218.625      C128,312,256,368,256,368s14-6.203,32.641-17.688C288.406,348.203,288,346.156,288,344C288,313.125,313.125,288,344,288z"
                                                                            data-original="#000000" class="active-path" data-old_color="#03A9F4"
                                                                            fill="#03A9F4" />
                                                                        <path d="M256,0C114.609,0,0,114.609,0,256c0,141.391,114.609,256,256,256c141.391,0,256-114.609,256-256      C512,114.609,397.391,0,256,0z M256,472c-119.297,0-216-96.703-216-216S136.703,40,256,40s216,96.703,216,216      S375.297,472,256,472z"
                                                                            data-original="#000000" class="active-path" data-old_color="#03A9F4"
                                                                            fill="#03A9F4" />
                                                                    </g>
                                                                </g>
                                                                <g>
                                                                    <path d="M344,304c-22.094,0-40,17.906-40,40s17.906,40,40,40s40-17.906,40-40S366.094,304,344,304z M368,352h-48v-16h48V352z"
                                                                        data-original="#000000" class="active-path" data-old_color="#03A9F4"
                                                                        fill="#03A9F4" />
                                                                </g>
                                                            </g>
                                                        </g>
                                                    </g>
                                                </svg>

                                            </div>
                                        </div>


                                        <!-- END Likes -->


                                    </div>


                                    <!-- END Header -->


                                    <div class="body text-center">


                                        <div class="play">

                                            <router-link :to="{name: 'movie-player', params:{id: item.id}}">

                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px"
                                                    viewBox="0 0 294.843 294.843" style="enable-background:new 0 0 294.843 294.843;"
                                                    xml:space="preserve" width="100%" class="play-svg">
                                                    <g>
                                                        <g>
                                                            <path d="M278.527,79.946c-10.324-20.023-25.38-37.704-43.538-51.132c-2.665-1.97-6.421-1.407-8.392,1.257s-1.407,6.421,1.257,8.392   c16.687,12.34,30.521,28.586,40.008,46.983c9.94,19.277,14.98,40.128,14.98,61.976c0,74.671-60.75,135.421-135.421,135.421   S12,222.093,12,147.421S72.75,12,147.421,12c3.313,0,6-2.687,6-6s-2.687-6-6-6C66.133,0,0,66.133,0,147.421   s66.133,147.421,147.421,147.421s147.421-66.133,147.421-147.421C294.842,123.977,289.201,100.645,278.527,79.946z"
                                                                data-original="#000000" class="active-path" data-old_color="#ffffff"
                                                                fill="#ffffff" />
                                                            <path d="M109.699,78.969c-1.876,1.067-3.035,3.059-3.035,5.216v131.674c0,3.314,2.687,6,6,6s6-2.686,6-6V94.74l88.833,52.883   l-65.324,42.087c-2.785,1.795-3.589,5.508-1.794,8.293c1.796,2.786,5.508,3.59,8.294,1.794l73.465-47.333   c1.746-1.125,2.786-3.073,2.749-5.15c-0.037-2.077-1.145-3.987-2.93-5.05L115.733,79.029   C113.877,77.926,111.575,77.902,109.699,78.969z"
                                                                data-original="#000000" class="active-path" data-old_color="#ffffff"
                                                                fill="#ffffff" />
                                                        </g>
                                                    </g>
                                                </svg>
                                            </router-link>
                                        </div>


                                    </div>


                                </div>

                                <!-- END Ovarlay -->
                            </div>
                        </router-link>


                        <div class="poster_title mt-2">
                            <b> {{item.name}} </b>
                        </div>
                        <div class="poster_genre  mb-2">
                            <small>{{item.genre}}</small>
                        </div>

                        <div class="progress" v-if="item.current_time !== null">
                            <div class="progress-bar" role="progressbar" :style="{width: item.current_time +'%'}" :aria-valuenow="item.current_time"
                                aria-valuemin="0" aria-valuemax="100"></div>
                        </div>


                    </div>

                    <!-- END Poster -->

                </div>

                <!-- END Movies List -->

            </div>



            <div v-else>
                <div class="mt-5 text-center notfound">

                    <h4>
                        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                            width="100%" viewBox="0 0 512.009 512.009" style="enable-background:new 0 0 512.009 512.009;" xml:space="preserve">
                            <circle style="fill:#F7B239;" cx="256.004" cy="256.004" r="256.004" />
                            <g>
                                <path style="fill:#E09B2D;" d="M121.499,390.501C29.407,298.407,22.15,153.608,99.723,53.204
                        c-8.593,6.638-16.861,13.895-24.743,21.777c-99.974,99.974-99.974,262.065,0,362.038s262.065,99.974,362.038,0
                        c7.881-7.881,15.138-16.15,21.777-24.743C358.392,489.85,213.593,482.593,121.499,390.501z" />
                                <path style="fill:#E09B2D;" d="M287.513,369.876c-1.126,0-2.27-0.201-3.383-0.626c-18.404-7.028-37.86-7.027-56.259,0
                        c-4.891,1.867-10.378-0.583-12.249-5.481c-1.87-4.896,0.585-10.379,5.481-12.249c22.828-8.716,46.964-8.717,69.795,0
                        c4.896,1.87,7.351,7.353,5.481,12.249C294.935,367.552,291.334,369.876,287.513,369.876z" />
                            </g>
                            <g>
                                <path style="fill:#4D4D4D;" d="M368.039,336.523c-33.142-22.293-71.884-34.077-112.037-34.077s-78.894,11.784-112.037,34.077
                        c-4.35,2.925-5.503,8.821-2.578,13.169c2.926,4.348,8.819,5.502,13.169,2.577c30.001-20.179,65.08-30.846,101.447-30.846
                        s71.446,10.667,101.447,30.846c1.624,1.093,3.465,1.617,5.287,1.617c3.053,0,6.05-1.471,7.882-4.194
                        C373.543,345.343,372.389,339.448,368.039,336.523z" />
                                <path style="fill:#4D4D4D;" d="M177.781,203.232c0-19.358-15.749-35.107-35.107-35.107s-35.107,15.749-35.107,35.107
                        s15.749,35.107,35.107,35.107S177.781,222.59,177.781,203.232z" />
                                <path style="fill:#4D4D4D;" d="M369.326,168.125c-19.358,0-35.107,15.749-35.107,35.107s15.749,35.107,35.107,35.107
                        s35.107-15.749,35.107-35.107S388.684,168.125,369.326,168.125z" />
                            </g>
                        </svg>

                        <strong>{{$t('home.sorry_no_result')}}</strong>

                    </h4>
                </div>
            </div>
        </div>
    </div>

</template>
<script>
    import {
        mapState
    } from "vuex";
    import collection from '../collection/new.vue';
    export default {
        name: "movies",
        components: {
            'collection-modal': collection
        },
        data() {
            return {
                active: null,
                collection: {
                    id: null,
                    poster: null,
                    name: null,
                    type: null,
                    index: null,
                }
            };
        },
        computed: mapState({
            data: state => state.movies.data,
            sort: state => state.event.sort,
            loading: state => state.movies.loading
        }),

        watch: {
            sort() {
                this.$store.dispatch("GET_MOVIES_SORT_BY_LIST", {
                    trending: this.sort.trending,
                    genre: this.sort.genre
                })
            }
        },

        mounted() {
            this.$store.dispatch("GET_MOVIES_LIST");
        },
        methods: {

            // Show modal of collection 
            SHOW_COLLECTION_MODAL(id, poster, name, type, index) {
                this.collection.id = id;
                this.collection.poster = poster;
                this.collection.name = name;
                this.collection.type = type;
                this.collection.index = index;
            },

            // Hide modal of collection
            HIDE_COLLECTION_MODAL_CANCEL() {
                this.collection.id = null;
            },

            // Hide modal and update array
            HIDE_COLLECTION_MODAL_SAVE() {
                this.collection.id = null;
                this.data.movies[this.collection.index].is_favorite = true;
            },

            // Delete mvoie or series from data array
            DELETE_FROM_COLLECTION(id, type, index) {
                this.data.movies[index].is_favorite = false;

                this.$store.dispatch('DELETE_FROM_COLLECTION', {
                    id,
                    type
                })
            },


            // Add new like or delete it 
            // Params type1 detected if add or delete
            ADD_NEW_LIKE(id, type, index, type1) {
                if (type1 === 'add') {
                    // Add true to data array
                    this.data.movies[index].is_like = true;
                    this.$store.dispatch('ADD_LIKE', {
                        id,
                        type
                    })

                } else {
                    // Add false to data array
                    this.data.movies[index].is_like = false;

                    this.$store.dispatch('ADD_LIKE', {
                        id,
                        type
                    })
                }
            },

        },

    };
</script>