<template>
  <div>
    <span class="exit" @click="$Helper.home()">
      <i class="fa fa-times-circle-o" aria-hidden="true"></i>
    </span>

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
        <div class="col-12 col-lg-12 plan-form">
          <h4>{{$t('setting.change_streaming_plan')}}</h4>
          <hr>
          <div class="col-lg-8 offset-lg-2">
            <div class="row">
              <div class="col-12 col-sm-6 col-md-5 col-lg-5 ml-md-2 ml-lg-2 mt-3 text-center plan-content" @click="plan_id = 1" :class="{active: plan.subscription_plan === 'monthly' && !plan_id, active_choise:plan_id === 1}">
                <h3>{{$t('setting.monthly')}}</h3>
                <h1>{{$t('setting.m_price')}}
                  <small>/{{$t('setting.mo')}}</small>
                </h1>
              </div>
              <div class="col-12 col-sm-6 col-md-5 col-lg-5 ml-md-2 ml-lg-2 mt-3 text-center plan-content" @click="plan_id = 2" :class="{active: plan.subscription_plan === 'yearly' && !plan_id,active_choise:plan_id === 2}">
                <h3>{{$t('setting.annual')}}</h3>
                <h1>{{$t('setting.y_price')}}
                  <small>/{{$t('setting.y')}}</small>
                </h1>
              </div>
              <div class="col-8 col-sm-4 col-lg-4 offset-2 offset-lg-3 offset-sm-4 mt-5">
                <button v-show="!button_loading" v-if="plan_id === 1 || plan_id === 2" class="btn btn-outline-primary w-100" @click="CHANGE_PLAN">{{$t('setting.update')}}</button>
                <button v-show="button_loading" class="btn btn-outline-primary" v-if="button_loading" disabled>
                  <i class="fa fa-circle-o-notch fa-spin"></i> Loading</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
</template>

<script>
  import {
    mapState
  } from "vuex";
  const alertify = require("alertify.js");
  import BounceLoader from 'vue-spinner/src/BounceLoader.vue';
  export default {
    components: {
      BounceLoader
    },
    data() {
      return {
        error: null,
        plan_id: false
      };
    },
    computed: mapState({
      loading: state => state.auth.loading,
      plan: state => state.auth.plan,
      button_loading: state => state.auth.button_loading
    }),
    created() {
      if (this.$auth.isAuthenticated()) {
        this.$store.dispatch("GET_PAYMENT");
      }
    },
    methods: {
      CHANGE_PLAN() {
        if (this.$auth.isAuthenticated()) {
          this.$store.dispatch("CHANGE_PLAN", this.plan_id);
        }
      },
    }
  };
</script>