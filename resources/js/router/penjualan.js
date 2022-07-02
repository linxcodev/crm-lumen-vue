import KeuanganData from  '../views/penjualan/keuangan/Data.vue'
import KeuanganIndex from  '../views/penjualan/keuangan/Index.vue'
import KeuanganDetail from  '../views/penjualan/keuangan/Detail.vue'

import PenjualanData from  '../views/penjualan/penjualan/Data.vue'
import PenjualanIndex from  '../views/penjualan/penjualan/Index.vue'
import PenjualanDetail from  '../views/penjualan/penjualan/Detail.vue'

export default [
    {
        path: '/keuangan',
        component: KeuanganIndex,
        children: [
            {
                path: '/keuangan',
                name: 'keuangan',
                component: KeuanganData,
                meta: { requiresAuth: true, title: 'List Keuangan' }
            },
            {
                path: '/keuangan-detail:id',
                name: 'detail.keuangan',
                component: KeuanganDetail,
                meta: { requiresAuth: true, title: 'Detail Keuangan' }
            }
        ]
    },
    {
        path: '/penjualan',
        component: PenjualanIndex,
        children: [
            {
                path: '/penjualan',
                name: 'penjualan',
                component: PenjualanData,
                meta: { requiresAuth: true, title: 'List Penjualan' }
            },
            {
                path: '/penjualan-detail:id',
                name: 'detail.penjualan',
                component: PenjualanDetail,
                meta: { requiresAuth: true, title: 'Detail Penjualan' }
            }
        ]
    }
]