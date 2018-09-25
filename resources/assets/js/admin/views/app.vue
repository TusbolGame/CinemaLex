<template>
<div>

<div class="row">

    <div class="col-sm-1 col-sm-1 hidden-xs-down" id="side-navbar">
        <div class="col-12 col-lg-8 col-sm-12" id="content">
            <ul class="list-unstyled text-center" id="list-menu">
               <li><a href="/administrator#/" ><i class="fa fa-tachometer" aria-hidden="true"></i> </a></li>
                <li><a href="/administrator#/movies/manage"><i class="fa fa-film fa-2x" aria-hidden="true"></i></a></li>
                <li><a href="/administrator#/series/manage"><i class="fa fa-television fa-2x" aria-hidden="true"></i></a></li>
                <li><a href="/administrator#/tv/manage"><img src="/img/live.svg" alt="actor" style="margin:13px;" width="25"></a></li>
                <li><a href="/administrator#/top/manage"><i class="fa fa-star fa-2x" aria-hidden="true"></i></a></li>
                <li><a href="/administrator#/actors/manage"><img src="/img/actor.svg" alt="actor" style="margin:13px;" width="25"></a></li>
                <li><a href="/administrator#/report/manage"><i class="fa fa-flag fa-2x" aria-hidden="true"></i></a></li>
                <li><a href="/administrator#/users/manage" ><i class="fa fa-users fa-2x" aria-hidden="true"></i></a></li>
                <li v-if="$auth.getUserInfo('permission') == 1">
                    <div class="dropdown">
                        <a id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-cog fa-2x" aria-hidden="true"></i>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                            <a href="/administrator#/settings/appearance/themes" role="button" class="dropdown-item"><i class="fa fa-paint-brush" aria-hidden="true"></i> Theme </a>
                            <a href="/administrator#/settings/users/manage" role="button" class="dropdown-item"><i class="fa fa-users" aria-hidden="true"></i> Administrators Manage</a>
                            <a href="/administrator#/settings/backup" role="button" class="dropdown-item"><i class="fa fa-cloud-upload" aria-hidden="true"></i> Backup</a>
                        </div>
                    </div>
                </li>
                <li class="mt-3" style="border:none !important;"  id="avatar">
                   <a href="/administrator#/profile/manage">
                    <img :src="$auth.getUserInfo('image')"  onError="this.onerror=null;this.src='/img/avatar.png';">
                   </a>
                   </li>
            </ul>
        </div>
    </div>    

 <div class="col-sm-11 offset-1">      
<router-view></router-view>
</div>
</div> 
</div>
</template>

<script>
export default {
  created() {
    axios.get("/api/admin/check/permission").then(response => {
      if (response.status === 200) {
        this.$auth.setDetails(
          response.data.data.email,
          response.data.data.name,
          response.data.cdn_user + response.data.data.image,
          response.data.data.role_id
        );
      }
    });
  }
};
</script>
