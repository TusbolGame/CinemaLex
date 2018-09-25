
<template>
    <div>
       <div class="col-12 plan" >
      
        <div class="col-1 back mt-2">
         <svg @click="EXIT" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px"
                    viewBox="0 0 489.6 489.6" style="enable-background:new 0 0 489.6 489.6;" xml:space="preserve" width="100%"
                    class="">
                    <g>
                        <g>
                            <g>
                                <path style="fill:#21262B" d="M244.8,489.6c135,0,244.8-109.8,244.8-244.8S379.8,0,244.8,0S0,109.8,0,244.8    S109.8,489.6,244.8,489.6z M244.8,19.8c124.1,0,225,100.9,225,225s-100.9,225-225,225s-225-100.9-225-225S120.7,19.8,244.8,19.8z"
                                    data-original="#2C2F33" class="active-path" data-old_color="#21262b" />
                                <path style="fill:#3C92CA" d="M265.5,326.1c1.9,1.9,4.5,2.9,7,2.9s5.1-1,7-2.9c3.9-3.9,3.9-10.1,0-14l-67.3-67.3l67.3-67.3    c3.9-3.9,3.9-10.1,0-14s-10.1-3.9-14,0l-74.3,74.3c-3.9,3.9-3.9,10.1,0,14L265.5,326.1z"
                                    data-original="#3C92CA" class="" />
                            </g>
                        </g>
                    </g>
                </svg>
            </div>
            </div>

            <!-- END back -->    

            <div class="p-2 p-sm-3 p-md-4 p-lg-5 pt-sm-5">
              <div class="col-12 col-lg-8 offset-lg-2 plan-form">
              <h5>STEP <b style="color:#3498db;">2</b> OF 3</h5>
              <h3>Just two more steps and you're done!</h3>
             <div class=" col-lg-10 offset-lg-1 ">
            <div class="form-group">
                <label class="col-8 control-label">Name</label>
                <div class="col-12">
                    <input class="form-control" type="name" name="name" v-validate="'required|alpha_spaces|min:6|max:24'"
                           :class="{'input': true, 'input-danger': errors.has('name') }" v-model="name"
                           placeholder="Name">
                    <span v-show="errors.has('name')" class="help is-danger">{{errors.first('name')}}</span>
                </div>
            </div>

            <div class="form-group">
                <label class="col-8 control-label">E-mail</label>
                <div class="col-12">
                    <input class="form-control" type="email" name="email" v-validate="'required|email|max:50'"
                           :class="{'input': true, 'input-danger': errors.has('email') }" v-model="email"
                           placeholder="E-mail">
                    <span v-show="errors.has('email')" class="help is-danger">{{errors.first('email')}}</span>
                </div>
            </div>

            <div class="form-group">
                <label class="col-8 control-label">Password</label>
                <div class="col-12">
                    <input class="form-control" type="password" name="confirm-field" v-validate="'min:8|required'"
                           :class="{'input': true, 'input-danger': errors.has('password') }" v-model="password"
                           placeholder="Password">
                </div>
            </div>

            <div class="form-group">
                <label class="col-8 control-label">Re-enter password</label>
                <div class="col-12">
                    <input class="form-control" type="password" name="password"
                           v-validate="'min:8|required|confirmed:confirm-field'"
                           :class="{'input': true, 'input-danger': errors.has('password') }" v-model="confirm"
                           placeholder="Re-enter password" data-vv-as="password">
                    <span v-show="errors.has('password')" class="help is-danger">{{ errors.first('password') }}</span>
                </div>
            </div>
            <div class="form-group">
                <div class="col-12">
                    <span v-show="msgShow" class="help is-danger">{{msg}}</span>
                </div>
            </div>
            <div class="form-group">
               <div class="col-12">
                <p>By clicking on Sign up, you agree to <router-link :to="{name: 'terms'}" style="color:#3498db;">Terms Of Service</router-link> and <router-link :to="{name: 'privacy'}" style="color:#3498db;">Privacy Policy</router-link></p>
               </div>
            </div>
            <div class="form-group">
                <div class="col-12">
                    <button v-if="!button_loading" class="btn btn-outline-primary" @click="NEXT" >SIGN UP</button>
                    <button v-if="button_loading" class="btn btn-outline-primary" disabled><i class="fa fa-circle-o-notch fa-spin"></i> Loading</button>
            </div>
            </div>
        </div>
    </div>
    </div>
    </div>
 </div>

</template>
<script>
    export default {
        name:'register',
        data() {
            return {
                name: '',
                email: '',
                password: '',
                confirm: '',
                msgShow: false,
                msg: '',
                button_loading:false,
            };
        },
        methods: {
            NEXT() {
                this.$validator.validateAll().then((result) => {
                    if (result) {
                 //   this.button_loading = true;
                      axios.post('/api/new/register', {
                            name: this.name,
                            email: this.email,
                            password: this.password,
                            password_confirmation: this.confirm,
                        }).then((response) => {
                                if (response.status === 200) {
                                    // Login
                                    var data = {
                                        grant_type: 'password',
                                        client_id: 2,
                                        client_secret: this.$Helper.client_secret(),
                                        username: this.email,
                                        password: this.password,
                                        scope: '',
                                    };
                                    axios.post('/oauth/token', data).then(res => {
                                               this.$auth.setToken(res.data.access_token, res.data.expires_in,response.data.username,response.data.image);
                                               this.button_loading = false;
                                               window.location.href = '/signup/payment'
                                        });
                                }
                             }, error => {
                                    this.loading = false;
                                    this.msgShow = true;
                                    this.msg = error.response.data.msg;
                            })
                    }
                });
            },
            EXIT(){
                localStorage.removeItem('_plan')
                this.$router.go(-1);
            }
        }
    }
</script>