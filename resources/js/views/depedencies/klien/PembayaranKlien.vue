<template>
  <!-- begin card -->
  <div class="card">
    <div class="card-header">
      <div class="row">
        <div class="col-md-6">
          <div class="card-title">
            <h5 class="mb-0">List Pembayaran Klien</h5>
          </div>
        </div>

        <div class="col-md-6">
          <button
            class="btn btn-primary btn-sm float-right"
            @click="$bvModal.show('modal-scoped')"
          >
            Tambah Pembayaran
          </button>
        </div>
      </div>
    </div>

    <div class="card-body">
      <div class="col-md-6">
        <b-form-group
          label="Tipe"
          label-cols-sm="3"
          label-cols-md="3"
          label-align-sm="right"
          label-for="nama_klien"
          class="mb-4"
        >
          <v-select
            label="value"
            :options="tipePembayaran"
            v-model="tipe_pembayaran"
            :reduce="(tipe) => tipe.key"
          ></v-select>
        </b-form-group>
      </div>

      <table class="table table-striped table-hover mt-3">
        <thead>
          <tr>
            <th>No</th>
            <th>Nama lengkap</th>
            <th>Invoice</th>
            <th>Pembayaran</th>
            <th>Nominal</th>
            <th>Catatan</th>
            <th>Tanggal</th>
            <th>Aksi</th>
          </tr>
        </thead>

        <tbody>
          <tr
            v-for="(data, index) in klienPembayaran.data"
            :key="index"
            v-show="!isLoading && klienPembayaran.data.length"
          >
            <td>{{ index + 1 }}</td>
            <td>
              <router-link
                :to="{
                  name: 'detail.klien',
                  params: { id: data.klien.id },
                }"
                title="Detail"
                >{{ data.klien.nama_lengkap }}</router-link
              >
            </td>
            <td>{{ data.invoice }}</td>
            <td>{{ data.tipe_pembayaran.value }}</td>
            <td>{{ nominal(data.nominal) }}</td>
            <td>{{ data.catatan }}</td>
            <td>
              {{ data.created_at }}
            </td>
            <td>
              <b-dropdown id="dropdown-1" text="Aksi" variant="primary" size="sm" class="m-md-2">
                <b-dropdown-item :disabled="isLoading" @click="getDataId(data.id)">
                  <i class="flaticon-edit"></i> Edit
                </b-dropdown-item>

                <b-dropdown-item :disabled="isLoading" @click="deleteData(data.id)">
                  <i class="flaticon2-trash"></i> Delete
                </b-dropdown-item>
                
                <b-dropdown-divider></b-dropdown-divider>

                <b-dropdown-item :disabled="isLoading" @click="genPdf(data.id)">
                  <i class="flaticon-download"></i> Download
                </b-dropdown-item>
              </b-dropdown>
            </td>
          </tr>

          <tr v-show="isLoading">
            <td colspan="8" class="text-center">Loading...</td>
          </tr>

          <tr v-show="!isLoading && !klienPembayaran.total">
            <td colspan="8" class="text-center">Belum ada data.</td>
          </tr>
        </tbody>
      </table>

      <div class="row mt-2">
        <div class="col-md-6">
          <p v-if="klienPembayaran.data">
            <i class="fa fa-bars"></i>
            <b>{{ klienPembayaran.data.length }}</b> klien dari
            <b>{{ klienPembayaran.total }}</b> total data klien
          </p>
        </div>
        <div class="col-md-6">
          <div class="float-right">
            <b-pagination
              size="sm"
              v-model="page"
              :total-rows="klienPembayaran.total"
              :per-page="klienPembayaran.per_page"
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
        <h5>{{ update > 0 ? "Update" : "Tambah" }} pembayaran klien</h5>
      </template>

      <div class="form-group">
        <label>Klien</label>

        <v-select
          label="nama_lengkap"
          :options="listKlien"
          v-model="data.id_klien"
          :reduce="(klien) => klien.id"
        ></v-select>
        <small class="text-danger" v-if="errors.bagian">{{
          errors.bagian[0]
        }}</small>
      </div>

      <div class="form-group">
        <label>Tipe Pembayaran</label>

        <v-select
          label="value"
          :options="tipePembayaran"
          v-model="data.tipe_pembayaran"
          :reduce="(tipe) => tipe.key"
        ></v-select>
        <small class="text-danger" v-if="errors.tipe_pembayaran">{{
          errors.tipe_pembayaran[0]
        }}</small>
      </div>

      <div class="form-group">
        <label>Nominal</label>

        <input type="text" class="form-control" v-model="nominalFormat" />
        <small class="text-danger" v-if="errors.nominal">{{
          errors.nominal[0]
        }}</small>
      </div>

      <div class="form-group">
        <label>Catatan</label>

        <textarea
          class="form-control"
          v-model="data.catatan"
          cols="30"
          rows="5"
        ></textarea>
        <small class="text-danger" v-if="errors.catatan">{{
          errors.catatan[0]
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
  name: "KlienData",
  components: {
    "v-select": vSelect,
  },
  data() {
    return {
      page: 1,
      update: 0,
      tipe_pembayaran: "",
      data: {
        id_klien: "",
        nominal: "",
        catatan: "",
        tipe_pembayaran: "",
        log: "",
      },
    };
  },
  created() {
    this.getKlien();
    this.getTipePembayaran();
    this.getPembayaranKlien();
  },
  computed: {
    ...mapGetters(["isLoading"]),

    ...mapState(["errors"]),
    ...mapState("system_log", ["log"]),
    ...mapState("pembayaran_klien", ["klienPembayaran", "tipePembayaran"]),
    ...mapState("karyawan", ["listKlien"]),

    nominalFormat: {
      get: function () {
        var number_string = this.data.nominal.toString().replace(/[^,\d]/g, ""),
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
        this.data.nominal = val.replaceAll(".", "");
      },
    },
  },
  methods: {
    ...mapActions("pembayaran_klien", [
      "getPembayaranKlien",
      "addPembayaranKlien",
      "deletePembayaranKlien",
      "getPembayaranKlienById",
      "updatePembayaranKlien",
      "getTipePembayaran",
      "generatePembayaran"
    ]),
    ...mapActions("karyawan", ["getKlien"]),

    nominal(nominal) {
      var number_string = nominal.toString().replace(/[^,\d]/g, ""),
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
      return "Rp. " + rupiah;
    },
    async postData() {
      try {
        let msg = "Data berhasil ditambah.";

        if (this.update > 0) {
          await this.updatePembayaranKlien({
            id: this.update,
            data: this.data,
          });

          msg = "Data berhasil diupdate.";
        } else {
          await this.addPembayaranKlien({
            log: this.log,
            id_klien: this.data.id_klien,
            tipe_pembayaran: this.data.tipe_pembayaran,
            nominal: this.data.nominal,
            catatan: this.data.catatan,
          });
        }

        this.getPembayaranKlien({
          page: this.page,
          search: this.tipe_pembayaran,
        });
        this.clearForm();
        this.$bvModal.hide("modal-scoped");
        this.$bvToast.toast(msg, successToas());
      } catch (error) {
        this.$bvToast.toast(error.message, errorToas());
      }
    },
    getDataId(id) {
      this.getPembayaranKlienById(id)
        .then((response) => {
          const data = response.data;
          this.update = data.id;

          this.data = {
            log: this.log,
            nominal: data.nominal,
            catatan: data.catatan,
            id_klien: data.id_klien,
            tipe_pembayaran: data.tipe_pembayaran,
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
        text: "Ini akan dihapus secara permanent, Apakah anda yakin?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#c3c3c3",
        confirmButtonText: "Iya, Lanjutkan!",
      }).then((result) => {
        if (result.value) {
          this.deletePembayaranKlien({ log: this.log, id: id })
            .then((res) => {
              this.getPembayaranKlien({
                page: this.page,
                search: this.tipe_pembayaran,
              });

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
    genPdf(id) {
      this.getPembayaranKlienById(id).then((res) => {
        this.generatePembayaran(id)
          .then((response) => {
            var fileURL = window.URL.createObjectURL(new Blob([response]));
            var fileLink = document.createElement("a");
            fileLink.href = fileURL;
            fileLink.setAttribute("download", `invoice-${res.data.invoice}.pdf`);
            document.body.appendChild(fileLink);
            fileLink.click();
          });
      })
    },
    close() {
      this.clearForm();
      this.$bvModal.hide("modal-scoped");
      this.$store.commit("CLEAR_ERRORS");
    },
    clearForm() {
      this.update = 0;

      this.data = {
        id_klien: "",
        nominal: "",
        catatan: "",
        log: this.log,
        tipe_pembayaran: "",
      };
    },
  },
  watch: {
    page() {
      this.getPembayaranKlien({
        page: this.page,
        search: this.tipe_pembayaran,
      });
    },
    tipe_pembayaran() {
      this.getPembayaranKlien({
        page: this.page,
        search: this.tipe_pembayaran,
      });
    },
  },
};
</script>