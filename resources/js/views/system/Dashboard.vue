<template>
  <div class="c-body">
    <main class="c-main">
      <div class="container-fluid">
        <div class="fade-in">
          <!-- begin header Card -->
          <div class="card">
            <div class="card-header">
              <h5 class="mb-0">Selamat datang di softwate CRM</h5>
            </div>
          </div>
          <!-- End header Card -->

          <Panel />

          <!-- begin chart -->
          <div class="row">
            <div class="col-md-6">
              <div class="card p-2">
                <line-chart :height="300" :chart-data="line_chart"></line-chart>
              </div>
            </div>

            <div class="col-md-6">
              <div class="card p-2">
                <bar-chart :height="300" :chart-data="bar_chart"></bar-chart>
              </div>
            </div>
          </div>
          <!-- end chart -->

          <div class="row">
            <Tugas />

            <Perusahaan />
          </div>
          <!-- end section -->

          <Produk />
        </div>
      </div>
    </main>
  </div>
</template>

<script>
import { mapActions, mapGetters, mapState } from 'vuex';

import BarChart from "../../components/chart/BarChart";
import LineChart from "../../components/chart/LineChart";

import Panel from '../../components/dashboard/Panel.vue';
import Tugas from '../../components/dashboard/Tugas.vue';
import Produk from '../../components/dashboard/Produk.vue';
import Perusahaan from '../../components/dashboard/Perusahaan.vue';

export default {
  name: "Dasboard",
  components: {
    LineChart,
    BarChart,
    Panel,
    Tugas,
    Perusahaan,
    Produk
  },
  data() {
    return {
      bar_chart: null,
      line_chart: null,
    };
  },
  computed: {
    ...mapGetters(["isLoading"]),
    ...mapState("dashboard", ["dashboard"]),
  },
  async created() {
    await this.getDashboard()
    this.fillData();
  },
  methods: {
    ...mapActions('dashboard', ['getDashboard']),

    fillData() {
      this.bar_chart = {
        labels: ["Data"],
        datasets: [
          {
            label: "Produk",
            backgroundColor: "#e34333",
            data: [this.dashboard.countProduk],
          },
          {
            label: "Penjualan",
            backgroundColor: "#e4732d",
            data: [this.dashboard.countPenjualan],
          },
          {
            label: "Keuangan",
            backgroundColor: "#f9c364",
            data: [this.dashboard.countKeuangan],
          },
          {
            label: "Deal",
            backgroundColor: "#5c8d5d",
            data: [this.dashboard.countDeal],
          },
        ],
      };

      this.line_chart = {
        labels: ["Senin", "Rabu", "Jumat", "Minggu"],
        datasets: [
          {
            label: "Tugas ditambah",
            backgroundColor: "#8dd8c8",
            data: this.kelompokkan(),
          },
          {
            label: "Tugas komplit",
            backgroundColor: "#8b94d9",
            data: this.kelompokkan(true),
          },
        ],
      };
    },
    kelompokkan(komplit = false) {
      let data = []
      const senin = [1], rabu = [2,3], jumat = [4,5], minggu = [6,0]
      
      Object.keys(this.dashboard.tugasDitambah.hari).map((key) => {
        let val = parseInt(this.dashboard.tugasDitambah.hari[key])
        let dates = komplit ? this.dashboard.tugasKomplit.dates[key] : this.dashboard.tugasDitambah.dates[key]

        if (senin.includes(val)) {
          data[0] = data[0] ? data[0] + dates : dates
        }

        if (rabu.includes(val)) {
          data[1] = data[1] ? data[1] + dates : dates
        }

        if (jumat.includes(val)) {
          data[2] = data[2] ? data[2] + dates : dates
        }

        if (minggu.includes(val)) {
          data[3] = data[3] ? data[3] + dates : dates
        }
      });

      return data
    },
    getRandomInt() {
      return Math.floor(Math.random() * (50 - 5 + 1)) + 5;
    },
  },
};
</script>