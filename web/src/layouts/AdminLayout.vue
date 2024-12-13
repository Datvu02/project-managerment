<template>
  <div class="admin">
    <el-container class="adminWrap">
      <el-header class="adminHeader">
        <div class="header-container">
          <div class="header-left">
            <a href="/project" class="heard-btn"><span><i class="el-icon-house"></i></span></a>
          </div>
          <div class="header-center">
            <a class="logo" href="/project">
              <img src="../assets/logowab.png" alt="logo">
            </a>
          </div>
          <div class="header-right">
            <el-dropdown>
              <el-avatar
                  v-if="authUser.avatar!=null" :src="'http://127.0.0.1:8000/storage/users/'+authUser.avatar"
                  alt=""></el-avatar>
              <el-avatar
                  v-else :src="profile.avatar" alt=""></el-avatar>
              <el-dropdown-menu slot="dropdown">
                <router-link to="/profile">
                  <el-dropdown-item>
                    Thông tin
                  </el-dropdown-item>
                </router-link>
                <span @click="handleLogout">
                  <el-dropdown-item>Đăng xuất</el-dropdown-item>
                </span>
              </el-dropdown-menu>
            </el-dropdown>
          </div>
        </div>
      </el-header>
      <el-main class="adminMain">
        <slot name="main"></slot>
      </el-main>
    </el-container>
  </div>
</template>

<script>
import {mapMutations, mapState} from "vuex";

export default {
  name: "AdminLayout",
  methods: {
    ...mapMutations('auth', ['updateLoginStatus', 'updateAuthUser', 'updateToken']),
    handleLogout() {
      localStorage.removeItem('access_token')
      localStorage.removeItem('vuex')
      this.updateLoginStatus(false)
      this.updateToken(null)
      this.updateAuthUser({})

      if (this.$router.currentRoute.name !== 'Login') {
        this.$router.push({name:'Login'})
      }
    }
  },
  computed: {
    ...mapState('auth', [
      'authUser'
    ]),
    ...mapState('profile', [
      'profile'
    ]),
  }
}
</script>

<style scoped lang="scss">
.admin {
  height: 100vh;
  overflow: auto;
  position: relative;
  background-position: center;
  background-repeat: no-repeat;
  background-size: cover;
  display: flex;
  align-items: center;
  justify-content: center;
  background-image: url("../assets/image/wp1874701-we-bare-bears-wallpapers.png");

  .adminWrap {
    height: 100%;
    display: flex;
    flex-direction: column;

    .adminHeader {
      min-height: 40px;
      max-height: 40px;
      overflow: hidden;
      padding: 0;

      .header-container {
        background-color: rgba(0, 0, 0, .32);
        box-sizing: border-box;
        display: flex;
        max-height: 40px;
        overflow: hidden;
        padding: 4px;
        justify-content: space-between;

        .header-left {
          a {
            border-radius: 3px;
            border: 0;
            text-decoration: none;
            align-items: center;
            background-color: hsla(0, 0%, 100%, .3);
            box-shadow: none;
            color: #fff;
            display: flex;
            font-weight: 700;
            height: 32px;
            line-height: 32px;
            margin: 0 4px 0 0;
            padding: 0;
            white-space: nowrap;

            span{
              display: flex;
              width: 32px;
              justify-content: center;
              align-items: center;
              margin: 0;
            }
          }
        }

        .header-center {
          .logo {
            cursor: pointer;
            img {
              height: 36px;
            }
          }
        }

        .header-right {
          .el-dropdown {
            .el-avatar {
              outline: none;
              width: 30px;
              height: 30px;
            }

            a {
              text-decoration: none;
            }
          }
        }
      }
    }

    .adminMain {
      position: relative;
      overflow-y: auto;
      outline: none;
      padding: 0;
      height: 100%;
    }


  }
}
</style>