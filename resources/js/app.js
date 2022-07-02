import Vue from 'vue'
import App from './App.vue'
// import './registerServiceWorker'
import router from './router'
import store from './store'

import CoreuiVue from '@coreui/coreui'
import VueSweetalert2 from 'vue-sweetalert2'

import {
    TablePlugin,
    ButtonPlugin,
    CardPlugin,
    SpinnerPlugin,
    FormGroupPlugin,
    InputGroupPlugin,
    PaginationPlugin,
    FormCheckboxPlugin,
    BadgePlugin,
    FormSelectPlugin,
    ProgressPlugin,
    FormInputPlugin,
    ModalPlugin,
    ToastPlugin,
    FormRadioPlugin,
    CollapsePlugin,
    // BDropdown,
    DropdownPlugin
} from 'bootstrap-vue';

[TablePlugin, ButtonPlugin, CardPlugin, SpinnerPlugin, FormGroupPlugin,
    InputGroupPlugin, PaginationPlugin, FormCheckboxPlugin, BadgePlugin,
    FormSelectPlugin, ProgressPlugin, FormInputPlugin, ModalPlugin, ToastPlugin,
    FormRadioPlugin, CollapsePlugin,  DropdownPlugin].forEach(comp => {
        Vue.use(comp);
    });

import 'sweetalert2/dist/sweetalert2.min.css';

Vue.config.productionTip = false
Vue.config.performance = true

if (process.env.VUE_APP_ENV === 'production') {
    Vue.config.devtools = false;
    Vue.config.debug = false;
    Vue.config.silent = true;
}

Vue.use(CoreuiVue)
Vue.use(VueSweetalert2)

import { mapActions } from 'vuex'

new Vue({
    router,
    store,
    render: h => h(App),
    computed: {
    },
    methods: {
        ...mapActions("system_log", ["getIp"])
    },
    created() {
        this.getIp()
    }
}).$mount('#app')
