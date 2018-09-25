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

        <h5 class="title p-2">Series</h5>
        <div class="m-2" id="manage-panel">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <router-link class="nav-link" :to="{name: 'series-manage'}">Manage</router-link>
                </li>
                <li class="nav-item">
                    <router-link class="nav-link" :to="{name: 'series-api'}">Api</router-link>
                </li>
                <li class="nav-item">
                    <router-link class="nav-link" :to="{name: 'series-custom'}">Custom</router-link>
                </li>
                <li class="col-lg-3 col-sm-4 col-12 offset-lg-6 offset-sm-3">
                    <div class="form-inline my-lg-0" id="search">
                        <input class="form-control mr-sm-2" type="text" v-model="query" placeholder="Search" @keyup="SEARCH">
                    </div>
                </li>
            </ul>
        </div>

        <!-- END Control Panel -->

        <div class="text-center" v-if="data.series === null">
            <h4>No result were found</h4>
        </div>

        <div v-else>

            <div class="m-2" v-if="Object.keys(data_search).length  > 0  && data_search.series !== null ">
                <div class="table table-responsive-sm table-hover">
                    <thead>
                        <th>Cover</th>
                        <th>Name</th>
                        <th>Year</th>
                        <th>Created date</th>
                        <th>Updated date</th>
                        <th>Control</th>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in data_search.series" :key="index">
                            <td>
                                <img :src="data.cdn.cdn_poster+item.poster" :alt="item.name" width="40">
                            </td>
                            <td>{{item.name}}</td>
                            <td>{{item.year}}</td>
                            <td>{{item.created_at}}</td>
                            <td>{{item.updated_at}}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button class="btn btn-sm btn-secondary btn-sm mr-2" @click="ADD_TO_TOP(item.id,index)" v-if="item.series_id !== item.id"
                                        :id="item.id">
                                        Top
                                    </button>
                                    <router-link class="btn btn-sm btn-outline-primary btn-sm mr-2" :to="{name:'series_edit', params: {id:item.id}}" role="button">Edit
                                    </router-link>
                                    <router-link class="btn btn-sm btn-outline-primary btn-sm mr-2" :to="{name:'series_manage_id', params: {id:item.id}}" role="button">Show
                                    </router-link>
                                    <button v-if="!button_loading" class="btn btn-sm btn-outline-danger btn-sm mr-2" @click="DELETE(item.id,index)" :id="item.id">Delete</button>
                                    <button v-if="button_loading === item.id" class="btn btn-sm btn-outline-danger btn-sm mr-2" disabled>
                                        <i class="fa fa-circle-o-notch fa-spin"></i> Loading</button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </div>
            </div>

            <!-- END Default Table -->

            <div class="m-2" v-if="data_search.series === null || Object.keys(data_search).length === 0">
                <div class="table table-responsive table-hover">
                    <thead>
                        <th>Cover</th>
                        <th>Name</th>
                        <th>Year</th>
                        <th>Created date</th>
                        <th>Updated date</th>
                        <th>Control</th>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in data.series.data" :key="index">
                            <td>
                                <img :src="data.cdn.cdn_poster+item.poster" :alt="item.name" width="40">
                            </td>
                            <td>{{item.name}}</td>
                            <td>{{item.year}}</td>
                            <td>{{item.created_at}}</td>
                            <td>{{item.updated_at}}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button class="btn btn-sm btn-secondary btn-sm mr-2" @click="ADD_TO_TOP(item.id,index)" v-if="item.series_id !== item.id"
                                        :id="item.id">
                                        Top
                                    </button>
                                    <router-link class="btn btn-sm btn-outline-primary btn-sm mr-2" :to="{name:'series_edit', params: {id:item.id}}" role="button">Edit
                                    </router-link>
                                    <router-link class="btn btn-sm btn-outline-primary btn-sm mr-2" :to="{name:'series_manage_id', params: {id:item.id}}" role="button">Show
                                    </router-link>
                                    <button v-if="!button_loading" class="btn btn-sm btn-outline-danger btn-sm mr-2" @click="DELETE(item.id,index)" :id="item.id">Delete</button>
                                    <button v-if="button_loading === item.id" class="btn btn-sm btn-outline-danger btn-sm mr-2" disabled>
                                        <i class="fa fa-circle-o-notch fa-spin"></i> Loading</button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </div>
            </div>


            <nav aria-label="pagination" v-if="data_search.series === null || Object.keys(data_search).length === 0">
                <ul class="pagination justify-content-center">
                    <li class="page-item" id="end">
                        <a class="page-link" @click="PAGINATION('/api/admin/get/series')">Begin</a>
                    </li>
                    <li class="page-item" id="prev" :class="{disabled: data.series.prev_page_url === null}">
                        <a class="page-link" @click="PAGINATION(data.series.prev_page_url)">Previous</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link">{{data.series.current_page}}/{{data.series.last_page}}</a>
                    </li>
                    <li class="page-item" id="next" :class="{disabled: data.series.next_page_url === null}">
                        <a class="page-link" @click="PAGINATION(data.series.next_page_url)">Next</a>
                    </li>
                    <li class="page-item" id="end">
                        <a class="page-link" @click="PAGINATION('/api/admin/get/series?page='+data.series.last_page)">End</a>
                    </li>
                </ul>
            </nav>

            <!-- END Pagination  -->

        </div>
    </div>
</template>

<script>
import { mapState } from "vuex";
export default {
  data() {
    return {
      query: ""
    };
  },

  computed: mapState({
    data: state => state.series.data,
    data_search: state => state.series.data_search,
    button_loading: state => state.series.button_loading,
    spinner_loading: state => state.series.spinner_loading
  }),

  created() {
    this.$store.dispatch("GET_ALL_SERIES");
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
          this.$store.dispatch("DELETE_SERIES", {
            id,
            key
          });
        }
      });
    },

    PAGINATION(path_url) {
      this.$store.dispatch("GET_SERIES_PAGINATION", path_url);
    },

    SEARCH() {
      if (this.query.length > 0) {
        this.$store.dispatch("GET_SERIES_SEARCH", this.query);
      } else {
        this.data_search.series = null;
      }
    },

    ADD_TO_TOP(id, key) {
      this.$store.dispatch("ADD_SERIES_TO_TOP", {
        id,
        key
      });
    }
  }
};
</script>