import Vue from 'vue'
import App from './App.vue'
import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';
import locale from 'element-ui/lib/locale/lang/vi';
import router from './router';
import store from './store';
import TextareaAutosize from 'vue-textarea-autosize'

Vue.use(TextareaAutosize)

Vue.use(ElementUI, { locale });

Vue.config.productionTip = false
Vue.config.devtools = process.env.NODE_ENV === 'development';

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app')