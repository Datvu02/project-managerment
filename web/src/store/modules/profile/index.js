import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default {
    namespaced: true,
    state: {
        // Khai báo state
        profile: {
            name: "Lê Hồng Minh",
            avatar: "https://i.pinimg.com/originals/eb/59/fc/eb59fc8a76791bc31663723c03c875d2.jpg"
        }
    },
    getters: {

    },
    mutations: {

    },
}