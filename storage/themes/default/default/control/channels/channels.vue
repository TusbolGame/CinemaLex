<template>
    <div>
        <div class="col-12">
            <div class="row">
                <div class="col-6 col-sm-3 col-lg-2 ml-lg-3 ml-sm-3" v-for="(item,index) in data.channels" :key="index" v-if="$auth.isAuthenticated()">
                    <router-link :to="{name: 'tv-player',params:{id: item.id}}">
                        <div class="tv-logo">
                            <img :src="data.cdn.cdn_poster+item.image" alt="item.name" width="100%">
                        </div>
                    </router-link>
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
        computed: mapState({
            data: state => state.tv.data,
            loading: state => state.tv.loading
        }),
        created() {
            this.$store.dispatch("LOAD_TV_LIST");
        }
    }
</script>