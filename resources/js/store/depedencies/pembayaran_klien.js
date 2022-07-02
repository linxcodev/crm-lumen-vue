import {$axios, $axios2} from '../../services/api.js'

const state = () => ({
    klienPembayaran: "",
    pembayaranDetail: [],
    tipePembayaran: []
})

const mutations = {
    ASSIGN_PAGINATE(state, payload) {
        state.klienPembayaran = payload
    },
    ASSIGN_DETAIL(state, payload) {
        state.pembayaranDetail = payload
    },
    ASSIGN_TIPE(state, payload) {
        state.tipePembayaran = payload
    },
}

const actions = {
    getPembayaranKlien({ commit }, payload) {
        let page = payload && typeof payload.page != 'undefined' ? payload.page : 1
        let search = payload && typeof payload.search != 'undefined' ? payload.search : ''
        commit('SET_LOADING',true, { root: true })

        return new Promise(async (resolve, reject) => {
            try {
                let network = await $axios.get(`klien/pembayaran?page=${page}&q=${search}`)
                const data = network.data.data

                commit('ASSIGN_PAGINATE', data)
                commit('SET_LOADING', false, { root: true })
                resolve(data)
            } catch (error) {
                commit('SET_LOADING', false, { root: true })
                reject(error.response.data)
            }
        })
    },
    getTipePembayaran({ commit }) {
        commit('SET_LOADING',true, { root: true })

        return new Promise(async (resolve, reject) => {
            try {
                let network = await $axios.get(`klien/pembayaran/tipe`)
                const data = network.data.data

                commit('ASSIGN_TIPE', data)
                commit('SET_LOADING', false, { root: true })
                resolve(data)
            } catch (error) {
                commit('SET_LOADING', false, { root: true })
                reject(error.response.data)
            }
        })
    },
    addPembayaranKlien({ commit }, payload) {
        commit('SET_LOADING',true, { root: true })
        return new Promise(async (resolve, reject) => {
            try {
                let network = await $axios.post(`klien/pembayaran`, payload)

                commit('CLEAR_ERRORS', '', { root: true })
                commit('SET_LOADING', false, { root: true })

                resolve(network.data)
            } catch (error) {
                commit('SET_LOADING', false, { root: true })
                
                if (error.response.status == 422) {
                    commit('SET_ERRORS', error.response.data.errors, { root: true })
                }

                reject(error.response.data)
            }
        })
    },
    updatePembayaranKlien({ commit }, payload) {
        commit('SET_LOADING',true, { root: true })
        return new Promise(async (resolve, reject) => {
            try {
                let network = await $axios.put(`klien/pembayaran/${payload.id}`, payload.data)

                commit('CLEAR_ERRORS', '', { root: true })
                commit('SET_LOADING', false, { root: true })

                resolve(network.data)
            } catch (error) {
                commit('SET_LOADING', false, { root: true })
                
                if (error.response.status == 422) {
                    commit('SET_ERRORS', error.response.data.errors, { root: true })
                }

                reject(error.response.data)
            }
        })
    },
    deletePembayaranKlien({ commit }, payload) {
        commit('SET_LOADING',true, { root: true })
        return new Promise(async (resolve, reject) => {
            try {
                let network = await $axios.post(`klien/pembayaran/${payload.id}`, payload)

                commit('CLEAR_ERRORS', '', { root: true })
                commit('SET_LOADING', false, { root: true })

                resolve(network.data)
            } catch (error) {
                commit('SET_LOADING', false, { root: true })
                
                if (error.response.status == 422) {
                    commit('SET_ERRORS', error.response.data.errors, { root: true })
                }

                reject(error.response.data)
            }
        })
    },
    getPembayaranKlienById({ commit }, payload) {
        commit('SET_LOADING',true, { root: true })
        return new Promise(async (resolve, reject) => {
            try {
                let network = await $axios.get(`klien/pembayaran/${payload}`)
                const data = network.data.data

                commit('ASSIGN_DETAIL', data)
                commit('CLEAR_ERRORS', '', { root: true })
                commit('SET_LOADING', false, { root: true })

                resolve(network.data)
            } catch (error) {
                commit('SET_LOADING', false, { root: true })
                
                if (error.response.status == 422) {
                    commit('SET_ERRORS', error.response.data.errors, { root: true })
                }

                reject(error.response.data)
            }
        })
    },
    generatePembayaran({ commit }, payload) {
        commit('SET_LOADING',true, { root: true })
        return new Promise(async (resolve, reject) => {
            try {
                let network = await $axios2.get(`klien/pembayaran/generate-pdf/${payload}`)

                commit('CLEAR_ERRORS', '', { root: true })
                commit('SET_LOADING', false, { root: true })

                resolve(network.data)
            } catch (error) {
                commit('SET_LOADING', false, { root: true })
                
                if (error.response.status == 422) {
                    commit('SET_ERRORS', error.response.data.errors, { root: true })
                }

                reject(error.response.data)
            }
        })
    },
}

export default {
    namespaced: true,
    state,
    mutations,
    actions
}