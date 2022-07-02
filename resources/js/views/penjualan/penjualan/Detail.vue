<template>
  <!-- begin card -->
  <div class="card" v-if="penjualanDetail">
    <div class="card-header">
      <div class="row">
        <div class="col-md-6">
          <div class="card-title">
            <h5 class="mb-0">Informasi Tentang {{ penjualanDetail.nama }}</h5>
          </div>
        </div>

        <div class="col-md-6">
          <button
            :disabled="isLoading"
            class="btn btn-danger btn-sm float-right"
            @click="deleteData(penjualanDetail.id)"
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
            <td>{{ penjualanDetail.nama }}</td>
          </tr>

          <tr>
            <th>Produk</th>
            <td v-if="penjualanDetail.products">
              <router-link
                :to="{
                  name: 'detail.produk',
                  params: { id: penjualanDetail.products.id },
                }"
                title="Detail"
                >{{ penjualanDetail.products.nama }}</router-link
              >
            </td>
          </tr>

          <tr>
            <th>Quantity</th>
            <td>{{ penjualanDetail.quantity }}</td>
          </tr>

          <tr>
            <th>Tanggal pembayaran</th>
            <td>{{ penjualanDetail.tangal_pembayaran }}</td>
          </tr>

          <tr>
            <th>Status</th>
            <td>{{ penjualanDetail.is_active ? "Aktif" : "Tidak aktif" }}</td>
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
  name: "DetailPenjualan",
  created() {
    this.getPenjualanById(this.$route.params.id)
  },
  computed: {
      ...mapGetters(["isLoading"]),

      ...mapState("system_log", ["log"]),
      ...mapState('penjualan', ['penjualanDetail'])
  },
  methods: {
    ...mapActions('penjualan', ['deletePenjualan', 'getPenjualanById']),

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
          this.deletePenjualan({ log: this.log, id: id})
            .then((res) => {
              if (res.error) {
                this.$bvToast.toast(res.message, errorToas());
              } else {
                this.$router.push({ name: 'penjualan', query: { toast: 'Data berhasil dihapus.' } })
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