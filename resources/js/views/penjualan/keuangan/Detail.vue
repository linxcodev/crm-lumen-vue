<template>
  <!-- begin card -->
  <div class="card" v-if="keuanganDetail">
    <div class="card-header">
      <div class="row">
        <div class="col-md-6">
          <div class="card-title">
            <h5 class="mb-0">Informasi Tentang {{ keuanganDetail.nama }}</h5>
          </div>
        </div>

        <div class="col-md-6">
          <button
            :disabled="isLoading"
            class="btn btn-danger btn-sm float-right"
            @click="deleteData(keuanganDetail.id)"
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
            <td>{{ keuanganDetail.nama }}</td>
          </tr>

          <tr>
            <th>Deskripsi</th>
            <td>{{ keuanganDetail.deskripsi }}</td>
          </tr>

          <tr>
            <th>Kategori</th>
            <td>{{ keuanganDetail.kategori }}</td>
          </tr>

          <tr>
            <th>Tipe</th>
            <td>{{ keuanganDetail.type }}</td>
          </tr>

          <tr>
            <th>Gross</th>
            <td>{{ keuanganDetail.gross }}</td>
          </tr>

          <tr>
            <th>Net</th>
            <td>{{ keuanganDetail.net }}</td>
          </tr>

          <tr>
            <th>Vat</th>
            <td>{{ keuanganDetail.vat }}</td>
          </tr>

          <tr>
            <th>Perusahaan</th>
            <td v-if="keuanganDetail.companies">
              <router-link
                :to="{
                  name: 'detail.perusahaan',
                  params: { id: keuanganDetail.companies.id },
                }"
                title="Detail"
                >{{ keuanganDetail.companies.nama }}</router-link
              >
            </td>
          </tr>

          <tr>
            <th>Tanggal</th>
            <td>{{ keuanganDetail.date }}</td>
          </tr>

          <tr>
            <th>Status</th>
            <td>{{ keuanganDetail.is_active ? "Aktif" : "Tidak aktif" }}</td>
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
  name: "DetailKeuangan",
  created() {
    this.getKeuanganById(this.$route.params.id)
  },
  computed: {
      ...mapGetters(["isLoading"]),

      ...mapState("system_log", ["log"]),
      ...mapState('keuangan', ['keuanganDetail'])
  },
  methods: {
    ...mapActions('keuangan', ['deleteKeuangan', 'getKeuanganById']),

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
          this.deleteKeuangan({ log: this.log, id: id})
            .then((res) => {
              if (res.error) {
                this.$bvToast.toast(res.message, errorToas());
              } else {
                this.$router.push({ name: 'keuangan', query: { toast: 'Data berhasil dihapus.' } })
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