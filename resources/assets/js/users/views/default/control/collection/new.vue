<style scoped>
  .modal {
    display: block;
    z-index: 100000;
    background-color: rgba(25, 25, 25, 0.9098039215686274);
  }

  .modal-dialog {
    max-width: 700px;
  }

  .modal-body {
    overflow: auto;
    max-height: 100%;
    overflow-x: hidden;
    background-color: #ffffff;
    color: #545454;
  }

  .modal-body .form-control {
    background-color: transparent;
    border: 1px solid #0275d8;
    color: #343434;
  }
</style>

<template>
  <div>
    <div id="collection-modal" class="modal fade show" v-if="show" tabindex="-1" role="dialog" aria-labelledby="exampleModalLiveLabel"
      style="display: block;">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-body p-md-0 p-lg-0">
            <div class="row">
              <div class="col-8 col-sm-4 col-md-5 col-lg-5 p-md-0 p-lg-0">

                <img :src="poster" :alt="name" width="100%">

              </div>

              <!-- END Poster -->

              <div class="col-12 col-sm-8 col-md-7 col-lg-7 mt-2">


                <h3>
                  <strong>{{name}}</strong>
                </h3>

                <div class="col-12 col-sm-12 col-md-12 col-lg-12 mt-4">

                  <label class="text-muted">My Collection</label>
                  <br>

                  <button class="btn btn-outline-primary ml-1" :class="{active: already_collection === item.id}" v-for="(item, index) in collections"
                    :key="index" @click="already_collection = item.id">{{item.name}} </button>

                </div>

                <div class="col-12 col-sm-12 col-md-12 col-lg-12 mt-4">

                  <label class="text-muted">New Collection</label>
                  <br>

                  <input class="form-control" type="text" placeholder="New Collection" v-model="new_collection">

                  <p class="text-danger">{{message}}</p>

                </div>



                <div class="footer col-12 mt-2 mt-sm-4 mt-md-4 mt-lg-4 mt-xl-4  cancel-save">
                  <button v-if="!disable_button" type="button" class="btn btn-outline-success" disabled>Save</button>
                  <button v-else type="button" class="btn btn-outline-success" @click="SAVE">Save</button>
                  <button type="button" class="btn btn-outline-danger pull-right" @click="CANCEL" data-dismiss="modal">Cancel</button>
                </div>

              </div>

            </div>
          </div>
        </div>
      </div>
    </div>


  </div>
</template>

<script>
  import {
    mapState
  } from "vuex";

  export default {
    props: ["id", "poster", "name", "type", "index"],

    data() {
      return {
        show: null,
        new_collection: null,
        already_collection: null,
        already_name: null,
        message: null,
        disable_button: false,
      };
    },

    watch: {
      already_collection() {
        if (this.already_collection !== null && this.already_collection !== "") {
          this.disable_button = true;
        } else {
          this.disable_button = false;

        }
      },
      new_collection() {
        if (this.new_collection !== null && this.new_collection !== "") {
          this.disable_button = true;
        } else {
          if (this.already_collection === null || this.already_collection === "") {
            this.disable_button = false;
          }
        }
      },
      id(val) {
        if (val !== null) {
          this.show = true;
          this.$store.dispatch("GET_ALL_COLLECTION");
        } else {
          this.show = false;
        }
      }
    },

    computed: mapState({
      collections: state => state.collections.collections
    }),

    methods: {

      CANCEL() {
        this.$emit("hideModalCollectionCancel", null);
      },

      SAVE() {

        this.$store.dispatch("ADD_NEW_TO_COLLECTION", {
          id: this.id,
          already_collection: this.already_collection,
          new_collection: this.new_collection,
          type: this.type
        });
        this.$emit("hideModalCollectionSave", null);

      }
    }
  };
</script>