<template>
  <!-- begin card -->
  <div class="card">
    <div class="card-header">
      <div class="row">
        <div class="col-md-6">
          <div class="card-title">
            <h5 class="mb-0">Tugas <span class="badge badge-success">{{karyawanDetail.taskCount}}</span></h5>
          </div>
        </div>
      </div>
    </div>

    <div class="card-body">
      <table class="table table-striped table-bordered" v-if="!isLoading">
        <thead>
          <tr>
            <th>Nama</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="(data, index) in karyawanDetail.tasks"
            :key="index"
            v-show="!isLoading && karyawanDetail.taskCount"
          >
            <td>{{ data.nama }}</td>
            <td class="text-right">
              <router-link :disabled="isLoading" class="btn btn-primary btn-sm"
                :to="{
                  name: 'detail.tugas',
                  params: { id: data.id },
                }"
              >
                <i class="flaticon2-exclamation"></i> Informasi lebih tentang tugas ini
              </router-link>
            </td>
          </tr>

          <tr v-show="!karyawanDetail.taskCount">
            <td colspan="2" class="text-center">Belum ada data.</td>
          </tr>
        </tbody>
      </table>

      <p class="text-center" v-else>Loading...</p>
    </div>
  </div>
  <!-- end card -->
</template>

<script>
import { mapGetters, mapState } from "vuex";

export default {
  name: "TugasKaryawan",
  created() {},
  computed: {
    ...mapGetters(["isLoading"]),
    ...mapState("karyawan", ["karyawanDetail"]),
  },
};
</script>