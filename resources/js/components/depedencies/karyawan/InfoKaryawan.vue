<template>
  <!-- begin card -->
  <div class="card">
    <div class="card-header">
      <div class="row">
        <div class="col-md-6">
          <div class="card-title">
            <h5 class="mb-0">Informasi Tentang {{ karyawanDetail.nama_lengkap }}</h5>
          </div>
        </div>

        <div class="col-md-6">
          <button
            :disabled="isLoading"
            class="btn btn-danger btn-sm float-right"
            @click="deleteData(karyawanDetail.id)"
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
            <td>{{ karyawanDetail.nama_lengkap }}</td>
          </tr>

          <tr>
            <th>No Hp</th>
            <td>{{ karyawanDetail.phone }}</td>
          </tr>

          <tr>
            <th>Alamat email</th>
            <td>{{ karyawanDetail.email }}</td>
          </tr>

          <tr>
            <th>Pekerjaan</th>
            <td>{{ karyawanDetail.pekerjaan }}</td>
          </tr>

          <tr>
            <th>Catatan</th>
            <td>{{ karyawanDetail.catatan }}</td>
          </tr>

          <tr>
            <th>Klien</th>
            <td>
              <router-link v-if="karyawanDetail.client"
                :to="{
                  name: 'detail.klien',
                  params: { id: karyawanDetail.client.id },
                }"
                title="Detail"
                >{{ karyawanDetail.client.nama_lengkap }}</router-link
              >
            </td>
          </tr>

          <tr>
            <th>Status</th>
            <td>{{ karyawanDetail.is_active ? "Aktif" : 'Tidak aktif' }}</td>
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
  name: "InfoKaryawan",
  computed: {
      ...mapGetters(["isLoading"]),

      ...mapState("system_log", ["log"]),
      ...mapState('karyawan', ['karyawanDetail'])
  },
  methods: {
    ...mapActions('karyawan', ['deleteKaryawan']),

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
          this.deleteKaryawan({ log: this.log, id: id})
            .then((res) => {
              if (res.error) {
                this.$bvToast.toast(res.message, errorToas());
              } else {
                this.$router.push({ name: 'karyawan', query: { toast: 'Data berhasil dihapus.' } })
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