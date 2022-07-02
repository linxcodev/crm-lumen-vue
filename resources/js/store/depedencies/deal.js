import { $axios, $axios2 } from '../../services/api.js'

const state = () => ({
    dealPaginate: "",
    dealDetail: [],
    listPerusahaan: ""
})

const mutations = {
    ASSIGN_PAGINATE(state, payload) {
        state.dealPaginate = payload
    },
    ASSIGN_DETAIL(state, payload) {
        state.dealDetail = payload
    },
    SET_PERUSAHAAN(state, payload) {
        state.listPerusahaan = payload
    },
}

const actions = {
    getDeal({ commit }, payload) {
        let page = payload && typeof payload.page != 'undefined' ? payload.page : 1
        let search = payload && typeof payload.search != 'undefined' ? payload.search : ''
        commit('SET_LOADING',true, { root: true })

        return new Promise(async (resolve, reject) => {
            try {
                let network = await $axios.get(`deal?page=${page}&q=${search}`)
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
    getPerusahaan({ commit }) {
        commit('SET_LOADING',true, { root: true })
        return new Promise(async (resolve, reject) => {
            try {
                let network = await $axios.get(`deal/get-perusahaan`)
                const data = network.data.data

                commit('SET_PERUSAHAAN', data)
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
    addDeal({ commit }, payload) {
        commit('SET_LOADING',true, { root: true })
        return new Promise(async (resolve, reject) => {
            try {
                let network = await $axios.post(`deal`, payload)

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
    updateDeal({ commit }, payload) {
        commit('SET_LOADING',true, { root: true })
        return new Promise(async (resolve, reject) => {
            try {
                let network = await $axios.put(`deal/${payload.id}`, payload.data)

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
    deleteDeal({ commit }, payload) {
        commit('SET_LOADING',true, { root: true })
        return new Promise(async (resolve, reject) => {
            try {
                let network = await $axios.post(`deal/${payload.id}`, payload)

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
    getDealById({ commit }, payload) {
        commit('SET_LOADING',true, { root: true })
        return new Promise(async (resolve, reject) => {
            try {
                let network = await $axios.get(`deal/${payload}`)
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
			$axios.post(`deal/set-aktive/${payload.id}/${payload.status}`, payload)
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
    addDealTerm({ commit }, payload) {
        commit('SET_LOADING',true, { root: true })
        return new Promise(async (resolve, reject) => {
            try {
                let network = await $axios.post(`deal/term`, payload)

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
    generateDealTerm({ commit }, payload) {
        commit('SET_LOADING',true, { root: true })
        return new Promise(async (resolve, reject) => {
            try {
                let network = await $axios2.get(`deal/term/generate-pdf?id_term=${payload.id}&id_deal=${payload.id_deal}`)

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
    deleteDealTerm({ commit }, payload) {
        commit('SET_LOADING',true, { root: true })
        return new Promise(async (resolve, reject) => {
            try {
                let network = await $axios.post(`deal/term/delete`, payload)

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