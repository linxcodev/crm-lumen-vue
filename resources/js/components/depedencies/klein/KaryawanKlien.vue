<template>
  <!-- begin card -->
  <div class="card">
    <div class="card-header">
      <div class="row">
        <div class="col-md-6">
          <div class="card-title">
            <h5 class="mb-0">Karyawan</h5>
          </div>
        </div>
      </div>
    </div>

    <div class="card-body">
      <table class="table table-striped table-bordered" v-if="!isLoading">
        <thead>
          <tr>
            <th>Nama lengkap</th>
            <th>No HP</th>
            <th>Email</th>
            <th>Pekerjaan</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr 
            v-for="(data, index) in klienDetail.employees"
            :key="index"
            v-show="!isLoading && klienDetail.employeesCount"
          >
            <td>{{ data.nama_lengkap }}</td>
            <td>{{ data.phone }}</td>
            <td>{{ data.email }}</td>
            <td>{{ data.pekerjaan }}</td>
            <td class="text-right">
              <button :disabled="isLoading" class="btn btn-danger btn-sm">
                <i class="flaticon2-trash"></i> Hapus
              </button>
            </td>
          </tr>

          <tr v-show="!klienDetail.employeesCount">
            <td colspan="5" class="text-center">Belum ada data.</td>
          </tr>
        </tbody>
      </table>

      <p class="text-center" v-else>Loading...</p>
    </div>
  </div>
  <!-- end card -->
</template>

<script>
import { mapGetters, mapState } from "vuex";

export default {
  name: "KaryawanKlien",
  created() {},
  computed: {
    ...mapGetters(["isLoading"]),
    ...mapState("klien", ["klienDetail"]),
  },
};
</script>