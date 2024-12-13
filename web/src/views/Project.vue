<template>
  <Project/>
</template>

<script>
import Project from "@/components/admin/Project";
import api from "@/api";
import {mapMutations} from "vuex";
export default {
  name: "Project",
  components:{
    Project
  },
  methods:{
    ...mapMutations('auth',[
      'updateAuthUser'
    ])
  },
  mounted() {
    api.getAuthUser().then((response) => {
      this.updateAuthUser(response.data)
    })
  }
}
</script>

<style scoped>

</style>