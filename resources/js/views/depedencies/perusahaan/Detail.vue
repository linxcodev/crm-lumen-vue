<template>
  <!-- begin card -->
  <div class="card" v-if="perusahaanDetail">
    <div class="card-header">
      <div class="row">
        <div class="col-md-6">
          <div class="card-title">
            <h5 class="mb-0">Informasi tentang {{ perusahaanDetail.nama }}</h5>
          </div>
        </div>

        <div class="col-md-6">
          <button
            :disabled="isLoading"
            class="btn btn-danger btn-sm float-right"
            @click="deleteData(perusahaanDetail.id)"
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
            <th>Nama</th>
            <td>{{ perusahaanDetail.nama }}</td>
          </tr>

          <tr>
            <th>Nomor Pajak</th>
            <td>
              {{ perusahaanDetail.nomor_pajak }}
            </td>
          </tr>

          <tr>
            <th>No HP</th>
            <td>{{ perusahaanDetail.phone }}</td>
          </tr>

          <tr>
            <th>Kota</th>
            <td>{{ perusahaanDetail.kota }}</td>
          </tr>

          <tr>
            <th>Alamat Penagihan</th>
            <td>{{ perusahaanDetail.alamat_penagihan }}</td>
          </tr>

          <tr>
            <th>Negara</th>
            <td>{{ perusahaanDetail.negara }}</td>
          </tr>

          <tr>
            <th>Kode Pos</th>
            <td>{{ perusahaanDetail.kode_pos }}</td>
          </tr>

          <tr>
            <th>Fax</th>
            <td>{{ perusahaanDetail.fax }}</td>
          </tr>

          <tr>
            <th>Deskripsi</th>
            <td>{{ perusahaanDetail.deskripsi }}</td>
          </tr>

          <tr>
            <th>Status</th>
            <td>{{ perusahaanDetail.is_active ? "Aktif" : "Tidak aktif" }}</td>
          </tr>
        </tbody>
      </table>

      <p class="text-center" v-else>Loading...</p>
    </div>
  </div>
  <!-- end card -->
</template>

<script>
import { mapActions, mapGetters, mapState } from 'vuex';
import { errorToas, successToas } from '../../../entities/notif';

export default {
  name: "PerusahaanDetail",
  created() {
    this.getPerusahaanById(this.$route.params.id)
  },
  computed: {
    ...mapGetters(["isLoading"]),
    ...mapState("system_log", ["log"]),
    ...mapState("perusahaan", ["perusahaanDetail"]),
  },
  methods: {
    ...mapActions('perusahaan', ['getPerusahaanById', 'deletePerusahaan']),

    deleteData(id) {
      this.$swal({
        title: "Informasi",
        text: "Ini tidak akan dihapus secara permanent, Anda dapat menghapus permanent di tempat sampah!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#c3c3c3",
        confirmButtonText: "Iya, Lanjutkan!",
      }).then((result) => {
        if (result.value) {
          this.deletePerusahaan({ log: this.log, id: id })
            .then((res) => {
              if (res.error) {
                this.$bvToast.toast(res.message, errorToas());
              } else {
                this.$router.push({
                  name: "perusahaan",
                  query: { toast: "Data berhasil dihapus." },
                });
              }
            })
            .catch((error) => {
              this.$bvToast.toast(error.message, errorToas());
            });
        }
      });
    },
  }
};
</script>

<style scoped>
td {
  text-align: right;
}
</style>