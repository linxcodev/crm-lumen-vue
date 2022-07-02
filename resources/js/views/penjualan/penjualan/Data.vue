<template>
  <!-- begin card -->
  <div class="card">
    <div class="card-header">
      <div class="row">
        <div class="col-md-6">
          <div class="card-title">
            <h5 class="mb-0">List Penjualan</h5>
          </div>
        </div>

        <div class="col-md-6">
          <button
            class="btn btn-primary btn-sm float-right"
            @click="$bvModal.show('modal-scoped')"
          >
            Tambah penjualan
          </button>
        </div>
      </div>
    </div>

    <div class="card-body">
      <div class="col-md-6">
        <b-form-group
          label="Cari"
          label-cols-sm="3"
          label-cols-md="3"
          label-align-sm="right"
          label-size="sm"
          label-for="nama_penjualan"
          class="mb-4"
        >
          <b-input-group size="sm">
            <b-form-input
              v-model="nama_penjualan"
              type="search"
              id="nama_penjualan"
              placeholder="Nama"
            ></b-form-input>

            <b-input-group-append>
              <b-button :disabled="!nama_penjualan" @click="nama_penjualan = ''"
                >Clear</b-button
              >
            </b-input-group-append>
          </b-input-group>
        </b-form-group>
      </div>

      <table class="table table-striped table-hover mt-3">
        <thead>
          <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Quantity</th>
            <th>Harga</th>
            <th>Produk</th>
            <th>Tanggal pembayaran</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>

        <tbody>
          <tr
            v-for="(data, index) in penjualanPaginate.data"
            :key="index"
            v-show="!isLoading && penjualanPaginate.data.length"
          >
            <td>{{ index + 1 }}</td>
            <td>
              <router-link
                :to="{
                  name: 'detail.penjualan',
                  params: { id: data.id },
                }"
                title="Detail"
                >{{ data.nama }}</router-link
              >
            </td>
            <td>
              {{ data.quantity }}
            </td>
            <td>{{ data.harga.formatted }}</td>
            <td>
              <router-link
                :to="{
                  name: 'detail.produk',
                  params: { id: data.products.id },
                }"
                title="Detail"
                >{{ data.products.nama }}</router-link
              >
            </td>
            <td>{{ data.tangal_pembayaran }}</td>
            <td>
              <div class="form-group">
                <b-form-checkbox
                  switch
                  value="1"
                  v-model="data.is_active"
                  @change="seterStatus(data.id, data.is_active)"
                >
                  {{ data.is_active ? "Aktif" : "Tidak aktif" }}
                </b-form-checkbox>
              </div>
            </td>
            <td>
              <b-button
                class="mr-1"
                @click="getDataId(data.id)"
                size="sm"
                variant="warning"
                :disabled="isLoading"
                ><i class="flaticon-edit"></i> Edit</b-button
              >
              <button
                :disabled="isLoading"
                class="btn btn-danger btn-sm"
                @click="deleteData(data.id)"
              >
                <i class="flaticon2-trash"></i> Hapus
              </button>
            </td>
          </tr>

          <tr v-show="isLoading">
            <td colspan="8" class="text-center">Loading...</td>
          </tr>

          <tr v-show="!isLoading && !penjualanPaginate.total">
            <td colspan="8" class="text-center">Belum ada data.</td>
          </tr>
        </tbody>
      </table>

      <div class="row mt-2">
        <div class="col-md-6">
          <p v-if="penjualanPaginate.data">
            <i class="fa fa-bars"></i>
            <b>{{ penjualanPaginate.data.length }}</b> penjualan dari
            <b>{{ penjualanPaginate.total }}</b> total data penjualan
          </p>
        </div>
        <div class="col-md-6">
          <div class="float-right">
            <b-pagination
              size="sm"
              v-model="page"
              :total-rows="penjualanPaginate.total"
              :per-page="penjualanPaginate.per_page"
              :disabled="isLoading"
            ></b-pagination>
          </div>
        </div>
      </div>
      <!-- end body -->
    </div>

    <!-- modal -->
    <b-modal id="modal-scoped" noCloseOnBackdrop>
      <template v-slot:modal-header="{ close }">
        <h5>{{ update > 0 ? "Update" : "Tambah" }} penjualan</h5>
      </template>

      <div class="form-group">
        <label>Nama</label>

        <input type="text" class="form-control" v-model="data.nama" />
        <small class="text-danger" v-if="errors.nama">{{
          errors.nama[0]
        }}</small>
      </div>

      <div class="form-group">
        <label>Produk</label>

        <v-select
          label="nama"
          :options="listProduk"
          v-model="data.id_produk"
          :reduce="(nama) => nama.id"
        ></v-select>
        <small class="text-danger" v-if="errors.id_produk">{{
          errors.id_produk[0]
        }}</small>
      </div>

      <div class="form-group">
        <label>Quantity</label>

        <input type="text" class="form-control" v-model="data.quantity" />
        <small class="text-danger" v-if="errors.quantity">{{
          errors.quantity[0]
        }}</small>
      </div>

      <div class="form-group">
        <label>Harga</label>

        <input type="text" class="form-control" v-model="formatHarga" />
        <small class="text-danger" v-if="errors.harga">{{
          errors.harga[0]
        }}</small>
      </div>

      <div class="form-group">
        <label>Tanggal pembayaran</label>

        <input
          type="date"
          class="form-control"
          v-model="data.tangal_pembayaran"
        />
        <small class="text-danger" v-if="errors.tangal_pembayaran">{{
          errors.tangal_pembayaran[0]
        }}</small>
      </div>

      <template v-slot:modal-footer="{ cancel }">
        <b-button
          size="sm"
          variant="primary"
          @click="postData"
          :disabled="isLoading"
        >
          {{ isLoading ? "Processing..." : "Simpan" }}
        </b-button>
        <b-button
          size="sm"
          variant="secondary"
          @click="close()"
          :disabled="isLoading"
        >
          Cancel
        </b-button>
      </template>
    </b-modal>
  </div>
  <!-- end card -->
</template>

<script>
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";
import { mapActions, mapGetters, mapState } from 'vuex';
import { errorToas, successToas } from '../../../entities/notif';

export default {
  name: "DataPenjualan",
  components: {
    "v-select": vSelect,
  },
  data() {
    return {
      page: 1,
      update: 0,
      nama_penjualan: "",
      data: {
        log: "",
        nama: "",
        quantity: "",
        id_produk: "",
        tangal_pembayaran: "",
        harga: ""
      },
    };
  },
  created() {
    const query = Object.assign({}, this.$route.query);
    if (query.toast) {
      this.$bvToast.toast(query.toast, successToas());

      delete query.toast;
      this.$router.replace({ query });
    }

    this.getProduk()
    this.getPenjualan()
  },
  computed: {
    ...mapGetters(["isLoading"]),

    ...mapState(["errors"]),
    ...mapState("system_log", ["log"]),
    ...mapState("penjualan", ["penjualanPaginate", "listProduk"]),

    formatHarga: {
      get: function () {
        var number_string = this.data.harga.toString().replace(/[^,\d]/g, ""),
          split = number_string.split(","),
          sisa = split[0].length % 3,
          rupiah = split[0].substr(0, sisa),
          ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        // tambahkan titik jika yang di input sudah menjadi angka ribuan
        if (ribuan) {
          var separator = sisa ? "." : "";
          rupiah += separator + ribuan.join(".");
        }

        rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
        return rupiah;
      },
      set: function (val) {
        this.data.harga = val.replaceAll(".", "");
      },
    },
  },
  methods: {
    ...mapActions("penjualan", [
      "getPenjualan",
      "addPenjualan",
      "deletePenjualan",
      "getPenjualanById",
      "updatePenjualan",
      "setStatus",
      "getProduk"
    ]),

    async postData() {
      try {
        let msg = "Data berhasil ditambah.";

        if (this.update > 0) {
          await this.updatePenjualan({
            id: this.update,
            data: this.data,
          });

          msg = "Data berhasil diupdate.";
        } else {
          await this.addPenjualan({
            log: this.log,
            nama: this.data.nama,
            quantity: this.data.quantity,
            harga: this.data.harga,
            id_produk: this.data.id_produk,
            tangal_pembayaran: this.data.tangal_pembayaran,
          });
        }

        this.getPenjualan({ page: this.page, search: this.nama_penjualan });
        this.clearForm();
        this.$bvModal.hide("modal-scoped");
        this.$bvToast.toast(msg, successToas());
      } catch (error) {
        this.$bvToast.toast(error.message, errorToas());
      }
    },
    getDataId(id) {
      this.getPenjualanById(id)
        .then((response) => {
          const data = response.data;
          this.update = data.id;

          this.data = {
            log: this.log,
            nama: data.nama,
            quantity: data.quantity,
            id_produk: data.id_produk,
            harga: data.harga,
            tangal_pembayaran: data.tangal_pembayaran,
          };

          this.$bvModal.show("modal-scoped");
        })
        .catch((error) => {
          this.$bvToast.toast(error.message, errorToas());
        });
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
          this.deletePenjualan({ log: this.log, id: id})
            .then((res) => {
              this.getPenjualan({ page: this.page, search: this.nama_penjualan });

              if (res.error) {
                this.$bvToast.toast(res.message, errorToas());
              } else {
                this.$bvToast.toast("Data berhasil dihapus.", successToas());
              }
            })
            .catch((error) => {
              this.$bvToast.toast(error.message, errorToas());
            });
        }
      });
    },
    seterStatus(id, status) {
      this.setStatus({
        id: id,
        status: status ? 1 : 0,
        log: this.log,
      })
        .then((resp) => {
          this.$bvToast.toast("Status berhasil diubah.", successToas());
        })
        .catch((error) => {
          this.$bvToast.toast(error.message, errorToas());
        });
    },
    close() {
      this.clearForm();
      this.$bvModal.hide("modal-scoped");
      this.$store.commit("CLEAR_ERRORS");
    },
    clearForm() {
      this.update = 0;

      this.data = {
        nama: "",
        log: this.log,
        quantity: "",
        id_produk: "",
        harga: "",
        tangal_pembayaran: "",
      };
    },
  },
  watch: {
    page() {
      this.getPenjualan({ page: this.page, search: this.nama_penjualan });
    },
    nama_penjualan() {
      this.getPenjualan({ page: this.page, search: this.nama_penjualan });
    },
  },
};
</script>