<template>
  <!-- begin card -->
  <div class="card">
    <div class="card-header">
      <div class="row">
        <div class="col-md-6">
          <div class="card-title">
            <h5 class="mb-0">Deal Term</h5>
          </div>
        </div>
      </div>
    </div>

    <div class="card-body">
      <table class="table table-striped table-bordered" v-if="!isLoading">
        <thead>
          <tr>
            <th>No</th>
            <th>Dibuat</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="(data, index) in dealsTerms"
            :key="index"
            v-show="!isLoading && dealsTerms && dealsTerms.length"
          >
            <td>{{ index + 1 }}</td>
            <td>{{ timeConverter(data.created_at) }}</td>
            <td class="text-right">
              <b-button
                class="mr-1"
                @click="genPdf(data.id, data.id_deal)"
                size="sm"
                variant="info"
                :disabled="isLoading"
                ><i class="flaticon-interface-11"></i> Generate PDF</b-button
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

          <tr v-show="dealsTerms && !dealsTerms.length">
            <td colspan="3" class="text-center">Belum ada data.</td>
          </tr>
        </tbody>
      </table>

      <p class="text-center" v-else>Loading...</p>
    </div>
  </div>
  <!-- end card -->
</template>

<script>
import { mapActions, mapGetters, mapState } from "vuex";
import { errorToas, successToas } from '../../../entities/notif';

export default {
  name: "TermDeal",
  created() {},
  computed: {
    ...mapGetters(["isLoading"]),

    ...mapState("system_log", ["log"]),
    ...mapState("deal", {
      dealsTerms: (state) => state.dealDetail.dealsTerms,
      dealDetail: (state) => state.dealDetail.deal,
    }),
  },
  methods: {
    ...mapActions("deal", ["getDealById", "generateDealTerm", "deleteDealTerm"]),

    genPdf(id, id_deal) {
      this.generateDealTerm({ id, id_deal })
        .then((response) => {
          var fileURL = window.URL.createObjectURL(new Blob([response]));
          var fileLink = document.createElement("a");
          fileLink.href = fileURL;
          fileLink.setAttribute("download", `${this.dealDetail.nama}.pdf`);
          document.body.appendChild(fileLink);
          fileLink.click();
        });
    },
    timeConverter(timestamp) {
      var a = new Date(timestamp);
      var months = [
        "Jan",
        "Feb",
        "Mar",
        "Apr",
        "May",
        "Jun",
        "Jul",
        "Aug",
        "Sep",
        "Oct",
        "Nov",
        "Dec",
      ];

      var year = a.getFullYear();
      var month = months[a.getMonth()];
      var date = a.getDate();

      var time = date + " " + month + " " + year;

      return time;
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
          this.deleteDealTerm({ log: this.log, id_term: id })
            .then((res) => {
              if (res.error) {
                this.$bvToast.toast(res.message, errorToas());
              } else {
                this.getDealById(this.$route.params.id)

                this.$bvToast.toast("Data berhasil dihapus.", successToas());
              }
            })
            .catch((error) => {
              this.$bvToast.toast(error.message, errorToas());
            });
        }
      });
    },
  },
};
</script>