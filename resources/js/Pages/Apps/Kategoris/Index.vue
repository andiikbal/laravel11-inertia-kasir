<template>
  <Head>
    <title>Kategori - Aplikasi Kasir</title>
  </Head>
  <main class="c-main">
    <div class="container-fluid">
      <div class="fade-in">
        <div class="row">
          <div class="col-md-12">
            <div class="card border-0 rounded-3 shadow border-top-purple">
              <div class="card-header">
                <span class="font-weight-bold">
                  <i class="fa fa-users"></i> KATEGORI
                </span>
              </div>
              <div class="card-body">
                <form @submit.prevent="handleSearch">
                  <div class="input-group mb-3">
                    <Link
                      href="/apps/kategoris/create"
                      v-if="hasAnyPermission(['kategoris.create'])"
                      class="btn btn-primary input-group-text"
                    >
                      <i class="fa fa-plus-circle me-2"></i> TAMBAH
                    </Link>

                    <input
                      id="cari"
                      type="text"
                      class="form-control"
                      v-model="cari"
                      placeholder="cari berdasarkan nama kategori..."
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
                      <th scope="col" class="align-middle">Nama</th>
                      <th scope="col" class="align-middle">Keterangan</th>
                      <th scope="col" class="align-middle" style="width: 20%">
                        Aksi
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr
                      v-for="(kategori, index) in kategoris.data"
                      :key="index"
                    >
                      <td>{{ kategori.nama }}</td>
                      <td>{{ kategori.keterangan }}</td>
                      <td class="text-center">
                        <Link
                          :href="`/apps/kategoris/${kategori.id}/edit`"
                          v-if="hasAnyPermission(['kategoris.edit'])"
                          class="btn btn-success btn-sm me-2"
                        >
                          <i class="fa fa-pencil-alt me-1"></i> EDIT
                        </Link>
                        <button
                          @click.prevent="destroy(kategori.id)"
                          v-if="hasAnyPermission(['kategoris.delete'])"
                          class="btn btn-danger btn-sm"
                        >
                          <i class="fa fa-trash"></i> DELETE
                        </button>
                      </td>
                    </tr>
                  </tbody>
                </table>
                <Pagination :links="kategoris.links" align="end" />
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
  layout: LayoutApp,

  components: {
    Head,
    Link,
    Pagination,
  },

  props: {
    kategoris: Object,
  },

  setup() {
    onMounted(() => {
      focused("cari");
    });

    const focused = (element) => {
      document.getElementById(element).focus();
    };

    //define state search
    const cari = ref("" || new URL(document.location).searchParams.get("q"));

    //define method search
    const handleSearch = () => {
      router.get("/apps/kategoris", {
        //send params "q" with value from state "cari"
        q: cari.value.trim(),
      });
    };

    //method destroy
    const destroy = (id) => {
      axios.post(`/apps/kategoris/${id}`).then((response) => {
        if (response.data.success) {
          Swal.fire(
            {
              title: "Anda yakin?",
              text: response.data.message,
              icon: "warning",
              showCancelButton: true,
              confirmButtonColor: "#d33",
              cancelButtonColor: "#198754",
              confirmButtonText: "Ya, hapus!",
            },
            focused("cari")
          ).then((result) => {
            if (result.isConfirmed) {
              router.post(
                `/apps/kategoris/${id}`,
                {
                  _method: "DELETE",
                },
                {
                  onSuccess: () => {
                    Swal.fire(
                      {
                        title: "Berhasil!",
                        text: "Data Kategori berhasil dihapus.",
                        icon: "success",
                        timer: 2000,
                        showConfirmButton: false,
                      },
                      focused("cari")
                    );
                  },
                }
              );
            }
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
