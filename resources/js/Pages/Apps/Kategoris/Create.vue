<template>
  <Head>
    <title>Tambah Kategori - Aplikasi Kasir</title>
  </Head>
  <main class="c-main">
    <div class="container-fluid">
      <div class="fade-in">
        <div class="row">
          <div class="col-md-6">
            <div class="card border-0 rounded-3 shadow border-top-purple">
              <div class="card-header">
                <span class="font-weight-bold">
                  <i class="fa fa-user"></i> TAMBAH KATEGORI
                </span>
              </div>
              <div class="card-body">
                <form @submit.prevent="submit">
                  <div class="row">
                    <div class="col-12">
                      <div class="mb-3">
                        <label class="fw-bold">Nama Kategori</label>
                        <input
                          id="nama_kategori"
                          class="form-control"
                          v-model="form.nama_kategori"
                          :class="{ 'is-invalid': errors.nama_kategori }"
                          type="text"
                          placeholder="Nama Kategori"
                          @keypress.enter.prevent="focused('keterangan')"
                        />
                      </div>
                      <div
                        v-if="errors.nama_kategori"
                        class="alert alert-danger py-2"
                        style="margin-top: -8px"
                      >
                        {{ errors.nama_kategori }}
                      </div>
                    </div>
                    <div class="col-12">
                      <div class="mb-3">
                        <label class="fw-bold">Keterangan</label>
                        <input
                          id="keterangan"
                          class="form-control"
                          v-model="form.keterangan"
                          :class="{ 'is-invalid': errors.keterangan }"
                          type="text"
                          placeholder="Keterangan"
                        />
                      </div>
                      <div
                        v-if="errors.keterangan"
                        class="alert alert-danger py-2"
                        style="margin-top: -8px"
                      >
                        {{ errors.keterangan }}
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-12">
                      <button
                        class="btn btn-primary shadow-sm rounded-sm"
                        type="submit"
                      >
                        SIMPAN
                      </button>
                      <button
                        class="btn btn-warning shadow-sm rounded-sm ms-3"
                        type="button"
                        @click="reset"
                      >
                        RESET
                      </button>
                    </div>
                  </div>
                </form>
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
import { Head, Link, router } from "@inertiajs/vue3";

//import reactive from vue
import { onMounted, reactive } from "vue";

//import sweet alert2
import Swal from "sweetalert2";

export default {
  layout: LayoutApp,

  //register component
  components: {
    Head,
    Link,
  },

  props: {
    errors: Object,
  },

  //composition API
  setup(props) {
    onMounted(() => {
      focused("nama_kategori");
    });

    //define form with reactive
    const form = reactive({
      nama_kategori: "",
      keterangan: "",
    });

    const focused = (element) => {
      document.getElementById(element).focus();
    };

    const trim = () => {
      form.nama_kategori = form.nama_kategori.trim();
      form.keterangan = form.keterangan.trim();
    };

    const submit = () => {
      trim();
      router.post(
        "/apps/kategoris",
        {
          nama_kategori: form.nama_kategori,
          keterangan: form.keterangan,
        },
        {
          onSuccess: () => {
            Swal.fire({
              title: "Berhasil!",
              text: "Kategori berhasil ditambahkan.",
              icon: "success",
              showConfirmButton: false,
              timer: 2000,
            });
          },
          onError: () => {
            if (props.errors.nama_kategori) {
              document.getElementById("nama_kategori").focus();
            } else if (props.errors.keterangan) {
              document.getElementById("keterangan").focus();
            }
          },
        }
      );
    };

    const reset = () => {
      router.get("/apps/kategoris/create");
    };

    return {
      focused,
      trim,
      form,
      submit,
      reset,
    };
  },
};
</script>

<style>
</style>
