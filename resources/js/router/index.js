import Vue from 'vue'
import VueRouter from 'vue-router'
import store from '../store'

import Login from '../views/Login.vue'

import system from './system'
import marketing from './marketing'
import penjualan from './penjualan'
import depedencies from './depedencies'

Vue.use(VueRouter)

const routes = [
  {
    path: '/login',
    name: 'login',
    component: Login
  },
  ...system,
  ...marketing,
  ...penjualan,
  ...depedencies,
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

router.beforeEach((to, from, next) => {
  store.commit('CLEAR_ERRORS')

  if (to.matched.some(record => record.meta.requiresAuth)) {
    let auth = store.getters.isAuth

    if (!auth) {
      next({ name: 'login' })
    } else {
      store.commit('LOADING_PAGE', true)
      next()
    }
  } else {
    store.commit('LOADING_PAGE', true)
    next()
  }
})


router.afterEach((to) => {
  store.commit('LOADING_PAGE', false)
  store.commit('SET_LOADING', false)

  Vue.nextTick(() => {
    document.title = 'SoftCRM' + ((to.meta.title) ? ' - ' + to.meta.title : '')
  });
})

export default router