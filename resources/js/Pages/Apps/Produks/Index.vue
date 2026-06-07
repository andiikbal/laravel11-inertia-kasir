<template>
  <Head>
    <title>Produk - Aplikasi Kasir</title>
  </Head>
  <main class="c-main">
    <div class="container-fluid">
      <div class="fade-in">
        <div class="row">
          <div class="col-md-12">
            <div class="card border-0 rounded-3 shadow border-top-purple">
              <div class="card-header">
                <span class="font-weight-bold">
                  <i class="fa fa-shopping-bag"></i> PRODUK
                </span>
              </div>
              <div class="card-body">
                <form @submit.prevent="handleSearch">
                  <div class="input-group mb-3">
                    <Link
                      href="/apps/produks/create"
                      v-if="hasAnyPermission(['produks.create'])"
                      class="btn btn-primary input-group-text"
                    >
                      <i class="fa fa-plus-circle me-2"></i> TAMBAH
                    </Link>
                    <input
                      id="cari"
                      type="text"
                      class="form-control"
                      v-model="cari"
                      placeholder="cari berdasarkan nama produk..."
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
                      <th scope="col" class="align-middle">Barcode</th>
                      <th scope="col" class="align-middle">Gambar</th>
                      <th scope="col" class="align-middle">Nama Produk</th>
                      <th scope="col" class="align-middle">Harga Beli</th>
                      <th scope="col" class="align-middle">Harga Jual</th>
                      <th scope="col" class="align-middle">Stok</th>
                      <th scope="col" class="align-middle" style="width: 20%">
                        Aksi
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(produk, index) in produks.data" :key="index">
                      <td class="text-center">
                        <Barcode
                          :value="produk.barcode"
                          :format="'CODE39'"
                          :lineColor="'#000'"
                          :width="1"
                          :height="20"
                        />
                        <!-- {{ produk.barcode }} -->
                      </td>
                      <td class="text-center">
                        <img :src="produk.gambar" width="80" />
                      </td>
                      <td>{{ produk.nama }}</td>
                      <td>Rp. {{ formatPrice(produk.harga_beli) }}</td>
                      <td>Rp. {{ formatPrice(produk.harga_jual) }}</td>
                      <td class="text-center">{{ produk.stok }}</td>
                      <td class="text-center">
                        <Link
                          :href="`/apps/produks/${produk.id}/edit`"
                          v-if="hasAnyPermission(['produks.edit'])"
                          class="btn btn-success btn-sm me-2"
                        >
                          <i class="fa fa-pencil-alt me-1"></i> EDIT
                        </Link>
                        <button
                          @click.prevent="destroy(produk.id)"
                          v-if="hasAnyPermission(['produks.delete'])"
                          class="btn btn-danger btn-sm"
                        >
                          <i class="fa fa-trash"></i> DELETE
                        </button>
                      </td>
                    </tr>
                  </tbody>
                </table>
                <Pagination :links="produks.links" align="end" />
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

//import component barcode
import Barcode from "../../../Components/Barcode.vue";

export default {
  //layout
  layout: LayoutApp,

  //register components
  components: {
    Head,
    Link,
    Pagination,
    Barcode,
  },

  //props
  props: {
    produks: Object,
  },

  //composition API
  setup() {
    onMounted(() => {
      focused("cari");
    });

    const focused = (element) => {
      document.getElementById(element).focus();
    };

    const cari = ref("" || new URL(document.location).searchParams.get("q"));

    const handleSearch = () => {
      router.get("/apps/produks", {
        q: cari.value.trim(),
      });
    };

    const destroy = (id) => {
      axios.post(`/apps/produks/${id}`).then((response) => {
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
            focused("cari")
          ).then((result) => {
            if (result.isConfirmed) {
              router.post(
                `/apps/produks/${id}`,
                {
                  _method: "DELETE",
                },
                {
                  onSuccess: () => {
                    Swal.fire(
                      {
                        title: "Berhasil!",
                        text: "Data produk berhasil dihapus.",
                        icon: "success",
                        timer: 2000,
                        showConfirmButton: false,
                      },
                      router.get("/apps/produks")
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
