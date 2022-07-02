<template>
  <div>
    <InfoDeal />

    <PerusahaanDeal />

    <Term />

    <TermDeal />
  </div>
</template>

<script>
import { mapActions } from 'vuex';

import Term from '../../../components/depedencies/deal/Term.vue'
import InfoDeal from '../../../components/depedencies/deal/InfoDeal.vue'
import TermDeal from '../../../components/depedencies/deal/TermDeal.vue'
import PerusahaanDeal from '../../../components/depedencies/deal/PerusahaanDeal.vue'

export default {
  name: "DealDetail",
  created() {
    this.getDealById(this.$route.params.id)
  },
  components: {
    Term,
    InfoDeal,
    TermDeal,
    PerusahaanDeal
  },
  methods: {
    ...mapActions('deal', ['getDealById'])
  }
};
</script>