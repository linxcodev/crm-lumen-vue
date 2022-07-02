<template>
  <div class="col-md-6 pl-0">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">
          Produk
          <span class="badge badge-primary"> {{ dashboard.countProduk }}</span>
        </h5>
      </div>

      <div class="card-body">
        <div v-if="dashboard.countProduk && !isLoading">
          <div
            class="list-group"
            v-for="data in dashboard.dataWithAllProducts"
            :key="data.id"
          >
            <router-link
              :to="{
                name: 'detail.produk',
                params: { id: data.id },
              }"
              class="list-group-item"
              title="Detail"
            >
              <span class="badge badge-info float-right">{{ data.created_at }}</span>
              <span class="badge badge-danger float-right mr-2"
                >{{ data.stok }} pcs</span
              >

              {{ data.nama }}
            </router-link>
          </div>

          <div class="text-right mt-2">
            <router-link style="text-decoration: none" :to="{ name: 'produk' }">
              Selengkapnya
            </router-link>
          </div>
        </div>

        <div class="text-center" v-else>
            <div v-if="!dashboard.countProduk">
                Tidak ada data.
            </div>
            <div v-else>
                Loading...
            </div>
        </div>
      </div>
      <!-- end body -->
    </div>
  </div>
</template>

<script>
import { mapGetters, mapState } from 'vuex';

export default {
  name: "ProdukDashboard",
  computed: {
      ...mapGetters(["isLoading"]),
      ...mapState("dashboard", ["dashboard"]),
  }
};
</script>