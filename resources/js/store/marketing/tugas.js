import $axios from '../../services/api.js'

const state = () => ({
    tugasPaginate: [],
    tugasDetail: [],
    listKaryawan: []
})

const mutations = {
    ASSIGN_PAGINATE(state, payload) {
        state.tugasPaginate = payload
    },
    ASSIGN_DETAIL(state, payload) {
        state.tugasDetail = payload
    },
    ASSIGN_KARYAWAN(state, payload) {
        state.listKaryawan = payload
    }
}

const actions = {
    getTugas({ commit }, payload) {
        let page = payload && typeof payload.page != 'undefined' ? payload.page : 1
        let search = payload && typeof payload.search != 'undefined' ? payload.search : ''
        commit('SET_LOADING',true, { root: true })

        return new Promise(async (resolve, reject) => {
            try {
                let network = await $axios.get(`task?page=${page}&q=${search}`)
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
    addTugas({ commit }, payload) {
        commit('SET_LOADING',true, { root: true })
        return new Promise(async (resolve, reject) => {
            try {
                let network = await $axios.post(`task`, payload)

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
    updateTugas({ commit }, payload) {
        commit('SET_LOADING',true, { root: true })
        return new Promise(async (resolve, reject) => {
            try {
                let network = await $axios.put(`task/${payload.id}`, payload.data)

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
    deleteTugas({ commit }, payload) {
        commit('SET_LOADING',true, { root: true })
        return new Promise(async (resolve, reject) => {
            try {
                let network = await $axios.post(`task/${payload.id}`, payload)

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
    getTugasById({ commit }, payload) {
        commit('SET_LOADING',true, { root: true })
        return new Promise(async (resolve, reject) => {
            try {
                let network = await $axios.get(`task/${payload}`)
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
    getKaryawan({ commit }, payload) {
        commit('SET_LOADING',true, { root: true })
        return new Promise(async (resolve, reject) => {
            try {
                let network = await $axios.get(`task/get-karyawan`)
                const data = network.data.data

                commit('ASSIGN_KARYAWAN', data)
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
			$axios.post(`task/set-aktive/${payload.id}/${payload.status}`, payload)
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
    komplitTugas({ commit }, payload) {
        commit('SET_LOADING',true, { root: true })
		return new Promise((resolve, reject) => {
			$axios.post(`task/completed/${payload.id}`, payload)
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
    unKomplitTugas({ commit }, payload) {
        commit('SET_LOADING',true, { root: true })
		return new Promise((resolve, reject) => {
			$axios.post(`task/uncompleted/${payload.id}`, payload)
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