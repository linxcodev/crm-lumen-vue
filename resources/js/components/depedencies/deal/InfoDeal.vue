<template>
  <!-- begin card -->
  <div class="card" v-if="dealDetail">
    <div class="card-header">
      <div class="row">
        <div class="col-md-6">
          <div class="card-title">
            <h5 class="mb-0">Informasi Tentang {{ dealDetail.nama }}</h5>
          </div>
        </div>

        <div class="col-md-6">
          <button
            :disabled="isLoading"
            class="btn btn-danger btn-sm float-right"
            @click="deleteData(dealDetail.id)"
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
            <td>{{ dealDetail.nama }}</td>
          </tr>

          <tr>
            <th>Perusahaan</th>
            <td>
              <router-link
                :to="{
                  name: 'detail.deal',
                  params: { id: dealDetail.companies.id },
                }"
                title="Detail"
                >{{ dealDetail.companies.nama }}</router-link
              >
            </td>
          </tr>

          <tr>
            <th>Tanggal Mulai</th>
            <td>{{ dealDetail.waktu_mulai }}</td>
          </tr>

          <tr>
            <th>Tanggal Berakhir</th>
            <td>{{ dealDetail.waktu_berakhir }}</td>
          </tr>

          <tr>
            <th>Status</th>
            <td>{{ dealDetail.is_active ? "Aktif" : "Tidak aktif" }}</td>
          </tr>
        </tbody>
      </table>

      <p class="text-center" v-else>Loading...</p>
    </div>
  </div>
  <!-- end card -->
</template>

<script>
import { mapActions, mapGetters, mapState } from "vuex";
import { errorToas, successToas } from "../../../entities/notif";

export default {
  name: "InfoDeal",
  computed: {
    ...mapGetters(["isLoading"]),

    ...mapState("system_log", ["log"]),
    ...mapState("deal", {
      dealDetail: (state) => state.dealDetail.deal,
    }),
  },
  methods: {
    ...mapActions("deal", ["deleteDeal"]),

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
          this.deleteDeal({ log: this.log, id: id })
            .then((res) => {
              if (res.error) {
                this.$bvToast.toast(res.message, errorToas());
              } else {
                this.$router.push({
                  name: "deal",
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
  },
};
</script>

<style scoped>
td {
  text-align: right;
}
</style>