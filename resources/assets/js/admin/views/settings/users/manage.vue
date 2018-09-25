<style scope>

</style>
<template>
    <div>
        <div class="col-12 my-3 p-2">
            <div class="group-btn">
                <router-link role="button" class="btn btn-sm btn-outline-primary" :to="{name: 'admins-user-create'}">Create new account</router-link>
            </div>
        </div>
        <div v-if="data.admins !== null">
            <hr>
            <div class="table-responisve m-2" id="users-manage">
                <div class="table table-hover">
                    <thead>
                        <th>#ID</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Permission</th>
                        <th>Created</th>
                        <th>Updated</th>
                        <th>Control</th>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in data.admins" :key="index">
                            <td>{{item.id}}</td>
                            <td>
                                <img :src="data.cdn.cdn_user+item.image" :alt="item.name" width="50" onError="this.onerror=null;this.src='/img/avatar.png';">
                            </td>
                            <td>{{item.name}}</td>
                            <td>
                                {{item.email}}
                            </td>
                            <td v-if="item.role_id === 3">
                                <span class="badge badge-primary">Blocked</span>
                            </td>
                            <td v-if="item.role_id === 1">
                                <span class="badge badge-success">Admin</span>
                            </td>
                            <td v-if="item.role_id === 2">
                                <span class="badge badge-primary">Manager</span>
                            </td>
                            <td>{{item.created_at}}</td>
                            <td>{{item.updated_at}}</td>

                            <td>
                                <div class="btn-group" role="group">
                                    <router-link role="button" class="btn btn-sm btn-outline-primary btn-sm mr-2" :to="{name: 'admins-user-edit',params:{id:item.id}}">Edit</router-link>
                                    <button class="btn btn-sm btn-outline-danger btn-sm mr-2" @click="DELETE(item.id,index)" :id="item.id">
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </div>
            </div>

            <!-- END TABLE -->

        </div>


        <div v-else class="text-center my-5">
            <h1>There is no users found</h1>
        </div>

    </div>
</template>
<script>
import { mapState } from "vuex";
const alertify = require("alertify.js");
export default {
  name: "admins-users-manage",
  data() {
    return {
      items: [],
      report_item: []
    };
  },

  computed: mapState({
    data: state => state.admins.data,
    invoices: state => state.admins.invoices,
    data_search: state => state.admins.data_search,
    button_loading: state => state.admins.button_loading,
    spinner_loading: state => state.admins.spinner_loading,
    invoice_spinner_loading: state => state.admins.invoice_spinner_loading
  }),

  created() {
    this.$store.dispatch("GET_ALL_ADMINS");
  },

  methods: {
    DELETE(id, key) {
      swal({
        title: "Are you sure to delete ?",
        icon: "warning",
        buttons: true,
        dangerMode: true
      }).then(willDelete => {
        if (willDelete) {
          this.$store.dispatch("DELETE_ADMIN", {
            id,
            key
          });
        }
      });
    }
  }
};
</script>