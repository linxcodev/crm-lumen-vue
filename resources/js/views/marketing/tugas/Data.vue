<template>
  <!-- begin card -->
  <div class="card">
    <div class="card-header">
      <div class="row">
        <div class="col-md-6">
          <div class="card-title">
            <h5 class="mb-0">List Tugas</h5>
          </div>
        </div>

        <div class="col-md-6">
          <button
            class="btn btn-primary btn-sm float-right"
            @click="$bvModal.show('modal-scoped')"
          >
            Tambah tugas
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
          label-for="nama_tugas"
          class="mb-4"
        >
          <b-input-group size="sm">
            <b-form-input
              v-model="nama_tugas"
              type="search"
              id="nama_tugas"
              placeholder="Nama tugas"
            ></b-form-input>

            <b-input-group-append>
              <b-button :disabled="!nama_tugas" @click="nama_tugas = ''"
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
            <th>Untuk karyawan</th>
            <th>Durasi</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>

        <tbody>
          <tr
            v-for="(data, index) in tugasPaginate.data"
            :key="index"
            v-show="!isLoading && tugasPaginate.data.length"
          >
            <td>{{ index + 1 }}</td>
            <td>
              <router-link
                :to="{
                  name: 'detail.tugas',
                  params: { id: data.id },
                }"
                title="Detail"
                >{{ data.nama }}</router-link
              >
            </td>
            <td>
              <router-link
                :to="{
                  name: 'detail.karyawan',
                  params: { id: data.employees.id },
                }"
                title="Detail"
                >{{ data.employees.nama_lengkap }}</router-link
              >
            </td>
            <td>{{ data.durasi }}</td>
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

          <tr v-show="!isLoading && !tugasPaginate.total">
            <td colspan="8" class="text-center">Belum ada data.</td>
          </tr>
        </tbody>
      </table>

      <div class="row mt-2">
        <div class="col-md-6">
          <p v-if="tugasPaginate.data">
            <i class="fa fa-bars"></i>
            <b>{{ tugasPaginate.data.length }}</b> tugas dari
            <b>{{ tugasPaginate.total }}</b> total data tugas
          </p>
        </div>
        <div class="col-md-6">
          <div class="float-right">
            <b-pagination
              size="sm"
              v-model="page"
              :total-rows="tugasPaginate.total"
              :per-page="tugasPaginate.per_page"
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
        <h5>{{ update > 0 ? "Update" : "Tambah" }} deal</h5>
      </template>

      <div class="form-group">
        <label>Nama</label>

        <input type="text" class="form-control" v-model="data.nama" />
        <small class="text-danger" v-if="errors.nama">{{
          errors.nama[0]
        }}</small>
      </div>

      <div class="form-group">
        <label>Karyawan</label>

        <v-select
          label="nama_lengkap"
          :options="listKaryawan"
          v-model="data.id_karyawan"
          :reduce="(nama) => nama.id"
        ></v-select>
        <small class="text-danger" v-if="errors.id_karyawan">{{
          errors.id_karyawan[0]
        }}</small>
      </div>

      <div class="form-group">
        <label>Durasi</label>

        <input type="text" class="form-control" v-model="data.durasi" />
        <small class="text-danger" v-if="errors.durasi">{{
          errors.durasi[0]
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
  name: "DataTugas",
  components: {
    "v-select": vSelect,
  },
  data() {
    return {
      page: 1,
      update: 0,
      nama_tugas: "",
      data: {
        log: "",
        nama: "",
        durasi: "",
        id_karyawan: "",
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

    this.getTugas()
    this.getKaryawan()
  },
  computed: {
    ...mapGetters(["isLoading"]),

    ...mapState(["errors"]),
    ...mapState("system_log", ["log"]),
    ...mapState("tugas", ["tugasPaginate", "listKaryawan"]),
  },
  methods: {
    ...mapActions("tugas", [
      "getTugas",
      "addTugas",
      "deleteTugas",
      "getTugasById",
      "updateTugas",
      "setStatus",
      "getKaryawan"
    ]),

    async postData() {
      try {
        let msg = "Data berhasil ditambah.";

        if (this.update > 0) {
          await this.updateTugas({
            id: this.update,
            data: this.data,
          });

          msg = "Data berhasil diupdate.";
        } else {
          await this.addTugas({
            log: this.log,
            nama: this.data.nama,
            durasi: this.data.durasi,
            id_karyawan: this.data.id_karyawan,
          });
        }

        this.getTugas({ page: this.page, search: this.nama_tugas });
        this.clearForm();
        this.$bvModal.hide("modal-scoped");
        this.$bvToast.toast(msg, successToas());
      } catch (error) {
        this.$bvToast.toast(error.message, errorToas());
      }
    },
    getDataId(id) {
      this.getTugasById(id)
        .then((response) => {
          const data = response.data;
          this.update = data.id;

          this.data = {
            log: this.log,
            nama: data.nama,
            durasi: data.durasi,
            id_karyawan: data.id_karyawan,
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
          this.deleteTugas({ log: this.log, id: id})
            .then((res) => {
              this.getTugas({ page: this.page, search: this.nama_tugas });

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
        durasi: "",
        id_karyawan: "",
      };
    },
  },
  watch: {
    page() {
      this.getTugas({ page: this.page, search: this.nama_tugas });
    },
    nama_tugas() {
      this.getTugas({ page: this.page, search: this.nama_tugas });
    },
  },
};
</script>