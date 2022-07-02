import Dashboard from  '../views/system/Dashboard.vue'
import Pengaturan from  '../views/system/Pengaturan.vue'

export default [
    {
        path: '/',
        name: 'dashboard',
        component: Dashboard,
        meta: { requiresAuth: true, title: 'Dasboard' }
    },
    {
        path: '/pengaturan',
        name: 'pengaturan',
        component: Pengaturan,
        meta: { requiresAuth: true, title: 'Pengaturan' }
    }
]