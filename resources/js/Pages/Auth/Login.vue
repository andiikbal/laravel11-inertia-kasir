<template>
  <Head>
    <title>Login Account - Aplikasi Kasir</title>
  </Head>
  <div class="col-md-4">
    <div class="fade-in">
      <div class="text-center mb-4">
        <a href="" class="text-dark text-decoration-none">
          <img :src="toko.logo" width="70" />
          <!-- <img :src="`${$page.props.toko.gambar}`" width="70" /> -->
          <!-- <img src="/images/cash-machine.png" width="70" /> -->
          <h3 class="mt-2 font-weight-bold">{{ toko.nama }}</h3>
          <!-- <h3 class="mt-2 font-weight-bold">APLIKASI KASIR</h3> -->
        </a>
      </div>
      <div class="card-group">
        <div class="card border-top-purple border-0 shadow-sm rounded-3">
          <div class="card-body">
            <div class="text-start">
              <h5>AKUN MASUK</h5>
              <p class="text-muted">Masuk ke akun Anda</p>
            </div>
            <hr />
            <div v-if="session.status" class="alert alert-success mt-2">
              {{ session.status }}
            </div>
            <form @submit.prevent="submit">
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text">
                    <i class="fa fa-envelope"></i>
                  </span>
                </div>
                <input
                  id="alamat_email"
                  class="form-control"
                  v-model="form.alamat_email"
                  :class="{ 'is-invalid': errors.email }"
                  type="email"
                  placeholder="Alamat Email"
                  @keypress.enter.prevent="focused(2)"
                />
              </div>
              <div
                v-if="errors.email"
                class="alert alert-danger py-2"
                style="margin-top: -8px"
              >
                {{ errors.email }}
              </div>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text">
                    <i class="fa fa-lock"></i>
                  </span>
                </div>
                <input
                  id="kata_sandi"
                  class="form-control"
                  v-model="form.kata_sandi"
                  :class="{ 'is-invalid': errors.password }"
                  type="password"
                  placeholder="Kata Sandi"
                />
              </div>
              <div
                v-if="errors.password"
                class="alert alert-danger py-2"
                style="margin-top: -8px"
              >
                {{ errors.password }}
              </div>
              <div class="row">
                <div class="col-12 mb-3 text-end">
                  <Link href="/forgot-password">Lupa Password?</Link>
                </div>
                <div class="col-12">
                  <button
                    class="btn btn-primary shadow-sm rounded-sm px-4 w-100"
                    type="submit"
                  >
                    MASUK
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
//import layout
import LayoutAuth from "../../Layouts/Auth.vue";

//import reactive
import { onMounted, reactive } from "vue";

//import Inertia
import { Head, Link, router } from "@inertiajs/vue3";

export default {
  layout: LayoutAuth,

  components: {
    Head,
    Link,
  },

  props: {
    errors: Object,
    session: Object,
    toko: Object,
  },

  //define composition API
  setup(props) {
    onMounted(() => {
      focused(1);
    });

    //define form state
    const form = reactive({
      alamat_email: "",
      kata_sandi: "",
    });

    //submit method
    const submit = () => {
      router.post(
        "/login",
        {
          email: form.alamat_email,
          password: form.kata_sandi,
        },
        {
          onError: () => {
            form.alamat_email = form.alamat_email.trim();
            form.kata_sandi = form.kata_sandi.trim();
            focused(1);
          },
        }
      );
    };

    const focused = (i) => {
      if (i == 1) {
        document.getElementById("alamat_email").focus();
      } else if (i == 2) {
        document.getElementById("kata_sandi").focus();
      }
    };

    //return form state and submit method
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
