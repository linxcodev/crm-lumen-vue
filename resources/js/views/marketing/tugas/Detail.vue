<template>
  <!-- begin card -->
  <div class="card">
    <div class="card-header">
      <div class="row">
        <div class="col-md-6">
          <div class="card-title">
            <h5 class="mb-0">Informasi Tentang {{ tugasDetail.nama }}</h5>
          </div>
        </div>

        <div class="col-md-6">
          <button
            :disabled="isLoading"
            class="btn btn-danger btn-sm float-right"
            @click="deleteData(tugasDetail.id)"
          >
            <i class="flaticon2-trash"></i> Hapus
          </button>

          <button
            :disabled="isLoading"
            class="btn btn-success btn-sm float-right mr-2"
            @click="komplitUnkomplit(!tugasDetail.completed, tugasDetail.id)"
          >
            {{ (tugasDetail.completed) ? 'Belum Komplit' : 'Komplitkan tugas' }}
          </button>
        </div>
      </div>
    </div>

    <div class="card-body">
      <table class="table table-striped table-bordered" v-if="!isLoading">
        <tbody>
          <tr>
            <th>Nama</th>
            <td>{{ tugasDetail.nama }}</td>
          </tr>

          <tr>
            <th>Karyawan</th>
            <td v-if="tugasDetail.employees">
              <router-link
                :to="{
                  name: 'detail.karyawan',
                  params: { id: tugasDetail.employees.id },
                }"
                title="Detail"
                >{{ tugasDetail.employees.nama_lengkap }}</router-link
              >
            </td>
          </tr>

          <tr>
            <th>Tanggal</th>
            <td>{{ tugasDetail.created_at }}</td>
          </tr>

          <tr>
            <th>Status</th>
            <td>{{ tugasDetail.is_active ? "Aktif" : "Tidak aktif" }}</td>
          </tr>

          <tr>
            <th>Komplit</th>
            <td>{{ tugasDetail.completed ? "Sudah" : "Belum" }} Komplit</td>
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
  name: "DetailTugas",
  created() {
    this.getTugasById(this.$route.params.id)
  },
  computed: {
      ...mapGetters(["isLoading"]),

      ...mapState("system_log", ["log"]),
      ...mapState('tugas', ['tugasDetail'])
  },
  methods: {
    ...mapActions('tugas', ['deleteTugas', 'getTugasById', 'komplitTugas', 'unKomplitTugas']),

    async komplitUnkomplit(komplit, id) {
      try {
        if (komplit) {
          await this.komplitTugas({id: id, log: this.log})
        } else {
          await this.unKomplitTugas({id: id, log: this.log})
        }
  
        this.$bvToast.toast("Komplit tugas berhasil diubah.", successToas());
        this.getTugasById(this.$route.params.id)
      } catch (error) {
        this.$bvToast.toast(error.message, errorToas());
      }
    },
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
          this.deleteTugas({ log: this.log, id: id})
            .then((res) => {
              if (res.error) {
                this.$bvToast.toast(res.message, errorToas());
              } else {
                this.$router.push({ name: 'tugas', query: { toast: 'Data berhasil dihapus.' } })
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