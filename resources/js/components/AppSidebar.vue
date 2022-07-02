<template>
      <div class="c-sidebar c-sidebar-dark c-sidebar-fixed c-sidebar-lg-show" id="sidebar">
      <div class="c-sidebar-brand"><img class="c-sidebar-brand-full" :src="'/img/logo.png'" style="max-height: 46px" alt="Logo"></div>
      
      <ul class="c-sidebar-nav">
        <li class="c-sidebar-nav-title">System</li>
        
        <li class="c-sidebar-nav-item">
          <router-link class="c-sidebar-nav-link" :to="{  name: 'dashboard' }"
          :class="[currentPage.includes('dashboard') ? activeClass : '']">
            <i class="mr-3 flaticon-squares-4"></i>Dashboard
          </router-link>
        </li>

        <li class="c-sidebar-nav-item">
          <router-link class="c-sidebar-nav-link" :to="{  name: 'pengaturan' }"
          :class="[currentPage.includes('pengaturan') ? activeClass : '']">
            <i class="mr-3 flaticon-settings-1"></i> Pengaturan
          </router-link>
        </li>

        <li class="c-sidebar-nav-title">Depedencies</li>

        <li class="c-sidebar-nav-item c-sidebar-nav-dropdown">
          <a class="c-sidebar-nav-link c-sidebar-nav-dropdown-toggle" href="#">
            <i class="mr-3 flaticon-folder"></i> Klien
          </a>

          <ul class="c-sidebar-nav-dropdown-items">
            <li class="c-sidebar-nav-item">
              <router-link class="c-sidebar-nav-link" :to="{ name: 'klien' }"
              :class="[currentPage.includes('klien') ? activeClass : '']"> 
                <span class="c-sidebar-nav-icon"></span> List
              </router-link>
            </li>
            <li class="c-sidebar-nav-item">
              <router-link class="c-sidebar-nav-link" :to="{ name: 'pembayaran.klien' }"
              :class="[currentPage.includes('pembayaran.klien') ? activeClass : '']">
                <span class="c-sidebar-nav-icon"></span> Pembayaran
              </router-link>
            </li>

          </ul>
        </li>

        <li class="c-sidebar-nav-item">
          <router-link class="c-sidebar-nav-link" :to="{  name: 'karyawan' }"
          :class="[currentPage.includes('karyawan') ? activeClass : '']">
            <i class="mr-3 flaticon-map"></i> Karyawan
          </router-link>
        </li>

        <li class="c-sidebar-nav-item">
          <router-link class="c-sidebar-nav-link" :to="{  name: 'deal' }"
          :class="[currentPage.includes('deal') ? activeClass : '']">
            <i class="mr-3 flaticon-diagram"></i> Deal
          </router-link>
        </li>

        <li class="c-sidebar-nav-item">
          <router-link class="c-sidebar-nav-link" :to="{  name: 'perusahaan' }"
          :class="[currentPage.includes('perusahaan') ? activeClass : '']">
            <i class="mr-3 flaticon-clipboard"></i> Perusahaan
          </router-link>
        </li>

        <li class="c-sidebar-nav-title">Marketing</li>
        
        <li class="c-sidebar-nav-item">
          <router-link class="c-sidebar-nav-link" :to="{  name: 'produk' }"
          :class="[currentPage.includes('produk') ? activeClass : '']">
            <i class="mr-3 flaticon-open-box"></i> Produk
          </router-link>
        </li>

        <li class="c-sidebar-nav-item">
          <router-link class="c-sidebar-nav-link" :to="{  name: 'tugas' }"
          :class="[currentPage.includes('tugas') ? activeClass : '']">
            <i class="mr-3 flaticon-tool"></i> Tugas
          </router-link>
        </li>

        <li class="c-sidebar-nav-title">Penjualan</li>
        
        <li class="c-sidebar-nav-item">
          <router-link class="c-sidebar-nav-link" :to="{  name: 'keuangan' }"
          :class="[currentPage.includes('keuangan') ? activeClass : '']">
            <i class="mr-3 flaticon-coins"></i> Keuangan
          </router-link>
        </li>

        <li class="c-sidebar-nav-item">
          <router-link class="c-sidebar-nav-link" :to="{  name: 'penjualan' }"
          :class="[currentPage.includes('penjualan') ? activeClass : '']">
            <i class="mr-3 flaticon-graph"></i> Penjualan
          </router-link>
        </li>

      </ul>

    </div>
</template>
<script>
import { mapState, mapActions, mapGetters } from 'vuex'
import { successToas, errorToas} from '../entities/notif'

export default {
  data() {
    return {
      activeClass: 'c-active'
    }
  },
  methods: {
  },
  computed: {
    ...mapGetters(['isAuth','baseURL']),
    
    currentPage() {
      return this.$route.name ? [this.$route.name] : [];
    }
  },
  async created() {
  }
}
</script>
<style type="text/css">
  
</style>