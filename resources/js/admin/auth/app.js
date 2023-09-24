import '../../bootstrap'
import Vue from "vue/dist/vue.esm"
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue/dist/bootstrap-vue.esm'

import router from './router.js'

import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

Vue.use(BootstrapVue)
Vue.use(IconsPlugin)
Vue.prototype.$axios = axios.create({
  baseURL: 'http://admin.ecsdz.com',
  timeout: 30000
})

window.router = router
window.app = new Vue({router: window.router}).$mount('#auth-app')
