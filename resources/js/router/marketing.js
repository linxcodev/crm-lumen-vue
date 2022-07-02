import TugasData from  '../views/marketing/tugas/Data.vue'
import TugasIndex from  '../views/marketing/tugas/Index.vue'
import TugasDetail from  '../views/marketing/tugas/Detail.vue'

import ProdukData from  '../views/marketing/produk/Data.vue'
import ProdukIndex from  '../views/marketing/produk/Index.vue'
import ProdukDetail from  '../views/marketing/produk/Detail.vue'

export default [
    {
        path: '/produk',
        component: ProdukIndex,
        children: [
            {
                path: '/produk',
                name: 'produk',
                component: ProdukData,
                meta: { requiresAuth: true, title: 'List Produk' }
            },
            {
                path: '/produk-detail:id',
                name: 'detail.produk',
                component: ProdukDetail,
                meta: { requiresAuth: true, title: 'Detail Produk' }
            }
        ]
    },
    {
        path: '/tugas',
        component: TugasIndex,
        children: [
            {
                path: '/tugas',
                name: 'tugas',
                component: TugasData,
                meta: { requiresAuth: true, title: 'List Tugas' }
            },
            {
                path: '/tugas-detail:id',
                name: 'detail.tugas',
                component: TugasDetail,
                meta: { requiresAuth: true, title: 'Detail Tugas' }
            }
        ]
    }
]