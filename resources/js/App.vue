<template>
  <div>
    <loading
      :active.sync="loadPage"
      :is-full-page="true"
      :loader="'bars'"
      :color="'#1f1498'"
    ></loading>

    <app-sidebar v-show="isAuth" />

    <div :class="{ 'c-wrapper': isAuth }">
      <app-header v-if="isAuth" />

      <transition name="fade" mode="out-in">
        <router-view />
      </transition>

      <app-footer v-if="isAuth" />
    </div>
  </div>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import AppSidebar from "./components/AppSidebar.vue";
import AppHeader from "./components/AppHeader.vue";
import AppFooter from "./components/AppFooter.vue";
import "vue-loading-overlay/dist/vue-loading.css";
import Loading from "vue-loading-overlay";

export default {
  components: {
    AppHeader,
    AppSidebar,
    AppFooter,
    Loading,
  },
  computed: {
    ...mapState(["loadPage"]),
    ...mapGetters(["isAuth"]),
  },
  methods: {},
  created() {},
};
</script>

<style lang="scss">
.swal2-popup {
  font-size: 0.7rem !important;
}
</style>
