<template>
    <div>
        <div class="container my-5">
            <div class="row">

                <!-- END LIST -->

                <div class="col-12 col-sm-6 col-lg-6" id="profile-setting">

                    <div id="profile-details">
                        <div class="form-group">
                            <div class="col-12">
                                <h4 v-if="data.card_brand === 'Visa'">
                                    <i class="fa fa-cc-visa" aria-hidden="true"></i> ************{{data.card_last_four}}</h4>
                                <hr>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-8 control-label">Name</label>
                            <div class="col-12">
                                <input class="form-control" type="name" name="name" v-validate="'min:6|max:24'" :class="{'input': true, 'input-danger': errors.has('name') }"
                                    v-model="data.name" placeholder="Name">
                                <span v-show="errors.has('name')" class="help is-danger">{{errors.first('name')}}
                                </span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-8 control-label">E-mail</label>
                            <div class="col-12">
                                <input class="form-control" type="email" name="email" v-model="data.email" v-validate="'max:50'" :class="{'input': true, 'input-danger': errors.has('email') }"
                                    placeholder="E-mail">
                                <span v-show="errors.has('email')" class="help is-danger">{{errors.first('email')}}
                                </span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-8 control-label">Password</label>
                            <div class="col-12">
                                <input class="form-control" type="password" name="confirm-field" v-validate="'min:8'" :class="{'input': true, 'input-danger': errors.has('password') }"
                                    v-model="password" placeholder="Password">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-8 control-label">Stripe id</label>
                            <div class="col-12">
                                <input class="form-control" type="text" v-model="data.stripe_id" placeholder="Stripe id">
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-12">
                                <button v-if="! button_loading" class="btn btn-sm btn-outline-primary mt-2" @click="UPDATE">Update</button>
                                <button v-if="button_loading" class="btn btn-sm btn-outline-primary m-2" disabled>
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
const alertify = require("alertify.js");
import { mapState } from "vuex";

export default {
  data() {
    return {
      password: null
    };
  },

  computed: mapState({
    data: state => state.users.data,
    button_loading: state => state.users.button_loading
  }),

  created() {
    this.$store.dispatch("GET_USER_DETAILS", this.$route.params.id);
  },

  methods: {
    UPDATE() {
      this.$validator.validateAll().then(result => {
        if (result) {
          this.$store.dispatch("UPDATE_USER_DETAILS", {
            id: this.data.id,
            name: this.data.name,
            email: this.data.email,
            stripe_id: this.data.stripe_id,
            password: this.password
          });
        }
      });
    }
  }
};
</script>