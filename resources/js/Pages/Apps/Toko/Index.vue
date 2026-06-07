<template>
  <Head>
    <title>Profil Toko - Aplikasi Kasir</title>
  </Head>
  <main class="c-main">
    <div class="container-fluid">
      <div class="fade-in">
        <div class="row">
          <div class="col-md-12">
            <div class="card border-0 rounded-3 shadow border-top-purple">
              <div class="card-header">
                <span class="font-weight-bold">
                  <i class="fa fa-user"></i> PROFIL TOKO
                </span>
              </div>
              <div class="card-body">
                <form @submit.prevent="submit">
                  <div class="row">
                    <div class="col-md-3 text-center">
                      <img
                        :src="toko.logo"
                        width="90%"
                        class="img-thumbnail mb-2"
                      />
                    </div>

                    <div class="col-md-9">
                      <form @submit.prevent="upload">
                        <div class="row">
                          <div class="col-md-9 col-lg-10">
                            <div class="mb-3">
                              <input
                                id="logo"
                                class="form-control"
                                @input="form.logo = $event.target.files[0]"
                                :class="{ 'is-invalid': errors.logo }"
                                type="file"
                              />
                            </div>
                            <div
                              v-if="errors.logo"
                              class="alert alert-danger py-2"
                              style="margin-top: -8px; margin-bottom: 10px"
                            >
                              {{ errors.logo }}
                            </div>
                          </div>
                          <div class="col-md-3 col-lg-2 mb-3">
                            <button
                              v-if="hasAnyPermission(['toko.edit'])"
                              class="btn btn-primary shadow-sm rounded-sm btn-block"
                              type="submit"
                            >
                              UPLOAD
                            </button>
                          </div>
                        </div>
                      </form>

                      <form @submit.prevent="submit">
                        <div class="row">
                          <div class="col-md-6">
                            <div class="mb-3">
                              <label class="fw-bold">Nama Toko</label>
                              <input
                                id="nama_toko"
                                class="form-control"
                                v-model="form.nama_toko"
                                :class="{ 'is-invalid': errors.nama_toko }"
                                type="text"
                                placeholder="Nama Toko"
                                @keypress.enter.prevent="focused('alamat_toko')"
                              />
                            </div>
                            <div
                              v-if="errors.nama_toko"
                              class="alert alert-danger py-2"
                              style="margin-top: -8px"
                            >
                              {{ errors.nama_toko }}
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="mb-3">
                              <label class="fw-bold">Alamat Toko</label>
                              <input
                                id="alamat_toko"
                                class="form-control"
                                v-model="form.alamat_toko"
                                :class="{ 'is-invalid': errors.alamat_toko }"
                                type="text"
                                placeholder="Alamat Toko"
                                @keypress.enter.prevent="focused('no_telepon')"
                              />
                            </div>
                            <div
                              v-if="errors.alamat_toko"
                              class="alert alert-danger py-2"
                              style="margin-top: -8px"
                            >
                              {{ errors.alamat_toko }}
                            </div>
                          </div>
                        </div>
                        <div class="row">
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
                                @keypress.enter.prevent="focused('keterangan')"
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
                          <div class="col-md-6">
                            <div class="mb-3">
                              <label class="fw-bold">Keterangan</label>
                              <input
                                id="keterangan"
                                class="form-control"
                                v-model="form.keterangan"
                                type="text"
                                placeholder="Keterangan"
                              />
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-12 d-flex justify-content-end">
                            <button
                              v-if="hasAnyPermission(['toko.edit'])"
                              class="btn btn-primary shadow-sm rounded-sm px-4"
                              type="submit"
                            >
                              UBAH
                            </button>
                          </div>
                        </div>
                      </form>
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
import LayoutApp from "../../../Layouts/App.vue"; //import layout
import { Head, router } from "@inertiajs/vue3"; //import Head and route from Inertia
import { onMounted, reactive } from "vue"; //import reactive from vue
import Swal from "sweetalert2"; //import sweet alert2

export default {
  layout: LayoutApp,
  components: { Head }, //register component
  props: { errors: Object, toko: Object },

  //composition API
  setup(props) {
    onMounted(() => {
      focused("nama_toko");
    });

    const focused = (element) => {
      document.getElementById(element).focus();
    };

    const trim = () => {
      form.nama_toko = form.nama_toko.trim();
      form.alamat_toko = form.alamat_toko.trim();
      form.no_telepon = form.no_telepon.trim();
      form.keterangan = form.keterangan.trim();
    };

    const form = reactive({
      logo: "",
      nama_toko: props.toko.nama,
      alamat_toko: props.toko.alamat,
      no_telepon: props.toko.no_telepon,
      keterangan: props.toko.keterangan,
    });

    const upload = () => {
      router.post(
        `/apps/toko/${props.toko.id}/upload`,
        {
          _method: "PUT",
          logo: form.logo,
        },
        {
          onSuccess: () => {
            Swal.fire(
              {
                title: "Sukses!",
                text: "Logo Toko berhasil diupload.",
                icon: "success",
                showConfirmButton: false,
                timer: 2000,
              },
              focused("nama_toko")
            );
            form.logo = "";
            document.getElementById("logo").value = "";
          },
          onError: () => {
            focused("logo");
          },
        }
      );
    };

    const submit = () => {
      trim(); // Trim the input values
      router.post(
        `/apps/toko/${props.toko.id}`,
        {
          _method: "PUT",
          nama_toko: form.nama_toko,
          alamat_toko: form.alamat_toko,
          no_telepon: form.no_telepon,
          keterangan: form.keterangan,
        },
        {
          onSuccess: () => {
            Swal.fire(
              {
                title: "Berhasil!",
                text: "Profil Toko berhasil diubah.",
                icon: "success",
                showConfirmButton: false,
                timer: 2000,
              },
              focused("nama_toko")
            );
          },
          onError: () => {
            if (props.errors.nama_toko) {
              focused("nama_toko");
            } else if (props.errors.alamat_toko) {
              focused("alamat_toko");
            } else if (props.errors.no_telepon) {
              focused("no_telepon");
            } else if (props.errors.keterangan) {
              focused("keterangan");
            }
          },
        }
      );
    };

    return {
      onMounted,
      focused,
      trim,
      form,
      upload,
      submit,
    };
  },
};
</script>

<style>
</style>
