<template>
  <Head>
    <title>Profil Pengguna - Aplikasi Kasir</title>
  </Head>
  <main class="c-main">
    <div class="container-fluid">
      <div class="fade-in">
        <div class="row">
          <div class="col-md-12">
            <div class="card border-0 rounded-3 shadow border-top-purple">
              <div class="card-header">
                <span class="font-weight-bold">
                  <i class="fa fa-user"></i> PROFIL PENGGUNA
                </span>
              </div>
              <div class="card-body">
                <div class="row mb-3">
                  <div class="col-md-3 text-center">
                    <img
                      :src="auth.user.photo"
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
                              id="photo"
                              class="form-control"
                              @input="form.photo = $event.target.files[0]"
                              :class="{ 'is-invalid': errors.photo }"
                              type="file"
                            />
                          </div>
                          <div
                            v-if="errors.photo"
                            class="alert alert-danger py-2"
                            style="margin-top: -8px; margin-bottom: 10px"
                          >
                            {{ errors.photo }}
                          </div>
                        </div>
                        <div class="col-md-3 col-lg-2 mb-3">
                          <button
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
                            <label class="fw-bold">Nama Lengkap</label>
                            <input
                              id="nama_lengkap"
                              class="form-control"
                              v-model="form.nama_lengkap"
                              :class="{ 'is-invalid': errors.nama_lengkap }"
                              type="text"
                              placeholder="Nama Lengkap"
                              @keypress.enter.prevent="focused('alamat_email')"
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
                            <label class="fw-bold">Alamat Email</label>
                            <input
                              id="alamat_email"
                              class="form-control"
                              v-model="form.alamat_email"
                              :class="{ 'is-invalid': errors.alamat_email }"
                              type="email"
                              placeholder="Alamat Email"
                              @keypress.enter.prevent="focused('kata_sandi')"
                            />
                          </div>
                          <div
                            v-if="errors.alamat_email"
                            class="alert alert-danger py-2"
                            style="margin-top: -8px"
                          >
                            {{ errors.alamat_email }}
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <div class="mb-3">
                            <label class="fw-bold">Kata Sandi</label>
                            <input
                              id="kata_sandi"
                              class="form-control"
                              v-model="form.kata_sandi"
                              :class="{ 'is-invalid': errors.kata_sandi }"
                              type="password"
                              placeholder="Kata Sandi"
                              @keypress.enter.prevent="focused('konfirmasi')"
                            />
                          </div>
                          <div
                            v-if="errors.kata_sandi"
                            class="alert alert-danger py-2"
                            style="margin-top: -8px"
                          >
                            {{ errors.kata_sandi }}
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="mb-3">
                            <label class="fw-bold">Konfirmasi Kata Sandi</label>
                            <input
                              id="konfirmasi"
                              class="form-control"
                              v-model="form.konfirmasi"
                              type="password"
                              placeholder="Konfirmasi Kata Sandi"
                            />
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12">
                          <div class="mb-3">
                            <label class="fw-bold">Peran Pengguna</label>
                            <br />
                            <div
                              class="form-check form-check-inline d-inline-flex"
                              v-for="(peran, index) in auth.user.roles"
                              :key="index"
                            >
                              <input
                                class="form-check-input"
                                type="checkbox"
                                :value="peran.name"
                                checked
                                disabled
                              />
                              <label class="form-check-label">
                                {{ peran.name }}
                              </label>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-12 d-flex justify-content-end">
                          <button
                            class="btn btn-primary shadow-sm rounded-sm"
                            type="submit"
                          >
                            UBAH
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
  props: { errors: Object, auth: Object },

  //composition API
  setup(props) {
    onMounted(() => {
      focused("nama_lengkap");
    });

    const focused = (element) => {
      document.getElementById(element).focus();
    };

    const trim = () => {
      form.nama_lengkap = form.nama_lengkap.trim();
      form.alamat_email = form.alamat_email.trim();
      form.kata_sandi = form.kata_sandi.trim();
      form.konfirmasi = form.konfirmasi.trim();
    };

    const form = reactive({
      photo: "",
      nama_lengkap: props.auth.user.name,
      alamat_email: props.auth.user.email,
      kata_sandi: "",
      konfirmasi: "",
    });

    const upload = () => {
      router.post(
        `/apps/profil/${props.auth.user.id}/upload`,
        {
          _method: "PUT",
          photo: form.photo,
        },
        {
          onSuccess: () => {
            Swal.fire(
              {
                title: "Sukses!",
                text: "Photo Profil berhasil diupload.",
                icon: "success",
                showConfirmButton: false,
                timer: 2000,
              },
              focused("nama_lengkap")
            );

            form.photo = "";
            document.getElementById("photo").value = "";
          },
          onError: () => {
            focused("photo");
          },
        }
      );
    };

    const submit = () => {
      trim();
      router.post(
        `/apps/profil/${props.auth.user.id}`,
        {
          _method: "PUT",
          nama_lengkap: form.nama_lengkap,
          alamat_email: form.alamat_email,
          kata_sandi: form.kata_sandi,
          kata_sandi_confirmation: form.konfirmasi,
        },
        {
          onSuccess: () => {
            Swal.fire(
              {
                title: "Sukses!",
                text: "Profil Pengguna berhasil diubah.",
                icon: "success",
                showConfirmButton: false,
                timer: 2000,
              },
              focused("nama_lengkap")
            );

            form.kata_sandi = "";
            form.konfirmasi = "";
          },
          onError: () => {
            if (props.errors.nama_lengkap) {
              focused("nama_lengkap");
            } else if (props.errors.kata_sandi) {
              focused("kata_sandi");
            } else if (props.errors.alamat_email) {
              focused("alamat_email");
            } else {
              focused("konfirmasi");
            }
          },
        }
      );
    };

    return { form, onMounted, focused, trim, upload, submit };
  },
};
</script>

<style>
</style>
