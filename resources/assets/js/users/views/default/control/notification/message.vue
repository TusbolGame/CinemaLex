<template>
  <div>
    <div class="col-12 alert-notification" v-if="message === 'confirm_email'">
      <div class="offset-sm-1 offset-lg-1 email-confirmed">
        <i class="fa fa-info-circle mr-2" aria-hidden="true"></i>
        <h3> Confirm Your Email </h3>
        <p>Please check your email,we send to you confirmation code.
          <br> If you recive confirmation email click to
          <a v-if="show" @click="SEND_MAIL" style="color:#0275d8;">send again</a>
          <a style="color:#0275d8;" v-else>Wait..</a>
        </p>
      </div>
    </div>

    <div class="col-12 alert-notification" v-if="message.cancel">
      <div class="offset-sm-1 offset-lg-1 email-confirmed">
        <div class="row">
          <div class="col-3 col-sm-2 col-lg-1">
            <i class="fa fa-info-circle mr-2" aria-hidden="true"></i>
          </div>
          <div class="col-7 col-sm-10 col-lg-10 ">
            <h5>Your membership ends soon. </h5>
            <p class="text-muted">Your last day to stream will be
              <b style="color:#0275d8">{{message.cancel_time}}</b>, If you’ve changed your mind, restart your membership now.</p>
            <div class="btn-group-sm mt-1">
              <button class="btn btn-sm btn-primary ml-2 mt-1" @click="RESUME">RESTART MEMBERSHIP</button>
              <button class="btn btn-sm btn-secondary ml-2 mt-1" @click="message.cancel = null">REMIND ME LATER</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
  const alertify = require('alertify.js');
  export default {
    name: 'message',
    props: {
      message: null,
    },
    data() {
      return {
        show: true,
      }
    },
    methods: {
      SEND_MAIL() {
        this.show = false;
        axios.get("/api/v1/register/sendactivity").then(response => {
          if (response.data.status === "success") {
            this.show = true;
            alertify.logPosition("top right");
            alertify.success(response.data.message);
          }
        });
      },
      RESUME() {
        this.$store.dispatch("RESUME_MEMBERSHIP");
        this.message.cancel = null;
      }
    }
  }
</script>