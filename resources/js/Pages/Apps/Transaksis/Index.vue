<template>
  <Head>
    <title>Transaksi - Aplikasi Kasir</title>
  </Head>
  <main class="c-main">
    <div class="container-fluid">
      <div class="fade-in">
        <div class="row">
          <div class="col-md-4">
            <div class="card border-0 rounded-3 shadow">
              <div class="card-body">
                <div class="input-group mb-3">
                  <span class="input-group-text">
                    <i class="fa fa-barcode"></i>
                  </span>
                  <input
                    id="barcode"
                    type="text"
                    class="form-control"
                    v-model="barcode"
                    @keypress.enter="cariProduk"
                    placeholder="Scan atau Input Barcode"
                  />
                </div>
                <div class="mb-3">
                  <label class="form-label fw-bold">Nama Produk</label>
                  <input
                    type="text"
                    class="form-control"
                    :value="produk.nama"
                    placeholder="Nama Produk"
                    readonly
                  />
                </div>
                <div class="mb-3">
                  <label class="form-label fw-bold">Kuantitas</label>
                  <input
                    id="kuantitas"
                    type="number"
                    class="form-control text-center"
                    v-model="kuantitas"
                    placeholder="Kuantitas"
                    min="1"
                    @keypress.enter.prevent="tambahItem"
                  />
                </div>
                <div class="text-end">
                  <button
                    id="clear"
                    @click.prevent="kosongkanPencarian"
                    class="btn btn-warning btn-md border-0 shadow text-uppercase mt-3 me-2"
                  >
                    CLEAR
                  </button>
                  <button
                    id="tambah_item"
                    class="btn btn-success btn-md border-0 shadow text-uppercase mt-3"
                    @click.prevent="tambahItem"
                  >
                    TAMBAH ITEM
                  </button>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-8">
            <div v-if="session.error" class="alert alert-danger">
              {{ session.error }}
            </div>

            <div v-if="session.success" class="alert alert-success">
              {{ session.success }}
            </div>

            <div class="card border-0 rounded-3 shadow border-top-success">
              <div class="card-body">
                <div class="row">
                  <div class="col-md-4 col-4">
                    <h4 class="fw-bold">GRAND TOTAL</h4>
                  </div>
                  <div class="col-md-8 col-8 text-end">
                    <h4 class="fw-bold">Rp. {{ formatPrice(grandTotal) }}</h4>
                    <div v-if="uangKembali > 0">
                      <hr />
                      <h5 class="text-success">
                        Kembali :
                        <strong>Rp. {{ formatPrice(uangKembali) }}</strong>
                      </h5>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="card border-0 rounded-3 shadow">
              <div class="card-body">
                <div class="row mb-3">
                  <div class="col-md-6">
                    <label class="fw-bold">Kasir</label>
                    <input
                      class="form-control"
                      type="text"
                      :value="auth.user.name"
                      readonly
                    />
                  </div>
                  <div class="col-md-6 float-end">
                    <label class="fw-bold">Pelanggan</label>
                    <VueMultiselect
                      id="pelanggan"
                      v-model="pelangganID"
                      label="nama"
                      track-by="nama"
                      :options="pelanggans"
                    ></VueMultiselect>
                  </div>
                </div>
                <hr />
                <table class="table table-bordered">
                  <thead>
                    <tr style="background-color: #e6e6e7">
                      <th scope="col">#</th>
                      <th scope="col">Nama Produk</th>
                      <th scope="col" class="text-end">Harga</th>
                      <th scope="col" class="text-center">Qty</th>
                      <th scope="col" class="text-end">Sub Total</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="keranjang in keranjangs" :key="keranjang.id">
                      <td class="text-center">
                        <button
                          @click.prevent="hapusItem(keranjang.id)"
                          class="btn btn-danger btn-sm rounded-pill"
                        >
                          <i class="fa fa-trash"></i>
                        </button>
                      </td>
                      <td>{{ keranjang.produk.nama }}</td>
                      <td class="text-end">
                        Rp. {{ formatPrice(keranjang.produk.harga_jual) }}
                      </td>
                      <td class="text-center">{{ keranjang.kuantitas }}</td>
                      <td class="text-end">
                        Rp. {{ formatPrice(keranjang.sub_total) }}
                      </td>
                    </tr>
                    <tr>
                      <td
                        colspan="4"
                        class="text-end fw-bold"
                        style="background-color: #e6e6e7"
                      >
                        TOTAL
                      </td>
                      <td
                        class="text-end fw-bold"
                        style="background-color: #e6e6e7"
                      >
                        Rp. {{ formatPrice(total) }}
                      </td>
                    </tr>
                  </tbody>
                </table>
                <hr />
                <div
                  class="d-flex align-items-end flex-column bd-highlight mb-3"
                >
                  <div class="mt-auto bd-highlight">
                    <label>Diskon (Rp.)</label>
                    <input
                      id="diskon"
                      type="number"
                      v-model="diskon"
                      class="form-control"
                      @keyup="setDiskon"
                      placeholder="Diskon (Rp.)"
                      @keypress.enter.prevent="focused('uang_tunai')"
                    />
                  </div>
                  <div class="bd-highlight mt-4">
                    <label>Bayar (Rp.)</label>
                    <input
                      id="uang_tunai"
                      type="number"
                      v-model="uangTunai"
                      @keyup="setUangKembali"
                      class="form-control"
                      placeholder="Bayar (Rp.)"
                      @keypress.enter.prevent="focused('bayar')"
                    />
                  </div>
                </div>
                <div class="text-end mt-4">
                  <button
                    class="btn btn-warning btn-md border-0 shadow text-uppercase me-2"
                    :disabled="grandTotal == 0"
                    @click="kosongkanKeranjang"
                  >
                    Batal
                  </button>
                  <button
                    id="bayar"
                    class="btn btn-purple btn-md border-0 shadow text-uppercase"
                    :disabled="uangTunai < grandTotal || grandTotal == 0"
                    @click="simpanTransaksi"
                  >
                    Bayar & Cetak
                  </button>
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
//import layout
import LayoutApp from "../../../Layouts/App.vue";

//import Heade from Inertia
import { Head, router } from "@inertiajs/vue3";

//import VueMultiselect
import VueMultiselect from "vue-multiselect";
import "vue-multiselect/dist/vue-multiselect.css";

//import ref form vue
import { isProxy, onMounted, ref } from "vue";

//import axios
import axios from "axios";

//import sweet alert2
import Swal from "sweetalert2";

export default {
  //layout
  layout: LayoutApp,

  //register components
  components: {
    Head,
    VueMultiselect,
  },

  props: {
    auth: Object,
    pelanggans: Array,
    session: Object,
  },

  //composition API
  setup(props) {
    onMounted(() => {
      loadData();
    });

    const keranjangs = ref([]);
    const total = ref(0);
    const grandTotal = ref(0);

    //define state "customer_id"
    const pelangganID = ref("");

    //define state "cash", "change" dan "discount"
    let uangTunai = ref(0);
    let uangKembali = ref(0);
    let diskon = ref(0);

    const loadData = () => {
      axios.post("/apps/transaksis/loadData", {}).then((response) => {
        kosongkanPencarian();
        keranjangs.value = response.data.data.keranjangs;
        total.value = response.data.data.total;
        grandTotal.value = response.data.data.total;
        diskon.value = 0;
        uangTunai.value = 0;
        uangKembali.value = 0;
      });
    };

    const focused = (element) => {
      if (element != "bayar") {
        document.getElementById(element).focus();
      } else {
        if (document.getElementById(element).attributes.disabled == undefined) {
          document.getElementById(element).click();
        }
      }
    };

    //define state
    const barcode = ref("");
    const produk = ref({});
    const kuantitas = ref(1);

    const kosongkanPencarian = () => {
      if (props.session.success) props.session.success = null;
      if (props.session.error) props.session.error = null;
      barcode.value = "";
      produk.value = {};
      kuantitas.value = 1;
      focused("barcode");
    };

    // method cariProduk
    const cariProduk = () => {
      if (barcode.value.trim()) {
        axios
          .post("/apps/transaksis/cariProduk", {
            barcode: barcode.value.trim(),
          })
          .then((response) => {
            kosongkanPencarian();
            if (response.data.success) {
              produk.value = response.data.data;
              focused("kuantitas");
            } else {
              props.session.error = "Produk tidak ditemukan !!!";
            }
          });
      } else {
        kosongkanPencarian();
        props.session.error = "Silahkan Masukkan Kode Produk";
      }
    };

    //method tambahItem
    const tambahItem = () => {
      if (produk.value.id == null) {
        kosongkanPencarian();
        props.session.error = "Produk tidak ditemukan !!!";
        return;
      } else if (typeof kuantitas.value == "string") {
        kuantitas.value = 1;
      }

      axios
        .post("/apps/transaksis/tambahItem", {
          produk_id: produk.value.id,
          kuantitas: kuantitas.value,
          harga_jual: produk.value.harga_jual,
        })
        .then((response) => {
          if (response.data.success) {
            loadData();
          } else {
            kosongkanPencarian();
            props.session.error = response.data.message;
          }
        });
    };

    //method "hapusItem"
    const hapusItem = (keranjang_id) => {
      Swal.fire({
        title: "Apakah anda yakin?",
        text: "Anda tidak dapat mengembalikan data ini!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#198754",
        confirmButtonText: "Ya, hapus!",
      }).then((result) => {
        if (result.isConfirmed) {
          axios
            .post("/apps/transaksis/hapusItem", {
              keranjang_id: keranjang_id,
            })
            .then((response) => {
              loadData();

              Swal.fire({
                title: "Sukses!",
                text: response.data.message,
                icon: "success",
                showConfirmButton: false,
                timer: 1000,
              });
            });
        }
      });
    };

    //method "setDiskon"
    const setDiskon = () => {
      grandTotal.value = total.value - diskon.value;
      if (uangTunai.value > 0) uangTunai.value = 0;
      if (uangKembali.value > 0) uangKembali.value = 0;

      if (grandTotal.value <= 0) {
        Swal.fire({
          title: "Peringatan!",
          text: "Diskon lebih besar atau sama dengan grand total !!!",
          icon: "warning",
          confirmButtonColor: "#f96",
          showConfirmButton: true,
        });
        diskon.value = 0;
      }
    };

    //method "setUangKembali"
    const setUangKembali = () => {
      uangKembali.value = uangTunai.value - grandTotal.value;
    };

    //method "kosongkanKeranjang"
    const kosongkanKeranjang = () => {
      Swal.fire({
        title: "Apakah anda yakin?",
        text: "Anda tidak dapat mengembalikan data transaksi ini!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#198754",
        confirmButtonText: "Ya, hapus!",
      }).then((result) => {
        if (result.isConfirmed) {
          axios
            .post("/apps/transaksis/kosongkanKeranjang", {})
            .then((response) => {
              loadData();

              Swal.fire({
                title: "Sukses!",
                text: response.data.message,
                icon: "success",
                showConfirmButton: false,
                timer: 1500,
              });
            });
        }
      });
    };

    //method "simpanTransaksi"
    const simpanTransaksi = () => {
      axios
        .post("/apps/transaksis/simpan", {
          pelanggan_id: pelangganID.value ? pelangganID.value.id : "",
          diskon: diskon.value,
          total: total.value,
          grand_total: grandTotal.value,
          uang_tunai: uangTunai.value,
          uang_kembali: uangKembali.value,
        })
        .then((response) => {
          Swal.fire({
            title: "Berhasil!",
            text: response.data.message,
            icon: "success",
            showConfirmButton: false,
            timer: 2000,
          }).then(() => {
            setTimeout(() => {
              //print
              window.open(
                `/apps/transaksis/print?faktur=${response.data.data.faktur}`,
                "_blank"
              );

              //reload page
              location.reload();
            }, 50);
          });
        });
    };

    return {
      loadData,
      barcode,
      produk,
      cariProduk,
      kosongkanPencarian,
      kuantitas,
      keranjangs,
      total,
      grandTotal,
      pelangganID,
      tambahItem,
      hapusItem,
      uangTunai,
      uangKembali,
      diskon,
      setDiskon,
      setUangKembali,
      focused,
      simpanTransaksi,
      kosongkanKeranjang,
    };
  },
};
</script>

<style>
</style>
