<template>
  <div>
    <InfoKaryawan />
    
    <TugasKaryawan />
  </div>
</template>

<script>
import { mapActions } from 'vuex';

import InfoKaryawan from '../../../components/depedencies/karyawan/InfoKaryawan.vue';
import TugasKaryawan from '../../../components/depedencies/karyawan/TugasKaryawan.vue';

export default {
  name: "KaryawanDetail",
  created() {
    this.getKaryawanById(this.$route.params.id)
  },
  components: {
    InfoKaryawan,
    TugasKaryawan
  },
  methods: {
    ...mapActions('karyawan', ['getKaryawanById'])
  }
};
</script>