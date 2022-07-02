import $axios from '../../services/api.js'

const state = () => ({
    dashboard: [],
})

const mutations = {
    ASSIGN_DATA(state, payload) {
        state.dashboard = payload
    }
}

const actions = {
    getDashboard({ commit }) {
        commit('SET_LOADING',true, { root: true })

        return new Promise(async (resolve, reject) => {
            try {
                let network = await $axios.get(`dashboard`)
                const data = network.data.data

                commit('ASSIGN_DATA', data)
                commit('SET_LOADING', false, { root: true })

                resolve(network.data)
            } catch (error) {
                commit('SET_LOADING', false, { root: true })
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