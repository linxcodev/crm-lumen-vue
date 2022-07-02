import KaryawanData from '../views/depedencies/karyawan/Data.vue'
import KaryawanIndex from '../views/depedencies/karyawan/Index.vue'
import KaryawanDetail from '../views/depedencies/karyawan/Detail.vue'

import KlienData from '../views/depedencies/klien/Data.vue'
import KlienIndex from '../views/depedencies/klien/Index.vue'
import KlienDetail from '../views/depedencies/klien/Detail.vue'
import PembayaranKlien from '../views/depedencies/klien/PembayaranKlien.vue'

import DealData from '../views/depedencies/deal/Data.vue'
import DealIndex from '../views/depedencies/deal/Index.vue'
import DealDetail from '../views/depedencies/deal/Detail.vue'

import PerusahaanData from '../views/depedencies/perusahaan/Data.vue'
import PerusahaanIndex from '../views/depedencies/perusahaan/Index.vue'
import PerusahaanDetail from '../views/depedencies/perusahaan/Detail.vue'

export default [
    {
        path: '/klien',
        component: KlienIndex,
        children: [
            {
                path: '/klien',
                name: 'klien',
                component: KlienData,
                meta: { requiresAuth: true, title: 'List Klien' }
            },
            {
                path: '/klien-detail:id',
                name: 'detail.klien',
                component: KlienDetail,
                meta: { requiresAuth: true, title: 'Detail Klien' }
            },
            {
                path: '/klien-pembayaran',
                name: 'pembayaran.klien',
                component: PembayaranKlien,
                meta: { requiresAuth: true, title: 'List Pembayaran Klien' }
            }
        ]
    },
    {
        path: '/deal',
        component: DealIndex,
        children: [
            {
                path: '/deal',
                name: 'deal',
                component: DealData,
                meta: { requiresAuth: true, title: 'List Deal' }
            },
            {
                path: '/deal-detail:id',
                name: 'detail.deal',
                component: DealDetail,
                meta: { requiresAuth: true, title: 'Detail Deal' }
            }
        ]
    },
    {
        path: '/karyawan',
        component: KaryawanIndex,
        children: [
            {
                path: '/karyawan',
                name: 'karyawan',
                component: KaryawanData,
                meta: { requiresAuth: true, title: 'List Karyawan' }
            },
            {
                path: '/karyawan-detail:id',
                name: 'detail.karyawan',
                component: KaryawanDetail,
                meta: { requiresAuth: true, title: 'Detail Karyawan' }
            }
        ]
    },
    {
        path: '/perusahaan',
        component: PerusahaanIndex,
        children: [
            {
                path: '/perusahaan',
                name: 'perusahaan',
                component: PerusahaanData,
                meta: { requiresAuth: true, title: 'List Perusahaan' }
            },
            {
                path: '/perusahaan-detail:id',
                name: 'detail.perusahaan',
                component: PerusahaanDetail,
                meta: { requiresAuth: true, title: 'Detail Perusahaan' }
            }
        ]
    }
]