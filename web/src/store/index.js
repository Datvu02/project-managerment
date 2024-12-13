import Vue from 'vue'
import Vuex from 'vuex'
import home from './modules/home/index'
import profile from './modules/profile/index'
import auth from './modules/auth/index'

Vue.use(Vuex)

const store = new Vuex.Store({
    state: {
        // Khai báo global state
    },
    mutations: {
        // Khai báo mutations
    },
    modules: {
        home,
        profile,
        auth
    }
})

export default store