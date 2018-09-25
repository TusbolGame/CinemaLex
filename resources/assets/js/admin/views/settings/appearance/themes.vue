<template>
  <div class="container theme">
    <div class="col-12 my-3 p-2">
      <h5 class="title p-2">Theme Manage</h5>
             <div class="btn-group">
          <router-link role="button" class="btn btn-primary-outline" :to="{name: 'themes'}" >Themes</router-link>
            <router-link role="button" class="btn btn-primary-outline" :to="{name: 'footer'}" >Footer</router-link>
    </div>
      <hr>
      <div class="col-12">
        <div class="row">

          <div class="col-12 col-sm-4 col-lg-4 theme-box" v-for="(item,index) in data" :key="index">
            <img :src="item.thumbnail" :alt="item.name" width="100%">
            <div class="title">
              <b class="p-1">{{item.name}}</b>
            </div>
            <hr>
            <div class="control mt-1">
              <div class="group-btn">
                <button v-if="!button_loading && item.status === 0" @click="SET(item.id)" class="btn btn-sm btn-outline-primary">Set</button>
                <button v-else class="btn btn-sm btn-outline-success" disabled>Active</button>
                <button v-if="button_loading === item.id" class="btn btn-sm btn-outline-primary" disabled>
                  <i class="fa fa-circle-o-notch fa-spin"></i> Loading</button>

                <button v-if="!button_loading && item.name !== 'default' && item.status !== 1 " @click="DELETE(item.id,index)" class="btn btn-sm btn-outline-danger">Delete</button>
                <button v-if="delete_loading === item.id" class="btn btn-sm btn-outline-danger" disabled>
                  <i class="fa fa-circle-o-notch fa-spin"></i> Loading</button>

              </div>
            </div>
          </div>

          <div class="col-12 col-sm-4 col-lg-4">
            <div class="col-12 add-theme-box">
              <input type="file" name="theme-zip" id="theme-zip" class="inputfile" v-validate="'mimes: application/zip, application/octet-stream'"
                @change="UPLOAD">
              <label for="theme-zip">
                <i class="fa fa-plus-circle fa-5x" aria-hidden="true"></i>
              </label>
            </div>
            <span v-show="errors.has('theme-zip')" class=" is-danger">{{ errors.first('theme-zip') }}</span>
          </div>
        </div>
      </div>

    </div>

  </div>
</template>

<script>
const alertify = require("alertify.js");
export default {
  name: "theme",
  data() {
    return {
      data: {},
      button_loading: false,
      delete_loading: false
    };
  },
  mounted() {
    axios.get("api/admin/settings/theme").then(
      res => {
        this.data = res.data;
      },
      error => {
        alertify.logPosition("top right");
        alertify.error("Error no data");
      }
    );
  },
  methods: {
    UPLOAD() {
      alertify.logPosition("top right");
      alertify.log("It will take some time");
      const formData = new FormData();
      const zip = document.getElementById("theme-zip").files[0];
      formData.append("theme-zip", zip);
      this.$validator.validateAll().then(result => {
        axios.post("api/admin/settings/theme/upload", formData).then(
          res => {
            if (res.data.status === "success") {
              alertify.logPosition("top right");
              alertify.success("Successful upload");
              this.data.push(res.data[0]);
            } else {
              alertify.logPosition("top right");
              alertify.error(res.data.message);
            }
          },
          error => {
            alertify.logPosition("top right");
            alertify.error("There is error in back-end");
            this.button_loading = false;
          }
        );
      });
    },
    SET(id) {
      this.button_loading = id;
      axios
        .post("api/admin/settings/theme/set", {
          id: id
        })
        .then(
          res => {
            if (res.data.status === "success") {
              alertify.logPosition("top right");
              alertify.success("Successful change");
              this.data = res.data[0];
              this.button_loading = false;
            } else {
              alertify.logPosition("top right");
              alertify.error(res.data.message);
              this.button_loading = false;
            }
          },
          error => {
            alertify.logPosition("top right");
            alertify.error("There is error in back-end");
            this.button_loading = false;
          }
        );
    },

    DELETE(id, key) {
      this.delete_loading = id;
      axios.delete("api/admin/settings/theme/delete/" + id).then(
        res => {
          if (res.data.status === "success") {
            alertify.logPosition("top right");
            alertify.success("Successful delete");
            this.data.splice(key, 1);
            this.delete_loading = false;
          } else {
            alertify.logPosition("top right");
            alertify.error(res.data.message);
            this.delete_loading = false;
          }
        },
        error => {
          alertify.logPosition("top right");
          alertify.error("There is error in back-end");
          this.delete_loading = false;
        }
      );
    }
  }
};
</script>