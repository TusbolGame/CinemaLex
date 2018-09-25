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


            <!-- END cCollection component -->

            <div class="item-list" v-if="data.data !== null">
                <div class="row">

                    <div class="col-12 top-film" v-if="data.top !== null">

                        <carousel class="top-carousel" navigationPrevLabel="<i class='fa fa-angle-left'></i>" navigationNextLabel="<i class='fa fa-angle-right fa-1x'></i>"
                            :navigationEnabled="true" :paginationEnabled="false" :autoplay="false" :autoplayTimeout="4000" easing="linear"
                            :perPageCustom="[[100,1], [320,1],[768,1], [1024,2]]">
                            <slide class="ml-2" v-for="(item,index) in data.top" :key="index">

                                <div class="film-cover" v-if="item.type === 'movie'">
                                    <img :src="data.cdn.backdrop+item.backdrop" :alt="item.name" width="100%" onError="this.onerror=null;this.src='/themes/default/img/no-image.gif';">
                                    <router-link :to="{name: 'movie-show', params:{id: item.id}}">
                                        <div class="film-ovarlay">

                                            <div class="film-details">
                                                <div class="title">
                                                    <h5 class="hidden-xs-down">{{item.name}}</h5>
                                                </div>
                                                <div class="hidden-xs-down overview">
                                                    <p>{{ item.overview | truncate(110, item.overview )}}</p>
                                                </div>

                                                <div class="control">
                                                    <div class="btn-group">
                                                        <button class="btn btn-sm btn-plus btn-circle btn-secondary" v-if="! item.is_favorite" @click.prevent="SHOW_COLLECTION_MODAL(item.id, data.cdn.poster+item.poster, item.name, 'movie', null, index)">
                                                            {{$t('home.my_list')}}

                                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px"
                                                                viewBox="0 0 31.444 31.444" style="enable-background:new 0 0 31.444 31.444;"
                                                                xml:space="preserve" width="2.2vh">
                                                                <g>
                                                                    <path d="M1.119,16.841c-0.619,0-1.111-0.508-1.111-1.127c0-0.619,0.492-1.111,1.111-1.111h13.475V1.127  C14.595,0.508,15.103,0,15.722,0c0.619,0,1.111,0.508,1.111,1.127v13.476h13.475c0.619,0,1.127,0.492,1.127,1.111  c0,0.619-0.508,1.127-1.127,1.127H16.833v13.476c0,0.619-0.492,1.127-1.111,1.127c-0.619,0-1.127-0.508-1.127-1.127V16.841H1.119z"
                                                                        data-original="#1E201D" class="active-path" data-old_color="#292b2c"
                                                                        fill="#292b2c" />
                                                                </g>
                                                            </svg>

                                                        </button>

                                                        <button class="btn btn-sm btn-plus btn-circle btn-secondary" v-if="item.is_favorite" @click.prevent="DELETE_FROM_COLLECTION(item.id, 'movie', null, index)">
                                                            {{$t('home.my_list')}}
                                                            <span v-if="item.is_favorite" @click.prevent="DELETE_FROM_COLLECTION(item.id, 'movie', null, index)">

                                                                <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                                                    viewBox="0 0 511.999 511.999" width="2.2vh" style="enable-background:new 0 0 511.999 511.999;"
                                                                    xml:space="preserve">
                                                                    <g>
                                                                        <g>
                                                                            <path d="M506.231,75.508c-7.689-7.69-20.158-7.69-27.849,0l-319.21,319.211L33.617,269.163c-7.689-7.691-20.158-7.691-27.849,0
                                                                                        c-7.69,7.69-7.69,20.158,0,27.849l139.481,139.481c7.687,7.687,20.16,7.689,27.849,0l333.133-333.136
                                                                                        C513.921,95.666,513.921,83.198,506.231,75.508z"
                                                                            />
                                                                        </g>
                                                                    </g>

                                                                </svg>

                                                            </span>

                                                        </button>


                                                        <router-link role="button" class="btn btn-sm btn-plus btn-circle btn-primary ml-2" :to="{name: 'movie-player', params: {id: item.id}}">
                                                            {{$t('home.play')}}

                                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px"
                                                                viewBox="0 0 191.255 191.255" style="enable-background:new 0 0 191.255 191.255;"
                                                                xml:space="preserve" width="100%" class="">
                                                                <g>
                                                                    <path d="M162.929,66.612c-2.814-1.754-6.514-0.896-8.267,1.917s-0.895,6.513,1.917,8.266c6.544,4.081,10.45,11.121,10.45,18.833  s-3.906,14.752-10.45,18.833l-98.417,61.365c-6.943,4.329-15.359,4.542-22.512,0.573c-7.154-3.97-11.425-11.225-11.425-19.406  V34.262c0-8.181,4.271-15.436,11.425-19.406c7.153-3.969,15.569-3.756,22.512,0.573l57.292,35.723  c2.813,1.752,6.513,0.895,8.267-1.917c1.753-2.812,0.895-6.513-1.917-8.266L64.512,5.247c-10.696-6.669-23.661-7-34.685-0.883  C18.806,10.48,12.226,21.657,12.226,34.262v122.73c0,12.605,6.58,23.782,17.602,29.898c5.25,2.913,10.939,4.364,16.616,4.364  c6.241,0,12.467-1.754,18.068-5.247l98.417-61.365c10.082-6.287,16.101-17.133,16.101-29.015S173.011,72.899,162.929,66.612z"
                                                                        data-original="#000000" class="active-path" data-old_color="#ffffff"
                                                                        fill="#ffffff" />
                                                                </g>
                                                            </svg>


                                                        </router-link>

                                                    </div>
                                                </div>
                                            </div>



                                        </div>
                                    </router-link>


                                </div>

                                <!-- END Top Movie -->

                                <div class="film-cover" v-if="item.type === 'series'">
                                    <img :src="data.cdn.backdrop+item.backdrop" :alt="item.name" width="100%" onError="this.onerror=null;this.src='/themes/default/img/no-image.gif';">
                                    <router-link :to="{name: 'series-show', params:{id: item.id}}">

                                        <div class="film-ovarlay">

                                            <div class="film-details">
                                                <div class="title">
                                                    <h5>{{item.name}}</h5>
                                                </div>
                                                <div class="hidden-xs-down overview">
                                                    <p>{{ item.overview | truncate(110, item.overview )}}</p>
                                                </div>

                                                <div class="control">
                                                    <button class="btn btn-sm btn-plus btn-circle btn-secondary" v-if="! item.is_favorite" @click.prevent="SHOW_COLLECTION_MODAL(item.id, data.cdn.poster+item.poster, item.name, 'series', null, index)">
                                                        {{$t('home.my_list')}}

                                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px"
                                                            viewBox="0 0 31.444 31.444" style="enable-background:new 0 0 31.444 31.444;"
                                                            xml:space="preserve" width="2.2vh">
                                                            <g>
                                                                <path d="M1.119,16.841c-0.619,0-1.111-0.508-1.111-1.127c0-0.619,0.492-1.111,1.111-1.111h13.475V1.127  C14.595,0.508,15.103,0,15.722,0c0.619,0,1.111,0.508,1.111,1.127v13.476h13.475c0.619,0,1.127,0.492,1.127,1.111  c0,0.619-0.508,1.127-1.127,1.127H16.833v13.476c0,0.619-0.492,1.127-1.111,1.127c-0.619,0-1.127-0.508-1.127-1.127V16.841H1.119z"
                                                                    data-original="#1E201D" class="active-path" data-old_color="#292b2c"
                                                                    fill="#292b2c" />
                                                            </g>
                                                        </svg>

                                                    </button>

                                                    <button class="btn btn-sm btn-plus btn-circle btn-secondary" v-if="item.is_favorite" @click.prevent="DELETE_FROM_COLLECTION(item.id, 'series', null, index)">
                                                        {{$t('home.my_list')}}
                                                        <span v-if="item.is_favorite" @click.prevent="DELETE_FROM_COLLECTION(item.id, 'movie', null, index)">

                                                            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                                                viewBox="0 0 511.999 511.999" width="2.2vh" style="enable-background:new 0 0 511.999 511.999;"
                                                                xml:space="preserve">
                                                                <g>
                                                                    <g>
                                                                        <path d="M506.231,75.508c-7.689-7.69-20.158-7.69-27.849,0l-319.21,319.211L33.617,269.163c-7.689-7.691-20.158-7.691-27.849,0
                                                                                    c-7.69,7.69-7.69,20.158,0,27.849l139.481,139.481c7.687,7.687,20.16,7.689,27.849,0l333.133-333.136
                                                                                    C513.921,95.666,513.921,83.198,506.231,75.508z"
                                                                        />
                                                                    </g>
                                                                </g>

                                                            </svg>

                                                        </span>

                                                    </button>

                                                    <router-link role="button" class="btn btn-sm btn-plus btn-circle btn-primary ml-2" :to="{name: 'series-player-current', params: {series_id: item.id}}">

                                                        {{$t('home.play')}}
                                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px"
                                                            viewBox="0 0 191.255 191.255" style="enable-background:new 0 0 191.255 191.255;"
                                                            xml:space="preserve" width="100%" class="">
                                                            <g>
                                                                <path d="M162.929,66.612c-2.814-1.754-6.514-0.896-8.267,1.917s-0.895,6.513,1.917,8.266c6.544,4.081,10.45,11.121,10.45,18.833  s-3.906,14.752-10.45,18.833l-98.417,61.365c-6.943,4.329-15.359,4.542-22.512,0.573c-7.154-3.97-11.425-11.225-11.425-19.406  V34.262c0-8.181,4.271-15.436,11.425-19.406c7.153-3.969,15.569-3.756,22.512,0.573l57.292,35.723  c2.813,1.752,6.513,0.895,8.267-1.917c1.753-2.812,0.895-6.513-1.917-8.266L64.512,5.247c-10.696-6.669-23.661-7-34.685-0.883  C18.806,10.48,12.226,21.657,12.226,34.262v122.73c0,12.605,6.58,23.782,17.602,29.898c5.25,2.913,10.939,4.364,16.616,4.364  c6.241,0,12.467-1.754,18.068-5.247l98.417-61.365c10.082-6.287,16.101-17.133,16.101-29.015S173.011,72.899,162.929,66.612z"
                                                                    data-original="#000000" class="active-path" data-old_color="#ffffff"
                                                                    fill="#ffffff" />
                                                            </g>
                                                        </svg>

                                                    </router-link>

                                                </div>
                                            </div>

                                        </div>
                                    </router-link>

                                </div>

                                <!-- END Top Series -->

                            </slide>
                        </carousel>

                    </div>

                    <!-- END Top  -->

                    <div class="col-12 mt-5 film-list">
                        <div class="recenlty">
                            <div class="ml-4">

                                <h3>
                                    <strong>{{$t('home.recenlty_watched')}}</strong>
                                </h3>
                            </div>
                            <div class="row">
                                <div class="mt-4 col-12 col-sm-6 col-md-4 col-lg-4 animation" v-for="(item, index) in data.recenlty" :key="index">

                                    <div v-if="item.type === 'movie'">

                                        <div class="poster" @mouseover="recenlty_active = item.id" @mouseout="recenlty_active = null">

                                            <router-link :to="{name: 'movie-show', params:{id: item.id}}">
                                                <div class="poster_img">

                                                    <img :src="data.cdn.backdrop+item.backdrop" :alt="item.name" width="100%">

                                                    <div class="poster_overlay_info" v-if="recenlty_active === item.id">

                                                        <div class="body text-center">


                                                            <div class="play">

                                                                <router-link :to="{name: 'movie-player', params:{id: item.id}}">

                                                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px"
                                                                        viewBox="0 0 294.843 294.843" style="enable-background:new 0 0 294.843 294.843;"
                                                                        xml:space="preserve" width="100%" class="play-svg">
                                                                        <g>
                                                                            <g>
                                                                                <path d="M278.527,79.946c-10.324-20.023-25.38-37.704-43.538-51.132c-2.665-1.97-6.421-1.407-8.392,1.257s-1.407,6.421,1.257,8.392   c16.687,12.34,30.521,28.586,40.008,46.983c9.94,19.277,14.98,40.128,14.98,61.976c0,74.671-60.75,135.421-135.421,135.421   S12,222.093,12,147.421S72.75,12,147.421,12c3.313,0,6-2.687,6-6s-2.687-6-6-6C66.133,0,0,66.133,0,147.421   s66.133,147.421,147.421,147.421s147.421-66.133,147.421-147.421C294.842,123.977,289.201,100.645,278.527,79.946z"
                                                                                    data-original="#000000" class="active-path"
                                                                                    data-old_color="#ffffff" fill="#ffffff" />
                                                                                <path d="M109.699,78.969c-1.876,1.067-3.035,3.059-3.035,5.216v131.674c0,3.314,2.687,6,6,6s6-2.686,6-6V94.74l88.833,52.883   l-65.324,42.087c-2.785,1.795-3.589,5.508-1.794,8.293c1.796,2.786,5.508,3.59,8.294,1.794l73.465-47.333   c1.746-1.125,2.786-3.073,2.749-5.15c-0.037-2.077-1.145-3.987-2.93-5.05L115.733,79.029   C113.877,77.926,111.575,77.902,109.699,78.969z"
                                                                                    data-original="#000000" class="active-path"
                                                                                    data-old_color="#ffffff" fill="#ffffff" />
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
                                        </div>

                                        <!-- END Poster -->

                                        <div class="progress mt-3" v-if="item.current_time !== null">
                                            <div class="progress-bar" role="progressbar" :style="{width: item.current_time +'%'}" :aria-valuenow="item.current_time"
                                                aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>

                                    <!-- END Movies List -->

                                    <div v-if="item.type === 'series'">
                                        <div class="poster" @mouseover="recenlty_active = item.id" @mouseout="recenlty_active = null">
                                            <router-link :to="{name: 'series-show', params:{id: item.id}}">

                                                <div class="poster_img">

                                                    <img :src="data.cdn.backdrop+item.backdrop" :alt="item.name" width="100%">
                                                    <div class="poster_overlay_info" v-if="recenlty_active === item.id">

                                                        <div class="body text-center">


                                                            <div class="play">
                                                                <router-link :to="{name: 'series-player-current', params: {series_id: item.id}}">

                                                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px"
                                                                        viewBox="0 0 294.843 294.843" style="enable-background:new 0 0 294.843 294.843;"
                                                                        xml:space="preserve" width="8vh" class="play-svg">
                                                                        <g>
                                                                            <g>
                                                                                <path d="M278.527,79.946c-10.324-20.023-25.38-37.704-43.538-51.132c-2.665-1.97-6.421-1.407-8.392,1.257s-1.407,6.421,1.257,8.392   c16.687,12.34,30.521,28.586,40.008,46.983c9.94,19.277,14.98,40.128,14.98,61.976c0,74.671-60.75,135.421-135.421,135.421   S12,222.093,12,147.421S72.75,12,147.421,12c3.313,0,6-2.687,6-6s-2.687-6-6-6C66.133,0,0,66.133,0,147.421   s66.133,147.421,147.421,147.421s147.421-66.133,147.421-147.421C294.842,123.977,289.201,100.645,278.527,79.946z"
                                                                                    data-original="#000000" class="active-path"
                                                                                    data-old_color="#ffffff" fill="#ffffff" />
                                                                                <path d="M109.699,78.969c-1.876,1.067-3.035,3.059-3.035,5.216v131.674c0,3.314,2.687,6,6,6s6-2.686,6-6V94.74l88.833,52.883   l-65.324,42.087c-2.785,1.795-3.589,5.508-1.794,8.293c1.796,2.786,5.508,3.59,8.294,1.794l73.465-47.333   c1.746-1.125,2.786-3.073,2.749-5.15c-0.037-2.077-1.145-3.987-2.93-5.05L115.733,79.029   C113.877,77.926,111.575,77.902,109.699,78.969z"
                                                                                    data-original="#000000" class="active-path"
                                                                                    data-old_color="#ffffff" fill="#ffffff" />
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
                                        </div>

                                        <!-- END Poster -->

                                        <div class="progress mt-3" v-if="item.current_time !== null">
                                            <div class="progress-bar" role="progressbar" :style="{width: item.current_time +'%'}" :aria-valuenow="item.current_time"
                                                aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>


                                    <!-- END Series List -->

                                </div>

                            </div>
                        </div>

                        <div class="carousel mt-5" v-for="(item, rootindex) in data.data" :key="rootindex" v-if="item.list.length > 0 ">
                            <div class="genre ml-4">

                                <h3 v-if="data.data[rootindex].type === 'Movies'">
                                    <strong>{{item.genre}}</strong>
                                    <small class="text-muted">-{{$t('home.movies')}} </small>
                                </h3>
                                <h3 v-if="data.data[rootindex].type === 'Series'">
                                    <strong> {{item.genre}}</strong>
                                    <small class="text-muted">-{{$t('home.series')}} </small>
                                </h3>

                            </div>


                            <carousel class="list-carousel" navigationPrevLabel="<i class='fa fa-angle-left'></i>" navigationNextLabel="<i class='fa fa-angle-right fa-1x'></i>"
                                :navigationEnabled="true" :paginationEnabled="false" :autoplay="false" :autoplayTimeout="4000"
                                easing="linear" :perPageCustom="[[320,2],[768, 4], [1024, 6]]">

                                <slide class="ml-4 mt-4 animation" v-for="(item, index) in data.data[rootindex].list" :key="index">

                                    <div v-if="data.data[rootindex].type === 'Movies'">

                                        <div class="poster" @mouseover="active = item.id">

                                            <router-link :to="{name: 'movie-show', params:{id: item.id}}">
                                                <div class="poster_img">

                                                    <img :src="data.cdn.poster+item.poster" :alt="item.name" width="100%">

                                                    <div class="poster_overlay_info" v-if="active === item.id">

                                                        <div class="header">


                                                            <div class="col-4 pull-left my-list p-1 p-md-2 p-lg-2 p-xl-2">

                                                                <div class="add text-left" v-if="! item.is_favorite">

                                                                    <svg @click.prevent="SHOW_COLLECTION_MODAL(item.id, data.cdn.poster+item.poster, item.name, 'movie', rootindex, index)" xmlns="http://www.w3.org/2000/svg"
                                                                        xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1"
                                                                        id="Layer_1" x="0px" y="0px" viewBox="0 0 300.003 300.003"
                                                                        style="enable-background:new 0 0 300.003 300.003;" xml:space="preserve"
                                                                        width="100%">
                                                                        <g>
                                                                            <g>
                                                                                <g>
                                                                                    <path d="M150,0C67.159,0,0.001,67.159,0.001,150c0,82.838,67.157,150.003,149.997,150.003S300.002,232.838,300.002,150    C300.002,67.159,232.839,0,150,0z M213.281,166.501h-48.27v50.469c-0.003,8.463-6.863,15.323-15.328,15.323    c-8.468,0-15.328-6.86-15.328-15.328v-50.464H87.37c-8.466-0.003-15.323-6.863-15.328-15.328c0-8.463,6.863-15.326,15.328-15.328    l46.984,0.003V91.057c0-8.466,6.863-15.328,15.326-15.328c8.468,0,15.331,6.863,15.328,15.328l0.003,44.787l48.265,0.005    c8.466-0.005,15.331,6.86,15.328,15.328C228.607,159.643,221.742,166.501,213.281,166.501z"
                                                                                        data-original="#000000" class="active-path"
                                                                                        data-old_color="#ffffff" fill="#ffffff"
                                                                                    />
                                                                                </g>
                                                                            </g>
                                                                        </g>
                                                                    </svg>

                                                                </div>

                                                                <div class="remove text-left" v-if="item.is_favorite">

                                                                    <svg @click.prevent="DELETE_FROM_COLLECTION(item.id, 'movie', rootindex, index)" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                        version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 52 52"
                                                                        style="enable-background:new 0 0 52 52;" xml:space="preserve"
                                                                        width="100%">
                                                                        <g>
                                                                            <g>
                                                                                <path d="M26,0C11.664,0,0,11.663,0,26s11.664,26,26,26s26-11.663,26-26S40.336,0,26,0z M40.495,17.329l-16,18   C24.101,35.772,23.552,36,22.999,36c-0.439,0-0.88-0.144-1.249-0.438l-10-8c-0.862-0.689-1.002-1.948-0.312-2.811   c0.689-0.863,1.949-1.003,2.811-0.313l8.517,6.813l14.739-16.581c0.732-0.826,1.998-0.9,2.823-0.166   C41.154,15.239,41.229,16.503,40.495,17.329z"
                                                                                    data-original="#000000" class="active-path"
                                                                                    data-old_color="#5FC4E9" fill="#03A9F4" />
                                                                            </g>
                                                                        </g>
                                                                    </svg>

                                                                </div>


                                                            </div>


                                                            <!-- END My List -->


                                                            <div class="col-4 pull-right likes p-1 p-md-2 p-lg-2 p-xl-2">

                                                                <div class="add text-right" v-if="! item.is_like">

                                                                    <svg @click.prevent="ADD_NEW_LIKE(item.id, 'movie', rootindex, index, 'add')" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                        version="1.1" id="Capa_1" x="0px" y="0px" width="100%"
                                                                        viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;"
                                                                        xml:space="preserve">
                                                                        <g>
                                                                            <g>
                                                                                <g>
                                                                                    <g>
                                                                                        <path d="M344,288c4.781,0,9.328,0.781,13.766,1.922C373.062,269.562,384,245.719,384,218.625C384,177.422,351.25,144,310.75,144     c-21.875,0-41.375,10.078-54.75,25.766C242.5,154.078,223,144,201.125,144C160.75,144,128,177.422,128,218.625     C128,312,256,368,256,368s14-6.203,32.641-17.688C288.406,348.203,288,346.156,288,344C288,313.125,313.125,288,344,288z"
                                                                                            data-original="#000000" class="active-path"
                                                                                            data-old_color="#ffffff" fill="#ffffff"
                                                                                        />
                                                                                        <path d="M256,0C114.609,0,0,114.609,0,256c0,141.391,114.609,256,256,256c141.391,0,256-114.609,256-256     C512,114.609,397.391,0,256,0z M256,472c-119.297,0-216-96.703-216-216S136.703,40,256,40s216,96.703,216,216     S375.297,472,256,472z"
                                                                                            data-original="#000000" class="active-path"
                                                                                            data-old_color="#ffffff" fill="#ffffff"
                                                                                        />
                                                                                    </g>
                                                                                    <path d="M344,304c-22.094,0-40,17.906-40,40s17.906,40,40,40s40-17.906,40-40S366.094,304,344,304z M368,352h-16v16h-16v-16h-16    v-16h16v-16h16v16h16V352z"
                                                                                        data-original="#000000" class="active-path"
                                                                                        data-old_color="#ffffff" fill="#ffffff"
                                                                                    />
                                                                                </g>
                                                                            </g>
                                                                        </g>
                                                                    </svg>
                                                                </div>


                                                                <div class="remove  text-right" v-if="item.is_like">

                                                                    <svg @click.prevent="ADD_NEW_LIKE(item.id, 'movie', rootindex, index, 'delete')" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                        version="1.1" id="Capa_1" x="0px" y="0px" width="100%"
                                                                        viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;"
                                                                        xml:space="preserve">
                                                                        <g>
                                                                            <g>
                                                                                <g>
                                                                                    <g>
                                                                                        <g>
                                                                                            <path d="M344,288c4.781,0,9.328,0.781,13.766,1.922C373.062,269.562,384,245.719,384,218.625C384,177.422,351.25,144,310.75,144      c-21.875,0-41.375,10.078-54.75,25.766C242.5,154.078,223,144,201.125,144C160.75,144,128,177.422,128,218.625      C128,312,256,368,256,368s14-6.203,32.641-17.688C288.406,348.203,288,346.156,288,344C288,313.125,313.125,288,344,288z"
                                                                                                data-original="#000000" class="active-path"
                                                                                                data-old_color="#03A9F4" fill="#03A9F4"
                                                                                            />
                                                                                            <path d="M256,0C114.609,0,0,114.609,0,256c0,141.391,114.609,256,256,256c141.391,0,256-114.609,256-256      C512,114.609,397.391,0,256,0z M256,472c-119.297,0-216-96.703-216-216S136.703,40,256,40s216,96.703,216,216      S375.297,472,256,472z"
                                                                                                data-original="#000000" class="active-path"
                                                                                                data-old_color="#03A9F4" fill="#03A9F4"
                                                                                            />
                                                                                        </g>
                                                                                    </g>
                                                                                    <g>
                                                                                        <path d="M344,304c-22.094,0-40,17.906-40,40s17.906,40,40,40s40-17.906,40-40S366.094,304,344,304z M368,352h-48v-16h48V352z"
                                                                                            data-original="#000000" class="active-path"
                                                                                            data-old_color="#03A9F4" fill="#03A9F4"
                                                                                        />
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
                                                                                    data-original="#000000" class="active-path"
                                                                                    data-old_color="#ffffff" fill="#ffffff" />
                                                                                <path d="M109.699,78.969c-1.876,1.067-3.035,3.059-3.035,5.216v131.674c0,3.314,2.687,6,6,6s6-2.686,6-6V94.74l88.833,52.883   l-65.324,42.087c-2.785,1.795-3.589,5.508-1.794,8.293c1.796,2.786,5.508,3.59,8.294,1.794l73.465-47.333   c1.746-1.125,2.786-3.073,2.749-5.15c-0.037-2.077-1.145-3.987-2.93-5.05L115.733,79.029   C113.877,77.926,111.575,77.902,109.699,78.969z"
                                                                                    data-original="#000000" class="active-path"
                                                                                    data-old_color="#ffffff" fill="#ffffff" />
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
                                        </div>

                                        <!-- END Poster -->

                                        <div class="progress" v-if="item.current_time !== null">
                                            <div class="progress-bar" role="progressbar" :style="{width: item.current_time +'%'}" :aria-valuenow="item.current_time"
                                                aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>

                                    <!-- END Movies List -->
                                    <div v-if="data.data[rootindex].type === 'Series'">
                                        <div class="poster" @mouseover="active = item.id">
                                            <router-link :to="{name: 'series-show', params:{id: item.id}}">

                                                <div class="poster_img">

                                                    <img :src="data.cdn.poster+item.poster" :alt="item.name" width="100%">
                                                    <div class="poster_overlay_info" v-if="active === item.id">

                                                        <div class="header">

                                                            <div class="col-4  pull-left my-list p-1 p-md-2 p-lg-2 p-xl-2">

                                                                <div class="add text-left" v-if="! item.is_favorite">

                                                                    <svg @click.prevent="SHOW_COLLECTION_MODAL(item.id, data.cdn.poster+item.poster, item.name, 'series', rootindex, index)"
                                                                        xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                        version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 300.003 300.003"
                                                                        style="enable-background:new 0 0 300.003 300.003;" xml:space="preserve"
                                                                        width="100%">
                                                                        <g>
                                                                            <g>
                                                                                <g>
                                                                                    <path d="M150,0C67.159,0,0.001,67.159,0.001,150c0,82.838,67.157,150.003,149.997,150.003S300.002,232.838,300.002,150    C300.002,67.159,232.839,0,150,0z M213.281,166.501h-48.27v50.469c-0.003,8.463-6.863,15.323-15.328,15.323    c-8.468,0-15.328-6.86-15.328-15.328v-50.464H87.37c-8.466-0.003-15.323-6.863-15.328-15.328c0-8.463,6.863-15.326,15.328-15.328    l46.984,0.003V91.057c0-8.466,6.863-15.328,15.326-15.328c8.468,0,15.331,6.863,15.328,15.328l0.003,44.787l48.265,0.005    c8.466-0.005,15.331,6.86,15.328,15.328C228.607,159.643,221.742,166.501,213.281,166.501z"
                                                                                        data-original="#000000" class="active-path"
                                                                                        data-old_color="#ffffff" fill="#ffffff"
                                                                                    />
                                                                                </g>
                                                                            </g>
                                                                        </g>
                                                                    </svg>

                                                                </div>

                                                                <div class="remove text-left" v-if="item.is_favorite">

                                                                    <svg @click.prevent="DELETE_FROM_COLLECTION(item.id, 'series', rootindex, index)" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                        version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 52 52"
                                                                        style="enable-background:new 0 0 52 52;" xml:space="preserve"
                                                                        width="100%">
                                                                        <g>
                                                                            <g>
                                                                                <path d="M26,0C11.664,0,0,11.663,0,26s11.664,26,26,26s26-11.663,26-26S40.336,0,26,0z M40.495,17.329l-16,18   C24.101,35.772,23.552,36,22.999,36c-0.439,0-0.88-0.144-1.249-0.438l-10-8c-0.862-0.689-1.002-1.948-0.312-2.811   c0.689-0.863,1.949-1.003,2.811-0.313l8.517,6.813l14.739-16.581c0.732-0.826,1.998-0.9,2.823-0.166   C41.154,15.239,41.229,16.503,40.495,17.329z"
                                                                                    data-original="#000000" class="active-path"
                                                                                    data-old_color="#5FC4E9" fill="#03A9F4" />
                                                                            </g>
                                                                        </g>
                                                                    </svg>

                                                                </div>


                                                            </div>


                                                            <!-- END My List -->


                                                            <div class="col-4 pull-right likes p-1 p-md-2 p-lg-2 p-xl-2">

                                                                <div class="add text-right" v-if="! item.is_like">

                                                                    <svg @click.prevent="ADD_NEW_LIKE(item.id, 'series', rootindex, index, 'add')" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                        version="1.1" id="Capa_1" x="0px" y="0px" width="100%"
                                                                        viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;"
                                                                        xml:space="preserve">
                                                                        <g>
                                                                            <g>
                                                                                <g>
                                                                                    <g>
                                                                                        <path d="M344,288c4.781,0,9.328,0.781,13.766,1.922C373.062,269.562,384,245.719,384,218.625C384,177.422,351.25,144,310.75,144     c-21.875,0-41.375,10.078-54.75,25.766C242.5,154.078,223,144,201.125,144C160.75,144,128,177.422,128,218.625     C128,312,256,368,256,368s14-6.203,32.641-17.688C288.406,348.203,288,346.156,288,344C288,313.125,313.125,288,344,288z"
                                                                                            data-original="#000000" class="active-path"
                                                                                            data-old_color="#ffffff" fill="#ffffff"
                                                                                        />
                                                                                        <path d="M256,0C114.609,0,0,114.609,0,256c0,141.391,114.609,256,256,256c141.391,0,256-114.609,256-256     C512,114.609,397.391,0,256,0z M256,472c-119.297,0-216-96.703-216-216S136.703,40,256,40s216,96.703,216,216     S375.297,472,256,472z"
                                                                                            data-original="#000000" class="active-path"
                                                                                            data-old_color="#ffffff" fill="#ffffff"
                                                                                        />
                                                                                    </g>
                                                                                    <path d="M344,304c-22.094,0-40,17.906-40,40s17.906,40,40,40s40-17.906,40-40S366.094,304,344,304z M368,352h-16v16h-16v-16h-16    v-16h16v-16h16v16h16V352z"
                                                                                        data-original="#000000" class="active-path"
                                                                                        data-old_color="#ffffff" fill="#ffffff"
                                                                                    />
                                                                                </g>
                                                                            </g>
                                                                        </g>
                                                                    </svg>
                                                                </div>


                                                                <div class="remove text-right" v-if="item.is_like">

                                                                    <svg @click.prevent="ADD_NEW_LIKE(item.id, 'series', rootindex, index, 'delete')" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                        version="1.1" id="Capa_1" x="0px" y="0px" width="100%"
                                                                        viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;"
                                                                        xml:space="preserve">
                                                                        <g>
                                                                            <g>
                                                                                <g>
                                                                                    <g>
                                                                                        <g>
                                                                                            <path d="M344,288c4.781,0,9.328,0.781,13.766,1.922C373.062,269.562,384,245.719,384,218.625C384,177.422,351.25,144,310.75,144      c-21.875,0-41.375,10.078-54.75,25.766C242.5,154.078,223,144,201.125,144C160.75,144,128,177.422,128,218.625      C128,312,256,368,256,368s14-6.203,32.641-17.688C288.406,348.203,288,346.156,288,344C288,313.125,313.125,288,344,288z"
                                                                                                data-original="#000000" class="active-path"
                                                                                                data-old_color="#03A9F4" fill="#03A9F4"
                                                                                            />
                                                                                            <path d="M256,0C114.609,0,0,114.609,0,256c0,141.391,114.609,256,256,256c141.391,0,256-114.609,256-256      C512,114.609,397.391,0,256,0z M256,472c-119.297,0-216-96.703-216-216S136.703,40,256,40s216,96.703,216,216      S375.297,472,256,472z"
                                                                                                data-original="#000000" class="active-path"
                                                                                                data-old_color="#03A9F4" fill="#03A9F4"
                                                                                            />
                                                                                        </g>
                                                                                    </g>
                                                                                    <g>
                                                                                        <path d="M344,304c-22.094,0-40,17.906-40,40s17.906,40,40,40s40-17.906,40-40S366.094,304,344,304z M368,352h-48v-16h48V352z"
                                                                                            data-original="#000000" class="active-path"
                                                                                            data-old_color="#03A9F4" fill="#03A9F4"
                                                                                        />
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
                                                                <router-link :to="{name: 'series-player-current', params: {series_id: item.id}}" v-if="item.already_episode !== 0 && item.already_episode !== null">

                                                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px"
                                                                        viewBox="0 0 294.843 294.843" style="enable-background:new 0 0 294.843 294.843;"
                                                                        xml:space="preserve" width="100%" class="play-svg">
                                                                        <g>
                                                                            <g>
                                                                                <path d="M278.527,79.946c-10.324-20.023-25.38-37.704-43.538-51.132c-2.665-1.97-6.421-1.407-8.392,1.257s-1.407,6.421,1.257,8.392   c16.687,12.34,30.521,28.586,40.008,46.983c9.94,19.277,14.98,40.128,14.98,61.976c0,74.671-60.75,135.421-135.421,135.421   S12,222.093,12,147.421S72.75,12,147.421,12c3.313,0,6-2.687,6-6s-2.687-6-6-6C66.133,0,0,66.133,0,147.421   s66.133,147.421,147.421,147.421s147.421-66.133,147.421-147.421C294.842,123.977,289.201,100.645,278.527,79.946z"
                                                                                    data-original="#000000" class="active-path"
                                                                                    data-old_color="#ffffff" fill="#ffffff" />
                                                                                <path d="M109.699,78.969c-1.876,1.067-3.035,3.059-3.035,5.216v131.674c0,3.314,2.687,6,6,6s6-2.686,6-6V94.74l88.833,52.883   l-65.324,42.087c-2.785,1.795-3.589,5.508-1.794,8.293c1.796,2.786,5.508,3.59,8.294,1.794l73.465-47.333   c1.746-1.125,2.786-3.073,2.749-5.15c-0.037-2.077-1.145-3.987-2.93-5.05L115.733,79.029   C113.877,77.926,111.575,77.902,109.699,78.969z"
                                                                                    data-original="#000000" class="active-path"
                                                                                    data-old_color="#ffffff" fill="#ffffff" />
                                                                            </g>
                                                                        </g>
                                                                    </svg>
                                                                </router-link>

                                                               <div v-else>
                                                                   <h3><strong>SOON</strong></h3>
                                                               </div>

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
                                        </div>

                                        <!-- END Poster -->

                                        <div class="progress" v-if="item.current_time !== null">
                                            <div class="progress-bar" role="progressbar" :style="{width: item.current_time +'%'}" :aria-valuenow="item.current_time"
                                                aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>




                                    <!-- END Series List -->

                                </slide>
                            </carousel>
                        </div>

                    </div>


                </div>
            </div>
            <div v-else>
                <div class="my-5 col-lg-6 offset-lg-3 text-center">
                    <h4>{{$t('home.sorry_no_result')}}</h4>
                </div>
            </div>
        </div>
    </div>

</template>
<script>
import { mapState } from "vuex";
import { Carousel, Slide } from "vue-carousel";
import collection from "./collection/new.vue";
export default {
  name: "home",
  components: {
    Carousel,
    Slide,
    "collection-modal": collection
  },
  data() {
    return {
      active: null,
      recenlty_active: null,
      collection: {
        id: null,
        poster: null,
        name: null,
        type: null,
        rootindex: null,
        index: null
      }
    };
  },
  computed: mapState({
    data: state => state.home.data,
    loading: state => state.home.loading
  }),

  mounted() {
    this.$store.dispatch("GET_HOME_LIST");
  },
  methods: {
    // Show modal of collection
    SHOW_COLLECTION_MODAL(id, poster, name, type, rootindex, index) {
      this.collection.id = id;
      this.collection.poster = poster;
      this.collection.name = name;
      this.collection.type = type;
      this.collection.rootindex = rootindex;
      this.collection.index = index;
    },

    // Hide modal of collection
    HIDE_COLLECTION_MODAL_CANCEL() {
      this.collection.id = null;
    },

    // Hide modal and update array
    HIDE_COLLECTION_MODAL_SAVE() {
      this.collection.id = null;
      if (this.collection.rootindex !== null) {
        // For list
        this.data.data[this.collection.rootindex].list[
          this.collection.index
        ].is_favorite = true;
      } else {
        // For top
        this.data.top[this.collection.index].is_favorite = true;
      }
    },

    // Delete mvoie or series from data array
    DELETE_FROM_COLLECTION(id, type, rootindex, index) {
      if (rootindex !== null) {
        // For list
        this.data.data[rootindex].list[index].is_favorite = false;
      } else {
        // For top
        this.data.top[index].is_favorite = false;
      }
      this.$store.dispatch("DELETE_FROM_COLLECTION", {
        id,
        type
      });
    },

    // Add new like or delete it
    // Params type1 detected if add or delete
    ADD_NEW_LIKE(id, type, rootindex, index, type1) {
      if (type1 === "add") {
        // Add true to data array
        this.data.data[rootindex].list[index].is_like = true;
        this.$store.dispatch("ADD_LIKE", {
          id,
          type
        });
      } else {
        // Add false to data array
        this.data.data[rootindex].list[index].is_like = false;

        this.$store.dispatch("ADD_LIKE", {
          id,
          type
        });
      }
    }
  },
  filters: {
    // Cut word
    truncate(string, value) {
      return string.substring(0, value) + "...";
    }
  }
};
</script>