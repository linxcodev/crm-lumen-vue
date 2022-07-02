import $axios from '../services/api.js'

const actions = {
	submit({ commit }, payload) {
		localStorage.setItem('token',null)

		commit('SET_TOKEN',null,{ root: true } )
		return new Promise((resolve, reject) => {
			commit('SET_LOADING', true, { root: true })
			
			$axios.post('login', payload)
			.then((response) => {
				if (response.data.status == 'success') {
					localStorage.setItem('token',response.data.access_token)
					
					commit('SET_TOKEN',response.data.access_token, { root: true })
					commit('SET_LOADING',false, { root: true })
				} else {
					commit('SET_ERRORS', { invalid: 'Email/Password salah' } , { root: true })
					commit('SET_LOADING',false, { root: true })
				}

				resolve(response.data)
			})
			.catch((error) => {
				if (error.response.status == 422) {
					commit('SET_ERRORS',error.response.data.errors, { root: true})
				}
				commit('SET_LOADING',false, { root: true })
				reject(error)
			})
		})
	},
	logoutSiswa({ commit }, payload) {
		return new Promise((resolve, reject) => {
			commit('SET_LOADING', true, { root: true })

			$axios.post('logout', payload) 
			.then((response) => {
				commit('SET_LOADING', false, { root: true })
				resolve(response.data)
			}) 
			.catch((err) => {
				commit('SET_LOADING', false, { root: true })
				reject(err.response.data)
			})
		})
	}
}

export default {
	namespaced: true,
	actions
}