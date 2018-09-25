import Vue from 'vue';


const module = {
    state: {
        data: [],
        season: [],
        suggestion: [],
        bookmark: [],
        url: [],
        show_report: false,
        next: null,
        next_episode: null,
        next_season: null,
        next_is: null,
        next_playlist: null,
        season_playlist_active: null

    },
    actions: {

        /**
         * Get movie vidoe,subtitle,suggestion
         * 
         * @param {any} param
         * @param {any} id 
         */

        LOAD_MOVIE_PLAYER({
            commit
        }, id) {
            axios.get('/api/v1/get/watch/movie/' + id).then(response => {
                if (response.status === 200) {
                    const data = response.data.data;
                    commit('SET_MOVIE', data);
                }
            }, error => {
                // Show Sweetalert if there is problem
                swal({
                    icon: 'error',
                    title: 'There was a problem playing the video, we will fix it soon',
                    dangerMode: true,
                    button: 'Back',
                }).then(() => {
                    window.history.back();
                    videojs.dispose();
                });
            });
        },


        /**
         * 
         * @param {*}  commit
         * @param {uuid,string,uuid}  Array  
         */
        LOAD_SERIES_PLAYER({commit}, {episode_id,type,series_id}) {
            axios.post('/api/v1/get/watch/series', {
                    episode_id: episode_id,
                    type: type,
                    series_id: series_id,
                })
                .then(response => {
                    if (response.data.status === 'success') {
                        const data = response.data.data;
                        commit('SET_SERIES', data);
                    }
                }, error => {
                    swal({
                        icon: 'error',
                        title: 'There was a problem playing the video, we will fix it soon',
                        dangerMode: true,
                        button: 'Back',
                    }).then(() => {
                        window.history.back();
                        videojs.dispose();
                    });
                });
        },

        // Tv live 
        LOAD_TV({
            commit
        }, id) {
            axios.get('/api/v1/get/watch/tv/' + id)
                .then((res) => {
                    const link = res.data.link;
                    commit('SET_TV', {
                        link
                    });

                });
        },
    },
    mutations: {

        /**
         * This mutations to set all video details (video resolation,subtitle,suggestion) in videojs player 
         * 
         * @param {*} state 
         * @param {*} data 
         */

        SET_MOVIE(state, data) {
            state.next = null;

            // Check if there is custom caption settting or not 
            // If there is custom setting add stylesheet
            const parsedCaption = JSON.parse(localStorage.getItem('caption'));
            let css;
            if (parsedCaption !== null && parsedCaption !== 'null' && parsedCaption !== undefined) {
                css = ` #my-player>div.vjs-text-track-display>div>div>div {
                                    color:` + parsedCaption['color'] + ` !important;
                                    background-color: ` + parsedCaption['background-color'] + ` !important;
                                    font-size: ` + parseInt(parsedCaption['font-size']) * 2 + `px;
                                    font-weight: ` + parsedCaption['font-weight'] + `
                                   }`;

            } else {
                css = ` #my-player>div.vjs-text-track-display>div>div>div {
                                    color:#fff !important;
                                    background-color:  transparent !important;
                                    font-size: 35px;
                                    font-weight: 600;
                                   }`;
            }
            const head = document.head || document.getElementsByTagName('head')[0];
            const style = document.createElement('style');

            style.type = 'text/css';
            if (style.styleSheet) {
                style.styleSheet.cssText = css;
            } else {
                style.appendChild(document.createTextNode(css));
            }

            head.appendChild(style);

            // Add new vidoejs player
            videojs('my-player', {
                controls: true,
                autoplay: true,
                remainingTimeDisplay: false,
                techOrder: ['html5', 'flash'],
                plugins: {
                    videoJsResolutionSwitcher: { // Resolation plugin 
                        default: 'high',
                        dynamicLabel: true
                    }
                }
            }, function () {
                var player = this;

                let recently_time;

                // Check if movie is watched before and add a current time to play from last 
                if (data.video[0].current_time !== null) {
                    player.currentTime(data.video[0].current_time);
                    recently_time = parseInt(data.video[0].current_time) + 5;
                } else {
                    recently_time = 200;
                }


                // Add resolution dynamically in sources via updateSrc method
                let video_resolution = [];
                for (var index = 0; index < data.video.length; index++) {
                    if (data.video[index].resolution !== '720' && data.video[index].resolution !== '1080' && data.video[index].resolution !== '1440') {
                        video_resolution.push({
                            'src': (data.video[index].type === 's3' ? data.cdn.cdn_video + data.video[index].video : data.video[index].video),
                            'type': 'video/webm',
                            'label': data.video[index].resolution
                        });
                    } else {
                        video_resolution.push({
                            'src': (data.video[index].type === 's3' ? data.cdn.cdn_video + data.video[index].video : data.video[index].video),
                            'type': 'video/webm',
                            'label': data.video[index].resolution + '<p id="caption-label">HD</p>',
                        });
                    }
                }
                player.updateSrc(video_resolution);


                // Before end show the suggestion and set recently time 
                player.on('timeupdate', function () {
                    // Display on arrival 40s before the end.
                    const movie_suggestions = document.getElementsByClassName('icon-vidoejs-next-movie')[0];
                    if ((player.duration() - 40) <= player.currentTime()) {
                        if (data.suggestion !== null) {
                            if (movie_suggestions.style.display !== 'block') {
                                movie_suggestions.setAttribute('style', 'display: block;');
                            }
                        }
                    } else {
                        if (data.suggestion !== null) {
                            movie_suggestions.setAttribute('style', 'display:none');
                        }
                    }
                    if ((player.duration() - 2) <= player.currentTime()) {
                        if (data.suggestion !== null) {
                            state.next = 'movie';
                            movie_suggestions.setAttribute('style', 'display:none');
                        }
                    }

                    /// Store recently time
                    if (player.currentTime().toFixed(0) == recently_time) {

                        // +5m
                        recently_time = recently_time + 200;

                        // Send request
                        axios.post('/api/v1/create/watch/movie/recently_time', {
                            current_time: player.currentTime().toFixed(0),
                            duration_time: player.duration().toFixed(0),
                            movie_id: data.video[0].id,
                        });
                    }
                });


                // Add report button 
                player.getChild('controlBar').removeChild('reportButton', {}, 1);
                var reButton = videojs.getComponent('Button');

                // Extend default
                var reportButton = videojs.extend(reButton, {
                    constructor: function () {
                        reButton.apply(this, arguments);
                        this.addClass('icon-vidoejs-report');
                        this.controlText('List');
                    },

                    handleClick: function () {
                        if (document.exitFullscreen) {
                            document.exitFullscreen();
                        } else if (document.webkitExitFullscreen) {
                            document.webkitExitFullscreen();
                        } else if (document.mozCancelFullScreen) {
                            document.mozCancelFullScreen();
                        } else if (document.msExitFullscreen) {
                            document.msExitFullscreen();
                        }
                        player.pause();
                        state.show_report = true;
                    },
                });

                // Register the new component
                videojs.registerComponent('reportButton', reportButton);
                player.getChild('controlBar').addChild('reportButton', {}, 9);


                // Buttton for suggestion movie 
                if (data.suggestion !== null) {
                    const nextButton = videojs.getComponent('Button');
                    const nextPreviewButton = videojs.extend(nextButton, {
                        constructor: function () {
                            nextButton.apply(this, arguments);
                            this.addClass('icon-vidoejs-next-movie');
                            this.contentEl().appendChild(videojs.dom.createEl('button', {
                                id: 'next-movie',
                                innerHTML: `<img src="` + data.cdn.cdn_backdrop + data.suggestion.backdrop + `" id="next-movie"width="100%" >
                                    <div class="body" id="next-movie">
                                    <div class="title"><p style="font-size:15px;"><b>` + data.suggestion.name + `</b></p></div></div>`,
                                tabindex: -1,
                                style: `font-size: 20px;
                                    width: 350px;
                                    -webkit-box-shadow: 2px 2px 5px 0px rgba(0, 0, 0, 1);
                                    moz-box-shadow: 2px 2px 5px 0px rgba(0, 0, 0, 1);
                                    box-shadow: 2px 2px 5px 0px rgba(0, 0, 0, 1);
                                    padding: 0;`
                            }));
                        },
                        handleClick: function () {
                            state.next = 'movie';
                        }
                    });
                    // Register the new component
                    videojs.registerComponent('nextPreviewButton', nextPreviewButton);
                    player.addChild('nextPreviewButton');
                }


                // Add exit button
                var exitButton = videojs.getComponent('Button');

                // Extend default
                var exitContentButton = videojs.extend(exitButton, {
                    constructor: function () {
                        exitButton.apply(this, arguments);
                        this.addClass('icon-vidoejs-exit');
                        this.controlText('List');
                    },

                    handleClick: function () {
                        player.dispose();
                        window.history.back();
                    },
                });

                // Register the new component
                videojs.registerComponent('exitContentButton', exitContentButton);
                player.addChild('exitContentButton', {}, 1);

                let movie_suggestions = document.getElementsByClassName('icon-vidoejs-exit')[0];
                player.on('useractive', function () {
                    movie_suggestions.setAttribute('style', 'display: block;');
                });
                player.on('userinactive', function () {
                    movie_suggestions.setAttribute('style', 'display: none;');
                });


                // Add caption to video
                if (data.subtitle !== null) {
                    for (var index = 0; index < data.subtitle.length; index++) {
                        var track =
                            ({
                                kind: 'translation',
                                label: data.subtitle[index].subtitle_language,
                                src: data.cdn.cdn_subtitle + data.subtitle[index].subtitle_name,
                            });
                        player.addRemoteTextTrack(track);
                    }
                }

                // Catch errror and show sweetalert
                player.on('error', function () {
                    swal({
                        icon: 'error',
                        title: 'There was a problem playing the video, we will fix it soon',
                        dangerMode: true,
                        button: 'Back',
                    }).then(() => {
                        window.history.back();
                    });
                });
            });
        },


        /**
         * 
         * @param {*} state 
         * @param {*} list 
         */
        SET_SERIES(state, data) {
            state.data = data.episode[0];
            state.season = data.season;
            state.next_season = data.next_season;
            state.next = null;
            state.next_is = null;
            state.next_playlist = null;
            state.season_playlist_active = data.episode[0].season_number;

            // Check if there is custom caption settting or not 
            // If there is custom setting add stylesheet
            const parsedCaption = JSON.parse(localStorage.getItem('caption'));
            let css;
            if (parsedCaption !== null && parsedCaption !== 'null' && parsedCaption !== undefined) {
                css = ` #my-player>div.vjs-text-track-display>div>div>div {
                                    color:` + parsedCaption['color'] + ` !important;
                                    background-color: ` + parsedCaption['background-color'] + ` !important;
                                    font-size: ` + parseInt(parsedCaption['font-size']) * 2 + `px;
                                    font-weight: ` + parsedCaption['font-weight'] + `
                                   }`;



            } else {
                css = ` #my-player>div.vjs-text-track-display>div>div>div {
                                    color:#fff !important;
                                    background-color:  transparent !important;
                                    font-size: 35px;
                                    font-weight: 600;
                                   }`;
            }
            const head = document.head || document.getElementsByTagName('head')[0];
            const style = document.createElement('style');

            style.type = 'text/css';
            if (style.styleSheet) {
                style.styleSheet.cssText = css;
            } else {
                style.appendChild(document.createTextNode(css));
            }

            head.appendChild(style);


            videojs('my-player', {
                controls: true,
                autoplay: true,
                remainingTimeDisplay: false,
                techOrder: ['html5', 'flash'],
                plugins: {
                    videoJsResolutionSwitcher: {
                        default: 'high',
                        dynamicLabel: true
                    }
                },
                controlBar: {
                    volumePanel: {
                        inline: false
                    }

                }
            }, function () {
                let player = this;

                // Add resolution dynamically in sources via updateSrc method
                let episode_now = [];
                for (let index = 0; index < data.episode.length; index++) {
                    if (data.episode[index].resolution !== '720' && data.episode[index].resolution !== '480') {
                        episode_now.push({
                            'src': (data.episode[index].type === 's3' ? data.cdn.cdn_video + data.episode[index].video : data.episode[index].video),
                            'type': 'video/mp4',
                            'label': data.episode[index].resolution,
                        });
                    } else {
                        episode_now.push({
                            'src': (data.episode[index].type === 's3' ? data.cdn.cdn_video + data.episode[index].video : data.episode[index].video),
                            'type': 'video/mp4',
                            'label': data.episode[index].resolution + '<p id="caption-label">HD</p>',
                        });
                    }
                }
                player.updateSrc(episode_now);


                // Check if episode is watched before and add a current time to play from last 
                let recently_time;
                if (data.episode[0].current_time !== null) {
                    player.currentTime(data.episode[0].current_time);
                    recently_time = parseInt(data.episode[0].current_time) + 5;
                } else {
                    recently_time = 200;
                }

                player.on('timeupdate', function () {
                    // Display on arrival two minutes before the end.
                    const next_episode_element = document.getElementsByClassName('icon-vidoejs-next-episode')[0];
                    const next_season_element = document.getElementsByClassName('icon-vidoejs-next-season')[0];
                    if ((player.duration() - 10) <= player.currentTime()) {
                        if (state.next_episode !== null && next_episode_element !== undefined) {
                            if (next_episode_element.style.display !== 'block') {
                                next_episode_element.setAttribute('style', 'display: block;');
                                state.next = 'episode';
                            }
                        }
                        if (state.next !== 'episode' && state.next_season !== null && next_season_element !== undefined) {
                            if (next_season_element.style.display !== 'block') {
                                next_season_element.setAttribute('style', 'display: block;');
                                state.next = 'season';
                            }
                        }

                    } else {
                        if (next_episode_element !== undefined) {
                            next_episode_element.setAttribute('style', 'display: none;');
                        }
                        if (next_season_element !== undefined) {
                            next_season_element.setAttribute('style', 'display: none;');
                        }
                    }


                    /// Store recently time
                    if (player.currentTime().toFixed(0) == recently_time) {

                        // +5m
                        recently_time = recently_time + 200;

                        // Send request
                        axios.post('/api/v1/create/watch/series/recently', {
                            current_time: player.currentTime().toFixed(0),
                            duration_time: player.duration().toFixed(0),
                            episode_id: data.episode[0].id,
                            series_id: data.episode[0].series_id
                        });
                    }
                });

                // Playlist Season

                // Create new component
                // Add season watched now
                let season_now = state.season_playlist_active;

                const Component = videojs.getComponent('Component');
                new Component(player);

                let exitSeasonButton = videojs.extend(Component, {
                    constructor: function () {
                        Component.apply(this, arguments);
                        this.addClass('col-8'); // xs
                        this.addClass('col-md-4'); // md
                        this.addClass('col-lg-4'); // lg
                        this.addClass('col-xl-3'); // xl
                        this.addClass('season-list');

                        // Add season List
                        let _listseason = '';
                        for (var key in state.season) {
                            if (state.season.hasOwnProperty(key)) {
                                if (season_now == key) {
                                    _listseason += `<button class="season-toggle-btn active noselect" id="` + key + `">` + key + `</button>`;

                                } else {
                                    _listseason += `<button class="season-toggle-btn noselect" id="` + key + `">` + key + `</button>`;
                                }
                            }
                        }

                        // Add list of episode
                        let _listepisode = '';

                        // if the id is equal then is you watch at the moment
                        for (let index = 0; index < state.season[season_now].length; index++) {

                            if (data.episode[0].id == state.season[season_now][index].id) {
                                _listepisode += `<li class="current mt-2">
                                                        <div class="image">
                                                     
                                                        <img src="` + data.cdn.cdn_backdrop + state.season[season_now][index].backdrop + `" width="100%">
                                                        
                                                        <div class="ovarlay">
                                                            <div class="number">
                                                             <p>` + state.season[season_now][index].episode_number + ` </p>
                                                            </div>

                                                            <div class="play">
                                                                 <h3>You watch</h3>
                                                            </div>

                                                        </div>

                                                        </div>
                                                        <div class="overview mt-1">
                                                            <strong>` + state.season[season_now][index].name + `</strong>
                                                            <p class="hidden-xs-down">` + state.season[season_now][index].overview + `</p>
                                                        </div>  
                                          </li>`;

                            } else {
                                _listepisode += `<li class="mt-2">
                                                       <div class="image">

                                                        <img class="" src="` + data.cdn.cdn_backdrop + state.season[season_now][index].backdrop + `" width="100%"  id="` + state.season[season_now][index].id + `">
                                                        <div class="episode" id="` + state.season[season_now][index].id + `"></div>
                                                        <div class="ovarlay">
                                                       
                                                         <div class="number">
                                                           <p>` + state.season[season_now][index].episode_number + ` </p>
                                                         </div>
                                                       
                                                         <div class="play">
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
                                                       
                                                       </div>
                                                       </div>

                                                        <div class="overview mt-1">
                                                             <strong>` + state.season[season_now][index].name + `</strong>
                                                             <p class="hidden-xs-down" id="` + state.season[season_now][index].id + `">` + state.season[season_now][index].overview + `</p>
                                                        </div>
                                            </li>`;
                            }
                        }

                        this.contentEl().appendChild(videojs.dom.createEl('div', {
                            className: 'vjs-menu-listbox',
                            innerHTML: `<div class="toggle-playlist-hide">
                                         <i class="fa fa-angle-right" aria-hidden="true"></i>

                                        </div>
                                        <div class="season ml-3">
                                        <div class="row">
                                        ` + _listseason + `
                                        </div>
                                        </div>
                                        <div class="list-episode">
                                        <ul class="list-unstyled ">` + _listepisode + `</ul>
                                        </div>`,
                            tabindex: -1,
                        }));

                    },

                });


                // Register component
                videojs.registerComponent('exitSeasonButton', exitSeasonButton);
                player.addChild('exitSeasonButton', {}, 1);


                // Add event listenr to change episode 
                document.addEventListener("click", (e) => {
                    if (e.target.className === 'episode') {
                        state.next_playlist = e.target.id;
                    }
                });

                // Create new component for toggle season playlist
                const toggle_playlist = videojs.getComponent('Component');
                new toggle_playlist(player);
                let toggle_play_list = videojs.extend(toggle_playlist, {
                    constructor: function () {
                        toggle_playlist.apply(this, arguments);
                        this.addClass('toggle-playlist-show');
                        this.contentEl().appendChild(videojs.dom.createEl('div', {
                            className: 'arrow-icon',
                            innerHTML: `<i class="fa fa-angle-left" aria-hidden="true"></i>                            `,
                            tabindex: -1,
                        }));
                    },
                });

                videojs.registerComponent('toggle_play_list', toggle_play_list);
                player.addChild('toggle_play_list', {}, 1);



                // Add event listenr to hide playlist
                document.querySelector('.toggle-playlist-hide').addEventListener('click', function (e) {
                    const element = document.getElementsByClassName('season-list');
                    element[0].classList.toggle('show');
                });

                // Add event listenr to show playlist
                document.querySelector('.toggle-playlist-show').addEventListener('click', function (e) {
                    const element = document.getElementsByClassName('season-list');
                    element[0].classList.toggle('show');
                });


                let season_toggle = document.getElementsByClassName("season-toggle-btn");
                for (var i = 0; i < season_toggle.length; i++) {
                    season_toggle[i].addEventListener('click', addSeason, false);
                }

                function addSeason(e) {

                    season_now = e.target.id;
                    state.season_playlist_active = e.target.id
                    // Clear old season 
                    let list_div = document.querySelector('.list-episode');
                    list_div.innerHTML = '';
                    // Add list of episode
                    let _listepisode = '';
                    _listepisode += `<ul class="list-unstyled ">`;

                    // if the id is equal then is you watch at the moment
                    for (let index = 0; index < state.season[season_now].length; index++) {

                        if (data.episode[0].id == state.season[season_now][index].id) {
                            _listepisode += `<li class="current mt-2">
                                  <div class="image">
                               
                                  <img src="` + data.cdn.cdn_backdrop + state.season[season_now][index].backdrop + `" width="100%">
                                  
                                  <div class="ovarlay">
                                      <div class="number">
                                       <p>` + state.season[season_now][index].episode_number + ` </p>
                                      </div>

                                      <div class="play">
                                           <h3>You watch</h3>
                                      </div>

                                  </div>

                                  </div>
                                  <div class="overview mt-1">
                                      <strong>` + state.season[season_now][index].name + `</strong>
                                      <p class="hidden-xs-down">` + state.season[season_now][index].overview + `</p>
                                  </div>  
                    </li>`;

                        } else {
                            _listepisode += `<li class="mt-2">
                                 <div class="image">

                                  <img class="" src="` + data.cdn.cdn_backdrop + state.season[season_now][index].backdrop + `" width="100%"  id="` + state.season[season_now][index].id + `">
                                  <div class="episode" id="` + state.season[season_now][index].id + `"></div>
                                  <div class="ovarlay">
                                 
                                   <div class="number">
                                     <p>` + state.season[season_now][index].episode_number + ` </p>
                                   </div>
                                 
                                   <div class="play">
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
                                 
                                 </div>
                                 </div>

                                  <div class="overview mt-1">
                                       <strong>` + state.season[season_now][index].name + `</strong>
                                       <p class="hidden-xs-down" id="` + state.season[season_now][index].id + `">` + state.season[season_now][index].overview + `</p>
                                  </div>
                      </li>`;
                        }
                    }
                    _listepisode += '</ul>';

                    list_div.innerHTML = _listepisode;
                }

                // Get the next episode
                // for loop check the equal beetween episode number and increase 1 
                for (let index = 0; index < data.season[season_now].length; index++) {
                    if (data.episode[0].episode_number === data.season[season_now][index].episode_number) {
                        if (data.season[season_now].hasOwnProperty(index + 1)) {
                            state.next_episode = data.season[season_now][index + 1];
                        } else {
                            state.next_episode = null;
                        }
                    }
                }



                /* ADD BUTTON REPORT SHOW
                 ***********************/
                player.getChild('controlBar').removeChild('reportButton', {}, 1);
                var reButton = videojs.getComponent('Button');

                // Extend default
                var reportButton = videojs.extend(reButton, {
                    constructor: function () {
                        reButton.apply(this, arguments);
                        this.addClass('icon-vidoejs-report');
                        this.controlText('List');
                    },

                    handleClick: function () {
                        if (document.exitFullscreen) {
                            document.exitFullscreen();
                        } else if (document.webkitExitFullscreen) {
                            document.webkitExitFullscreen();
                        } else if (document.mozCancelFullScreen) {
                            document.mozCancelFullScreen();
                        } else if (document.msExitFullscreen) {
                            document.msExitFullscreen();
                        }

                        state.show_report = true;
                    },
                });

                // Register the new component
                videojs.registerComponent('reportButton', reportButton);
                player.getChild('controlBar').addChild('reportButton', {}, 9);




                /* Next epiosde button
                 ***********************/
                // Extend default
                if (state.next_episode !== null) {
                    var nextButton = videojs.getComponent('Button');
                    var nextPreviewButton = videojs.extend(nextButton, {
                        constructor: function () {
                            nextButton.apply(this, arguments);
                            this.addClass('icon-vidoejs-next-episode');
                            this.contentEl().appendChild(videojs.dom.createEl('button', {
                                id: 'next-episode',
                                innerHTML: `<img src="` + data.cdn.cdn_backdrop + state.next_episode.backdrop + `" id="next-episode"  width="100%" >
                                    <div class="body" id="next-episode">
                                    <div class="title" id="next-episode"> <p> <b> PLAY NEXT EPISODE </b><br>S` + state.next_episode.season_number + `E` + state.next_episode.episode_number + ': ' + state.next_episode.name + `</p> </div></div > `,
                                tabindex: -1,
                                style: `font-size: 20px;
                                    width: 400px;
                                    -webkit-box-shadow: 2px 2px 5px 0px rgba(0, 0, 0, 1);
                                    moz-box-shadow: 2px 2px 5px 0px rgba(0, 0, 0, 1);
                                    box-shadow: 2px 2px 5px 0px rgba(0, 0, 0, 1);
                                    padding: 0;`
                            }));
                        },
                        handleClick: function () {
                            state.next_is = 'episode';
                        },
                    });
                    // Register the new component
                    videojs.registerComponent('nextPreviewButton', nextPreviewButton);
                    player.addChild('nextPreviewButton');
                }



                /* Next season button
                 ***********************/
                // Extend default
                if (state.next_season !== null) {
                    var nextSeasonButton = videojs.getComponent('Button');
                    var nextSeasonPreviewButton = videojs.extend(nextSeasonButton, {
                        constructor: function () {
                            nextSeasonButton.apply(this, arguments);
                            this.addClass('icon-vidoejs-next-season');
                            this.contentEl().appendChild(videojs.dom.createEl('div', {
                                id: 'next-season',
                                innerHTML: `<img src="` + data.cdn.cdn_backdrop + state.next_season.backdrop + `" id="next-season"width="100%" >
                                <div class="body" id="next-season">
                                <div class="title"><p><b>PLAY NEXT SEASON</b><br>S` + state.next_season.season_number + `E` + state.next_season.episode_number + ': ' + state.next_season.name + `</p></div></div>`,
                                tabindex: -1,
                                style: `font-size: 20px;
                                    width: 350px;
                                    -webkit-box-shadow: 2px 2px 5px 0px rgba(0, 0, 0, 1);
                                    moz-box-shadow: 2px 2px 5px 0px rgba(0, 0, 0, 1);
                                    box-shadow: 2px 2px 5px 0px rgba(0, 0, 0, 1);
                                    padding: 0;`
                            }));
                        },
                        handleClick: function () {
                            state.next_is = 'season';
                        },
                    });

                    // Register the new component
                    videojs.registerComponent('nextSeasonPreviewButton', nextSeasonPreviewButton);
                    player.addChild('nextSeasonPreviewButton');
                }



                /* ADD EXIT BUTTON
                 ***********************/
                var exitButton = videojs.getComponent('Button');

                // Extend default
                var exitContentButton = videojs.extend(exitButton, {
                    constructor: function () {
                        exitButton.apply(this, arguments);
                        this.addClass('icon-vidoejs-exit');
                        this.controlText('Exit');

                    },

                    handleClick: function () {
                        player.dispose();
                        window.history.back();
                    },
                });

                // Register the new component
                videojs.registerComponent('exitContentButton', exitContentButton);
                player.addChild('exitContentButton', {}, 1);

                let series_suggestions = document.getElementsByClassName('icon-vidoejs-exit')[0];

                player.on('useractive', function () {
                    series_suggestions.setAttribute('style', 'display: block;');
                });
                player.on('userinactive', function () {
                    series_suggestions.setAttribute('style', 'display: none;');
                });



                /* Add Caption
                 ***********************/

                if (data.subtitle !== null) {
                    for (var index = 0; index < data.subtitle.length; index++) {
                        var track =
                            ({
                                kind: 'translation',
                                label: data.subtitle[index].subtitle_language,
                                src: data.cdn.cdn_subtitle + data.subtitle[index].subtitle_name,
                            });
                        player.addRemoteTextTrack(track);
                        document.querySelector('.vjs-texttrack-settings ').firstChild.remove();
                    }
                }


                /* Catch error
                 ***********************/

                player.on('error', function () {
                    swal({
                        icon: 'error',
                        title: 'There was a problem playing the video, we will fix it soon',
                        dangerMode: true,
                        button: 'Back',
                    }).then(() => {
                        window.history.back();
                        videojs.dispose();
                    });
                });
            });
        },


        /**
         * 
         * @param {*} state 
         * @param {*} link 
         */
        SET_TV(state, link) {
            videojs('my-player', {
                controls: true,
                autoplay: true,
                remainingTimeDisplay: false,
                techOrder: ['html5', 'flash', 'hlsjs'],
                controlBar: {
                    volumePanel: {
                        inline: false
                    }
                },
                flash: {
                    hls: {
                        withCredentials: false
                    }
                },
                html5: {
                    hls: {
                        withCredentials: false
                    }
                }
            }, function () {
                let player = this;

                /* Add ources via updateSrc method
                 ************************************/

                player.src({
                    src: link.link,
                    type: 'application/x-mpegURL',
                });

                /* ADD BUTTON REPORT SHOW
                 ***********************/
                player.getChild('controlBar').removeChild('reportButton', {}, 1);
                var reButton = videojs.getComponent('Button');

                // Extend default
                var reportButton = videojs.extend(reButton, {
                    constructor: function () {
                        reButton.apply(this, arguments);
                        this.addClass('icon-vidoejs-report');
                        this.controlText('List');
                    },

                    handleClick: function () {
                        if (document.exitFullscreen) {
                            document.exitFullscreen();
                        } else if (document.webkitExitFullscreen) {
                            document.webkitExitFullscreen();
                        } else if (document.mozCancelFullScreen) {
                            document.mozCancelFullScreen();
                        } else if (document.msExitFullscreen) {
                            document.msExitFullscreen();
                        }
                        player.pause();
                        state.show_report = true;
                    },
                });

                // Register the new component
                videojs.registerComponent('reportButton', reportButton);
                player.getChild('controlBar').addChild('reportButton', {}, 9);



                /* ADD EXIT BUTTON
                 ***********************/
                var exitButton = videojs.getComponent('Button');

                // Extend default
                var exitContentButton = videojs.extend(exitButton, {
                    constructor: function () {
                        exitButton.apply(this, arguments);
                        this.addClass('icon-vidoejs-exit');
                        this.controlText('List');

                    },

                    handleClick: function () {
                        player.dispose();
                        window.history.back();
                    },
                });

                // Register the new component
                videojs.registerComponent('exitContentButton', exitContentButton);
                player.addChild('exitContentButton', {}, 1);

                let series_suggestions = document.getElementsByClassName('icon-vidoejs-exit')[0];

                player.on('useractive', function () {
                    series_suggestions.setAttribute('style', 'display: block;');
                });
                player.on('userinactive', function () {
                    series_suggestions.setAttribute('style', 'display: none;');
                });


                /* Catch error
                 ***********************/

                player.on('error', function (e) {
                    swal({
                        icon: 'error',
                        title: 'There is problem in this episode, we will fix it soon.',
                        dangerMode: true,
                        button: 'Back',
                    }).then(() => {
                        window.history.back();
                        videojs.dispose();
                    });
                });
            });
        },


        CLOSE_REPORT(state) {
            state.show_report = false;
        }
    }
};
export default module;