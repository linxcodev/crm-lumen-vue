<template>
  <div class="col-md-6">
    <div class="card">
      <div class="card-header">
        <div class="row">
          <div class="col-md-3">
            <h5 class="mb-0">
              Tugas
              <span class="badge badge-primary">
                {{ dashboard.countTugas }}</span
              >
            </h5>
          </div>

          <div class="col-md-9 text-right">
            Completed: {{ dashboard.completedTugas }} | Uncompleted:
            {{ dashboard.uncompletedTugas }}
          </div>
        </div>
      </div>

      <div class="card-body">
        <div v-if="dashboard.countTugas && !isLoading">
          <div
            class="list-group"
            v-for="data in dashboard.dataWithAllTasks"
            :key="data.id"
          >
            <router-link
              :to="{
                name: 'detail.tugas',
                params: { id: data.id },
              }"
              class="list-group-item"
              title="Detail"
            >
              <span class="badge badge-info float-right">{{ data.created_at }}</span>
              <span class="badge badge-danger float-right mr-2"
                >Durasi: {{ data.durasi }} hari</span
              >
              {{ data.nama }}
            </router-link>
          </div>

          <div class="text-right mt-2">
            <router-link style="text-decoration: none" :to="{ name: 'tugas' }">
              Selengkapnya
            </router-link>
          </div>
        </div>

        <div class="text-center" v-else>
          <div v-if="!dashboard.countTugas">Tidak ada data.</div>
          <div v-else>Loading...</div>
        </div>
      </div>
      <!-- end body -->
    </div>
  </div>
</template>

<script>
import { mapGetters, mapState } from 'vuex';

export default {
  name: "TugasDashboard",
  computed: {
    ...mapGetters(["isLoading"]),
    ...mapState("dashboard", ["dashboard"]),
    
  },
};
</script>