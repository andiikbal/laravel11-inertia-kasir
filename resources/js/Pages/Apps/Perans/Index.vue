<template>
  <Head>
    <title>Peran - Aplikasi Kasir</title>
  </Head>
  <main class="c-main">
    <div class="container-fluid">
      <div class="fade-in">
        <div class="row">
          <div class="col-md-12">
            <div class="card border-0 rounded-3 shadow border-top-purple">
              <div class="card-header">
                <span class="font-weight-bold">
                  <i class="fa fa-shield-alt"></i> PERAN
                </span>
              </div>
              <div class="card-body">
                <form @submit.prevent="handleSearch">
                  <div class="input-group mb-3">
                    <Link
                      href="/apps/perans/create"
                      v-if="hasAnyPermission(['roles.create'])"
                      class="btn btn-primary input-group-text"
                    >
                      <i class="fa fa-plus-circle me-2"></i> TAMBAH
                    </Link>
                    <input
                      id="cari"
                      type="text"
                      class="form-control"
                      v-model="cari"
                      placeholder="cari berdasarkan peran..."
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
                    <tr class="text-center">
                      <th scope="col">Peran</th>
                      <th scope="col" style="width: 50%">Izin</th>
                      <th scope="col" style="width: 20%">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(peran, index) in perans.data" :key="index">
                      <td>{{ peran.name }}</td>
                      <td>
                        <span
                          v-for="(permission, index) in peran.permissions"
                          :key="index"
                          class="badge badge-primary shadow border-0 ms-2 mb-2"
                        >
                          {{ permission.name }}
                        </span>
                      </td>
                      <td class="text-center">
                        <Link
                          :href="`/apps/perans/${peran.id}/edit`"
                          v-if="hasAnyPermission(['roles.edit'])"
                          class="btn btn-success btn-sm me-2"
                        >
                          <i class="fa fa-pencil-alt me-1"></i> EDIT
                        </Link>
                        <button
                          @click.prevent="destroy(peran.id)"
                          v-if="hasAnyPermission(['roles.delete'])"
                          class="btn btn-danger btn-sm"
                        >
                          <i class="fa fa-trash"></i> DELETE
                        </button>
                      </td>
                    </tr>
                  </tbody>
                </table>
                <Pagination :links="perans.links" align="end" />
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

//import axios
import axios from "axios";

//import sweet alert2
import Swal from "sweetalert2";

export default {
  //layout
  layout: LayoutApp,

  //register component
  components: {
    Head,
    Link,
    Pagination,
  },

  props: {
    perans: Object,
  },

  setup() {
    onMounted(() => {
      document.getElementById("cari").focus();
    });

    //define state search
    const cari = ref("" || new URL(document.location).searchParams.get("q"));

    //define method search
    const handleSearch = () => {
      if (cari.value == null) {
        document.getElementById("cari").focus();
      } else {
        router.get("/apps/perans", {
          q: cari.value.trim(),
        });
      }
    };

    //define method destroy
    const destroy = (id) => {
      document.getElementById("cari").focus();

      Swal.fire({
        title: "Anda yakin?",
        text: "Anda tidak dapat mengembalikan data ini!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#198754",
        confirmButtonText: "Ya, hapus!",
      }).then((result) => {
        if (result.isConfirmed) {
          axios.delete(`/apps/perans/${id}`).then((response) => {
            if (response.data.success) {
              Swal.fire({
                title: "Berhasil",
                text: "Peran berhasil dihapus.",
                icon: "success",
                timer: 2000,
                showConfirmButton: false,
              });
              router.get("/apps/perans");
            } else {
              Swal.fire({
                title: "Gagal!",
                text: "Peran gagal dihapus, karena digunakan pada tabel pengguna.",
                icon: "error",
                timer: 2000,
                showConfirmButton: false,
              });
            }
          });
        }
      });
    };

    return {
      cari,
      handleSearch,
      destroy,
    };
  },
};
</script>

<style>
</style>
