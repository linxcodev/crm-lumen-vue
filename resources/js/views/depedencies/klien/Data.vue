<template>
  <!-- begin card -->
  <div class="card">
    <div class="card-header">
      <div class="row">
        <div class="col-md-6">
          <div class="card-title">
            <h5 class="mb-0">List Klien</h5>
          </div>
        </div>

        <div class="col-md-6">
          <button
            class="btn btn-primary btn-sm float-right"
            @click="$bvModal.show('modal-scoped')"
          >
            Tambah Klien
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
          label-for="nama_klien"
          class="mb-4"
        >
          <b-input-group size="sm">
            <b-form-input
              v-model="nama_klien"
              type="search"
              id="nama_klien"
              placeholder="Nama klien"
            ></b-form-input>

            <b-input-group-append>
              <b-button :disabled="!nama_klien" @click="nama_klien = ''"
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
            <th>Nama lengkap</th>
            <th>No HP</th>
            <th>Email</th>
            <th>Bagian</th>
            <th>Budget</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>

        <tbody>
          <tr
            v-for="(data, index) in klienPaginate.data"
            :key="index"
            v-show="!isLoading && klienPaginate.data.length"
          >
            <td>{{ index + 1 }}</td>
            <td>
              <router-link
                :to="{
                  name: 'detail.klien',
                  params: { id: data.id },
                }"
                title="Detail"
                >{{ data.nama_lengkap }}</router-link
              >
            </td>
            <td>{{ data.phone }}</td>
            <td>{{ data.email }}</td>
            <td>{{ data.bagian }}</td>
            <td>{{ data.budget.formatted }}</td>
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

          <tr v-show="!isLoading && !klienPaginate.total">
            <td colspan="8" class="text-center">Belum ada data.</td>
          </tr>
        </tbody>
      </table>

      <div class="row mt-2">
        <div class="col-md-6">
          <p v-if="klienPaginate.data">
            <i class="fa fa-bars"></i>
            <b>{{ klienPaginate.data.length }}</b> klien dari
            <b>{{ klienPaginate.total }}</b> total data klien
          </p>
        </div>
        <div class="col-md-6">
          <div class="float-right">
            <b-pagination
              size="sm"
              v-model="page"
              :total-rows="klienPaginate.total"
              :per-page="klienPaginate.per_page"
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
        <h5>{{ update > 0 ? "Update" : "Tambah" }} klien</h5>
      </template>

      <div class="form-group">
        <label>Nama lengkap</label>

        <input type="text" class="form-control" v-model="data.nama_lengkap" />
        <small class="text-danger" v-if="errors.nama_lengkap">{{
          errors.nama_lengkap[0]
        }}</small>
      </div>

      <div class="form-group">
        <label>No HP</label>

        <input type="text" class="form-control" v-model="data.phone" />
        <small class="text-danger" v-if="errors.phone">{{
          errors.phone[0]
        }}</small>
      </div>

      <div class="form-group">
        <label>Email</label>

        <input type="text" class="form-control" v-model="data.email" />
        <small class="text-danger" v-if="errors.email">{{
          errors.email[0]
        }}</small>
      </div>

      <div class="form-group">
        <label>Budget</label>

        <input type="text" class="form-control" v-model="formatBudget" />
        <small class="text-danger" v-if="errors.budget">{{
          errors.budget[0]
        }}</small>
      </div>

      <div class="form-group">
        <label>Bagian</label>

        <v-select
          :options="['transport', 'logistic', 'finances']"
          v-model="data.bagian"
        ></v-select>
        <small class="text-danger" v-if="errors.bagian">{{
          errors.bagian[0]
        }}</small>
      </div>

      <div class="form-group">
        <label>Kota</label>

        <input type="text" class="form-control" v-model="data.kota" />
        <small class="text-danger" v-if="errors.kota">{{
          errors.kota[0]
        }}</small>
      </div>

      <div class="form-group">
        <label>Negara</label>

        <input type="text" class="form-control" v-model="data.negara" />
        <small class="text-danger" v-if="errors.negara">{{
          errors.negara[0]
        }}</small>
      </div>

      <div class="form-group">
        <label>Lokasi</label>

        <input type="text" class="form-control" v-model="data.lokasi" />
        <small class="text-danger" v-if="errors.lokasi">{{
          errors.lokasi[0]
        }}</small>
      </div>

      <div class="form-group">
        <label>Zip</label>

        <input type="text" class="form-control" v-model="data.zip" />
        <small class="text-danger" v-if="errors.zip">{{ errors.zip[0] }}</small>
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
      nama_klien: "",
      data: {
        zip: "",
        kota: "",
        phone: "",
        email: "",
        bagian: "",
        budget: "",
        lokasi: "",
        negara: "",
        nama_lengkap: "",
        log: "kosong",
      },
    };
  },
  created() {
    this.getKlien();
  },
  computed: {
    ...mapGetters(["isLoading"]),

    ...mapState(["errors"]),
    ...mapState("system_log", ["log"]),
    ...mapState("klien", ["klienPaginate"]),

    formatBudget: {
      get: function () {
        var number_string = this.data.budget.replace(/[^,\d]/g, "").toString(),
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
        this.data.budget = val.replaceAll(".", "");
      },
    },
  },
  methods: {
    ...mapActions("klien", [
      "getKlien",
      "addKlien",
      "deleteKlien",
      "getKlienById",
      "updateKlien",
      "setStatus"
    ]),

    async postData() {
      try {
        let msg = "Data berhasil ditambah.";

        if (this.update > 0) {
          await this.updateKlien({
            id: this.update,
            data: this.data,
          });

          msg = "Data berhasil diupdate.";
        } else {
          await this.addKlien({
            log: this.log,
            zip: this.data.zip,
            kota: this.data.kota,
            phone: this.data.phone,
            email: this.data.email,
            negara: this.data.negara,
            bagian: this.data.bagian,
            budget: this.data.budget,
            lokasi: this.data.lokasi,
            nama_lengkap: this.data.nama_lengkap,
          });
        }

        this.getKlien({ page: this.page, search: this.nama_klien });
        this.clearForm();
        this.$bvModal.hide("modal-scoped");
        this.$bvToast.toast(msg, successToas());
      } catch (error) {
        this.$bvToast.toast(error.message, errorToas());
      }
    },
    getDataId(id) {
      this.getKlienById(id)
        .then((response) => {
          const data = response.data.clientDetails;
          this.update = data.id;

          this.data = {
            zip: data.zip,
            kota: data.kota,
            phone: data.phone,
            email: data.email,
            bagian: data.bagian,
            budget: data.budget,
            lokasi: data.lokasi,
            negara: data.negara,
            log: this.log,
            nama_lengkap: data.nama_lengkap,
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
          this.deleteKlien(id)
            .then((res) => {
              this.getKlien({ page: this.page, search: this.nama_klien });

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
        zip: "",
        kota: "",
        phone: "",
        email: "",
        bagian: "",
        budget: "",
        lokasi: "",
        negara: "",
        nama_lengkap: "",
        log: this.log,
      };
    },
  },
  watch: {
    page() {
      this.getKlien({ page: this.page, search: this.nama_klien });
    },
    nama_klien() {
      this.getKlien({ page: this.page, search: this.nama_klien });
    },
  },
};
</script>