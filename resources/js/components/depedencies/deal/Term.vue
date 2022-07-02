<template>
  <!-- begin card -->
  <div class="card">
    <div class="card-header">
      <div class="row">
        <div class="col-md-6">
          <div class="card-title">
            <h5 class="mb-0">Term of agreement</h5>
          </div>
        </div>
      </div>
    </div>

    <div class="card-body">
      <div v-if="!isLoading">
        <textarea cols="30" rows="5" v-model="term" class="form-control"></textarea>

        <button class="btn btn-primary btn-sm mt-3" @click="postData">Simpan</button>
      </div>

      <p class="text-center" v-else>Loading...</p>
    </div>
  </div>
  <!-- end card -->
</template>

<script>
import { mapActions, mapGetters, mapState } from "vuex";
import { errorToas, successToas } from '../../../entities/notif';

export default {
  name: "Term",
  data() {
    return {
      term: "Lorem ipsum dolor sit amet consectetur adipisicing elit. Eligendi laborum possimus amet modi cum. Explicabo aut illum repellendus minus laborum, commodi sapiente officia, assumenda tempora dolorum error reiciendis, animi temporibus?"
    }
  },
  computed: {
    ...mapGetters(["isLoading"]),

    ...mapState("system_log", ["log"]),
  },
  methods: {
    ...mapActions("deal", ["addDealTerm", "getDealById"]),

    async postData() {
      try {
        const id = this.$route.params.id

        await this.addDealTerm({
          id_deal: id,
          body: '<p>' + this.term + '</p>',
          log: this.log
        })

        this.getDealById(id)
        this.$bvToast.toast("Term berhasil ditambah.", successToas());
      } catch (error) {
        this.$bvToast.toast(error.message, errorToas());
      }
    }
  }
};
</script>

<style scoped>
td {
  text-align: right;
}
</style>