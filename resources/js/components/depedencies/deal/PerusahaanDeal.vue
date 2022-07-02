<template>
  <!-- begin card -->
  <div class="card" v-if="perusahaan">
    <div class="card-header">
      <div class="row">
        <div class="col-md-6">
          <div class="card-title">
            <h5 class="mb-0">Perusahaan {{ perusahaan.nama }}</h5>
          </div>
        </div>
      </div>
    </div>

    <div class="card-body">
      <table class="table table-striped table-bordered" v-if="!isLoading">
        <tbody>
          <tr>
            <th>Nama</th>
            <td>{{ perusahaan.nama }}</td>
          </tr>

          <tr>
            <th>Nomor Pajak</th>
            <td>
              {{ perusahaan.nomor_pajak }}
            </td>
          </tr>

          <tr>
            <th>No HP</th>
            <td>{{ perusahaan.phone }}</td>
          </tr>

          <tr>
            <th>Kota</th>
            <td>{{ perusahaan.kota }}</td>
          </tr>

          <tr>
            <th>Alamat Penagihan</th>
            <td>{{ perusahaan.alamat_penagihan }}</td>
          </tr>

          <tr>
            <th>Negara</th>
            <td>{{ perusahaan.negara }}</td>
          </tr>

          <tr>
            <th>Kode Pos</th>
            <td>{{ perusahaan.kode_pos }}</td>
          </tr>

          <tr>
            <th>Fax</th>
            <td>{{ perusahaan.fax }}</td>
          </tr>

          <tr>
            <th>Deskripsi</th>
            <td>{{ perusahaan.deskripsi }}</td>
          </tr>

          <tr>
            <th>Status</th>
            <td>{{ perusahaan.is_active ? "Aktif" : "Tidak aktif" }}</td>
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
  name: "PerusahaanDeal",
  computed: {
    ...mapGetters(["isLoading"]),

    ...mapState("system_log", ["log"]),
    ...mapState("deal", ['dealDetail']),

    perusahaan: function () {
      if (this.dealDetail.deal) {
        return this.dealDetail.deal.companies
      }
    }
  },
};
</script>

<style scoped>
td {
  text-align: right;
}
</style>