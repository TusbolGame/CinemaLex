<template>
    <div>
        <transition name="fade">
            <div class="row">
                <span class="exit" @click="$Helper.home()">
                    <i class="fa fa-times-circle-o" aria-hidden="true"></i>
                </span>
                <!-- EXIT -->
                <div class="col-12 col-sm-3 col-lg-3  profile-menu">
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


<!-- Modal -->
<div class="modal fade" id="checkModal" tabindex="-1" role="dialog" aria-labelledby="checkModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="checkModalLabel">Please Re-enter Your Password</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
             <div class="form-group">
                        <label class="col-8 control-label">
                         <p>For your security, you must re-enter your password to continue</p>
                            {{$t('setting.password')}}
                        </label>
                        <div class="col-12">
                            <input class="form-control" type="password" name="current" v-validate="'min:8|required'" :class="{'input': true, 'input-danger': errors.has('current') }"
                                v-model="current_password" :placeholder="$t('setting.password')">
                        </div>
                    </div>
                                                <div class="form-group" v-if="error !== null">
                                <div class="col-12">
                                    <span class="is-danger">{{error}}</span>
                                </div>
                            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" @click="UPDATE_DETAILS">Submit</button>
      </div>
    </div>
  </div>
</div>

                <!-- END LIST -->

                <div class="col-12 col-sm-9 col-lg-9 offset-sm-3 offset-lg-3 p-sm-3 mt-5 profile-edit h-100">
                    <div class="col-sm-10 col-lg-8 offset-sm-1 offset-lg-2">
                        <div id="avatar-img">
                            <img :src="avatar_image" alt="avatar-profile" width="100" onError="this.onerror=null;this.src='/img/avatar.png';">

                            <label for="avatar-img-file">
                                <i class="fa fa-pencil" aria-hidden="true">{{$t('setting.update_profile_picture')}}</i>
                            </label>
                            <input type="file" id="avatar-img-file" name="avatar" class="inputfile" @change="UPDATE_IMAGE" v-validate="'mimes:image/jpeg,image/png'">
                        </div>
                        <div class="text-center">
                            <span v-show="errors.has('avatar')" class="is-danger">{{ errors.first('avatar')}}</span>
                        </div>
                        <!-- END avatar-img -->

                        <div id="profile-details">

                            <div class="form-group">
                                <label class="col-8 control-label">{{$t('setting.name')}}</label>
                                <div class="col-12">
                                    <input class="form-control" type="name" name="name" v-validate="'required|min:6|max:24'" :class="{'input': true, 'input-danger': errors.has('name') }"
                                        v-model="name" :placeholder="$t('setting.name')">
                                    <span v-show="errors.has('name')" class="help is-danger">{{errors.first('name')}}
                                    </span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-8 control-label">{{$t('setting.mail')}}</label>
                                <div class="col-12">
                                    <input class="form-control" type="email" name="email" v-model="email" v-validate="'required|max:50'" :class="{'input': true, 'input-danger': errors.has('email') }"
                                        :placeholder="$t('setting.mail')">
                                    <span v-show="errors.has('email')" class="help is-danger">{{errors.first('email')}}
                                    </span>
                                </div>
                            </div>
                            <div class="form-group" v-if="success !== null">
                                <div class="col-12">
                                    <span class="is-success">{{success}}</span>
                                </div>
                            </div>
                            <div class="form-group" v-if="error !== null">
                                <div class="col-12">
                                    <span class="is-danger">{{error}}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-12">
                                    <button class="btn btn-outline-primary" data-toggle="modal" data-target="#checkModal">{{$t('setting.update')}}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
    import BounceLoader from 'vue-spinner/src/BounceLoader.vue';
    export default {
        data() {
            return {
                name: null,
                email: null,
                current_password: null,
                avatar_image: "",
                success: null,
                error: null,
                show_modal: false,
            };
        },
        created() {
            if (this.$auth.isAuthenticated()) {
                this.avatar_image = this.$auth.getUserInfo('image');
                axios.get("/api/user/").then(res => {
                    this.name = res.data.name;
                    this.email = res.data.email;
                });
            }
        },
        methods: {
            UPDATE_IMAGE() {
                const formData = new FormData();
                const image = document.getElementById("avatar-img-file");
                formData.append("image", image.files[0]);
                axios.post("/api/update/profile/image", formData).then(response => {
                    if (response.data.status === "success") {
                        this.avatar_image = response.data.url + response.data.image;
                        this.$auth.setUpdate(null,response.data.url+response.data.image, null);
                    }
                });
            },
            UPDATE_DETAILS() {
                this.$validator.validateAll().then(result => {
                    if (result) {
                        axios.post("/api/update/profile/details", {
                                'current-password': this.current_password,
                                name: this.name,
                                email: this.email
                            })
                            .then(response => {
                                if (response.status === 200) {
                                    localStorage.setItem("name", this.name);
                                    this.success = response.data.message;
                                    this.error= null;

                                    // close modal
                                    document.getElementsByClassName('close')[0].click();
                                }
                            }, error => {
                                    this.error = error.response.data.message;
                                    this.success = null;
                            });
                    }
                });
            },
        }
    };
</script>