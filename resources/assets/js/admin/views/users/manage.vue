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

        <h5 class="title p-2">Users</h5>
        <div class="col-12 row">
            <div class="col-6 my-3 p-2">
                <div class="group-btn">
                    <button class="btn btn-sm btn-outline-primary" @click="ALL">All</button>
                    <button class="btn btn-sm btn-outline-success" @click="ACTIVITY">activity</button>
                    <button class="btn btn-sm btn-outline-danger" @click="INACTIVITY">Inactivity</button>
                </div>
            </div>
            <div class="col-6 mt-4 pull-right">
                <div class="pull-right" id="search">
                    <input class="form-control mr-sm-2" type="text" v-model="search_query" placeholder="Search" @keyup="SEARCH">
                </div>
            </div>
        </div>

        <!-- END Panel -->

        <div class="modal fade" id="CreateUserModal" tabindex="-1" role="dialog" aria-labelledby="CreateUserModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">

                        <div class="spinner-load" v-if="invoice_spinner_loading">
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

                        <!-- END Spinner Load -->

                        <div v-if="invoices !== null && !invoice_spinner_loading">
                            <div class="form-group">
                                <div class="col-12">
                                    <small>Next plan</small>
                                    <h6 v-if="invoices.name === 'Monthly'">{{invoices.name}} for ${{invoices.amount/100}}/mo</h6>
                                    <h6 v-if="invoices.name === 'Yearly'"> {{invoices.name}} for ${{invoices.amount/100}}/y</h6>
                                    <hr>
                                </div>
                            </div>
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Description</th>
                                        <th>Start period</th>
                                        <th>End period</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item,key) in invoices.invoices" :key="key">
                                        <td>Cinemarine service</td>
                                        <td>{{item.start}}</td>
                                        <td>{{item.end}}</td>
                                        <td>${{item.total/100}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal" @click="invoices = null">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- END Modal -->


        <div class="text-center" v-if="data.users === null">
            <h4>No result were found</h4>
        </div>

        <div v-else>
            <div v-if="Object.keys(data_search).length  > 0  && data_search.users !== null ">
                <div class="table-responisve m-2" id="users-manage">
                    <div class="table table-hover">
                        <thead>
                            <th>#ID</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Updated</th>
                            <th>Control</th>
                        </thead>
                        <tbody>
                            <tr v-for="(item, index) in data_search.users" :key="index">
                                <td>{{item.id}}</td>
                                <td>
                                    <img :src="data.cdn.cdn_user+item.image" :alt="item.name" width="50" onError="this.onerror=null;this.src='/img/avatar.png';">
                                </td>
                                <td>{{item.name}}</td>
                                <td>
                                    {{item.email}}
                                </td>
                                <td>
                                    <span class="badge badge-warning" v-if="item.confirmed === 0">Unconfirmed mail</span>
                                    <span class="badge badge-success" v-if="new Date(item.period_end) > new Date()">Activity</span>
                                    <span class="badge badge-danger" v-if="new Date(item.period_end) < new Date()">Inactivity</span>

                                </td>
                                <td>{{item.created_at}}</td>
                                <td>{{item.updated_at}}</td>

                                <td>
                                    <div class="btn-group" role="group">
                                        <router-link role="button" class="btn btn-sm btn-outline-primary btn-sm mr-2" :to="{name: 'user-edit',params:{id:item.id}}">Edit</router-link>
                                        <button class="btn btn-sm btn-outline-danger btn-sm mr-2" @click="DELETE(item.id,index)">Delete</button>
                                        <button class="btn btn-sm btn-outline-danger btn-sm mr-2" data-toggle="modal" data-target="#CreateUserModal" @click="BILLING(item.id)"
                                            v-if="new Date(item.period_end) > new Date()">Billing</button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </div>
                </div>
            </div>


            <div v-if="data_search.users === null || Object.keys(data_search).length === 0">
                <div class="table-responisve m-2" id="users-manage">
                    <div class="table table-hover">
                        <thead>
                            <th>#ID</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Updated</th>
                            <th>Control</th>
                        </thead>
                        <tbody>
                            <tr v-for="(item, index) in data.users.data" :key="index">
                                <td>{{item.id}}</td>
                                <td>
                                    <img :src="data.cdn.cdn_user+item.image" :alt="item.name" width="50" onError="this.onerror=null;this.src='/img/avatar.png';">
                                </td>
                                <td>{{item.name}}</td>
                                <td>
                                    {{item.email}}
                                </td>
                                <td>
                                    <span class="badge badge-warning" v-if="item.confirmed === 0">Unconfirmed mail</span>
                                    <span class="badge badge-success" v-if="new Date(item.period_end) > new Date()">Activity</span>
                                    <span class="badge badge-danger" v-if="new Date(item.period_end) < new Date()">Inactivity</span>
                                </td>
                                <td>{{item.created_at}}</td>
                                <td>{{item.updated_at}}</td>

                                <td>
                                    <div class="btn-group" role="group">
                                        <router-link role="button" class="btn btn-sm btn-outline-primary btn-sm mr-2" :to="{name: 'user-edit',params:{id:item.id}}">Edit</router-link>
                                        <button class="btn btn-sm btn-outline-danger btn-sm mr-2" @click="DELETE(item.id,index)" :id="item.id">Delete</button>
                                        <button class="btn btn-sm btn-outline-danger btn-sm mr-2" data-toggle="modal" data-target="#CreateUserModal" @click="BILLING(item.id)"
                                            v-if="new Date(item.period_end) > new Date()">Billing</button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </div>
                </div>


                <nav aria-label="pagination">
                    <ul class="pagination justify-content-center">
                        <li class="page-item" id="end">
                            <a class="page-link" @click="PAGINATION('/api/admin/get/users')">Begin</a>
                        </li>
                        <li class="page-item" id="prev" :class="{disabled: data.users.prev_page_url === null}">
                            <a class="page-link" @click="PAGINATION(data.users.prev_page_url)">Previous</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link">{{data.users.current_page}}/{{data.users.last_page}}</a>
                        </li>
                        <li class="page-item" id="next" :class="{disabled: data.users.next_page_url === null}">
                            <a class="page-link" @click="PAGINATION(data.users.next_page_url)">Next</a>
                        </li>
                        <li class="page-item" id="end">
                            <a class="page-link" @click="PAGINATION('/api/admin/get/users?page='+data.users.last_page)">End</a>
                        </li>
                    </ul>
                </nav>

                <!-- END PAGINATE -->

            </div>

        </div>


        <!-- END TABLE -->

    </div>

</template>
<script>
import { mapState } from "vuex";
const alertify = require("alertify.js");
export default {
  name: "users-manage",
  data() {
    return {
      date: new Date(),
      search_query: null
    };
  },

  computed: mapState({
    data: state => state.users.data,
    invoices: state => state.users.invoices,
    data_search: state => state.users.data_search,
    button_loading: state => state.users.button_loading,
    spinner_loading: state => state.users.spinner_loading,
    invoice_spinner_loading: state => state.users.invoice_spinner_loading
  }),

  created() {
    this.$store.dispatch("GET_ALL_USERS");
  },

  methods: {
    PAGINATION(path_url) {
      this.$store.dispatch("GET_ALL_USERS_BY_PAGINATION", path_url);
    },

    DELETE(id, key) {
      swal({
        title: "Are you sure to delete ?",
        icon: "warning",
        buttons: true,
        dangerMode: true
      }).then(willDelete => {
        if (willDelete) {
          this.$store.dispatch("DELETE_USER", {
            id,
            key
          });
        }
      });
    },

    ALL() {
      this.$store.dispatch("GET_ALL_USERS");
    },

    INACTIVITY() {
      this.$store.dispatch("GET_INACTIVITY_USERS");
    },

    ACTIVITY() {
      this.$store.dispatch("GET_ACTIVITY_USERS");
    },

    SEARCH() {
      this.$store.dispatch("GET_USERS_SEARCH", this.search_query);
    },

    BILLING(id) {
      this.$store.dispatch("GET_USERS_BILLING", id);
    }
  }
};
</script>