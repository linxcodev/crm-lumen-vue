<template>
  <div class="row section">
    <div class="col-md-3 col-sm-12 col-xs-12">
      <div class="panel panel-primary text-center no-boder bg-color-green">
        <div class="panel-body boxes">
          <i class="flaticon-users"></i>
          <h3 style="padding: 8px; font-size: 18px" v-if="!isLoading">
              Klien: {{ dashboard.countKlien }} ({{ dashboard.deactivatedKlien }})
          </h3>
          <h3 style="padding: 8px; font-size: 18px" v-else>Loading...</h3>
        </div>

        <router-link style="text-decoration: none" :to="{ name: 'klien' }">
          <div class="panel-footer back-footer-green boxes-font">
            {{ dashboard.klienInLatestMonth }} Naik di 30 hari
          </div>
        </router-link>
      </div>
    </div>

    <div class="col-md-3 col-sm-12 col-xs-12">
      <div class="panel panel-primary text-center no-boder bg-color-blue">
        <div class="panel-body boxes">
          <i class="flaticon-clipboard"></i>
          <h3 style="padding: 8px; font-size: 18px" v-if="!isLoading">
              Perusahaan: {{ dashboard.countPerusahaan }} ({{ dashboard.deactivatedPerusahaan }})
          </h3>
          <h3 style="padding: 8px; font-size: 18px" v-else>Loading...</h3>
        </div>

        <router-link style="text-decoration: none" :to="{ name: 'perusahaan' }">
          <div class="panel-footer back-footer-blue boxes-font">
            {{ dashboard.perusahaanInLatestMonth }} Naik di 30 hari
          </div>
        </router-link>
      </div>
    </div>

    <div class="col-md-3 col-sm-12 col-xs-12">
      <div class="panel panel-primary text-center no-boder bg-color-red">
        <div class="panel-body boxes">
          <i class="flaticon-map"></i>
          <h3 style="padding: 8px; font-size: 18px" v-if="!isLoading">
              Karyawan: {{ dashboard.countKaryawan }} ({{ dashboard.deactivatedKaryawan }})
          </h3>
          <h3 style="padding: 8px; font-size: 18px" v-else>Loading...</h3>
        </div>

        <router-link style="text-decoration: none" :to="{ name: 'karyawan' }">
          <div class="panel-footer back-footer-red boxes-font">
            {{ dashboard.karyawanInLatestMonth }} Naik di 30 hari
          </div>
        </router-link>
      </div>
    </div>

    <div class="col-md-3 col-sm-12 col-xs-12">
      <div class="panel panel-primary text-center no-boder bg-color-brown">
        <div class="panel-body boxes">
          <i class="flaticon-diagram"></i>
          <h3 style="padding: 8px; font-size: 18px" v-if="!isLoading">
              Deal: {{ dashboard.countDeal }} ({{ dashboard.deactivatedDeal }})
          </h3>
          <h3 style="padding: 8px; font-size: 18px" v-else>Loading...</h3>
        </div>

        <router-link style="text-decoration: none" :to="{ name: 'deal' }">
          <div class="panel-footer back-footer-brown boxes-font">
            {{ dashboard.dealInLatestMonth }} Naik di 30 hari
          </div>
        </router-link>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapState } from 'vuex';

export default {
  name: "PanelDashboard",
  computed: {
    ...mapGetters(["isLoading"]),
    ...mapState("dashboard", ["dashboard"]),
  },
};
</script>

<style scoped>
.small {
  max-width: 600px;
  margin: 150px auto;
}

a.list-group-item {
  color: #555;
}

.pembatasan {
  display: inline-block;
  max-width: 45%;
  /* height: 1.5em; */
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.section {
  margin-bottom: 1.5rem;
}

.panel-footer {
  padding: 10px 15px;
  background-color: #f5f5f5;
  border-top: 1px solid #ddd;
  border-bottom-right-radius: 3px;
  border-bottom-left-radius: 3px;
}
.bg-color-green {
  background-color: #fff;
  color: #5cb85c;
}
.no-boder {
  border: 1px solid #f3f3f3;
}
.boxes {
  padding: 8px !important;
  border-style: solid;
}
.boxes-font {
  font-size: 15px;
  padding: 7px;
}
.back-footer-green {
  background-color: #5cb85c;
  color: #fff;
  border-top: 0px solid #fff;
}
i {
  font-size: 3em;
}

.bg-color-blue {
  background-color: #fff;
  color: #4cb1cf;
}
.back-footer-blue {
  background-color: #4cb1cf;
  color: #fff;
  border-top: 0px solid #fff;
}

.bg-color-red {
  background-color: #fff;
  color: #f0433d;
}
.back-footer-red {
  background-color: #f0433d;
  color: #fff;
  border-top: 0px solid #fff;
}

.bg-color-brown {
  background-color: #fff;
  color: #f0ad4e;
}
.back-footer-brown {
  background-color: #f0ad4e;
  color: #fff;
  border-top: 0px solid #fff;
}
</style>