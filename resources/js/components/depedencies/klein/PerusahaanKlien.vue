<template>
  <!-- begin card -->
  <div class="card">
    <div class="card-header">
      <div class="row">
        <div class="col-md-6">
          <div class="card-title">
            <h5 class="mb-0">Perusahaan</h5>
          </div>
        </div>
      </div>
    </div>

    <div class="card-body">
      <table class="table table-striped table-bordered" v-if="!isLoading">
        <thead>
          <tr>
            <th>Nama</th>
            <th>No HP</th>
            <th>Nomor pajak</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="(data, index) in klienDetail.companies"
            :key="index"
            v-show="!isLoading && klienDetail.companiesCount"
          >
            <td>{{ data.nama }}</td>
            <td>{{ data.phone }}</td>
            <td>{{ data.nomor_pajak }}</td>
            <td class="text-right">
              <button :disabled="isLoading" class="btn btn-danger btn-sm">
                <i class="flaticon2-trash"></i> Hapus
              </button>
            </td>
          </tr>

          <tr v-show="!klienDetail.companiesCount">
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
  name: "PerusahaanKlien",
  created() {},
  computed: {
    ...mapGetters(["isLoading"]),
    ...mapState("klien", ["klienDetail"]),
  },
};
</script>