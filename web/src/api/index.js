import axios from 'axios'
import store from "../store";
import router from "../router";
// Lấy ra các biến cần thiết: base url, token
const baseUrl = process.env.VUE_APP_BASE_URL;

// Tạo instance của axios

export const apiAxios = axios.create({
    baseURL: `${baseUrl}/api`,
    headers: {
        post: {
            'Content-Type': 'application/json'
        },
    }
})
apiAxios.interceptors.request.use(config => {
    let token = store.state.auth.token
    if (token) {
        config.headers.common['Authorization'] = `Bearer ${token}`
    }
    return config
}, error => {
    return Promise.reject(error)
})
// Xử lý logout khi response 401
apiAxios.interceptors.response.use(undefined, (error) => {
    if (error) {
        const originalRequest = error.config;
        if (error.response.status === 401 && !originalRequest._retry && router.currentRoute.name !== 'Login') {
            store.commit('auth/updateLoginStatus', false)
            store.commit('auth/updateAuthUser', {})
            localStorage.removeItem('access_token')
            return router.push({name: 'Login'})
        }
    }
})
// Khai báo các request sử dụng trong hệ thống
export default {
    getAuthUser() {
        return apiAxios({
            method: 'get',
            url: '/auth/me'
        })
    },
    register(data) {
        return apiAxios({
            method: 'post',
            url: 'auth/register',
            data: data
        })
    },
    login(data) {
        return apiAxios({
            method: 'post',
            url: 'auth/login',
            data: data
        })
    },
    logout() {
        return apiAxios({
            method: 'post',
            urd: '/auth/logout',
        })
    },

    //list
    getList(params) {
        return apiAxios({
            method: 'get',
            url: '/directories',
            params: params
        })
    },
    addList(data) {
        return apiAxios({
            method: 'post',
            url: '/directories',
            data: data
        })
    },
    updateTitleList(data, id) {
        return apiAxios({
            method: 'put',
            url: '/directories/' + id,
            data: data
        })
    },
    changeIndexList(data, id) {
        return apiAxios({
            method: 'put',
            url: 'directories/' + id + '/index',
            data: data
        })
    },
    deleteList(id) {
        return apiAxios({
            method: 'delete',
            url: 'directories/' + id,
        })
    },


    //cards
    addCards(data) {
        return apiAxios({
            method: 'post',
            url: '/cards',
            data: data
        })
    },
    getCard(id) {
        return apiAxios({
            method: 'get',
            url: '/cards/' + id,
        })
    },
    changeCardList(data, id) {
        return apiAxios({
            method: 'put',
            url: 'cards/' + id + '/directory',
            data: data
        })
    },
    changeStatusDeadline(data, id) {
        return apiAxios({
            method: 'put',
            url: 'cards/' + id + '/change-status-deadline',
            data: data
        })
    },
    changeStatusTodo(data, id) {
        return apiAxios({
            method: 'put',
            url: '/cards/' + id + '/change-status',
            data: data
        })
    },
    updateCard(data, id) {
        return apiAxios({
            method: 'put',
            url: '/cards/' + id,
            data: data
        })
    },

    deleteCard(id) {
        return apiAxios({
            method: 'delete',
            url: '/cards/' + id,
        })
    },

    //label
    getLabels(params = null) {
        return apiAxios({
            method: 'get',
            url: '/labels',
            params: params
        })
    },
    addLabel(data, id) {
        return apiAxios({
            method: 'post',
            url: '/cards/' + id + '/label',
            data: data
        })
    },
    detachLabels(data, id) {
        return apiAxios({
            method: 'delete',
            url: '/cards/' + id + '/detach-labels',
            data: data
        })
    },
    attachLabels(data, id) {
        return apiAxios({
            method: 'post',
            url: '/cards/' + id + '/attach-labels',
            data: data
        })
    },

    updateLabels(data, id) {
        return apiAxios({
            method: 'put',
            url: '/labels/' + id,
            data: data
        })
    },
    deleteLabels( id) {
        return apiAxios({
            method: 'delete',
            url: '/labels/' + id,
        })
    },

    //Checklist

    addCheckList(data) {
        return apiAxios({
            method: 'post',
            url: '/check-lists',
            data: data
        })
    },

    updateCheckListTitle(data, id) {
        return apiAxios({
            method: 'put',
            url: '/check-lists/' + id,
            data: data
        })
    },

    deleteChecklist(id) {
        return apiAxios({
            method: 'delete',
            url: '/check-lists/' + id,
        })
    },

    //ChildCheckList

    addCheckListChild(data) {
        return apiAxios({
            method: 'post',
            url: '/check-list-childs',
            data: data
        })
    },

    updateCheckListTitleChild(data, id) {
        return apiAxios({
            method: 'put',
            url: '/check-list-childs/' + id,
            data: data
        })
    },

    deleteChecklistChild(id) {
        return apiAxios({
            method: 'delete',
            url: '/check-list-childs/' + id,
        })
    },
    changeStatusChild(data, id) {
        return apiAxios({
            method: 'put',
            url: '/check-list-childs/' + id + '/change-status',
            data: data
        })
    },
    //file
    uploadFileCard(data, id) {
        return apiAxios({
            method: 'post',
            url: 'cards/' + id + '/upload-file',
            data: data
        })
    },

    deleteFile(id) {
        return apiAxios({
            method: 'delete',
            url: '/files/' + id
        })
    },

    updteFile(data, id) {
        return apiAxios({
            method: 'put',
            url: '/files/' + id,
            data: data
        })
    },

    updatePassword(data) {
        return apiAxios({
            method: 'put',
            url: '/users/password',
            data: data
        })
    },

    updateInfo(data) {
        return apiAxios({
            method: 'post',
            url: '/users',
            data: data
        })
    },
    allUser() {
          return apiAxios({
              method: 'get',
              url: '/users',
          })
      },
    listUserByProject(id) {
          return apiAxios({
              method: 'get',
              url: '/users/project/'+id,
          })
      },
    getMyProject(){
      return apiAxios({
        method: 'get',
        url: '/projects',
      })
    },
    storeProject(data) {
      return apiAxios({
        method: 'post',
        url: '/projects',
        data: data
      })
    },
    removeProject(id){
      return apiAxios({
        method: 'delete',
        url:'/projects/'+id,
      })
    },
    getCardByProject(id){
      return apiAxios({
        method: 'get',
        url: '/projects/cards/'+id
      })
    }
}