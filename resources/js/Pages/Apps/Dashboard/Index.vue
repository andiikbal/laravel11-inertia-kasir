<template>
  <Head>
    <title>Dashboard - Aplikasi Kasir</title>
  </Head>

  <main class="c-main">
    <div class="container-fluid">
      <div class="fade-in">
        <div class="row">
          <div class="col-md-8">
            <div
              v-if="hasAnyPermission(['dashboard.grafik_penjualan'])"
              class="card border-0 rounded-3 shadow border-top-purple"
            >
              <div class="card-header">
                <span class="font-weight-bold">
                  <i class="fa fa-chart-bar"></i> GRAFIK PENJUALAN 7 HARI
                  TERAKHIR
                </span>
              </div>
              <div class="card-body">
                <BarChart :chartData="chartSellWeek" :options="options" />
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div
              v-if="hasAnyPermission(['dashboard.penjualan_hari_ini'])"
              class="card border-0 rounded-3 shadow border-top-info mb-4"
            >
              <div class="card-header">
                <span class="font-weight-bold">
                  <i class="fa fa-chart-line"></i> PENJUALAN HARI INI
                </span>
              </div>
              <div class="card-body">
                <strong>{{ jumlah_transaksi_penjualan_hari_ini }}</strong>
                PENJUALAN
                <hr />
                <h5 class="fw-bold">
                  Rp. {{ formatPrice(jumlah_penjualan_hari_ini) }}
                </h5>
              </div>
            </div>

            <div
              v-if="hasAnyPermission(['dashboard.keuntungan_hari_ini'])"
              class="card border-0 rounded-3 shadow border-top-success"
            >
              <div class="card-header">
                <span class="font-weight-bold">
                  <i class="fa fa-chart-bar"></i> KEUNTUNGAN HARI INI
                </span>
              </div>
              <div class="card-body">
                <h5 class="fw-bold">
                  Rp. {{ formatPrice(jumlah_keuntungan_hari_ini) }}
                </h5>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div
              v-if="hasAnyPermission(['dashboard.produk_terlaris'])"
              class="card border-0 rounded-3 shadow border-top-warning"
            >
              <div class="card-header">
                <span class="font-weight-bold">
                  <i class="fa fa-chart-pie"></i> PRODUK TERLARIS
                </span>
              </div>
              <div class="card-body">
                <DoughnutChart :chartData="chartBestProduct" />
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div
              v-if="hasAnyPermission(['dashboard.stok_produk'])"
              class="card border-0 rounded-3 shadow border-top-danger"
            >
              <div class="card-header">
                <span class="font-weight-bold">
                  <i class="fa fa-box-open"></i> STOK PRODUK
                </span>
              </div>
              <div class="card-body">
                <div v-if="stok_produk_kurang.length > 0">
                  <ol class="list-group list-group-numbered">
                    <li
                      v-for="produk in stok_produk_kurang"
                      :key="produk.id"
                      class="list-group-item d-flex justify-content-between align-items-start"
                    >
                      <div class="ms-2 me-auto">
                        <div class="fw-bold">{{ produk.nama }}</div>
                        <div class="text-muted">
                          Kategori : {{ produk.kategori.nama }}
                        </div>
                      </div>
                      <span class="badge bg-danger rounded-pill">
                        {{ produk.stok }}
                      </span>
                    </li>
                  </ol>
                </div>
                <div
                  v-else
                  class="alert alert-danger border-0 shadow rounded-3"
                >
                  Data Tidak Tersedia!.
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
</template>

<script>
//import layout
import LayoutApp from "../../../Layouts/App.vue";

//import Heade and useForm from Inertia
import { Head } from "@inertiajs/vue3";

//import ref from vue
import { ref } from "vue";

//chart
import { BarChart, DoughnutChart } from "vue-chart-3";
import { Chart, registerables } from "chart.js";

//register chart
Chart.register(...registerables);

export default {
  layout: LayoutApp,

  //register component
  components: {
    Head,
    BarChart,
    DoughnutChart,
  },

  props: {
    jumlah_transaksi_penjualan_hari_ini: Number,
    jumlah_penjualan_hari_ini: Number,
    jumlah_keuntungan_hari_ini: Number,
    tanggal_penjualan: Array,
    jumlah_keseluruhan: Array,
    produk: Array,
    total: Array,
    stok_produk_kurang: Array,
  },

  setup(props) {
    //method random color
    function randomBackgroundColor(length) {
      var data = [];
      for (var i = 0; i < length; i++) {
        data.push(getRandomColor());
      }
      return data;
    }

    //method generate random color
    function getRandomColor() {
      var letters = "0123456789ABCDEF".split("");
      var color = "#";
      for (var i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 16)];
      }
      return color;
    }

    //option chart
    const options = ref({
      responsive: true,
      plugins: {
        legend: {
          display: false,
        },
        title: {
          display: false,
        },
      },
      beginZero: true,
    });

    //chart sell week
    const chartSellWeek = {
      labels: props.tanggal_penjualan,
      datasets: [
        {
          data: props.jumlah_keseluruhan,
          backgroundColor: randomBackgroundColor(
            props.tanggal_penjualan.length
          ),
        },
      ],
    };

    //chart produk terlaris
    const chartBestProduct = {
      labels: props.produk,
      datasets: [
        {
          data: props.total,
          backgroundColor: randomBackgroundColor(5),
        },
      ],
    };

    return {
      options,
      chartSellWeek,
      chartBestProduct,
    };
  },
};
</script>

<style>
</style>
