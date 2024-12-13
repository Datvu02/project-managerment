<template>
  <Admin/>
</template>

<script>
import Admin from "@/components/admin/Admin";
import api from "@/api";
import {mapMutations} from "vuex";
export default {
  name: "Admin",
  components:{
    Admin
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