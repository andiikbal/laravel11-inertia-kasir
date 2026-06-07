<template>
  <Head>
    <title>Pelanggan - Aplikasi Kasir</title>
  </Head>
  <main class="c-main">
    <div class="container-fluid">
      <div class="fade-in">
        <div class="row">
          <div class="col-md-12">
            <div class="card border-0 rounded-3 shadow border-top-purple">
              <div class="card-header">
                <span class="font-weight-bold">
                  <i class="fa fa-shopping-bag"></i> PELANGGAN
                </span>
              </div>
              <div class="card-body">
                <form @submit.prevent="handleSearch">
                  <div class="input-group mb-3">
                    <Link
                      href="/apps/pelanggans/create"
                      v-if="hasAnyPermission(['pelanggans.create'])"
                      class="btn btn-primary input-group-text"
                    >
                      <i class="fa fa-plus-circle me-2"></i> TAMBAH
                    </Link>
                    <input
                      id="cari"
                      type="text"
                      class="form-control"
                      v-model="cari"
                      placeholder="cari berdasarkan nama pelanggan..."
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
                      <th scope="col" class="align-middle">Nama Lengkap</th>
                      <th scope="col" class="align-middle">No. Telepon</th>
                      <th scope="col" class="align-middle">Alamat</th>
                      <th scope="col" class="align-middle" style="width: 20%">
                        Aksi
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr
                      v-for="(pelanggan, index) in pelanggans.data"
                      :key="index"
                    >
                      <td>{{ pelanggan.nama }}</td>
                      <td class="text-center">{{ pelanggan.no_telepon }}</td>
                      <td>{{ pelanggan.alamat }}</td>
                      <td class="text-center">
                        <Link
                          :href="`/apps/pelanggans/${pelanggan.id}/edit`"
                          v-if="hasAnyPermission(['pelanggans.edit'])"
                          class="btn btn-success btn-sm me-2"
                        >
                          <i class="fa fa-pencil-alt me-1"></i> EDIT
                        </Link>
                        <button
                          @click.prevent="destroy(pelanggan.id)"
                          v-if="hasAnyPermission(['pelanggans.delete'])"
                          class="btn btn-danger btn-sm"
                        >
                          <i class="fa fa-trash"></i> DELETE
                        </button>
                      </td>
                    </tr>
                  </tbody>
                </table>
                <Pagination :links="pelanggans.links" align="end" />
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

  //register components
  components: {
    Head,
    Link,
    Pagination,
  },

  //props
  props: {
    pelanggans: Object,
  },

  //composition API
  setup() {
    onMounted(() => {
      document.getElementById("cari").focus();
    });

    const focused = (i) => {
      if (i == 1) {
        document.getElementById("cari").focus();
      }
    };

    const cari = ref("" || new URL(document.location).searchParams.get("q"));

    const handleSearch = () => {
      router.get("/apps/pelanggans", {
        q: cari.value.trim(),
      });
    };

    const destroy = (id) => {
      axios.post(`/apps/pelanggans/${id}`).then((response) => {
        if (response.data.success) {
          Swal.fire(
            {
              title: "Apakah anda yakin?",
              text: response.data.message,
              icon: "warning",
              showCancelButton: true,
              confirmButtonColor: "#d33",
              cancelButtonColor: "#198754",
              confirmButtonText: "Ya, hapus!",
            },
            document.getElementById("cari").focus()
          ).then((result) => {
            if (result.isConfirmed) {
              router.post(
                `/apps/pelanggans/${id}`,
                {
                  _method: "DELETE",
                },
                {
                  onSuccess: () => {
                    Swal.fire(
                      {
                        title: "Berhasil!",
                        text: "Data Pelanggan berhasil dihapus.",
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
            focused(1);
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
            focused(1)
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
