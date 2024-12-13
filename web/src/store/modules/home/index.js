import Vue from 'vue'
import Vuex from 'vuex'


Vue.use(Vuex)

export default {
    namespaced: true,
    state: {
        labelShow: false,
        // Khai báo state
        list: [],
        cardDetail:{}
    },
    getters: {
        getList: (state) => (id) => {
            return state.list.find(list => list.id === id)
        }
    },
    mutations: {
        addCard(state, data) {
            state.list.find(list => list.id === data.list_id).cards.push(data.card)
        },
        showLable(state) {
            state.labelShow = !state.labelShow
        },
        updateList(state, list) {
            state.list = list;
        },
        updateCardDetail(state,card){
            state.cardDetail = card
        }
    },
}