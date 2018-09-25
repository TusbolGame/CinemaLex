<template>
  <div>

        <div class="login p-1 p-sm-2 p-md-5 p-lg-5 p-xl-5">
      <div class="col-12 col-sm-8 col-md-8 col-lg-8 offset-sm-2 offset-lg-2 p-xs-5 login-form">
        <div class="row">
          <div class="hidden-sm-down col-md-6 col-lg-6 side-cover">
            <img src="/themes/default/img/background.jpg" alt="cover" class="hidden-lg-down" width="110%">
            <img class="hidden-xl-up" src="/themes/default/img/background.jpg" alt="cover" width="160%">
          </div>

          <div class="col-12 col-sm-12 col-md-6 col-lg-6 p-lg-5 pt-sm-5 p-3">
              <h3 class="ml-3"><strong>Rest your password</strong></h3>
              <div class="form-group">
                <label class="col-8 control-label">Password</label>
                <div class="col-12">
                  <input class="form-control" type="password" name="confirm-field" v-validate="'min:8|required'" :class="{'input': true, 'input-danger': errors.has('password') }"
                    v-model="password" placeholder="Password">
                </div>
              </div>

              <div class="form-group">
                <label class="col-8 control-label">Confirm-Password</label>
                <div class="col-12">
                  <input class="form-control" type="password" name="password" v-validate="'min:8|required|confirmed:confirm-field'" :class="{'input': true, 'input-danger': errors.has('password') }"
                    v-model="confirm" placeholder="Confirm password" data-vv-as="password">
                  <span v-show="errors.has('password')" class="help is-danger">{{ errors.first('password') }}
                  </span>
                </div>
              </div>
              <div class="form-group">
                <div class="col-12">
                  <button v-if="!button_loading" class="btn btn-outline-primary" @click="changePassword">Continue</button>
                  <button v-if="button_loading" class="btn btn-outline-primary" disabled>
                    <i class="fa fa-circle-o-notch fa-spin"></i> Loading</button>
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
    data() {
      return {
        email: "",
        password: "",
        confirm: "",
        button_loading: false,
      };
    },

    created() {
      this.$store.dispatch('CHECK_FORGET_CODE', this.$route.params.code);
    },

    methods: {
      changePassword() {
        this.$validator.validateAll().then(result => {
          if (result) {
            this.button_loading = true;
            this.$store.dispatch('CHANGE_FORGET_PASSWORD', {
              code: this.$route.params.code,
              password: this.password,
              password_confirmation: this.confirm
            })
          }
        });
      }
    }
  };
</script>