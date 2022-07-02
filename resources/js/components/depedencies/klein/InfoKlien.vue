<template>
  <!-- begin card -->
  <div class="card">
    <div class="card-header">
      <div class="row">
        <div class="col-md-6">
          <div class="card-title">
            <h5 class="mb-0">Informasi Tentang {{ klienDetail.nama_lengkap }}</h5>
          </div>
        </div>

        <div class="col-md-6">
          <button
            :disabled="isLoading"
            class="btn btn-danger btn-sm float-right"
          >
            <i class="flaticon2-trash"></i> Hapus
          </button>
        </div>
      </div>
    </div>

    <div class="card-body">
      <table class="table table-striped table-bordered" v-if="!isLoading">
        <tbody>
          <tr>
            <th>Nama lengkap</th>
            <td>{{ klienDetail.nama_lengkap }}</td>
          </tr>

          <tr>
            <th>No Hp</th>
            <td>{{ klienDetail.phone }}</td>
          </tr>

          <tr>
            <th>Alamat email</th>
            <td>{{ klienDetail.email }}</td>
          </tr>

          <tr>
            <th>Bagian</th>
            <td>{{ klienDetail.bagian }}</td>
          </tr>

          <tr>
            <th>Budget</th>
            <td>{{ (klienDetail.formattedBudget) ? klienDetail.formattedBudget.formatted : '' }}</td>
          </tr>

          <tr>
            <th>Status</th>
            <td>{{ klienDetail.is_active ? "Aktif" : 'Tidak aktif' }}</td>
          </tr>
        </tbody>
      </table>

      <p class="text-center" v-else>Loading...</p>
    </div>
  </div>
  <!-- end card -->
</template>

<script>
import { mapGetters, mapState } from 'vuex';

export default {
  name: "InfoKlien",
  computed: {
      ...mapGetters(["isLoading"]),
      ...mapState('klien', ['klienDetail'])
  },
};
</script>

<style scoped>
td {
  text-align: right;
}
</style>