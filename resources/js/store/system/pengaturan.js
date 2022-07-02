import { $axios } from '../../services/api.js'

const state = () => ({
    pengaturan: [],
    list_log: [],
})

const mutations = {
    ASSIGN_DATA(state, payload) {
        state.pengaturan = payload
    },
    ASSIGN_LOG(state, payload) {
        state.list_log = payload
    }
}

const actions = {
    getPengaturan({ commit }) {
        commit('SET_LOADING',true, { root: true })

        return new Promise(async (resolve, reject) => {
            try {
                let network = await $axios.get(`pengaturan`)
                const data = network.data.data

                commit('ASSIGN_LOG', data.list_log)
                commit('ASSIGN_DATA', data.pengaturan)
                commit('SET_LOADING', false, { root: true })

                resolve(network.data)
            } catch (error) {
                commit('SET_LOADING', false, { root: true })
                reject(error.response.data)
            }
        })
    },
    updatePengaturan({ commit, state }, payload) {
        commit('SET_LOADING',true, { root: true })

        return new Promise(async (resolve, reject) => {
            try {
                const network = await $axios.put(`pengaturan`, state.pengaturan)

                commit('SET_LOADING', false, { root: true })
                resolve(network.data)
            } catch (error) {
                commit('SET_LOADING', false, { root: true })
                reject(error.response.data)
            }
        })
    }
}

export default {
    namespaced: true,
    state,
    mutations,
    actions
}