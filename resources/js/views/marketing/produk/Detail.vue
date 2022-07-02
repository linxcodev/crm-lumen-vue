<template>
  <!-- begin card -->
  <div class="card" v-if="produkDetail">
    <div class="card-header">
      <div class="row">
        <div class="col-md-6">
          <div class="card-title">
            <h5 class="mb-0">Informasi tentang {{ produkDetail.nama }}</h5>
          </div>
        </div>

        <div class="col-md-6">
          <button
            :disabled="isLoading"
            class="btn btn-danger btn-sm float-right"
            @click="deleteData(produkDetail.id)"
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
            <td>{{ produkDetail.nama }}</td>
          </tr>

          <tr>
            <th>Kategori</th>
            <td>
              {{ produkDetail.kategori }}
            </td>
          </tr>

          <tr>
            <th>Stok</th>
            <td>{{ produkDetail.stok }}</td>
          </tr>

          <tr>
            <th>Harga</th>
            <td>{{ produkDetail.harga }}</td>
          </tr>

          <tr>
            <th>Status</th>
            <td>{{ produkDetail.is_active ? "Aktif" : "Tidak aktif" }}</td>
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
import { errorToas } from '../../../entities/notif';

export default {
  name: "ProdukDetail",
  created() {
    this.getProdukById(this.$route.params.id)
  },
  computed: {
    ...mapGetters(["isLoading"]),
    ...mapState("system_log", ["log"]),
    ...mapState("produk", ["produkDetail"]),
  },
  methods: {
    ...mapActions('produk', ['getProdukById', 'deleteProduk']),

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
          this.deleteProduk({ log: this.log, id: id })
            .then((res) => {
              if (res.error) {
                this.$bvToast.toast(res.message, errorToas());
              } else {
                this.$router.push({
                  name: "produk",
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