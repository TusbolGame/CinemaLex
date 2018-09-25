<template>
  <div>
    <span class="exit" @click="$Helper.home()">
      <i class="fa fa-times-circle-o" aria-hidden="true"></i>
    </span>
    <!-- EXIT -->
    <div class="row">
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
      <!-- END LIST -->

      <div class="col-12 col-sm-9 col-lg-9 offset-sm-3 offset-lg-3 p-sm-3 mt-5 profile-edit h-100">
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
        <div class="form-group">
          <div class="col-12">
            <h4 v-if="info.card_brand === 'Visa'">
              <i class="fa fa-cc-visa" aria-hidden="true"></i> ************{{info.card_number}}</h4>
            <hr>
          </div>
        </div>
        <div class="form-group">
          <div class="col-12">
            <label>{{$t('setting.credit_card_or_debit')}}</label>
            <template>
              <card class='stripe-card' :class='{ complete }' :stripe='$Helper.stripe_key()' :options='stripeOptions' @change='complete = $event.complete'
              />
            </template>
          </div>
        </div>
        <small class="is-danger">{{error}}</small>
        <br>
        <div class="form-group">
          <div class="col-12">
            <button v-if="!update_loading" class="btn btn-outline-primary" :disabled='!complete' @click="UPDATE">{{$t('setting.update')}}</button>
            <button v-if="update_loading" class="btn btn-outline-primary" disabled>
              <i class="fa fa-circle-o-notch fa-spin"></i> Loading</button>
          </div>
        </div>
        <hr>
        <div class="form-group">
          <div class="col-12">
            <b>{{$t('setting.cancel_memebrship')}}</b>
            <br>
            <small>{{$t('setting.cancel_note')}}</small>
            <br>
            <button v-show="!button_loading" v-if="payment_update.subscription_status === 'active' && !payment_update.cancel || payment_update.subscription_status === 'trialing' && !payment_update.cancel"
              class="btn btn-sm btn-outline-danger mt-2" @click="CANCEL">{{$t('setting.cancel_memebrship')}}</button>
            <button v-show="!button_loading" v-if="payment_update.cancel  || payment_update.subscription_status === 'canceled' || payment_update.subscription_status === 'unpaid' || payment_update.subscription_status === 'past_due' && !button_loading"
              class="btn btn-sm btn-outline-danger mt-2" @click="RESUME">{{$t('setting.resume_memebrship')}}</button>
            <button class="btn btn-sm btn-outline-danger m-2" v-if="button_loading" disabled>
              <i class="fa fa-circle-o-notch fa-spin"></i> Loading</button>
          </div>
        </div>

      </div>
    </div>
  </div>
  </div>
</template>

<script>
  const alertify = require("alertify.js");
  import {
    mapState
  } from "vuex";
  import {
    Card,
    createToken
  } from 'vue-stripe-elements-plus'
  import BounceLoader from 'vue-spinner/src/BounceLoader.vue';
  export default {
    components: {
      Card,
      BounceLoader
    },
    data() {
      return {
        showModelError: false,
        success: false,
        complete: false,
        number: false,
        expiry: false,
        cvc: false,
        vise_brand: null,
        visa_number: null,
        error: null,
        resume: false,
        update_loading: false,
        stripeOptions: {
          style: {
            base: {
              iconColor: "#8898AA",
              color: "#767676",
              lineHeight: "36px",
              fontWeight: 300,
              fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
              fontSize: "19px",

              "::placeholder": {
                color: "#8898AA"
              }
            },
            invalid: {
              iconColor: "#e85746",
              color: "#e85746"
            }
          },
          classes: {
            focus: "is-focused",
            empty: "is-empty"
          }
        }
      };
    },
    computed: mapState({
      loading: state => state.auth.loading,
      info: state => state.auth.items,
      payment_update: state => state.auth.payment_update,
      button_loading: state => state.auth.button_loading,
    }),
    mounted() {
      if (this.$auth.isAuthenticated()) {
        this.$store.dispatch("GET_PAYMENT");
      }
    },
    methods: {
      UPDATE() {
        // createToken returns a Promise which resolves in a result object with
        // either a token or an error key.
        // See https://stripe.com/docs/api#tokens for the token object.
        // See https://stripe.com/docs/api#errors for the error object.
        // More general https://stripe.com/docs/stripe.js#stripe-create-token.
        createToken().then(data => {
          if (data.token.id !== null) {
            axios
              .post("/api/update/profile/payment/update", {
                token: data.token.id
              })
              .then(
                res => {
                  if (res.data.status === "success") {
                    alertify.logPosition("top right");
                    alertify.success("Successful update");
                    this.update_loading = false;
                  }
                },
                error => {
                  if (error.response.status === 500) {
                    this.error = error.response.data.message;
                    this.update_loading = false;
                  }
                });
          }
        });
      },
      CANCEL() {
        this.$store.dispatch("CANCEL_MEMBERSHIP");
      },
      RESUME() {
        this.$store.dispatch("RESUME_MEMBERSHIP");
      },
    }
  };
</script>