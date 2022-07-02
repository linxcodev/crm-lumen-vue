<template>
  <div>
    <InfoKlien />

    <PerusahaanKlien />

    <KaryawanKlien />
  </div>
</template>

<script>
import { mapActions } from 'vuex';

import InfoKlien from '../../../components/depedencies/klein/InfoKlien.vue'
import KaryawanKlien from '../../../components/depedencies/klein/KaryawanKlien.vue'
import PerusahaanKlien from '../../../components/depedencies/klein/PerusahaanKlien.vue'

export default {
  name: "KlienDetail",
  created() {
    this.getKlienById(this.$route.params.id)
  },
  components: {
    InfoKlien,
    PerusahaanKlien,
    KaryawanKlien
  },
  methods: {
    ...mapActions('klien', ['getKlienById'])
  }
};
</script>