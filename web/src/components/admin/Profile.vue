<template>
  <AdminLayout>
    <template #main>
      <div class="main-header">
        <div class="headerWarp">
          <div class="infoWarp">
            <div class="info">
              <div class="infoAvatar">
                <div class="avatarCover">
                  <img v-if="authUser.avatar !=null " :src="'http://127.0.0.1:8000/storage/users/'+authUser.avatar" alt="">
                  <img v-else :src="profile.avatar" alt="">
                </div>
              </div>
              <div class="infoName">
                <span class="title">{{ authUser.name }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="main-content">
        <div class="mainWrap">
          <div class="content">
            <div class="profile">
              <img class="bannerProfile"
                   src="https://a.trellocdn.com/prgb/dist/images/member-home/taco-privacy.ced1e262c59e0225e3aa.svg"
                   alt="">
              <h1 class="titleContent">Quản lý thông tin của bạn</h1>
              <p class="subContent">Đây là nơi bạn có thể thay đổi thông tin hồ sơ của mình và tìm hiểu những gì người
                dùng và các Power-Ups khác sẽ có thể thấy. Để tìm hiểu thêm, hãy xem Điều khoản dịch vụ hoặc Chính sách
                Riêng tư của chúng tôi.</p>
              <h3>Thông tin</h3>
              <hr>
              <div class="changeProfile">
                <div class="profileAvatar">
                  <h3>hình đại diện</h3>
                  <div class="avatarCover">
                    <Upload class="avatarUpload" @changeImage="onChangeImage" :url="url"/>
                  </div>
                </div>
                <form class="profileForm" action="">
                  <div class="subLabel">
                    <span>Tên đầy đủ</span>
                  </div>
                  <div class="formInput">
                    <input v-if="authUser.name != null" class="formControl" type="text" v-model="name">
                    <div v-if="errorName !== '' " class="error">
                      <span> {{ errorName }} </span>
                    </div>
                  </div>

                  <button class="saveProfile" type="button" @click="handleUpdateInfo">Lưu</button>
                </form>
              </div>

              <hr>

              <h3>Đổi mật khẩu</h3>
              <hr>
              <div class="changePassword">
                <form class="profileForm" action="">
                  <div class="subLabel">
                    <span>Mật khẩu cũ</span>
                  </div>
                  <div class="formInput">
                    <input class="formControl" type="password" v-model="current_password">
                    <div v-if="errorCurrentPassword !== '' " class="error">
                      <span> {{ errorCurrentPassword }} </span>
                    </div>
                  </div>

                  <div class="subLabel">
                    <span>Mật khẩu mới</span>
                  </div>
                  <div class="formInput">
                    <input class="formControl" type="password" v-model="password">
                    <div v-if="errorPassword !== '' " class="error">
                      <span> {{ errorPassword }} </span>
                    </div>
                  </div>

                  <div class="subLabel">
                    <span>Nhập lại mật khẩu</span>
                  </div>
                  <div class="formInput">
                    <input class="formControl" type="password" v-model="password_confirmation">
                    <div v-if="errorPasswordConfirm !== '' " class="error">
                      <span> {{ errorPasswordConfirm }} </span>
                    </div>
                  </div>

                  <button class="btn-change-password" type="button" @click="handleUpdatePassword">Thay đổi mật khẩu
                  </button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </template>
  </AdminLayout>
</template>

<script>
import AdminLayout from "@/layouts/AdminLayout";
import {mapMutations, mapState} from "vuex";
import Upload from "@/components/include/Upload";
import _ from "lodash";
import api from '../../api'

export default {
  name: "Profile",
  components: {
    AdminLayout,
    Upload
  },
  methods: {
    ...mapMutations('auth',[
        'updateAuthUser'
    ]),

    handleUpdatePassword() {
      if (this.isValidPassword()) {
        let data = {
          password: this.password,
          password_confirmation: this.password_confirmation,
          current_password: this.current_password
        }
        api.updatePassword(data).then(() => {
          this.$message({
            type: "success",
            message: "Cập nhật thành công"
          })
        }).catch(error => {
          this.errorPassword = _.get(error, 'password', '')
          this.errorPasswordConfirm = _.get(error, 'password_confirmation', '')
          this.errorCurrentPassword = _.get(error, 'current_password', '')
        })
      }
    },
    handleUpdateInfo() {
      if (this.isValidInfo()) {
        const frmdata = new FormData();
        frmdata.append('name', this.name)
        if (this.avatar != null) {
          frmdata.append('avatar',this.avatar)
        }

        api.updateInfo(frmdata).then(()=>{
          api.getAuthUser().then((res)=>{
            this.updateAuthUser(res.data)
            this.$message({
              type: "success",
              message: "Cập nhật thành công"
            })
          })
        })
      }
    },
    onChangeImage(img) {
      this.avatar = img
    },
    isValidPassword() {
      let error = false;
      this.resetError()


      if (this.password.length < 6) {
        error = true;
        this.errorPassword = 'Mật khẩu phải lớn hơn 6 ký tự';
      }

      if (this.password.length === 0) {
        error = true;
        this.errorPassword = 'Mật khẩu mới không được để trống';
      }

      if (this.current_password.length === 0) {
        error = true;
        this.errorCurrentPassword = 'Mật khẩu cũ không được để trống';
      }

      if (this.password !== this.password_confirmation) {
        error = true;
        this.errorPasswordConfirm = "Mật khẩu không trùng khớp"
      }


      return !error
    },
    isValidInfo() {
      let error = false;
      this.resetError()

      if (this.name.length === 0) {
        error = true;
        this.errorName = 'Tên người dùng không được để trống';
      }
      return !error
    },
    resetError() {
      this.errorName = ""
      this.errorCurrentPassword = ""
      this.errorPassword = ""
      this.errorPasswordConfirm = ""
    }
  }
  ,
  computed: {
    ...mapState('auth', [
      'authUser'
    ]),
    ...mapState('profile', [
      'profile'
    ]),
  },
  data() {
    return {
      name: "",
      avatar: "",
      url: "",
      password: "",
      current_password: "",
      password_confirmation: "",
      errorName: "",
      errorPassword: "",
      errorCurrentPassword: "",
      errorPasswordConfirm: "",
    }
  },
  mounted() {
    this.name = this.authUser.name;
    this.url = this.authUser.avatar
  },
  watch: {
    name() {
      this.errorName = ''
    },
    current_password() {
      this.errorCurrentPassword = ""
    },
    password() {
      this.errorPassword = ""
    },
    password_confirmation() {
      this.errorPasswordConfirm = ""
    }
  }
}
</script>

<style scoped lang="scss">
.main-header {

  .headerWarp {
    display: flex;
    flex-direction: column;
    justify-content: center;
    margin: 0 auto;
    max-width: 650px;
    padding: 32px;
    position: relative;

    .infoWarp {
      .info {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px 0;
      }

      .infoAvatar {
        position: relative;
        margin-right: 16px;

        .avatarCover {
          position: relative;
          line-height: 10px;
          overflow: hidden;
          white-space: nowrap;

          img {
            height: 48px;
            width: 48px;
            line-height: 48px;
            border-radius: 50%;
          }
        }
      }

      .infoName {
        display: flex;
        align-items: baseline;

        .title {
          font-size: 24px;
          font-weight: 500;
          line-height: 28px;
          color: #0c3953;
          margin-right: 10px;

        }

        .subTitle {
          font-size: 12px;
          line-height: 20px;
          color: #5e6c84;
        }
      }
    }
  }
}

.main-content {
  max-width: 900px;
  margin: 0 auto;
  padding: 32px;
  position: relative;

  .mainWrap {
    .profile {
      width: 100%;
      max-width: 530px;
      display: flex;
      flex-direction: column;
      margin: auto;
      text-align: left;
      background-color: white;
      padding: 20px;
      border-radius: 5px;


      .bannerProfile {
        display: block;
        margin: 18px auto 42px;
        max-width: 100%;
        height: auto;
      }

      h1 {
        color: #172b4d;
        font-size: 24px;
        font-weight: 600;
        letter-spacing: -.01em;
        line-height: 28px;
        margin-top: 0px;
        margin-bottom: 10px;
      }

      p {
        color: #5e6c84;
        font-size: 14px;
        line-height: 18px;
        margin-bottom: 8px;
        white-space: pre-line;
      }

      h3 {
        color: #172b4d;
        font-size: 20px;
        letter-spacing: -.008em;
        font-weight: 500;
        line-height: 26px;
        margin-top: 40px;
      }

      hr {
        margin-top: 0;
        margin-bottom: 15px;
      }

      .changeProfile {
        display: flex;
        justify-content: space-between;
        flex-direction: row-reverse;

        .profileAvatar {
          align-items: center;
          display: flex;
          flex-direction: column;
          margin-right: 4px;
          margin-top: 16px;

          h3 {
            color: #172b4d;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: -.003em;
            line-height: 16px;
            margin-top: 0;
            margin-bottom: 12px;
          }

          .avatarCover {
            border-radius: 50%;
            cursor: pointer;
            overflow: hidden;
            position: relative;

            .avatarUpload {
              height: 100px;
              width: 100px;
              line-height: 100px;
            }

          }

          .changeAvatar {
            background: linear-gradient(
                    0deg, rgba(0, 0, 0, .5) 50%, transparent 0);
            border-radius: 0;
            border: none;
            color: #fff;
            font-size: 16px;
            font-weight: 400;
            height: 100%;
            line-height: 2.5em;
            margin: 0;
            position: absolute;
            text-decoration: underline;
            top: 0;
            visibility: hidden;
            width: 100%;
            display: inline-block;
            box-sizing: border-box;
            -webkit-appearance: none;
            padding: 6px 12px;
          }

          .changeAvatar:hover {
            visibility: visible;
          }
        }

        form {
          display: flex;
          flex-direction: column;
          flex: 0 0 355px;

          .subLabel {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            margin-bottom: 12px;
            margin-top: 0;

            span {
              color: #172b4d;
              font-size: 14px;
              font-weight: 600;
              letter-spacing: -.003em;
              line-height: 16px;
              margin-top: 0;
              padding-top: 16px;
            }
          }

          .formInput {
            width: 100%;

            input {
              outline: none;
              border: none;
              box-sizing: border-box;
              color: #172b4d;
              font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Noto Sans, Ubuntu, Droid Sans, Helvetica Neue, sans-serif;
              font-size: 14px;
              line-height: 20px;
              font-weight: 400;
              transition-property: background-color, border-color, box-shadow;
              transition-duration: 85ms;
              transition-timing-function: ease;
              border-radius: 3px;
              padding: 8px 12px;
              box-shadow: inset 0 0 0 2px #dfe1e6;
              background-color: #fafbfc;
              margin-bottom: 0;
              width: 100%;
            }
          }

          button {
            margin-top: 40px;
            margin-right: 0;
            background-color: #61bd4f;
            box-shadow: none;
            border: none;
            color: #fff;
            outline: 0;
            font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Noto Sans, Ubuntu, Droid Sans, Helvetica Neue, sans-serif;
            font-size: 14px;
            line-height: 20px;
            font-weight: 400;
            box-sizing: border-box;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 3px;
            cursor: pointer;
            padding: 6px 12px;
            text-decoration: none;
            transition-property: background-color, border-color, box-shadow;
            transition-duration: 85ms;
            transition-timing-function: ease;
          }
        }
      }

      .changePassword {
        display: flex;

        form {
          display: flex;
          flex-direction: column;
          flex: 0 0 355px;

          .subLabel {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            margin-bottom: 12px;
            margin-top: 0;

            span {
              color: #172b4d;
              font-size: 14px;
              font-weight: 600;
              letter-spacing: -.003em;
              line-height: 16px;
              margin-top: 0;
              padding-top: 16px;
            }
          }

          .formInput {
            width: 100%;

            input {
              outline: none;
              border: none;
              box-sizing: border-box;
              color: #172b4d;
              font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Noto Sans, Ubuntu, Droid Sans, Helvetica Neue, sans-serif;
              font-size: 14px;
              line-height: 20px;
              font-weight: 400;
              transition-property: background-color, border-color, box-shadow;
              transition-duration: 85ms;
              transition-timing-function: ease;
              border-radius: 3px;
              padding: 8px 12px;
              box-shadow: inset 0 0 0 2px #dfe1e6;
              background-color: #fafbfc;
              width: 100%;
              margin-bottom: 10px;
            }
          }

          button {
            font-weight: 400;
            align-self: flex-start;
            font-size: 14px;
            letter-spacing: 0;
            margin-top: 0;
            color: #172b4d;
            font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Noto Sans, Ubuntu, Droid Sans, Helvetica Neue, sans-serif;
            line-height: 20px;
            box-sizing: border-box;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 3px;
            cursor: pointer;
            padding: 6px 12px;
            text-decoration: none;
            background-color: rgba(9, 30, 66, .04);
            box-shadow: none;
            border: none;
            transition-property: background-color, border-color, box-shadow;
            transition-duration: 85ms;
            transition-timing-function: ease;
          }

          button:hover {
            background-color: #ecedf0;
          }
        }

      }
    }
  }
}
</style>