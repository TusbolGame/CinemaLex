<style>
.default {
  margin-top: 0 !important;
}

.bg-faded {
  position: absolute !important;
  background-color: transparent;
  margin-top: 5px !important;
}

.bg-faded .right {
  position: absolute;
  right: 15px;
}

.section1 {
  width: 100%;
  overflow: hidden;
  color: #fff;
  padding: 0;
}

.section1 .fullscreen-bg {
  position: relative;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  width: 100%;
  overflow: hidden;
}

.section1 .fullscreen-bg__video {
  width: 100%;
}

@media (max-aspect-ratio: 16/9) {
  .section1 .fullscreen-bg__video {
    width: 300% !important;
    left: -100% !important;
  }
}

@media (max-width: 768px) {
  .section1 .ovarlay .text {
    position: relative;
    top: 20% !important;
  }
}

.section1 .fullscreen-bg .ovarlay,
.section2 .ovarlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(18, 18, 18, 0.75);
  background: -moz-linear-gradient(
    left,
    rgba(18, 18, 18, 0.75) 0%,
    rgba(18, 18, 18, 0.75) 100%
  );
  background: -webkit-gradient(
    left top,
    right top,
    color-stop(0%, rgba(18, 18, 18, 0.75)),
    color-stop(100%, rgba(18, 18, 18, 0.75))
  );
  background: -webkit-linear-gradient(
    left,
    rgba(18, 18, 18, 0.75) 0%,
    rgba(18, 18, 18, 0.75) 100%
  );
  background: -o-linear-gradient(
    left,
    rgba(18, 18, 18, 0.75) 0%,
    rgba(18, 18, 18, 0.75) 100%
  );
  background: -ms-linear-gradient(
    left,
    rgba(18, 18, 18, 0.75) 0%,
    rgba(18, 18, 18, 0.75) 100%
  );
  background: linear-gradient(
    to right,
    rgba(18, 18, 18, 0.75) 0%,
    rgba(18, 18, 18, 0.75) 100%
  );
  filter: progid:DXImageTransform.Microsoft.gradient(
      startColorstr="#121212",
      endColorstr="#121212",
      GradientType=1
    );
}

.section1 .ovarlay .text {
  position: relative;
  top: 30%;
}

.section1 .ovarlay .text h1 {
  font-size: 70px;
}

.section1 .ovarlay .scroll-down i {
  position: fixed;
  left: 50%;
  bottom: -25;
  font-size: 70px;
  transform: translate(-50%, -50%);
  cursor: pointer;
}

.section2 {
  width: 100%;
  max-height: 100%;
  overflow: hidden;
  color: #fff;
  padding: 0;
}

.section2 .ovarlay .tv {
  position: relative;
  left: 30px;
  top: 10px;
  display: inline-block;
}

.section2 .ovarlay .series {
  position: absolute;
  left: 45%;
  top: 10px;
  display: inline-block;
}

.section2 .ovarlay .movie {
  position: absolute;
  left: 30px;
  top: 52%;
}

.section2 .ovarlay .kids {
  position: absolute;
  left: 59%;
  top: 52%;
  display: inline-block;
}

.section3 {
  position: relative;
  width: 100%;
  height: 100%;
  overflow: hidden;
  color: rgb(43, 43, 43);
}

.section3 #phone-2 {
  -ms-transform: rotate(50deg);
  -webkit-transform: rotate(50deg);
  transform: rotate(50deg);
 
}

.section3 #ipad {
  -ms-transform: rotate(-50deg);

  -webkit-transform: rotate(-50deg);
  transform: rotate(-50deg);

}

.section3 .middle {
  position: absolute;
  left: 50%;
  top: 75%;
  -webkit-transform: translate(-50%, -50%);
  transform: translate(-50%, -50%);
}

#mainFooter {
  background: #000;
  display: flex;
  align-items: center;
  z-index: 8000;
  flex: none;
  width: 100%;
  padding: 10 20;
}

#mainFooter .footer-left {
  width: 126px;
  display: block;
  float: left;
}

.vertical-logo-container {
  width: 126px;
  padding-top: 55px;
  position: relative;
  display: inline-block;
}

.vertical-logo {
  margin: 0;
  padding: 0;
  transition: all 150ms linear;
  max-height: 100%;
  position: absolute;
  top: 0;
  left: 0;
}

#mainFooter .footer-right {
  width: calc(100% - 126px);
  display: block;
  float: right;
  text-align: right;
}

.social-nav {
  margin-bottom: 2.5em;
  list-style: none;
  padding: 0;
  margin: 0;
}

.social-nav li,
.legal-nav li {
  display: inline-block;
  margin-left: 2.8em;
  color: #afafaf;
  transition: all 150ms linear;
  line-height: 1;
  font-size: 1em;
  margin-bottom: 0;
}

.social-nav .icon-bubble {
  width: 2.5em;
  height: 2.5em;
  border-radius: 50%;
  text-align: center;
  margin-left: 1.25em;
  border: none;
  background: none;
  vertical-align: middle;
  position: relative;
  z-index: 1;
  backface-visibility: hidden;
  transition: color 0.3s;
  font-size: 1.25em;
  line-height: 3em;
}

.social-nav .icon-bubble::before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  border-radius: 50%;
  z-index: -1;
  box-shadow: inset 0 0 0 35px #221f1f;
  transform: scale3d(0.9, 0.9, 1);
  transition: box-shadow 0.2s, transform 0.2s;
}

.social-nav .icon-bubble a {
  display: block;
  color: #fff;
  outline: 0;
}

.social-nav li a,
.legal-nav li a {
  color: #8c8c8c !important;
  text-decoration: none;
  padding-bottom: 1em;
}

.hidden {
  display: none !important;
}

.legal-nav li:last-child {
  font-weight: 700;
  border-left: 1px solid #8c8c8c;
  padding-left: 2.8em;
}

.legal-nav li:last-child a {
  color: #d3d3d3;
}

.social-nav li:hover a,
.legal-nav li:hover a {
  color: #03A9F4;
  border-bottom: 1px solid #03A9F4;
}

.legal-nav li:last-child:hover a {
  color: #03A9F4;
}

.social-nav {
  margin-bottom: 2.5em;
}

.social-nav .icon-bubble:hover:before {
  box-shadow: inset 0 0 0 2px #03A9F4;
  transform: scale3d(1, 1, 1);
}

.social-nav .icon-bubble:hover a {
  border-bottom: none;
  color: #03A9F4;
}

@media screen and (max-width: 767px) {
  #mainFooter {
    box-lines: multiple;
    flex-wrap: wrap;
    box-align: start;
    flex-align: start;
    align-items: flex-start;
    padding: 2.2em 2.2em 5rem;
  }
  #mainFooter .footer-left,
  #mainFooter .footer-right {
    width: 50%;
  }
}

@media screen and (max-width: 767px) {
  #mainFooter .social-nav {
    display: none;
  }
  #mainFooter .legal-nav {
    margin-top: -5px;
  }
  #mainFooter .legal-nav li {
    display: block;
    margin: 0 auto;
    text-align: right;
    font-size: 1.25em;
    line-height: 2;
  }
}

@media screen and (max-width: 767px) {
  .legal-nav li:last-child {
    border-left: none;
    padding-left: 0;
  }
}

#mainFooter .fa {
  font-size: 25px;
  position: relative;
  top: 13px;
}
</style>


<template>
  <div>
    <nav class="navbar navbar-toggleable-md navbar-light fixed-top bg-faded m-md-5 m-lg-5 m-xl-5">
      </button>
      <a class="navbar-brand" href="#">
        <img src="/img/logo.png" alt="logo" width="200">
      </a>

      <div class="right my-2 my-lg-0">
        <router-link role="button" class="btn btn-primary" :to="{name: 'login'}">Sign in</router-link>
      </div>
    </nav>

    <!-- END navbar -->

    <div class="row">
      <div class=" col-12 section1">
        <div class="fullscreen-bg">
          <video loop muted autoplay poster="/themes/default/img/video_cover.png" class="fullscreen-bg__video">
            <source src="/themes/default/video/cover.webm" type="video/webm">
            <source src="/themes/default/video/cover.mp4" type="video/mp4">
          </video>

          <div class="ovarlay">

            <div class="text text-center">
              <h1>All Your TV In One Place</h1>
              <h5>Watch thousands of shows and movies, with plans starting at $5.99/month for a limited time.*</h5>
              <router-link role="button" class="btn btn-lg btn-primary mt-3" :to="{name: 'plan'}">JOIN FREE FOR A WEEK</router-link>
            </div>


          </div>
        </div>
      </div>

      <!-- END section1 -->

      <div class="col-12 section2">
        <div class="row">
          <div class="col-12">
            <img src="/themes/default/img/cover_section2.jpg" alt="tv" width="100%">

            <div class="ovarlay">

              <div class="tv">
                <h1>Live Tv</h1>
                <p class="hidden-xs-down">Get access to 50+ Live on deman channel</p>
              </div>


              <div class="series">
                <h1>Season Tv</h1>
                <p class="hidden-xs-down">Discover seasons of popular shows</p>
              </div>

              <div class="movie">
                <h1>Movies</h1>
                <p class="hidden-xs-down">Watch movies all the time</p>
              </div>

              <div class="kids">
                <h1>Kids</h1>
                <p class="hidden-xs-down">Let the kids watch their favorites too</p>
              </div>

            </div>

          </div>
        </div>
      </div>

      <!-- END section2 -->
      <div class="col-12 section3 p-2">
        <div class="row hidden-sm-down">
          <div class="col-md-4 col-lg-4 col-xl-4 text-center">
            <img src="/themes/default/img/phone_2.png" alt="phone_2" id="phone-2" width="70%">
          </div>
          <div class="middle text-center">
            <h1 class="hidden-md-down">
              <strong> All your TV in one place </strong>
            </h1>
           <h3 class="hidden-lg-up">
              <strong> All your TV in one place </strong>
            </h3>
            <router-link role="button" class="btn btn-primary" :to="{name: 'plan'}">START YOUR FREE TRIAL</router-link>
          </div>
          <div class="col-md-4 col-lg-4 col-xl-4 offset-4 text-center">
            <img src="/themes/default/img/ipad.png" alt="ipad" id="ipad" width="100%">
          </div>
        </div>

        <div class="row hidden-md-up">
          <div class="col-6 col-md-4 text-center">
            <img src="/themes/default/img/phone_2.png" alt="phone_2" width="70%">
          </div>
          <div class="col-6 col-md-4 text-center">
            <img src="/themes/default/img/phone_1.png" alt="phone_1" width="70%">

          </div>
          <div class="col-12 col-md-4 col-xs-push mt-3 text-center">
            <img src="/themes/default/img/phone_3.png" alt="phone_3" width="100%">
            <div class="mt-5 mb-3">
              <h1>
                <strong> All your TV in one place </strong>
              </h1>
              <router-link role="button" class="btn btn-primary" :to="{name: 'plan'}">START YOUR FREE TRIAL</router-link>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- END section3 -->

    <div class="col-12 mt-4 p-0">
      <div class="row">
        <footer id="mainFooter">
          <div class="footer-left">
            <a class="vertical-logo-container" href="/">
              <img class="vertical-logo" src="/img/logo.png" width="200px">
            </a>
          </div>
          <div class="footer-right mt-5 mt-sm-0">
            <ul class="social-nav">
              <li class="icon-bubble">
                <a :href="data.social_facebook" target="_blank">
                  <i class="fa fa-facebook"></i>
                  <span class="hidden">{{$t('app_name')}} on Facebook</span>
                </a>
              </li>
              <li class="icon-bubble">
              <a :href="data.social_twitter" target="_blank">
                  <i class="fa fa-twitter"></i>
                  <span class="hidden">{{$t('app_name')}} on Twitter</span>
                </a>
              </li>
              <li class="icon-bubble">
                <a :href="data.social_instagram" target="_blank">
                  <i class="fa fa-instagram"></i>
                  <span class="hidden">{{$t('app_name')}} on Instagram</span>
                </a>
              </li>
            </ul>
            <ul class="legal-nav">
              <li>
                <router-link :to="{name: 'about-us'}">About Us</router-link>
              </li>
              <li>
                <router-link :to="{name: 'terms'}">Terms Of Service</router-link>
              </li>
              <li>
                <router-link :to="{name: 'contact-us'}">Contact Us</router-link>
              </li>
              <li>
                <router-link :to="{name: 'privacy'}">Privacy Policy</router-link>
              </li>

              <li class="mt-md-3">© 2018 {{$t('app_name')}}</li>
            </ul>
          </div>
        </footer>
      </div>
    </div>
  </div>

</template>

<script >
import { mapState } from "vuex";
export default {
  computed: mapState({
    data: state => state.home.footer
  }),
  created() {
    this.$store.dispatch("GET_HOME_FOOTER_DETAILS");
  }
};
</script>
