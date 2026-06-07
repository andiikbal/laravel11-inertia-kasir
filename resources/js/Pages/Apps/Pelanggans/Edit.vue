<template>
  <Head>
    <title>Edit Pelanggan - Aplikasi Kasir</title>
  </Head>
  <main class="c-main">
    <div class="container-fluid">
      <div class="fade-in">
        <div class="row">
          <div class="col-md-12">
            <div class="card border-0 rounded-3 shadow border-top-purple">
              <div class="card-header">
                <span class="font-weight-bold">
                  <i class="fa fa-shopping-bag"></i> EDIT PELANGGAN
                </span>
              </div>
              <div class="card-body">
                <form @submit.prevent="submit">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="mb-3">
                        <label class="fw-bold">Nama Lengkap</label>
                        <input
                          id="nama_lengkap"
                          class="form-control"
                          v-model="form.nama_lengkap"
                          :class="{ 'is-invalid': errors.nama_lengkap }"
                          type="text"
                          placeholder="Nama Lengkap"
                          @keypress.enter.prevent="focused('no_telepon')"
                        />
                      </div>
                      <div
                        v-if="errors.nama_lengkap"
                        class="alert alert-danger py-2"
                        style="margin-top: -8px"
                      >
                        {{ errors.nama_lengkap }}
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="mb-3">
                        <label class="fw-bold">No. Telepon</label>
                        <input
                          id="no_telepon"
                          class="form-control"
                          v-model="form.no_telepon"
                          :class="{ 'is-invalid': errors.no_telepon }"
                          type="text"
                          placeholder="No. Telepon"
                          @keypress.enter.prevent="focused('alamat')"
                        />
                      </div>
                      <div
                        v-if="errors.no_telepon"
                        class="alert alert-danger py-2"
                        style="margin-top: -8px"
                      >
                        {{ errors.no_telepon }}
                      </div>
                    </div>
                  </div>

                  <div class="mb-3">
                    <label class="fw-bold">Alamat</label>
                    <textarea
                      id="alamat"
                      class="form-control"
                      v-model="form.alamat"
                      :class="{ 'is-invalid': errors.alamat }"
                      type="text"
                      rows="4"
                      placeholder="Alamat"
                      @keypress.enter.prevent="focused('submit')"
                    ></textarea>
                  </div>
                  <div
                    v-if="errors.alamat"
                    class="alert alert-danger py-2"
                    style="margin-top: -8px"
                  >
                    {{ errors.alamat }}
                  </div>

                  <div class="row">
                    <div class="col-12">
                      <button
                        id="submit"
                        class="btn btn-primary shadow-sm rounded-sm"
                        type="submit"
                      >
                        UBAH
                      </button>

                      <Link
                        :href="`/apps/pelanggans/${pelanggan.id}/edit`"
                        class="btn btn-warning input-group-text ms-2"
                      >
                        RESET
                      </Link>
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

//import Heade and Link from Inertia
import { Head, Link, router } from "@inertiajs/vue3";

//import reactive from vue
import { onMounted, reactive } from "vue";

//import sweet alert2
import Swal from "sweetalert2";

export default {
  layout: LayoutApp,

  //register components
  components: {
    Head,
    Link,
  },

  //props
  props: {
    errors: Object,
    pelanggan: Object,
  },

  //composition API
  setup(props) {
    onMounted(() => {
      focused("nama_lengkap");
    });

    //define form with reactive
    const form = reactive({
      id: props.pelanggan.id,
      nama_lengkap: props.pelanggan.nama,
      no_telepon: props.pelanggan.no_telepon,
      alamat: props.pelanggan.alamat,
    });

    const focused = (element) => {
      if (element == "submit") document.getElementById(element).click();
      else document.getElementById(element).focus();
    };

    //method "submit"
    const submit = () => {
      router.post(
        `/apps/pelanggans/${props.pelanggan.id}`,
        {
          _method: "PUT",
          nama_lengkap: form.nama_lengkap.trim(),
          no_telepon: form.no_telepon.trim(),
          alamat: form.alamat.trim(),
        },
        {
          onSuccess: () => {
            Swal.fire({
              title: "Berhasil!",
              text: "Pelanggan berhasil diubah.",
              icon: "success",
              showConfirmButton: false,
              timer: 2000,
            });
          },
          onError: () => {
            form.nama_lengkap = form.nama_lengkap.trim();
            form.no_telepon = form.no_telepon.trim();
            form.alamat = form.alamat.trim();
            focused("nama_lengkap");
          },
        }
      );
    };

    return {
      form,
      submit,
      focused,
    };
  },
};
</script>

<style>
</style>
