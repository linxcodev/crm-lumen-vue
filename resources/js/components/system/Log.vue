<template>
  <!-- begin card -->
  <div class="card">
    <div class="card-header">
      <div class="card-title">
        <h5 class="mb-0">System Log</h5>
      </div>
    </div>

    <div class="card-body">
      <table class="table table-striped table-hover">
        <thead>
          <tr>
            <th>ID User</th>
            <th>Aksi</th>
            <th>Kota</th>
            <th>Negara</th>
            <th>IP Address</th>
            <th>Tanggal</th>
          </tr>
        </thead>

        <tbody>
          <tr v-for="(log, index) in list_log" :key="index" v-show="!isLoading && list_log.length">
            <td>{{ log.id_user }}</td>
            <td>{{ log.aksi }}</td>
            <td>{{ log.kota }}</td>
            <td>{{ log.negara }}</td>
            <td>{{ log.ip_address }}</td>
            <td>{{ log.tanggal }}</td>
          </tr>
          
          <tr v-show="isLoading">
            <td colspan="7" class="text-center">Loading...</td>
          </tr>

          <tr v-show="!isLoading && !list_log.length">
            <td colspan="7" class="text-center">Tidak ada data</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  <!-- end card -->
</template>

<script>
import { mapGetters, mapState } from 'vuex';

export default {
  name: 'SystemLog',
  computed: {
    ...mapGetters(["isLoading"]),
    ...mapState(["errors"]),
    ...mapState('pengaturan', ['list_log'])
  }
};
</script>