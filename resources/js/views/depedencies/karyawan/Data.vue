<template>
  <!-- begin card -->
  <div class="card">
    <div class="card-header">
      <div class="row">
        <div class="col-md-6">
          <div class="card-title">
            <h5 class="mb-0">List Karyawan</h5>
          </div>
        </div>

        <div class="col-md-6">
          <button
            class="btn btn-primary btn-sm float-right"
            @click="$bvModal.show('modal-scoped')"
          >
            Tambah Karyawan
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
          label-for="nama_karyawan"
          class="mb-4"
        >
          <b-input-group size="sm">
            <b-form-input
              v-model="nama_karyawan"
              type="search"
              id="nama_karyawan"
              placeholder="Nama karyawan"
            ></b-form-input>

            <b-input-group-append>
              <b-button :disabled="!nama_karyawan" @click="nama_karyawan = ''"
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
            <th>Pekerjaan</th>
            <th>Klien</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>

        <tbody>
          <tr
            v-for="(data, index) in karyawanPaginate.data"
            :key="index"
            v-show="!isLoading && karyawanPaginate.data.length"
          >
            <td>{{ index + 1 }}</td>
            <td>
              <router-link
                :to="{
                  name: 'detail.karyawan',
                  params: { id: data.id },
                }"
                title="Detail"
                >{{ data.nama_lengkap }}</router-link
              >
            </td>
            <td>{{ data.phone }}</td>
            <td>{{ data.email }}</td>
            <td>{{ data.pekerjaan }}</td>
            <td>
              <router-link
                :to="{
                  name: 'detail.klien',
                  params: { id: data.client.id },
                }"
                title="Detail"
                >{{ data.client.nama_lengkap }}</router-link
              >
            </td>
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

          <tr v-show="!isLoading && !karyawanPaginate.total">
            <td colspan="8" class="text-center">Belum ada data.</td>
          </tr>
        </tbody>
      </table>

      <div class="row mt-2">
        <div class="col-md-6">
          <p v-if="karyawanPaginate.data">
            <i class="fa fa-bars"></i>
            <b>{{ karyawanPaginate.data.length }}</b> karyawan dari
            <b>{{ karyawanPaginate.total }}</b> total data karyawan
          </p>
        </div>
        <div class="col-md-6">
          <div class="float-right">
            <b-pagination
              size="sm"
              v-model="page"
              :total-rows="karyawanPaginate.total"
              :per-page="karyawanPaginate.per_page"
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
        <label>Pekerjaan</label>

        <input type="text" class="form-control" v-model="data.pekerjaan" />
        <small class="text-danger" v-if="errors.pekerjaan">{{
          errors.pekerjaan[0]
        }}</small>
      </div>

      <div class="form-group">
        <label>Klien</label>

        <v-select
          label="nama_lengkap"
          :options="listKlien"
          v-model="data.id_klien"
          :reduce="(nama) => nama.id"
        ></v-select>
        <small class="text-danger" v-if="errors.id_klien">{{
          errors.id_klien[0]
        }}</small>
      </div>

      <div class="form-group">
        <label>Catatan</label>

        <textarea type="text" class="form-control" v-model="data.catatan"></textarea>
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
      nama_karyawan: "",
      data: {
        catatan: "",
        phone: "",
        email: "",
        id_klien: "",
        pekerjaan: "",
        nama_lengkap: "",
        log: "kosong",
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

    this.getKlien()
    this.getKaryawan();
  },
  computed: {
    ...mapGetters(["isLoading"]),

    ...mapState(["errors"]),
    ...mapState("system_log", ["log"]),
    ...mapState("karyawan", ["karyawanPaginate", "listKlien"]),
  },
  methods: {
    ...mapActions("karyawan", [
      "getKaryawan",
      "addKaryawan",
      "deleteKaryawan",
      "getKaryawanById",
      "updateKaryawan",
      "setStatus",
      "getKlien"
    ]),

    async postData() {
      try {
        let msg = "Data berhasil ditambah.";

        if (this.update > 0) {
          await this.updateKaryawan({
            id: this.update,
            data: this.data,
          });

          msg = "Data berhasil diupdate.";
        } else {
          await this.addKaryawan({
            log: this.log,
            phone: this.data.phone,
            email: this.data.email,
            catatan: this.data.catatan,
            id_klien: this.data.id_klien,
            pekerjaan: this.data.pekerjaan,
            nama_lengkap: this.data.nama_lengkap,
          });
        }

        this.getKaryawan({ page: this.page, search: this.nama_karyawan });
        this.clearForm();
        this.$bvModal.hide("modal-scoped");
        this.$bvToast.toast(msg, successToas());
      } catch (error) {
        this.$bvToast.toast(error.message, errorToas());
      }
    },
    getDataId(id) {
      this.getKaryawanById(id)
        .then((response) => {
          const data = response.data;
          this.update = data.id;

          this.data = {
            log: this.log,
            phone: data.phone,
            email: data.email,
            catatan: data.catatan,
            id_klien: data.id_klien,
            pekerjaan: data.pekerjaan,
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
          this.deleteKaryawan({ log: this.log, id: id})
            .then((res) => {
              this.getKaryawan({ page: this.page, search: this.nama_karyawan });

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
        phone: "",
        email: "",
        id_klien: "",
        catatan: "",
        pekerjaan: "",
        log: "kosong",
        nama_lengkap: "",
      };
    },
  },
  watch: {
    page() {
      this.getKaryawan({ page: this.page, search: this.nama_karyawan });
    },
    nama_karyawan() {
      this.getKaryawan({ page: this.page, search: this.nama_karyawan });
    },
  },
};
</script>