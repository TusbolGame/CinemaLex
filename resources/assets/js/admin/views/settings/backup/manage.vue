<template>
    <div>
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

        <div class="col-12 my-3 p-2">
            <div class="group-btn">
                <button class="btn btn-sm btn-outline-primary" @click="MAKE_BACKUP(0)">Create file backup</button>
                <button class="btn btn-sm btn-outline-primary" @click="MAKE_BACKUP(1)">Create database backup</button>
            </div>
        </div>

        <div v-if="empty">
            <div class="table-responisve m-2" id="users-manage">
                <div class="table table-hover">
                    <thead>
                        <th>Name</th>
                        <th>Control</th>
                    </thead>
                    <tbody>
                        <tr v-for="(item,index) in items" :key="index">
                            <td>{{item}}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a class="btn btn-sm btn-outline-primary btn-sm mr-2" role="button" :href="url+item" target="_blank">Download</a>
                                    <button class="btn btn-sm btn-outline-danger btn-sm mr-2" @click="DELETE(item.id,index)" :id="item.id">
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </div>
            </div>
        </div>
        <div v-else>
            <div class="text-center my-5">
                <h1>There is no backup found</h1>
            </div>
        </div>
    </div>
</template>
<script>
import BounceLoader from "vue-spinner/src/BounceLoader.vue";
const alertify = require("alertify.js");
export default {
  name: "backup",
  data() {
    return {
      items: [],
      url: "",
      empty: true,
      loading: false,
      error: false
    };
  },

  mounted() {
    this.loading = true;
    axios.get("api/admin/get/settings/backup").then(
      res => {
        if (res.data.status === "success") {
          this.empty = true;
          this.items = res.data[0];
          this.url = res.data.url;
          this.loading = false;
        } else if (res.data.status === "empty") {
          this.empty = false;
          this.loading = false;
        }
      },
      error => {
        if (error.response.status === 401) {
          this.error = 401;
          this.loading = false;
        }
      }
    );
  },
  methods: {
    MAKE_BACKUP(id) {
      swal({
        title: "This will take time 10-30m",
        icon: "warning",
        buttons: true,
        dangerMode: true
      }).then(willDelete => {
        if (willDelete) {
          this.loading = true;
          axios
            .post("/api/admin/new/settings/backup", {
              id: id
            })
            .then(
              res => {
                if (res.data.status === "success") {
                  this.items.splice(key, 1);
                  alertify.logPosition("top right");
                  alertify.success("Successful Create");
                  this.loading = false;
                } else {
                  alertify.logPosition("top right");
                  alertify.error(res.data.message);
                  this.loading = false;
                }
              },
              error => {
                alertify.logPosition("top right");
                alertify.error("There is error in Back-end.");
                this.loading = false;
              }
            );
        }
      });
    },
    DOWNLOAD(name) {
      axios.post("/api/admin/download/settings/backup/download", {
        name: name
      });
    },

    DELETE(id, key) {
      axios
        .post("/api/admin/delete/settings/backup", {
          name: this.items[key]
        })
        .then(
          res => {
            if (res.data.status === "success") {
              this.items.splice(key, 1);
              alertify.logPosition("top right");
              alertify.success("Successful Delete");
              this.loading = false;
            } else {
              alertify.logPosition("top right");
              alertify.error(res.data.message);
              this.loading = false;
            }
          },
          error => {
            alertify.logPosition("top right");
            alertify.error("There is error in Back-end.");
            this.loading = false;
          }
        );
    }
  }
};
</script>