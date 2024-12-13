<template>
  <Login/>
</template>

<script>
import Login from "@/components/auth/Login";
import {mapState} from 'vuex'
export default {
  name: "LoginView",
  components: {
    Login
  },
  computed:{
    ...mapState('auth',[
        'isAuthenticated'
    ])
  },
  created() {
    if (this.isAuthenticated){
      this.$router.push({name:'Project'})
    }
  }
}
</script>

<style scoped>

</style>