<template>
  <!-- begin card -->
  <div class="card">
    <div class="card-header">
      <div class="card-title">
        <h5 class="mb-0">Pengaturan</h5>
      </div>
    </div>

    <div class="card-body">
      <div v-if="isLoading" class="text-center">
        Loading...
      </div>

      <div v-else>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="pagination">Pagination size</label>
              <input type="text" id="pagination" v-model="pengaturan.pagination_size" class="form-control" />
            </div>

            <div class="form-group">
              <label for="priority">Priority size</label>
              <input type="text" id="priority" v-model="pengaturan.priority_size" class="form-control" />
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="currency">Currency type</label>
              <select name="" id="currency" class="form-control" v-model="pengaturan.currency">
                <option value="USD">USD</option>
                <option value="IDR">IDR</option>
              </select>
            </div>

            <div class="form-group">
              <label for="tax">Tax</label>
              <input type="text" id="tax" v-model="pengaturan.invoice_tax" class="form-control" />
            </div>
          </div>
        </div>

        <button class="btn btn-primary" @click="simpan">Simpan</button>
      </div>
    </div>
  </div>
  <!-- end card -->
</template>

<script>
import { mapActions, mapGetters, mapState } from 'vuex';
import { errorToas, successToas } from '../../entities/notif'

export default {
    name: 'FormPengaturan',
    created() {
        this.getPengaturan()
    },
    computed: {
      ...mapGetters(["isLoading"]),
      
      ...mapState(["errors"]),
      ...mapState("system_log", ["log"]),

      pengaturan: {
        get: function() {
          return this.$store.state.pengaturan.pengaturan;
        },
        set: function(val) {
          this.$store.commit("pengaturan/ASSIGN_DATA", val);
        }
      }
    },
    methods: {
        ...mapActions('pengaturan', ['getPengaturan', 'updatePengaturan']),

        async simpan() {
          try {
            this.pengaturan.log = this.log
            await this.updatePengaturan()
  
            this.getPengaturan()
            this.$bvToast.toast("Pengaturan berhasil disimpan.", successToas());
          } catch (error) {
              // get error key first secara dinamis
              this.$bvToast.toast(error[Object.keys(error)[0]][0], errorToas());
          }
        }
    }
};
</script>