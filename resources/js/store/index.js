import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

import auth from './auth'
import user from './user'
// system
import dashboard from './system/dashboard'
import pengaturan from './system/pengaturan'
import system_log from './system/system_log'
// penjualan
import keuangan from './penjualan/keuangan'
import penjualan from './penjualan/penjualan'
// marketing
import tugas from './marketing/tugas'
import produk from './marketing/produk'
// depedencies
import deal from './depedencies/deal'
import klien from './depedencies/klien'
import karyawan from './depedencies/karyawan'
import perusahaan from './depedencies/perusahaan'
import pembayaran_klien from './depedencies/pembayaran_klien'

export default new Vuex.Store({
  modules: {
    auth,
    user,
    // system
    pengaturan,
    dashboard,
    // penjualan
    penjualan,
    keuangan,
    // marketing
    produk,
    tugas,
    // depedencies
    deal,
    klien,
    karyawan,
    perusahaan,
    system_log,
    pembayaran_klien
  },
  state: {
    errors: [],
    isLoading: false,
    loadPage: false,
    token: localStorage.getItem('token'),
    baseURL: ''
  },
  getters: {
    isAuth: state => {
      return state.token != "null" && state.token != null && state.token != "undefined" && state.token != undefined
    },
    isLoading: state => {
      return state.isLoading
    },
    baseURL: state => {
      return state.baseURL
    }
  },
  mutations: {
    SET_TOKEN(state, payload) {
      state.token = payload
    },
    SET_ERRORS(state, payload) {
      state.errors = payload
    },
    CLEAR_ERRORS(state) {
      state.errors = []
    },
    SET_LOADING(state, payload) {
      state.isLoading = payload
    },
    LOADING_PAGE(state, payload) {
      state.loadPage = payload
    },
    SET_BASEURL(state, payload) {
      state.baseURL = payload
    }
  }
})
