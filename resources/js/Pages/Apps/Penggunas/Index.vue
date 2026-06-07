<template>
  <Head>
    <title>Pengguna - Aplikasi Kasir</title>
  </Head>
  <main class="c-main">
    <div class="container-fluid">
      <div class="fade-in">
        <div class="row">
          <div class="col-md-12">
            <div class="card border-0 rounded-3 shadow border-top-purple">
              <div class="card-header">
                <span class="font-weight-bold">
                  <i class="fa fa-users"></i> PENGGUNA
                </span>
              </div>
              <div class="card-body">
                <form @submit.prevent="handleSearch">
                  <div class="input-group mb-3">
                    <Link
                      href="/apps/penggunas/create"
                      v-if="hasAnyPermission(['users.create'])"
                      class="btn btn-primary input-group-text"
                    >
                      <i class="fa fa-plus-circle me-2"></i> TAMBAH
                    </Link>

                    <input
                      id="cari"
                      type="text"
                      class="form-control"
                      v-model="cari"
                      placeholder="cari berdasarkan nama pengguna..."
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
                      <th scope="col">Nama Lengkap</th>
                      <th scope="col">Alamat Email</th>
                      <th scope="col">Peran</th>
                      <th scope="col" style="width: 20%">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr
                      v-for="(pengguna, index) in penggunas.data"
                      :key="index"
                    >
                      <td>{{ pengguna.name }}</td>
                      <td>{{ pengguna.email }}</td>
                      <td class="text-center">
                        <span
                          v-for="(peran, index) in pengguna.roles"
                          :key="index"
                          class="badge badge-primary shadow border-0 ms-2 mb-2"
                        >
                          {{ peran.name }}
                        </span>
                      </td>
                      <td class="text-center">
                        <Link
                          :href="`/apps/penggunas/${pengguna.id}/edit`"
                          v-if="hasAnyPermission(['users.edit'])"
                          class="btn btn-success btn-sm me-2"
                        >
                          <i class="fa fa-pencil-alt me-1"></i> EDIT
                        </Link>
                        <button
                          @click.prevent="destroy(pengguna.id)"
                          v-if="
                            hasAnyPermission(['users.delete']) &&
                            auth.user.id !== pengguna.id
                          "
                          class="btn btn-danger btn-sm"
                        >
                          <i class="fa fa-trash"></i> DELETE
                        </button>
                      </td>
                    </tr>
                  </tbody>
                </table>
                <Pagination :links="penggunas.links" align="end" />
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

  //props
  props: {
    penggunas: Object,
    auth: Object,
  },

  setup(props) {
    onMounted(() => {
      console.log(props.auth);

      focused("cari");
    });

    // function to focus on the search input
    const focused = (element) => {
      document.getElementById(element).focus();
    };

    // reactive variable for search input
    const cari = ref("" || new URL(document.location).searchParams.get("q"));

    // function to handle search
    const handleSearch = () => {
      cari.value = cari.value != null ? cari.value.trim() : ""; // Trim whitespace

      if (cari.value === "") focused("cari");
      else
        router.get("/apps/penggunas", {
          q: cari.value.trim(),
        });
    };

    const destroy = (id) => {
      axios.post(`/apps/penggunas/${id}`).then((response) => {
        if (response.data.success) {
          Swal.fire(
            {
              title: "Apakah anda yakin?",
              text: response.data.message,
              icon: "warning",
              showCancelButton: true,
              confirmButtonColor: "#d33",
              confirmButtonText: "Ya, hapus!",
              cancelButtonColor: "#198754",
              cancelButtonText: "Batal",
            },
            focused("cari")
          ).then((result) => {
            if (result.isConfirmed) {
              router.post(
                `/apps/penggunas/${id}`,
                {
                  _method: "DELETE",
                },
                {
                  onSuccess: () => {
                    Swal.fire(
                      {
                        title: "Berhasil!",
                        text: "Data Pengguna berhasil dihapus.",
                        icon: "success",
                        timer: 2000,
                        showConfirmButton: false,
                      },
                      (cari.value = "")
                    );
                  },
                }
              );
            }
            focused("cari");
          });
        } else {
          Swal.fire(
            {
              title: "Gagal!",
              text: response.data.message,
              icon: "error",
              timer: 2000,
              showConfirmButton: false,
            },
            focused("cari")
          );
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
