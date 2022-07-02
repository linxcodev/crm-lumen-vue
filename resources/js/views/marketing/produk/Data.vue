<template>
  <!-- begin card -->
  <div class="card">
    <div class="card-header">
      <div class="row">
        <div class="col-md-6">
          <div class="card-title">
            <h5 class="mb-0">List Produk</h5>
          </div>
        </div>

        <div class="col-md-6">
          <button
            class="btn btn-primary btn-sm float-right"
            @click="$bvModal.show('modal-scoped')"
          >
            Tambah produk
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
          label-for="nama_produk"
          class="mb-4"
        >
          <b-input-group size="sm">
            <b-form-input
              v-model="nama_produk"
              type="search"
              id="nama_produk"
              placeholder="Nama klien"
            ></b-form-input>

            <b-input-group-append>
              <b-button :disabled="!nama_produk" @click="nama_produk = ''"
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
            <th>Kategori</th>
            <th>Stok</th>
            <th>Harga</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>

        <tbody>
          <tr
            v-for="(data, index) in produkPaginate.data"
            :key="index"
            v-show="!isLoading && produkPaginate.data.length"
          >
            <td>{{ index + 1 }}</td>
            <td>
              <router-link
                :to="{
                  name: 'detail.produk',
                  params: { id: data.id },
                }"
                title="Detail"
                >{{ data.nama }}</router-link
              >
            </td>
            <td>
              {{ data.kategori }}
            </td>
            <td>{{ data.stok }}</td>
            <td v-if="data.harga">{{ data.harga.formatted }}</td>
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

          <tr v-show="!isLoading && !produkPaginate.total">
            <td colspan="8" class="text-center">Belum ada data.</td>
          </tr>
        </tbody>
      </table>

      <div class="row mt-2">
        <div class="col-md-6">
          <p v-if="produkPaginate.data">
            <i class="fa fa-bars"></i>
            <b>{{ produkPaginate.data.length }}</b> klien dari
            <b>{{ produkPaginate.total }}</b> total data klien
          </p>
        </div>
        <div class="col-md-6">
          <div class="float-right">
            <b-pagination
              size="sm"
              v-model="page"
              :total-rows="produkPaginate.total"
              :per-page="produkPaginate.per_page"
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
        <h5>{{ update > 0 ? "Update" : "Tambah" }} produk</h5>
      </template>

      <div class="form-group">
        <label>Nama</label>

        <input type="text" class="form-control" v-model="data.nama" />
        <small class="text-danger" v-if="errors.nama">{{
          errors.nama[0]
        }}</small>
      </div>

      <div class="form-group">
        <label>Kategori</label>

        <input type="text" class="form-control" v-model="data.kategori" />
        <small class="text-danger" v-if="errors.kategori">{{
          errors.kategori[0]
        }}</small>
      </div>

      <div class="form-group">
        <label>Stok</label>

        <input type="text" class="form-control" v-model="data.stok" />
        <small class="text-danger" v-if="errors.stok">{{
          errors.stok[0]
        }}</small>
      </div>

      <div class="form-group">
        <label>Harga</label>

        <input type="text" class="form-control" v-model="formatHarga" />
        <small class="text-danger" v-if="errors.harga">{{
          errors.harga[0]
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
import { mapActions, mapGetters, mapState } from "vuex";
import { errorToas, successToas } from "../../../entities/notif";

export default {
  name: "ProdukData",
  components: {
    "v-select": vSelect,
  },
  data() {
    return {
      page: 1,
      update: 0,
      nama_produk: "",
      data: {
        log: "",
        nama: "",
        stok: "",
        kategori: "",
        harga: "",
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

    this.getProduk();
  },
  computed: {
    ...mapGetters(["isLoading"]),

    ...mapState(["errors"]),
    ...mapState("system_log", ["log"]),
    ...mapState("produk", ["produkPaginate"]),

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
    ...mapActions("produk", [
      "getProduk",
      "addProduk",
      "deleteProduk",
      "getProdukById",
      "updateProduk",
      "setStatus",
    ]),

    async postData() {
      try {
        let msg = "Data berhasil ditambah.";

        if (this.update > 0) {
          await this.updateProduk({
            id: this.update,
            data: this.data,
          });

          msg = "Data berhasil diupdate.";
        } else {
          await this.addProduk({
            log: this.log,
            nama: this.data.nama,
            stok: this.data.stok,
            kategori: this.data.kategori,
            harga: this.data.harga,
          });
        }

        this.getProduk({ page: this.page, search: this.nama_produk });
        this.clearForm();
        this.$bvModal.hide("modal-scoped");
        this.$bvToast.toast(msg, successToas());
      } catch (error) {
        this.$bvToast.toast(error.message, errorToas());
      }
    },
    getDataId(id) {
      this.getProdukById(id)
        .then((response) => {
          const data = response.data;
          this.update = data.id;

          this.data.log = this.log;
          this.data.nama = data.nama;
          this.data.stok = data.stok;
          this.data.kategori = data.kategori;
          this.data.harga = data.harga;

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
          this.deleteProduk({ log: this.log, id: id })
            .then((res) => {
              this.getProduk({ page: this.page, search: this.nama_produk });

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
        stok: "",
        kategori: "",
        harga: "",
      };
    },
  },
  watch: {
    page() {
      this.getProduk({ page: this.page, search: this.nama_produk });
    },
    nama_produk() {
      this.getProduk({ page: this.page, search: this.nama_produk });
    },
  },
};
</script>