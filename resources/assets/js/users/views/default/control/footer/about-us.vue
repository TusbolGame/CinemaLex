<template>
    <div>
        <div class="col-4">
            <a class="navbar-brand" href="/">
               <img src="/img/logo.png" alt="logo" width="200">
            </a>
        </div>
        <div class="col-12 p-5">
            <div class="title">
                <h2>About Us</h2>
                <hr>
            </div>
            <div class="body" v-html="data.about">

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
