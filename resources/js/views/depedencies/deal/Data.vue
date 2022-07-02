<template>
  <!-- begin card -->
  <div class="card">
    <div class="card-header">
      <div class="row">
        <div class="col-md-6">
          <div class="card-title">
            <h5 class="mb-0">List Deal</h5>
          </div>
        </div>

        <div class="col-md-6">
          <button
            class="btn btn-primary btn-sm float-right"
            @click="$bvModal.show('modal-scoped')"
          >
            Tambah Deal
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
          label-for="nama_deal"
          class="mb-4"
        >
          <b-input-group size="sm">
            <b-form-input
              v-model="nama_deal"
              type="search"
              id="nama_deal"
              placeholder="Nama klien"
            ></b-form-input>

            <b-input-group-append>
              <b-button :disabled="!nama_deal" @click="nama_deal = ''"
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
            <th>Deal perusahaan</th>
            <th>Tanggal mulai</th>
            <th>Tanggal berakhir</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>

        <tbody>
          <tr
            v-for="(data, index) in dealPaginate.data"
            :key="index"
            v-show="!isLoading && dealPaginate.data.length"
          >
            <td>{{ index + 1 }}</td>
            <td>
              <router-link
                :to="{
                  name: 'detail.deal',
                  params: { id: data.id },
                }"
                title="Detail"
                >{{ data.nama }}</router-link
              >
            </td>
            <td v-if="data.companies">
              <router-link
                :to="{
                  name: 'detail.deal',
                  params: { id: data.companies.id },
                }"
                title="Detail"
                >{{ data.companies.nama }}</router-link
              >
            </td>
            <td>{{ data.waktu_mulai }}</td>
            <td>{{ data.waktu_berakhir }}</td>
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

          <tr v-show="!isLoading && !dealPaginate.total">
            <td colspan="8" class="text-center">Belum ada data.</td>
          </tr>
        </tbody>
      </table>

      <div class="row mt-2">
        <div class="col-md-6">
          <p v-if="dealPaginate.data">
            <i class="fa fa-bars"></i>
            <b>{{ dealPaginate.data.length }}</b> deal dari
            <b>{{ dealPaginate.total }}</b> total data deal
          </p>
        </div>
        <div class="col-md-6">
          <div class="float-right">
            <b-pagination
              size="sm"
              v-model="page"
              :total-rows="dealPaginate.total"
              :per-page="dealPaginate.per_page"
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
        <label>Tanggal mulai</label>

        <input type="date" class="form-control" v-model="data.waktu_mulai" />
        <small class="text-danger" v-if="errors.waktu_mulai">{{
          errors.waktu_mulai[0]
        }}</small>
      </div>

      <div class="form-group">
        <label>Tanggal berakhir</label>

        <input
          type="date"
          class="form-control"
          v-model="data.waktu_berakhir"
        />
        <small class="text-danger" v-if="errors.waktu_berakhir">{{
          errors.waktu_berakhir[0]
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
  name: "DealData",
  components: {
    "v-select": vSelect,
  },
  data() {
    return {
      page: 1,
      update: 0,
      nama_deal: "",
      data: {
        log: "",
        nama: "",
        waktu_mulai: "",
        id_perusahaan: "",
        waktu_berakhir: "",
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
    
    this.getDeal()
    this.getPerusahaan()
  },
  computed: {
    ...mapGetters(["isLoading"]),

    ...mapState(["errors"]),
    ...mapState("system_log", ["log"]),
    ...mapState("deal", ["dealPaginate", "listPerusahaan"]),
  },
  methods: {
    ...mapActions("deal", [
      "getDeal",
      "addDeal",
      "deleteDeal",
      "getDealById",
      "updateDeal",
      "setStatus",
      "getPerusahaan"
    ]),

    async postData() {
      try {
        let msg = "Data berhasil ditambah.";

        if (this.update > 0) {
          await this.updateDeal({
            id: this.update,
            data: this.data,
          });

          msg = "Data berhasil diupdate.";
        } else {
          await this.addDeal({
            log: this.log,
            nama: this.data.nama,
            waktu_mulai: this.data.waktu_mulai,
            id_perusahaan: this.data.id_perusahaan,
            waktu_berakhir: this.data.waktu_berakhir,
          });
        }

        this.getDeal({ page: this.page, search: this.nama_deal });
        this.clearForm();
        this.$bvModal.hide("modal-scoped");
        this.$bvToast.toast(msg, successToas());
      } catch (error) {
        this.$bvToast.toast(error.message, errorToas());
      }
    },
    getDataId(id) {
      this.getDealById(id)
        .then((response) => {
          const data = response.data.deal;
          this.update = data.id;

          this.data = {
            log: this.log,
            nama: data.nama,
            waktu_mulai: data.waktu_mulai,
            id_perusahaan: data.id_perusahaan,
            waktu_berakhir: data.waktu_berakhir,
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
          this.deleteDeal({ log: this.log, id: id})
            .then((res) => {
              this.getDeal({ page: this.page, search: this.nama_deal });

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
        waktu_mulai: "",
        id_perusahaan: "",
        waktu_berakhir: "",
      };
    },
  },
  watch: {
    page() {
      this.getDeal({ page: this.page, search: this.nama_deal });
    },
    nama_deal() {
      this.getDeal({ page: this.page, search: this.nama_deal });
    },
  },
};
</script>