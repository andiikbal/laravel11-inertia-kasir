<template>
  <Head>
    <title>Edit Produk - Aplikasi Kasir</title>
  </Head>
  <main class="c-main">
    <div class="container-fluid">
      <div class="fade-in">
        <div class="row">
          <div class="col-md-12">
            <div class="card border-0 rounded-3 shadow border-top-purple">
              <div class="card-header">
                <span class="font-weight-bold">
                  <i class="fa fa-shopping-bag"></i> EDIT PRODUK
                </span>
              </div>
              <div class="card-body">
                <form @submit.prevent="submit">
                  <div class="mb-3">
                    <input
                      id="gambar"
                      class="form-control"
                      @input="form.gambar = $event.target.files[0]"
                      :class="{ 'is-invalid': errors.gambar }"
                      type="file"
                    />
                  </div>
                  <div
                    v-if="errors.gambar"
                    class="alert alert-danger py-2"
                    style="margin-top: -8px"
                  >
                    {{ errors.gambar }}
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <div class="mb-3">
                        <label class="fw-bold">Barcode</label>
                        <input
                          id="barcode"
                          class="form-control"
                          v-model="form.barcode"
                          :class="{ 'is-invalid': errors.barcode }"
                          type="text"
                          placeholder="Barcode / Kode Produk"
                          @keypress.enter.prevent="focused('kategori')"
                        />
                      </div>
                      <div
                        v-if="errors.barcode"
                        class="alert alert-danger py-2"
                        style="margin-top: -8px"
                      >
                        {{ errors.barcode }}
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="mb-3">
                        <label class="fw-bold">Kategori</label>
                        <VueMultiselect
                          id="kategori"
                          v-model="kategoriID"
                          label="nama"
                          track-by="nama"
                          :options="kategoris"
                          :class="{ 'border-danger': errors.kategori }"
                        ></VueMultiselect>
                      </div>
                      <div
                        v-if="errors.kategori"
                        class="alert alert-danger py-2"
                        style="margin-top: -16px"
                      >
                        {{ errors.kategori }}
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <div class="mb-3">
                        <label class="fw-bold">Nama Produk</label>
                        <input
                          id="nama_produk"
                          class="form-control"
                          v-model="form.nama_produk"
                          :class="{ 'is-invalid': errors.nama_produk }"
                          type="text"
                          placeholder="Nama Produk"
                          @keypress.enter.prevent="focused('stok')"
                        />
                      </div>
                      <div
                        v-if="errors.nama_produk"
                        class="alert alert-danger py-2"
                        style="margin-top: -8px"
                      >
                        {{ errors.nama_produk }}
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="mb-3">
                        <label class="fw-bold">Stok</label>
                        <input
                          id="stok"
                          class="form-control"
                          v-model="form.stok"
                          :class="{ 'is-invalid': errors.stok }"
                          type="number"
                          placeholder="Stok"
                          @keypress.enter.prevent="focused('keterangan')"
                        />
                      </div>
                      <div
                        v-if="errors.stok"
                        class="alert alert-danger py-2"
                        style="margin-top: -8px"
                      >
                        {{ errors.stok }}
                      </div>
                    </div>
                  </div>

                  <div class="mb-3">
                    <label class="fw-bold">Keterangan</label>
                    <textarea
                      id="keterangan"
                      class="form-control"
                      v-model="form.keterangan"
                      :class="{ 'is-invalid': errors.keterangan }"
                      type="text"
                      rows="4"
                      placeholder="Keterangan"
                      @keypress.enter.prevent="focused('harga_beli')"
                    ></textarea>
                  </div>
                  <div
                    v-if="errors.keterangan"
                    class="alert alert-danger py-2"
                    style="margin-top: -8px"
                  >
                    {{ errors.keterangan }}
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <div class="mb-3">
                        <label class="fw-bold">Harga Beli</label>
                        <input
                          id="harga_beli"
                          class="form-control"
                          v-model="form.harga_beli"
                          :class="{ 'is-invalid': errors.harga_beli }"
                          type="number"
                          placeholder="Harga Beli"
                          @keypress.enter.prevent="focused('harga_jual')"
                        />
                      </div>
                      <div
                        v-if="errors.harga_beli"
                        class="alert alert-danger py-2"
                        style="margin-top: -8px"
                      >
                        {{ errors.harga_beli }}
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="mb-3">
                        <label class="fw-bold">Harga Jual</label>
                        <input
                          id="harga_jual"
                          class="form-control"
                          v-model="form.harga_jual"
                          :class="{ 'is-invalid': errors.harga_jual }"
                          type="number"
                          placeholder="Harga Jual"
                        />
                      </div>
                      <div
                        v-if="errors.harga_jual"
                        class="alert alert-danger py-2"
                        style="margin-top: -8px"
                      >
                        {{ errors.harga_jual }}
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
                        :href="`/apps/produks/${produk.id}/edit`"
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

//import VueMultiselect
import VueMultiselect from "vue-multiselect";
import "vue-multiselect/dist/vue-multiselect.css";

//import reactive from vue
import { onMounted, reactive, ref } from "vue";

//import sweet alert2
import Swal from "sweetalert2";

export default {
  //layout
  layout: LayoutApp,

  //register components
  components: {
    Head,
    Link,
    VueMultiselect,
  },

  //props
  props: {
    errors: Object,
    kategoris: Array,
    produk: Object,
  },

  //composition API
  setup(props) {
    //focus on input element when component is mounted
    onMounted(() => {
      focused("barcode");
    });

    //function to focus on input element
    const focused = (element) => {
      document.getElementById(element).focus();
    };

    //function to trim input values
    const trim = () => {
      form.barcode = form.barcode.trim();
      form.nama_produk = form.nama_produk.trim();
      form.keterangan = form.keterangan.trim();
    };

    const kategoriID = ref(
      props.produk.kategori ? props.produk.kategori : null
    );

    //define form with reactive
    const form = reactive({
      gambar: "",
      barcode: props.produk.barcode,
      nama_produk: props.produk.nama,
      keterangan: props.produk.keterangan,
      harga_beli: props.produk.harga_beli,
      harga_jual: props.produk.harga_jual,
      stok: props.produk.stok,
    });

    //method "submit"
    const submit = () => {
      trim();
      router.post(
        `/apps/produks/${props.produk.id}`,
        {
          _method: "PUT",
          gambar: form.gambar,
          barcode: form.barcode,
          kategori: kategoriID.value.id,
          nama_produk: form.nama_produk,
          keterangan: form.keterangan,
          harga_beli: form.harga_beli,
          harga_jual: form.harga_jual,
          stok: form.stok,
        },
        {
          onSuccess: () => {
            Swal.fire(
              {
                title: "Berhasil!",
                text: "Produk berhasil diubahhh.",
                icon: "success",
                showConfirmButton: false,
                timer: 2000,
              },
              router.get("/apps/produks")
            );
          },
          onError: () => {
            if (errors.gambar) {
              focused("gambar");
            } else if (errors.barcode) {
              focused("barcode");
            } else if (errors.kategori) {
              focused("kategori");
            } else if (errors.nama_produk) {
              focused("nama_produk");
            } else if (errors.stok) {
              focused("stok");
            } else if (errors.keterangan) {
              focused("keterangan");
            } else if (errors.harga_beli) {
              focused("harga_beli");
            } else if (errors.harga_jual) {
              focused("harga_jual");
            }
          },
        }
      );
    };

    return {
      focused,
      trim,
      kategoriID,
      form,
      submit,
    };
  },
};
</script>

<style>
</style>
