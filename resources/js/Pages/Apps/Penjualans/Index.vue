<template>
  <Head>
    <title>Laporan Penjualan - Aplikasi Kasir</title>
  </Head>
  <main class="c-main">
    <div class="container-fluid">
      <div class="fade-in">
        <div class="row">
          <div class="col-md-12">
            <div class="card border-0 rounded-3 shadow border-top-purple">
              <div class="card-header">
                <span class="font-weight-bold">
                  <i class="fa fa-chart-bar"></i> LAPORAN PENJUALAN
                </span>
              </div>
              <div class="card-body">
                <form @submit.prevent="filter">
                  <div class="row">
                    <div class="col-md-5">
                      <div class="mb-3">
                        <label class="form-label fw-bold">TANGGAL AWAL</label>
                        <input
                          type="date"
                          v-model="form.tanggal_awal"
                          class="form-control"
                        />
                      </div>
                      <div
                        v-if="errors.tanggal_awal"
                        class="alert alert-danger py-2"
                        style="margin-top: -8px"
                      >
                        {{ errors.tanggal_awal }}
                      </div>
                    </div>
                    <div class="col-md-5">
                      <div class="mb-3">
                        <label class="form-label fw-bold">TANGGAL AKHIR</label>
                        <input
                          type="date"
                          v-model="form.tanggal_akhir"
                          class="form-control"
                        />
                      </div>
                      <div
                        v-if="errors.tanggal_akhir"
                        class="alert alert-danger py-2"
                        style="margin-top: -8px"
                      >
                        {{ errors.tanggal_akhir }}
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="mb-3">
                        <label class="form-label fw-bold text-white">*</label>
                        <button
                          class="btn btn-md btn-purple border-0 shadow w-100"
                          type="submit"
                        >
                          <i class="fa fa-filter"></i> FILTER
                        </button>
                      </div>
                    </div>
                  </div>
                </form>

                <div
                  v-if="errors.message"
                  class="alert alert-danger py-2"
                  style="margin-top: -8px"
                >
                  {{ errors.message }}
                </div>

                <div v-if="penjualans.length > 0">
                  <hr />
                  <div class="export text-end mb-3">
                    <a
                      :href="`/apps/penjualans/export?tanggal_awal=${form.tanggal_awal}&tanggal_akhir=${form.tanggal_akhir}`"
                      target="_blank"
                      class="btn btn-success btn-md border-0 shadow me-3"
                    >
                      <i class="fa fa-file-excel"></i> EXCEL
                    </a>
                    <a
                      :href="`/apps/penjualans/pdf?tanggal_awal=${form.tanggal_awal}&tanggal_akhir=${form.tanggal_akhir}`"
                      target="_blank"
                      class="btn btn-secondary btn-md border-0 shadow me-3"
                    >
                      <i class="fa fa-file-pdf"></i> PDF
                    </a>
                    <a
                      :href="`/apps/penjualans`"
                      class="btn btn-warning btn-md border-0 shadow"
                    >
                      RESET
                    </a>
                  </div>
                  <table class="table table-bordered">
                    <thead>
                      <tr style="background-color: #e6e6e7">
                        <th scope="col">Tanggal</th>
                        <th scope="col">Faktur</th>
                        <th scope="col">Kasir</th>
                        <th scope="col">Pelanggan</th>
                        <th scope="col">Total</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="penjualan in penjualans" :key="penjualan.id">
                        <td>{{ penjualan.created_at }}</td>
                        <td class="text-center">{{ penjualan.faktur }}</td>
                        <td>{{ penjualan.kasir.name }}</td>
                        <td>
                          {{
                            penjualan.pelanggan
                              ? penjualan.pelanggan.nama
                              : "Umum"
                          }}
                        </td>
                        <td class="text-end">
                          Rp. {{ formatPrice(penjualan.grand_total) }}
                        </td>
                      </tr>
                      <tr>
                        <td
                          colspan="4"
                          class="text-end fw-bold"
                          style="background-color: #e6e6e7"
                        >
                          TOTAL
                        </td>
                        <td
                          class="text-end fw-bold"
                          style="background-color: #e6e6e7"
                        >
                          Rp. {{ formatPrice(total) }}
                        </td>
                      </tr>
                    </tbody>
                  </table>
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
//import layout App
import LayoutApp from "../../../Layouts/App.vue";

//import Head and Link from Inertia
import { Head, Link } from "@inertiajs/vue3";

//import axios
import axios from "axios";

//import hook ref
import { ref, reactive } from "vue";

export default {
  //layout App
  layout: LayoutApp,

  //register components
  components: {
    Head,
    Link,
  },

  //props
  props: {
    errors: Object,
    session: Object,
  },

  //composition API
  setup(props) {
    const total = ref(0);
    const penjualans = ref([]);

    //define form with reactive
    const form = reactive({
      tanggal_awal: "",
      tanggal_akhir: "",
    });

    //define methods filter
    const filter = () => {
      props.errors.tanggal_awal = "";
      props.errors.tanggal_akhir = "";
      props.errors.message = "";
      if (form.tanggal_awal == "" && form.tanggal_akhir == "") {
        props.errors.tanggal_awal = "Tanggal Awal harus diisi";
        props.errors.tanggal_akhir = "Tanggal Akhir harus diisi";
      } else if (form.tanggal_awal == "") {
        props.errors.tanggal_awal = "Tanggal Awal harus diisi";
      } else if (form.tanggal_akhir == "") {
        props.errors.tanggal_akhir = "Tanggal Akhir harus diisi";
      } else if (form.tanggal_awal > form.tanggal_akhir) {
        props.errors.tanggal_awal = "Tanggal Awal lebih besar";
      } else {
        axios
          .post("/apps/penjualans", {
            tanggal_awal: form.tanggal_awal,
            tanggal_akhir: form.tanggal_akhir,
          })
          .then((response) => {
            penjualans.value = response.data.data.penjualans;
            total.value = response.data.data.total;
            if (total.value == 0) {
              props.errors.message = "Data Transaksi kosong";
            }
          });
      }
    };

    return {
      penjualans,
      total,
      form,
      filter,
    };
  },
};
</script>

<style>
</style>
