import $axios from '../../services/api.js'

const state = () => ({
    produkPaginate: [],
    produkDetail: [],
})

const mutations = {
    ASSIGN_PAGINATE(state, payload) {
        state.produkPaginate = payload
    },
    ASSIGN_DETAIL(state, payload) {
        state.produkDetail = payload
    },
}

const actions = {
    getProduk({ commit }, payload) {
        let page = payload && typeof payload.page != 'undefined' ? payload.page : 1
        let search = payload && typeof payload.search != 'undefined' ? payload.search : ''
        commit('SET_LOADING',true, { root: true })

        return new Promise(async (resolve, reject) => {
            try {
                let network = await $axios.get(`produk?page=${page}&q=${search}`)
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
    addProduk({ commit }, payload) {
        commit('SET_LOADING',true, { root: true })
        return new Promise(async (resolve, reject) => {
            try {
                let network = await $axios.post(`produk`, payload)

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
    updateProduk({ commit }, payload) {
        commit('SET_LOADING',true, { root: true })
        return new Promise(async (resolve, reject) => {
            try {
                let network = await $axios.put(`produk/${payload.id}`, payload.data)

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
    deleteProduk({ commit }, payload) {
        commit('SET_LOADING',true, { root: true })
        return new Promise(async (resolve, reject) => {
            try {
                let network = await $axios.post(`produk/${payload.id}`, payload)

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
    getProdukById({ commit }, payload) {
        commit('SET_LOADING',true, { root: true })
        return new Promise(async (resolve, reject) => {
            try {
                let network = await $axios.get(`produk/${payload}`)
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
			$axios.post(`produk/set-aktive/${payload.id}/${payload.status}`, payload)
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