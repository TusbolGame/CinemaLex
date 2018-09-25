<template>
    <div>
        <div class="spinner-load" v-if="spinner_loading">
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

        <!-- END spinner load -->

        <h5 class="title p-2">Live Tv</h5>
        <div class="m-2" id="manage-panel">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <router-link class="nav-link" :to="{name: 'tv-manage'}">Manage</router-link>
                </li>
                <li class="nav-item">
                    <router-link class="nav-link" :to="{name: 'tv-create'}">Create</router-link>
                </li>
            </ul>
        </div>
        <div class="m-2" v-if="data.channels !== null">
            <div class="table table-responsive table-hover">
                <thead>
                    <th>Cover</th>
                    <th>Name</th>
                    <th>Created date</th>
                    <th>Updated date</th>
                    <th>Control</th>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in data.channels" :key="index">
                        <td>
                            <img :src="data.cdn.cdn_poster+item.image" :alt="item.name" width="40">
                        </td>
                        <td>{{item.name}}</td>
                        <td>{{item.created_at}}</td>
                        <td>{{item.updated_at}}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <button class="btn btn-sm btn-outline-danger btn-sm mr-2" @click="DELETE(item.id,index)" :id="item.id">
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </div>
        </div>
        <div v-else class="text-center">
            <h4>No result were found</h4>
        </div>
    </div>
</template>

<script>
const alertify = require("alertify.js");
import { mapState } from "vuex";

export default {
  data() {
    return {};
  },

  computed: mapState({
    data: state => state.channels.data,
    button_loading: state => state.channels.button_loading,
    spinner_loading: state => state.channels.spinner_loading
  }),
  created() {
    this.$store.dispatch("GET_ALL_CHANNELS");
  },
  methods: {
    DELETE(id, key) {
      swal({
        title: "Are you sure to delete ?",
        icon: "warning",
        text: "All videos and subtitles will removed!",
        buttons: true,
        dangerMode: true
      }).then(willDelete => {
        if (willDelete) {
          this.$store.dispatch("DELETE_CHANNEL", {
            id,
            key
          });
        }
      });
    }
  }
};
</script>