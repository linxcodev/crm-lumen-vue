import $axios from '../services/api.js'

const state = () => ({
    authenticated: [],
})

const mutations = {
    ASSIGN_USER_AUTH(state, payload) {
        state.authenticated = payload
    }
}

const actions = {
    getUserLogin({ commit }) {
        commit('SET_LOADING',true, { root: true })
        return new Promise(async (resolve, reject) => {
            try {
                let netowrk = await $axios.get(`authenticated`)

                commit('ASSIGN_USER_AUTH', netowrk.data.data)
                commit('SET_LOADING', false, { root: true })
                resolve(netowrk.data)
            } catch (error) {
                commit('SET_LOADING', false, { root: true })
                reject(error.response.data)
            }
        })
    },
    changeUserPassword({ commit }, payload) {
        commit('SET_LOADING', true, { root: true })
        return new Promise(async (resolve, reject) => {
            try {
                let network = await $axios.post('change-password', payload)

                commit('SET_LOADING', false, { root: true })
                resolve(network.data)
            } catch(error) {
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