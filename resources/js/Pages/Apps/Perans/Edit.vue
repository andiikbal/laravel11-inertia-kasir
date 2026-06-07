<template>
  <Head>
    <title>Edit Peran - Aplikasi Kasir</title>
  </Head>
  <main class="c-main">
    <div class="container-fluid">
      <div class="fade-in">
        <div class="row">
          <div class="col-md-12">
            <div class="card border-0 rounded-3 shadow border-top-purple">
              <div class="card-header">
                <span class="font-weight-bold">
                  <i class="fa fa-shield-alt"></i> EDIT PERAN
                </span>
              </div>
              <div class="card-body">
                <form @submit.prevent="submit">
                  <div class="mb-3">
                    <label class="fw-bold">Nama Peran</label>
                    <input
                      id="nama_peran"
                      class="form-control"
                      v-model="form.nama_peran"
                      :class="{ 'is-invalid': errors.nama_peran }"
                      type="text"
                      placeholder="Nama Peran"
                    />
                    <div
                      v-if="errors.nama_peran"
                      class="alert alert-danger mt-2 py-2"
                    >
                      {{ errors.nama_peran }}
                    </div>
                  </div>

                  <hr />
                  <div class="mb-3">
                    <label class="fw-bold">Izin</label>
                    <br />
                    <div
                      v-if="errors.izins"
                      class="alert alert-danger py-2 mb-3"
                    >
                      {{ errors.izins }}
                    </div>
                    <div
                      class="form-check form-check-inline d-inline-flex"
                      v-for="(izin, index) in izins"
                      :key="index"
                    >
                      <input
                        class="form-check-input"
                        type="checkbox"
                        v-model="form.izins"
                        :value="izin.name"
                        :id="`check-${izin.id}`"
                      />
                      <label class="form-check-label" :for="`check-${izin.id}`">
                        {{ izin.name }}
                      </label>
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
                        :href="`/apps/perans/${peran.id}/edit`"
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

  //props
  props: {
    errors: Object,
    peran: Object,
    izins: Array,
  },

  //composition API
  setup(props) {
    onMounted(() => {
      focused("nama_peran");
    });

    const focused = (element) => {
      document.getElementById(element).focus();
    };

    const trim = () => {
      form.nama_peran = form.nama_peran.trim();
    };

    //define form with reactive
    const form = reactive({
      nama_peran: props.peran.name,
      izins: props.peran.permissions.map((obj) => obj.name),
    });

    //method "submit"
    const submit = () => {
      trim();
      //send data to server
      router.post(
        `/apps/perans/${props.peran.id}`,
        {
          _method: "PUT",
          nama_peran: form.nama_peran,
          izins: form.izins,
        },
        {
          onSuccess: () => {
            Swal.fire({
              title: "Berhasil!",
              text: `Peran ${form.nama_peran} berhasil diubah.`,
              icon: "success",
              showConfirmButton: false,
              timer: 2000,
            });
          },
          onError: () => {
            focused("nama_peran");
          },
        }
      );
    };

    return {
      focused,
      trim,
      form,
      submit,
    };
  },
};
</script>

<style>
</style>
