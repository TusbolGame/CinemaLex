<template>
    <div>
        <div class="container my-5">
            <div class="row">
                <div class="col-12 col-sm-3 col-lg-3">
                    <div class="list-group">
                        <router-link class="list-group-item list-group-item-action" :to="{name: 'profile'}">Profile</router-link>
                        <router-link class="list-group-item list-group-item-action" :to="{name: 'security'}" exact>Security</router-link>
                    </div>
                    <hr>
                    <b class="is-danger" style="cursor:pointer; margin-left:21px;" @click="LOGOUT">Logout</b>
                </div>

                <div class="col-12 col-sm-6 col-lg-4 mt-5" id="profile-setting">

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
                            <span v-show="errors.has('password')" class="help is-danger">{{ errors.first('password')}}</span>
                        </div>
                    </div>
                    <div class="form-group" v-if="success">
                        <div class="col-12">
                            <span class="is-success">Successful change.</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-12">
                            <button class="btn btn-sm btn-outline-primary" @click="changePassword">Update</button>
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
      password: "",
      confirm: "",
      success: ""
    };
  },
  methods: {
    changePassword() {
      this.$validator.validateAll().then(result => {
        if (result) {
          axios
            .post("api/admin/profile/password", {
              password: this.password,
              password_confirmation: this.confirm
            })
            .then(
              res => {
                if (res.data.status === "success") {
                  this.success = true;
                  this.$auth.destroyToken();
                  this.$router.push({
                    name: "login"
                  });
                }
              },
              error => {
                alertify.logPosition("top right");
                alertify.error("There is error in back-end");
              }
            );
        }
      });
    },

    LOGOUT() {
      axios.post("admin/logout").then(res => {
        location.reload();
      });
    }
  }
};
</script>