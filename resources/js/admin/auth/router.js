import Vue from "vue/dist/vue.esm"
import VueRouter from "vue-router"
import VueRouteMiddleware from 'vue-route-middleware'

import Login from './components/Login.vue'
import Register from './components/Register.vue'
import EmailVerify from './components/EmailVerify.vue'

Vue.use(VueRouter)

const routes = [
  {path: '/', redirect: 'Login'},
  {name: 'Login', path: '/login', component: Login},
  {name: 'Register', path: '/register', component: Register},
  {
    name: 'EmailVerify',
    path: '/email_verify',
    component: EmailVerify,
    meta: {
      middleware: (to, from, next) => {
        if(!window.emailVerifyToken) next({ name: 'Login' });
      }
    }
  },
];

const router = new VueRouter({
  mode: 'hash',
  routes,
});

router.beforeEach(VueRouteMiddleware());

export default router;
