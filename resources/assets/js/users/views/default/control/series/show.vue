<template>
    <div>
        <transition name="slide-fade">

            <div class="show" v-if="data.series !== null">

                <collection-modal @hideModalCollectionCancel="HIDE_COLLECTION_MODAL_CANCEL" @hideModalCollectionSave="HIDE_COLLECTION_MODAL_SAVE"
                    :id="collection.id" :poster="collection.poster" :name="collection.name" :type="collection.type" :index="collection.index"></collection-modal>



                <!-- END Collection component -->


                <div class="body pl-md-3 pl-lg-3 pl-xl-2">

                    <div class="header p-4 pl-5  ">

                        <div class="row">

                            <div class="back" @click="$router.go(-1)">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px"
                                    viewBox="0 0 489.6 489.6" style="enable-background:new 0 0 489.6 489.6;" xml:space="preserve"
                                    width="100%" class="">
                                    <g>
                                        <g>
                                            <g>
                                                <path style="fill:#E8EDED" d="M244.8,489.6c135,0,244.8-109.8,244.8-244.8S379.8,0,244.8,0S0,109.8,0,244.8    S109.8,489.6,244.8,489.6z M244.8,19.8c124.1,0,225,100.9,225,225s-100.9,225-225,225s-225-100.9-225-225S120.7,19.8,244.8,19.8z"
                                                    data-original="#2C2F33" class="" data-old_color="#2C2F33" />
                                                <path style="fill:#54C0EB" d="M265.5,326.1c1.9,1.9,4.5,2.9,7,2.9s5.1-1,7-2.9c3.9-3.9,3.9-10.1,0-14l-67.3-67.3l67.3-67.3    c3.9-3.9,3.9-10.1,0-14s-10.1-3.9-14,0l-74.3,74.3c-3.9,3.9-3.9,10.1,0,14L265.5,326.1z"
                                                    data-original="#3C92CA" class="active-path" data-old_color="#54c0eb" />
                                            </g>
                                        </g>
                                    </g>
                                </svg>
                            </div>

                            <!-- END Back -->

                            <div class="collection ml-4">


                                <div class="add" v-if="! data.series.is_favorite" @click.prevent="SHOW_COLLECTION_MODAL(data.series.id, data.cdn.cdn_poster+data.series.poster, data.series.name, 'series')">

                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px"
                                        viewBox="0 0 300.003 300.003" style="enable-background:new 0 0 300.003 300.003;" xml:space="preserve"
                                        width="100%">
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
                                    <p>{{$t('show.my_collection')}}</p>
                                </div>

                                <div class="remove" v-if="data.series.is_favorite" @click.prevent="DELETE_FROM_COLLECTION(data.series.id, 'series')">

                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px"
                                        viewBox="0 0 52 52" style="enable-background:new 0 0 52 52;" xml:space="preserve" width="100%">
                                        <g>
                                            <g>
                                                <path d="M26,0C11.664,0,0,11.663,0,26s11.664,26,26,26s26-11.663,26-26S40.336,0,26,0z M40.495,17.329l-16,18   C24.101,35.772,23.552,36,22.999,36c-0.439,0-0.88-0.144-1.249-0.438l-10-8c-0.862-0.689-1.002-1.948-0.312-2.811   c0.689-0.863,1.949-1.003,2.811-0.313l8.517,6.813l14.739-16.581c0.732-0.826,1.998-0.9,2.823-0.166   C41.154,15.239,41.229,16.503,40.495,17.329z"
                                                    data-original="#000000" class="active-path" data-old_color="#5FC4E9" fill="#03A9F4"
                                                />
                                            </g>
                                        </g>
                                    </svg>
                                    <p>{{$t('show.my_collection')}}</p>

                                </div>

                            </div>


                            <!-- END My Collection -->

                            <div class="like ml-4">

                                <div class="add" v-if="! data.series.is_like" @click.prevent="ADD_NEW_LIKE(data.series.id, 'series', 'add')">

                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px"
                                        width="100%" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
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
                                    <p>{{$t('show.like')}}</p>
                                </div>


                                <div class="remove" v-if="data.series.is_like" @click.prevent="ADD_NEW_LIKE(data.series.id, 'series', 'delete')">

                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px"
                                        width="100%" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
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
                                    <p>{{$t('show.like')}}</p>

                                </div>

                            </div>

                        </div>


                    </div>



                    <!-- END Control Panel -->



                    <div class="hidden-sm-down background-sm-up">
                        <div v-if="show_season === 1">
                            <img :src="data.cdn.cdn_backdrop+data.series.backdrop" :alt="data.series.name" class="backdrop" width="100%">
                        </div>

                        <div v-else>
                            <img :src="data.cdn.cdn_backdrop+data.season[show_season][0].backdrop" :alt="data.series.name" class="backdrop" width="100%">
                        </div>
                    </div>

                    <div class="hidden-md-up background-sm-down">
                        <div v-if="show_season === 1">
                            <img :src="data.cdn.cdn_backdrop+data.series.backdrop" :alt="data.series.name" class="backdrop" width="100%">
                        </div>

                        <div v-else>
                            <img :src="data.cdn.cdn_backdrop+data.season[show_season][0].backdrop" :alt="data.series.name" class="backdrop" width="100%">
                        </div>
                        <router-link :to="{name: 'series-player-current', params: {series_id: data.series.id}}">
                            <div class="play hidden-md-up">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px"
                                    viewBox="0 0 294.843 294.843" style="enable-background:new 0 0 294.843 294.843;" xml:space="preserve"
                                    width="75px" class="play-svg">
                                    <g>
                                        <g>
                                            <path d="M278.527,79.946c-10.324-20.023-25.38-37.704-43.538-51.132c-2.665-1.97-6.421-1.407-8.392,1.257s-1.407,6.421,1.257,8.392   c16.687,12.34,30.521,28.586,40.008,46.983c9.94,19.277,14.98,40.128,14.98,61.976c0,74.671-60.75,135.421-135.421,135.421   S12,222.093,12,147.421S72.75,12,147.421,12c3.313,0,6-2.687,6-6s-2.687-6-6-6C66.133,0,0,66.133,0,147.421   s66.133,147.421,147.421,147.421s147.421-66.133,147.421-147.421C294.842,123.977,289.201,100.645,278.527,79.946z"
                                                data-original="#000000" class="active-path" data-old_color="#ffffff" fill="#ffffff"
                                            />
                                            <path d="M109.699,78.969c-1.876,1.067-3.035,3.059-3.035,5.216v131.674c0,3.314,2.687,6,6,6s6-2.686,6-6V94.74l88.833,52.883   l-65.324,42.087c-2.785,1.795-3.589,5.508-1.794,8.293c1.796,2.786,5.508,3.59,8.294,1.794l73.465-47.333   c1.746-1.125,2.786-3.073,2.749-5.15c-0.037-2.077-1.145-3.987-2.93-5.05L115.733,79.029   C113.877,77.926,111.575,77.902,109.699,78.969z"
                                                data-original="#000000" class="active-path" data-old_color="#ffffff" fill="#ffffff"
                                            />
                                        </g>
                                    </g>
                                </svg>
                            </div>
                        </router-link>
                    </div>



                    <!-- END Background image -->

                    <div class="row">
                        <div class="col-1 season-list hidden-sm-down" v-if="data.season !== null">
                            <ul class="list-unstyled">
                                <li >
                                    <strong class="noselect" @click="show_season = index"> {{$t('show.season') + index}} </strong>
                                </li>
                            </ul>
                        </div>

                        <div :class="{'col-12 col-md-11 col-lg-11 col-md-11': data.season !== null, 'col-12': data.season === null }">
                            <div class="col-12 overview  pt-md-5 pt-lg-5 pt-xl5">
                                <div class="row">

                                    <div class="col-12 col-sm-12 col-md-5 col-lg-5 col-lx-5 mt-5">

                                        <div class="title">
                                            <div class="col-12">
                                                <h5>
                                                    <strong>{{data.series.name}} </strong>
                                                    <strong class="age-rating">{{data.series.age}}</strong>
                                                </h5>
                                                <!-- END Name -->
                                                <p>{{data.series.overview}}</p>
                                                <!-- END Overview -->
                                            </div>
                                        </div>

                                        <div class="more-details">

                                            <div class="col-12 year-genre p-3">
                                                <p class="year">{{data.series.year}}</p>
                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px"
                                                    width="8px" viewBox="0 0 438.533 438.533" style="enable-background:new 0 0 438.533 438.533;"
                                                    xml:space="preserve" class="circular-shape">
                                                    <g>
                                                        <g>
                                                            <path d="M409.133,109.203c-19.608-33.592-46.205-60.189-79.798-79.796C295.736,9.801,259.058,0,219.273,0   c-39.781,0-76.47,9.801-110.063,29.407c-33.595,19.604-60.192,46.201-79.8,79.796C9.801,142.8,0,179.489,0,219.267   c0,39.78,9.804,76.463,29.407,110.062c19.607,33.592,46.204,60.189,79.799,79.798c33.597,19.605,70.283,29.407,110.063,29.407   s76.47-9.802,110.065-29.407c33.593-19.602,60.189-46.206,79.795-79.798c19.603-33.596,29.403-70.284,29.403-110.062   C438.533,179.485,428.732,142.795,409.133,109.203z"
                                                                data-original="#000000" class="active-path" data-old_color="#ffffff"
                                                                fill="#ffffff" />

                                                        </g>
                                                    </g>
                                                </svg>
                                                <p class="genre">{{data.series.genre}}</p>
                                            </div>

                                            <!-- END Year And Genre -->

                                            <div class="col-12 rate">

                                                <h1>{{data.series.rate}}
                                                    <small>/10</small>
                                                </h1>

                                            </div>

                                            <!-- END Rate -->

                                        </div>

                                    </div>

                                    <div class="col-md-5 col-lg-5 col-lx-5 mt-5 hidden-sm-down">
                                        <router-link :to="{name: 'series-player-current', params: {series_id: data.series.id}}">
                                            <div class="play-md-up text-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px"
                                                    viewBox="0 0 294.843 294.843" style="enable-background:new 0 0 294.843 294.843;"
                                                    xml:space="preserve" width="120px" class="play-svg">
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
                                            </div>
                                        </router-link>
                                    </div>

                                </div>
                            </div>



                            <!-- END Overview -->

                        </div>

                        <div class="col-12 footer series-footer">
                            <div class="p-2 p-md-5 p-lg-5 p-xl-5 mt-5">
                                <div class="row">

                                    <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-4 mt-2 cast">
                                        <h3>
                                            <strong>{{$t('show.cast')}}</strong>
                                        </h3>
                                        <div class="row">
                                            <div class="col-4 col-sm-4 col-md-6 col-lg-4 col-xl-3 mb-2" v-for="(item, index) in data.casts" :key="index">
                                                <router-link :to="{name: 'cast', params: {id: item.id}}">
                                                    <div class="image" @mouseover="castShow = item.id">
                                                        <img :src="data.cdn.cdn_cast+item.image" :alt="item.name" width="100%">

                                                        <div class="ovarlay" v-if="castShow === item.id">
                                                            <p class="noselect">{{item.name}}</p>
                                                        </div>
                                                    </div>
                                                </router-link>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- END Cast -->
                                     <div class="col-12 season">
                                        <div class="float-left">
                                        <h3>
                                            <strong>{{$t('show.season')+ show_season}}</strong>
                                        </h3>
                                        </div>

                                       <div class="float-right hidden-md-up">
                                          <div class="dropdown">
                                        <button class="btn btn-circle btn-secondary" id="dropdownTrendingButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{$t('show.season')+ show_season}}</button>

                                        <div class="dropdown-menu  dropdown-menu-right" aria-labelledby="dropdownTrendingButton">
                                            <a class="dropdown-item" v-for="(item, index) in data.season" :key="index" :class="{active: show_season == index}"
                                            @click="show_season = index">{{$t('show.season') + index}}</a>
                                        </div>

                                        </div>
                                       </div>
                                       </div>

                                    <div class="col-12 col-sm-12 col-md-8 col-lg-8 col-xl-8 mt-2 season" v-if="data.series.trailer !== null">
                                        <transition>
                                            <div v-if="data.season !== null">
                                                <div class="row">

                                                    <div class="col-6 col-md-6 col-lg-6 col-xl-4 mb-2" v-for="(item, index) in data.season[show_season]" :key="index">

                                                        <div class="backdrop">
                                                            <router-link :to="{name: 'series-player-specific', params:{episode_number: item.id, series_id: data.series.id}}">
                                                                <img :src="data.cdn.cdn_backdrop+item.backdrop" :alt="item.name" width="100%">

                                                            </router-link>
                                                            <div class="overview mt-2">
                                                                <strong>{{item.episode_number+ ' '+item.name}}</strong>

                                                                <p class="hidden-xs-down">{{ item.overview | truncate(70, item.overview)}}</p>
                                                            </div>

                                                        </div>

                                                    </div>

                                                </div>
                                            </div>

                                            <div v-else>

                                                <div class="mt-5 text-center notfound">
                                                    <h4>
                                                        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                                            width="10vh" viewBox="0 0 512.009 512.009" style="enable-background:new 0 0 512.009 512.009;"
                                                            xml:space="preserve">
                                                            <circle style="fill:#F7B239;" cx="256.004" cy="256.004" r="256.004" />
                                                            <g>
                                                                <path style="fill:#E09B2D;" d="M121.499,390.501C29.407,298.407,22.15,153.608,99.723,53.204
                                                    c-8.593,6.638-16.861,13.895-24.743,21.777c-99.974,99.974-99.974,262.065,0,362.038s262.065,99.974,362.038,0
                                                    c7.881-7.881,15.138-16.15,21.777-24.743C358.392,489.85,213.593,482.593,121.499,390.501z"
                                                                />
                                                                <path style="fill:#E09B2D;" d="M287.513,369.876c-1.126,0-2.27-0.201-3.383-0.626c-18.404-7.028-37.86-7.027-56.259,0
                                                    c-4.891,1.867-10.378-0.583-12.249-5.481c-1.87-4.896,0.585-10.379,5.481-12.249c22.828-8.716,46.964-8.717,69.795,0
                                                    c4.896,1.87,7.351,7.353,5.481,12.249C294.935,367.552,291.334,369.876,287.513,369.876z"
                                                                />
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

                                                        <strong>{{$t('show.no_series')}}</strong>

                                                    </h4>
                                                </div>

                                            </div>

                                        </transition>

                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- END Footer -->

                    </div>
                </div>

            </div>
            <div v-else>
                <div class="spinner-load-show">
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
            </div>
        </transition>
    </div>
</template>
<script>
    const plyr = require('plyr');
    require('plyr/dist/plyr.css');
    import {
        mapState
    } from 'vuex';
    import collection from '../collection/new.vue';
    export default {
        name: 'series-show',

        data() {
            return {
                castShow: null,
                show_season: 1,
                collection: {
                    id: null,
                    poster: null,
                    name: null,
                    type: null,
                    index: null,
                }
            };
        },

        components: {
            'collection-modal': collection
        },

        computed: mapState({
            data: state => state.series.show,
            loading: state => state.series.loading,
        }),

        mounted() {
            this.$store.dispatch('GET_SERIES_DETAILS', this.$route.params.id);
        },
        watch: {
            data() {
                if (this.data.series.runtime <= 60) {
                    this.data.series.runtime = this.data.series.runtime + 'm';
                } else if (this.data.series.runtime >= 60) {
                    const minutes = this.data.series.runtime % 60;
                    const hours = Math.floor(this.data.series.runtime / 60)
                    this.data.series.runtime = hours + 'h ' + minutes + 'm';
                }
                // Replice special characters

                this.data.series.genre = this.data.series.genre.replace(/-/g, ', ')

                // Run plyr to show trailer
                setTimeout(() => {
                    plyr.setup('.plyr');
                }, 100);

                // Set season
                this.season = this.data.season;
            },
        },

        methods: {

            SIMILAR_SHOW(id) {
                this.$store.dispatch('GET_SERIES_DETAILS', id);
            },

            // Show modal of collection 
            SHOW_COLLECTION_MODAL(id, poster, name, type) {
                this.collection.id = id;
                this.collection.poster = poster;
                this.collection.name = name;
                this.collection.type = type;
            },

            // Hide modal of collection
            HIDE_COLLECTION_MODAL_CANCEL() {
                this.collection.id = null;
            },

            // Hide modal and update array
            HIDE_COLLECTION_MODAL_SAVE() {
                this.collection.id = null;
                this.data.series.is_favorite = true;
            },

            // Delete mvoie or series from data array
            DELETE_FROM_COLLECTION(id, type, index) {
                this.data.series.is_favorite = false;

                this.$store.dispatch('DELETE_FROM_COLLECTION', {
                    id,
                    type
                })
            },


            // Add new like or delete it 
            // Params type1 detected if add or delete
            ADD_NEW_LIKE(id, type, type1) {
                if (type1 === 'add') {
                    // Add true to data array
                    this.data.series.is_like = true;
                    this.$store.dispatch('ADD_LIKE', {
                        id,
                        type
                    })

                } else {
                    // Add false to data array
                    this.data.series.is_like = false;

                    this.$store.dispatch('ADD_LIKE', {
                        id,
                        type
                    })
                }
            },

        },


        filters: {
            // Cut word  
            truncate(string, value) {
                return string.substring(0, value) + '...';
            }
        }
    }
</script>