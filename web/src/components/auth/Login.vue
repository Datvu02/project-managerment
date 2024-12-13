<template>
  <LoginLayout>
    <template #login>
      <main class="loginWrap">
        <div class="logoWrap">
          <img class="logo" src="../../assets/logowab.png" alt=""></div>
        <el-form :model="loginForm" :rules="rules" ref="ruleForm">
          <div class="formInputWrap">
            <div class="sui-input-wrap">
              <div class="sui-input-prefix"></div>
              <el-form-item prop="email">
                <el-input placeholder="Email" v-model="loginForm.email" autocomplete="off"></el-input>
              </el-form-item>
              <div class="sui-input-subfix"></div>
            </div>
          </div>
          <div class="formInputWrap">
            <div class="sui-input-wrap">
              <div class="sui-input-prefix"></div>
              <el-form-item prop="password">
                <el-input placeholder="Mật khẩu" type="password" v-model="loginForm.password"
                          autocomplete="off"></el-input>
              </el-form-item>
              <div class="sui-input-subfix"></div>
            </div>
          </div>

          <button class="submitButton"
                  tabindex="0" type="button" @click="submitLogin()">
            <span class="btnLabel">ĐĂNG NHẬP</span>
            <span class="MuiTouchRipple-root"></span>
          </button>
        </el-form>

        <div class="returnRegisterWrap">
          <button class="registerBtn">
            <span class="btnLabel" @click="register()">Đăng kí</span>
            <span class="MuiTouchRipple-root"></span>
          </button>
        </div>
      </main>
    </template>
  </LoginLayout>
</template>

<script>
import LoginLayout from "@/layouts/LoginLayout";
import {mapState, mapActions, mapMutations} from 'vuex'
import api from '../../api'

export default {
  name: "Login",
  components: {
    LoginLayout
  },
  methods: {
    ...mapActions,
    ...mapMutations('auth', ['updateLoginStatus', 'updateAuthUser', 'updateToken']),
    register() {
      this.$router.push('/register')
    },
    submitLogin() {
      this.$refs.ruleForm.validate((valid) => {
        let data= {
          email: this.loginForm.email,
          password: this.loginForm.password,
        }
        if (valid) {
          api.login(data).then((response) => {
            if (response){
              localStorage.setItem('access_token', response.data.access_token)
              this.updateToken(response.data.access_token)
              this.updateLoginStatus(true)
              api.getAuthUser().then((res)=>{
                this.updateAuthUser(res.data)
                if (this.$router.currentRoute.name !== 'Project') {
                  this.$router.push('/project')
                }
              })
            }
          }).catch(() => {
            this.$message({message: 'Có 1 lỗi gì đó', type: 'error'});
          })
        } else {
          return false;
        }
      });
    }
  },
  computed: {
    ...mapState('auth', ['isAuthenticated']),
  },
  created() {
    if (this.isAuthenticated) this.$router.push({name: 'Project'})
  },
  data() {
    return {
      loginForm: {
        email: "",
        password: "",
      },
      rules: {
        email: [
          {required: true, message: 'Vui lòng nhập email!', trigger: ['blur','change']},
        ],
        password: [
          {required: true, message: 'Vui lòng nhập mật khẩu!', trigger: ['blur','change']},
        ],
      },
    }
  }
}
</script>

<style scoped lang="scss">
.loginWrap {
  position: absolute;
  right: 200px;
  margin-right: 200px;
  width: 444px;
  display: block;
  box-sizing: border-box;
  margin: 0 auto;
  background: #fff;
  padding: 24px;
  border-radius: 10px;

  .logoWrap {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 24px;

    img {
      width: 200px;
      box-sizing: border-box;
      vertical-align: middle;
      border-style: none;
    }
  }

  .formInputWrap {
    margin-bottom: 15px;


    .formInput {
      border-radius: 4px;
      height: 50px;
    }

    .error {
      border-color: #ee4f5e !important;
    }

    .sui-error-message {
      display: flex;
      align-items: center;
      color: #ee4f5e;
      margin-top: 8px;
      font-size: 12px;

      .sui-error-message-icon {
        color: #ee4f5e;
        font-size: 12px;
        margin-right: 4px;
        height: 12px;
      }
    }


    .sui-input-wrap {
      position: relative;
      height: 50px;
      //width: 100%;

      .sui-input {
        box-sizing: border-box;
        //position: absolute;
        width: 100%;
        //height: 100%;
        padding: 12px;
        font-size: 14px;
        line-height: 18px;
        color: #253036;
        border-radius: 4px;
        height: 50px;
        border: 1px solid #d8e0ea;
        outline: unset;

        &:hover,
        &:active,
        &:focus {
          border: 1px solid #0080dd;
        }

        &.has-error {
          border: 1px solid #f54b5e;
        }

        &.read-only {
          background: #0093e9;
        }
      }

      .sui-input-prefix {
        position: absolute;
        top: 0;
        left: 12px;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
      }

      .sui-input-subfix {
        position: absolute;
        top: 0;
        right: 12px;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
      }

      &.sui-input-has-prefix {
        .sui-input {
          padding-left: 40px;
        }
      }

      &.sui-input-has-subfix {
        .sui-input {
          padding-right: 40px;
        }
      }

      .sui-input-clear {
        cursor: pointer;
        position: absolute;
        top: 0;
        right: 12px;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;

        //.sui-input-clear-icon {
        //  color: $colorSecond;
        //}
      }
    }

  }

  .forgotPwdTitle {
    font-size: 20px;
    font-weight: bold;
    text-align: center;
    margin-bottom: 40px;
  }

  .forgotPwdDescription {
    font-size: 14px;
    color: #54657a;
    margin-bottom: 8px;
  }

  .forgotPwdWrap {
    margin-top: 8px;
    display: flex;
    align-items: center;
    justify-content: flex-end;

    .forgotPwdBtn {
      color: #0080dd;
      line-height: 18px;
      background: transparent;
      border-radius: 4px;
      text-transform: none;
      border: none;

      background: none;
      outline: none;
      cursor: pointer;
      font-size: 15px;
      padding: 6px 8px;
    }

    .forgotPwdBtn:hover {
      background: #F5F5F5;
    }
  }


  .submitButton {
    width: 100%;
    height: 40px;
    border: none;
    background: #2CCB93;
    color: #fff;
    text-decoration: none;
    font-size: 14px;
    line-height: 18px;
    text-transform: none;
    padding: 6px 8px;
    min-width: 64px;
    box-sizing: border-box;
    transition: background-color 250ms cubic-bezier(0.4, 0, 0.2, 1) 0ms, box-shadow 250ms cubic-bezier(0.4, 0, 0.2, 1) 0ms, border 250ms cubic-bezier(0.4, 0, 0.2, 1) 0ms;
    font-family: "Roboto", "Helvetica", "Arial", sans-serif;
    font-weight: 800;
    outline: none;
    border-radius: 4px;
    cursor: pointer;


    .btnLabel {
      width: 100%;
      display: inherit;
      align-items: inherit;
      justify-content: inherit;
      box-sizing: border-box;
    }
  }

  .returnRegisterWrap {
    margin-top: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding-top: 24px;
    box-sizing: border-box;
    color: #5cdee4;

    .registerBtn {
      font-size: 14px;
      line-height: 18px;
      background: transparent;
      border-radius: 4px;
      text-transform: none;
      outline: none;
      cursor: pointer;
      border: none;
      background: none;
      padding: 6px 8px;
      box-sizing: border-box;
      transition: background-color 250ms cubic-bezier(0.4, 0, 0.2, 1) 0ms, box-shadow 250ms cubic-bezier(0.4, 0, 0.2, 1) 0ms, border 250ms cubic-bezier(0.4, 0, 0.2, 1) 0ms;
      font-family: "Roboto", "Helvetica", "Arial", sans-serif;
      font-weight: 500;
      border: 0;
      margin: 0;
      display: inline-flex;
      outline: 0;
      color: #2CCB93;
    }

    .registerBtn:hover {
      text-decoration: none;
      background-color: rgba(0, 0, 0, 0.04);
    }


  }

}

</style>