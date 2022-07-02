<template>
  <!-- begin card -->
  <div class="card">
    <div class="card-header">
      <div class="row">
        <div class="col-md-6">
          <div class="card-title">
            <h5 class="mb-0">List Keuangan</h5>
          </div>
        </div>

        <div class="col-md-6">
          <button
            class="btn btn-primary btn-sm float-right"
            @click="$bvModal.show('modal-scoped')"
          >
            Tambah keuangan
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
          label-for="nama_keuangan"
          class="mb-4"
        >
          <b-input-group size="sm">
            <b-form-input
              v-model="nama_keuangan"
              type="search"
              id="nama_keuangan"
              placeholder="Nama"
            ></b-form-input>

            <b-input-group-append>
              <b-button :disabled="!nama_keuangan" @click="nama_keuangan = ''"
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
            <th>Tipe</th>
            <th>Gross</th>
            <th>Net</th>
            <th>Vat</th>
            <th>Perusahaan</th>
            <th>Tanggal</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>

        <tbody>
          <tr
            v-for="(data, index) in keuanganPaginate.data"
            :key="index"
            v-show="!isLoading && keuanganPaginate.data.length"
          >
            <td>{{ index + 1 }}</td>
            <td>
              <router-link
                :to="{
                  name: 'detail.keuangan',
                  params: { id: data.id },
                }"
                title="Detail"
                >{{ data.nama }}</router-link
              >
            </td>
            <td>
              {{ data.kategori }}
            </td>
            <td>{{ data.type }}</td>
            <td>{{ data.gross }}</td>
            <td>{{ data.net }}</td>
            <td>{{ data.vat }}</td>
            <td>
              <router-link
                :to="{
                  name: 'detail.perusahaan',
                  params: { id: data.companies.id },
                }"
                title="Detail"
                >{{ data.companies.nama }}</router-link
              >
            </td>
            <td>{{ data.date }}</td>
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

          <tr v-show="!isLoading && !keuanganPaginate.total">
            <td colspan="8" class="text-center">Belum ada data.</td>
          </tr>
        </tbody>
      </table>

      <div class="row mt-2">
        <div class="col-md-6">
          <p v-if="keuanganPaginate.data">
            <i class="fa fa-bars"></i>
            <b>{{ keuanganPaginate.data.length }}</b> keuangan dari
            <b>{{ keuanganPaginate.total }}</b> total data keuangan
          </p>
        </div>
        <div class="col-md-6">
          <div class="float-right">
            <b-pagination
              size="sm"
              v-model="page"
              :total-rows="keuanganPaginate.total"
              :per-page="keuanganPaginate.per_page"
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
        <h5>{{ update > 0 ? "Update" : "Tambah" }} keuangan</h5>
      </template>

      <div class="form-group">
        <label>Nama</label>

        <input type="text" class="form-control" v-model="data.nama" />
        <small class="text-danger" v-if="errors.nama">{{
          errors.nama[0]
        }}</small>
      </div>

      <div class="form-group">
        <label>Perusahaan</label>

        <v-select
          label="nama"
          :options="listPerusahaan"
          v-model="data.id_perusahaan"
          :reduce="(nama) => nama.id"
        ></v-select>
        <small class="text-danger" v-if="errors.id_perusahaan">{{
          errors.id_perusahaan[0]
        }}</small>
      </div>

      <div class="form-group">
        <label>Deskripsi</label>

        <input type="text" class="form-control" v-model="data.deskripsi" />
        <small class="text-danger" v-if="errors.deskripsi">{{
          errors.deskripsi[0]
        }}</small>
      </div>

      <div class="form-group">
        <label>Gross</label>

        <input type="text" class="form-control" v-model="data.gross" />
        <small class="text-danger" v-if="errors.gross">{{
          errors.gross[0]
        }}</small>
      </div>

      <div class="form-group">
        <label>Tanggal</label>

        <input type="date" class="form-control" v-model="data.date" />
        <small class="text-danger" v-if="errors.date">{{
          errors.date[0]
        }}</small>
      </div>

      <div class="form-group">
        <label>Tipe</label>

        <v-select
          :options="[
            'Invoice',
            'proforma invoice',
            'advance',
            'simple transfer',
          ]"
          v-model="data.type"
        ></v-select>
        <small class="text-danger" v-if="errors.type">{{
          errors.type[0]
        }}</small>
      </div>

      <div class="form-group">
        <label>Kategori</label>

        <v-select
          :options="[
            'steady income',
            'large order',
            'small order',
            'one-off order',
          ]"
          v-model="data.kategori"
        ></v-select>
        <small class="text-danger" v-if="errors.kategori">{{
          errors.kategori[0]
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
  name: "DataKeuangan",
  components: {
    "v-select": vSelect,
  },
  data() {
    return {
      page: 1,
      update: 0,
      nama_keuangan: "",
      data: {
        log: "",
        nama: "",
        date: "",
        type: "",
        gross: "",
        kategori: "",
        deskripsi: "",
        id_perusahaan: "",
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

    this.getKeuangan()
    this.getPerusahaan()
  },
  computed: {
    ...mapGetters(["isLoading"]),

    ...mapState(["errors"]),
    ...mapState("system_log", ["log"]),
    ...mapState("keuangan", ["keuanganPaginate"]),
    ...mapState("deal", ["listPerusahaan"]),
  },
  methods: {
    ...mapActions("deal", ["getPerusahaan"]),
    ...mapActions("keuangan", [
      "getKeuangan",
      "addKeuangan",
      "deleteKeuangan",
      "getKeuanganById",
      "updateKeuangan",
      "setStatus",
    ]),

    async postData() {
      try {
        let msg = "Data berhasil ditambah.";

        if (this.update > 0) {
          await this.updateKeuangan({
            id: this.update,
            data: this.data,
          });

          msg = "Data berhasil diupdate.";
        } else {
          await this.addKeuangan({
            log: this.log,
            nama: this.data.nama,
            date: this.data.date,
            type: this.data.type,
            gross: this.data.gross,
            kategori: this.data.kategori,
            deskripsi: this.data.deskripsi,
            id_perusahaan: this.data.id_perusahaan,
          });
        }

        this.getKeuangan({ page: this.page, search: this.nama_keuangan });
        this.clearForm();
        this.$bvModal.hide("modal-scoped");
        this.$bvToast.toast(msg, successToas());
      } catch (error) {
        this.$bvToast.toast(error.message, errorToas());
      }
    },
    getDataId(id) {
      this.getKeuanganById(id)
        .then((response) => {
          const data = response.data;
          this.update = data.id;

          this.data = {
            log: this.log,
            nama: data.nama,
            date: data.date,
            type: data.type,
            gross: data.gross.toString(),
            kategori: data.kategori,
            deskripsi: data.deskripsi,
            id_perusahaan: data.id_perusahaan,
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
          this.deleteKeuangan({ log: this.log, id: id })
            .then((res) => {
              this.getKeuangan({ page: this.page, search: this.nama_keuangan });

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
        date: '',
        type: '',
        gross: '',
        kategori: '',
        deskripsi: '',
        id_perusahaan: ''
      };
    },
  },
  watch: {
    page() {
      this.getKeuangan({ page: this.page, search: this.nama_keuangan });
    },
    nama_keuangan() {
      this.getKeuangan({ page: this.page, search: this.nama_keuangan });
    },
  },
};
</script>