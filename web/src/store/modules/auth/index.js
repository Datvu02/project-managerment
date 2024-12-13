import Vue from 'vue'
import Vuex from 'vuex'


Vue.use(Vuex)

export default {
    namespaced: true,
    state: {
        isAuthenticated: false,
        authUser: {},
        token: localStorage.getItem('access_token') || ''
    },
    getters: {
        //
    },
    actions:{

    }
    ,
    mutations: {
        updateLoginStatus (state, isAuthenticated) {
            state.isAuthenticated = isAuthenticated;
        },
        updateAuthUser (state, authUser) {
            state.authUser = authUser;
        },
        updateToken(state, token) {
          state.token = token
        }
    },
}