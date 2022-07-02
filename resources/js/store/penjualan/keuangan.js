import $axios from '../../services/api.js'

const state = () => ({
    keuanganPaginate: [],
    keuanganDetail: [],
})

const mutations = {
    ASSIGN_PAGINATE(state, payload) {
        state.keuanganPaginate = payload
    },
    ASSIGN_DETAIL(state, payload) {
        state.keuanganDetail = payload
    }
}

const actions = {
    getKeuangan({ commit }, payload) {
        let page = payload && typeof payload.page != 'undefined' ? payload.page : 1
        let search = payload && typeof payload.search != 'undefined' ? payload.search : ''
        commit('SET_LOADING',true, { root: true })

        return new Promise(async (resolve, reject) => {
            try {
                let network = await $axios.get(`keuangan?page=${page}&q=${search}`)
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
    addKeuangan({ commit }, payload) {
        commit('SET_LOADING',true, { root: true })
        return new Promise(async (resolve, reject) => {
            try {
                let network = await $axios.post(`keuangan`, payload)

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
    updateKeuangan({ commit }, payload) {
        commit('SET_LOADING',true, { root: true })
        return new Promise(async (resolve, reject) => {
            try {
                let network = await $axios.put(`keuangan/${payload.id}`, payload.data)

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
    deleteKeuangan({ commit }, payload) {
        commit('SET_LOADING',true, { root: true })
        return new Promise(async (resolve, reject) => {
            try {
                let network = await $axios.post(`keuangan/${payload.id}`, payload)

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
    getKeuanganById({ commit }, payload) {
        commit('SET_LOADING',true, { root: true })
        return new Promise(async (resolve, reject) => {
            try {
                let network = await $axios.get(`keuangan/${payload}`)
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
    setStatus({ commit }, payload) {
        commit('SET_LOADING',true, { root: true })
		return new Promise((resolve, reject) => {
			$axios.post(`keuangan/set-aktive/${payload.id}/${payload.status}`, payload)
			.then((response) => {
                commit('SET_LOADING', false, { root: true })
				resolve(response.data)
			})
			.catch((error) => {
                commit('SET_LOADING', false, { root: true })
				reject(error.response.data)
			})
		})
	},
}

export default {
    namespaced: true,
    state,
    mutations,
    actions
}