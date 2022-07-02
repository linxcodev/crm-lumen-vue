import { $geoip } from '../../services/api.js'

const state = () => ({
    log: [],
})

const mutations = {
    ASSIGN_DATA(state, payload) {
        state.log = {
            ip_address: payload.ip, 
            kota: payload.city,
            negara: payload.country_name,
            asn: payload.asn
        }
    }
}

const actions = {
    getIp({ commit }) {
        commit('SET_LOADING',true, { root: true })

        return new Promise(async (resolve, reject) => {
            try {
                // mendapatkan informasi ip
                const network = await $geoip.get('https://json.geoiplookup.io')
                
                commit('ASSIGN_DATA', network.data)
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