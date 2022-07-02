<template>
  <div class="col-md-6">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">
          Perusahaan
          <span class="badge badge-primary">
            {{ dashboard.countPerusahaan }}</span
          >
        </h5>
      </div>

      <div class="card-body">
        <div v-if="dashboard.countPerusahaan && !isLoading">
          <div
            class="list-group"
            v-for="data in dashboard.dataWithAllCompanies"
            :key="data.id"
          >
            <router-link
              :to="{
                name: 'detail.perusahaan',
                params: { id: data.id },
              }"
              class="list-group-item"
              title="Detail"
            >
              <span class="badge badge-warning float-right text-white"
                >Phone: {{ data.phone }}</span
              >

              {{ data.nama }}
            </router-link>
          </div>

          <div class="text-right mt-2">
            <router-link
              style="text-decoration: none"
              :to="{ name: 'perusahaan' }"
            >
              Selengkapnya
            </router-link>
          </div>
        </div>

        <div class="text-center" v-else>
            <div v-if="!dashboard.countPerusahaan">
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
  name: "PerusahaanDashboard",
  computed: {
    ...mapGetters(["isLoading"]),
    ...mapState("dashboard", ["dashboard"]),
  },
};
</script>