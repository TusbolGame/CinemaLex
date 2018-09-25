<template>
    <div class="container theme">
        <div class="col-12 my-3 p-2">
            <h5 class="title p-2">Footer Manage</h5>
            <div class="btn-group">
                <router-link role="button" class="btn btn-primary-outline" :to="{name: 'themes'}">Themes</router-link>
                <router-link role="button" class="btn btn-primary-outline" :to="{name: 'footer'}">Footer</router-link>
            </div>
            <hr>
            <div class="col-12 col-md-8">
                <div class="form-group">
                    <label for="brandname">Social Facebook</label>
                    <input type="text" class="form-control" placeholder="Social Facebook" v-model="facebook">
                </div>

                <div class="form-group">
                    <label for="geneticname">Social Instagram</label>
                    <input type="text" class="form-control" placeholder="Social Instagram" v-model="instagram">
                </div>

                <div class="form-group">
                    <label for="geneticname">Social Twitter</label>
                    <input type="text" class="form-control" placeholder="Social Twitter" v-model="twitter">
                </div>

                <div class="form-group">
                    <label for="description">About Us</label>
                    <vue-editor id="about" v-model="about"></vue-editor>
                </div>
                <div class="form-group">
                    <label for="description">Terms Of Service </label>
                    <vue-editor id="terms"  v-model="terms"></vue-editor>
                </div>
                <div class="form-group">
                    <label for="description">Privacy Policy </label>
                    <vue-editor id="privacy"  v-model="privacy"></vue-editor>
                </div>

                <div class="form-group">
                    <button v-if="!button_loading" @click="UPDATE" class="btn btn-sm btn-outline-primary">Update</button>
                    <button v-if="button_loading" class="btn btn-sm btn-outline-danger" disabled> </button>
                </div>

            </div>
        </div>

    </div>
</template>

<script>
const alertify = require("alertify.js");
import { VueEditor } from "vue2-editor";
export default {
  name: "footer",
  data() {
    return {
      facebook: "",
      instagram: "",
      twitter: "",
      about: "",
      terms: "",
      privacy: "",
      button_loading: false,
      delete_loading: false
    };
  },
  components: {
    VueEditor
  },
  mounted() {
    axios.get("/api/admin/get/settings/appearance/footer").then(resposne => {
      if (resposne.data.data !== null) {
        this.facebook = resposne.data.data.social_facebook;
        this.instagram = resposne.data.data.social_twitter;
        this.twitter = resposne.data.data.social_instagram;
        this.about = resposne.data.data.about;
        this.terms = resposne.data.data.terms;
        this.privacy = resposne.data.data.privacy;
      }
    });
  },
  methods: {
    UPDATE() {
      this.button_loading = true;
      axios
        .post("/api/admin/update/settings/appearance/footer", {
          facebook: this.facebook,
          instagram: this.instagram,
          twitter: this.twitter,
          about: this.about,
          terms: this.terms,
          privacy: this.privacy
        })
        .then(
          response => {
            if (response.status === 200) {
              alertify.logPosition("top right");
              alertify.success(response.data.message);
              this.button_loading = false;
            }
          },
          error => {
            alertify.logPosition("top right");
            alertify.error(error.response.data.message);
            this.button_loading = false;
          }
        );
    }
  }
};
</script>