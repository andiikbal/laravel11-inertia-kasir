<template>
  <Head>
    <title>Izin - Aplikasi Kasir</title>
  </Head>
  <main class="c-main">
    <div class="container-fluid">
      <div class="fade-in">
        <div class="row">
          <div class="col-md-12">
            <div class="card border-0 rounded-3 shadow border-top-purple">
              <div class="card-header">
                <span class="font-weight-bold">
                  <i class="fa fa-key"></i> IZIN
                </span>
              </div>
              <div class="card-body">
                <form @submit.prevent="handleSearch">
                  <div class="input-group mb-3">
                    <input
                      id="cari"
                      type="text"
                      class="form-control"
                      v-model="cari"
                      placeholder="cari berdasarkan nama izin..."
                    />
                    <button
                      class="btn btn-primary input-group-text"
                      type="submit"
                    >
                      <i class="fa fa-search me-2"></i> CARI
                    </button>
                  </div>
                </form>
                <table class="table table-striped table-bordered table-hover">
                  <thead>
                    <tr>
                      <th scope="col">Nama Izin</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(izin, index) in izins.data" :key="index">
                      <td>{{ izin.name }}</td>
                    </tr>
                  </tbody>
                </table>
                <Pagination :links="izins.links" align="end" />
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

//import component pagination
import Pagination from "../../../Components/Pagination.vue";

//import Heade and Link from Inertia
import { Head, Link, router } from "@inertiajs/vue3";

//import ref from vue
import { onMounted, ref } from "vue";

export default {
  //layout
  layout: LayoutApp,

  //register component
  components: {
    Head,
    Link,
    Pagination,
  },

  //props
  props: {
    izins: Object,
  },

  setup() {
    onMounted(() => {
      focused(1);
    });

    const focused = (i) => {
      if (i == 1) {
        document.getElementById("cari").focus();
      }
    };

    //define state search
    const cari = ref("" || new URL(document.location).searchParams.get("q"));

    //define method search
    const handleSearch = () => {
      if (cari.value == null) {
        router.get("/apps/izins");
      } else {
        router.get("/apps/izins", {
          q: cari.value.trim(),
        });
      }
    };

    return {
      cari,
      handleSearch,
    };
  },
};
</script>

<style>
</style>
