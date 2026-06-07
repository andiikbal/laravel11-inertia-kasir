<template>
  <Head>
    <title>Edit Pengguna - Aplikasi Kasir</title>
  </Head>
  <main class="c-main">
    <div class="container-fluid">
      <div class="fade-in">
        <div class="row">
          <div class="col-md-12">
            <div class="card border-0 rounded-3 shadow border-top-purple">
              <div class="card-header">
                <span class="font-weight-bold">
                  <i class="fa fa-user"></i> EDIT PENGGUNA
                </span>
              </div>
              <div class="card-body">
                <form @submit.prevent="submit">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="mb-3">
                        <label class="fw-bold" for="nama_lengkap">
                          Nama Lengkap
                        </label>
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
                        <label class="fw-bold" for="alamat_email">
                          Alamat Email
                        </label>
                        <input
                          id="alamat_email"
                          class="form-control"
                          v-model="form.alamat_email"
                          :class="{ 'is-invalid': errors.alamat_email }"
                          type="text"
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
                        <label class="fw-bold" for="kata_sandi">
                          Kata Sandi
                        </label>
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
                        <label class="fw-bold" for="konfirmasi">
                          Konfirmasi Kata Sandi
                        </label>
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
                        <div class="fw-bold mb-2">Peran</div>
                        <!-- <br /> -->
                        <div
                          v-if="errors.peran"
                          class="alert alert-danger py-2"
                          style="margin-bottom: 8px"
                        >
                          {{ errors.peran }}
                        </div>
                        <div
                          class="form-check form-check-inline d-inline-flex"
                          v-for="(peran, index) in perans"
                          :key="index"
                        >
                          <input
                            class="form-check-input"
                            type="checkbox"
                            v-model="form.perans"
                            :value="peran.name"
                            :id="`check-${peran.id}`"
                          />
                          <label
                            class="form-check-label"
                            :for="`check-${peran.id}`"
                          >
                            {{ peran.name }}
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-12">
                      <button
                        class="btn btn-primary shadow-sm rounded-sm"
                        type="submit"
                      >
                        UBAH
                      </button>
                      <Link
                        :href="`/apps/penggunas/${pengguna.id}/edit`"
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
  //layout
  layout: LayoutApp,

  //register component
  components: {
    Head,
    Link,
  },

  //props
  props: {
    errors: Object,
    pengguna: Object,
    perans: Array,
  },

  //composition API
  setup(props) {
    onMounted(() => {
      trimInput();
      focused("nama_lengkap");
    });

    const focused = (element) => {
      document.getElementById(element).focus();
    };

    // function to trim whitespace from input fields
    const trimInput = () => {
      form.nama_lengkap = form.nama_lengkap.trim();
      form.alamat_email = form.alamat_email.trim();
      form.kata_sandi = form.kata_sandi.trim();
      form.konfirmasi = form.konfirmasi.trim();
    };

    //define form with reactive
    const form = reactive({
      nama_lengkap: props.pengguna.name,
      alamat_email: props.pengguna.email,
      kata_sandi: "",
      konfirmasi: "",
      perans: props.pengguna.roles.map((obj) => obj.name),
    });

    const submit = () => {
      trimInput();
      router.put(
        `/apps/penggunas/${props.pengguna.id}`,
        {
          nama_lengkap: form.nama_lengkap,
          alamat_email: form.alamat_email,
          kata_sandi: form.kata_sandi,
          kata_sandi_confirmation: form.konfirmasi,
          peran: form.perans,
        },
        {
          onSuccess: () => {
            Swal.fire({
              title: "Sukses!",
              text: "Pengguna berhasil diubah.",
              icon: "success",
              showConfirmButton: false,
              timer: 2000,
            }).then(() => {
              router.get("/apps/penggunas");
            });
          },
          onError: () => {
            const conditions = props.errors;
            if (conditions.nama_lengkap) focused("nama_lengkap");
            else if (conditions.alamat_email) focused("alamat_email");
            else if (conditions.kata_sandi) focused("kata_sandi");
            else if (conditions.konfirmasi) focused("konfirmasi");
          },
          onFinish: () => {
            if (Object.keys(props.errors).length === 0) {
            } else {
            }
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
