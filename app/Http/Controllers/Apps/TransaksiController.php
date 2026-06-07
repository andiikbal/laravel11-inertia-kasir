<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Keranjang;
use App\Models\Produk;
use App\Models\Pelanggan;
use App\Models\Toko;
use App\Models\Transaksi;
use Illuminate\Support\Str;

class TransaksiController extends Controller
{
  private $toko, $pelanggans, $keranjangs, $keranjangs_all;

  public function __construct()
  {
    $this->toko = Toko::find(1);
    $this->pelanggans = Pelanggan::latest()->get();
    $this->keranjangs = Keranjang::with('produk')->where('kasir_id', auth()->user()->id)->latest()->get();
    $this->keranjangs_all = Keranjang::all();
  }

  public function loadData()
  {
    return response()->json([
      'success' => true,
      'data'    => [
        'keranjangs'  => $this->keranjangs,
        'total'       => $this->keranjangs->sum('sub_total'),
      ],
    ]);
  }

  public function index()
  {
    return Inertia::render('Apps/Transaksis/Index', [
      'toko'        => $this->toko,
      'pelanggans'  => $this->pelanggans,
    ]);
  }

  public function cariProduk(Request $request)
  {
    $produk = Produk::where('barcode', $request->barcode)->first();

    if ($produk) {
      return response()->json([
        'success' => true,
        'data'    => $produk
      ]);
    }

    return response()->json([
      'success' => false,
      'data'    => null
    ]);
  }

  public function tambahItem(Request $request)
  {
    $success = false;
    $produk = Produk::find($request->produk_id);
    $produk_user_login = Keranjang::with('produk')
      ->where('produk_id', $request->produk_id)
      ->where('kasir_id', auth()->user()->id)
      ->first();
    $produk_user_all = Keranjang::with('produk')
      ->where('produk_id', $request->produk_id)
      ->get();

    if ($produk_user_all->count() > 0) {
      if ($produk->stok >= ($produk_user_all->sum('kuantitas') + $request->kuantitas)) {
        $success = true;
      } else {
        $success = false;
      }
    } else {
      if ($produk->stok >= $request->kuantitas) {
        $success = true;
      } else {
        $success = false;
      }
    }

    if ($success) {
      if ($produk_user_login) {
        $produk_user_login->increment('kuantitas', $request->kuantitas);
        $produk_user_login->sub_total = $produk_user_login->produk->harga_jual * $produk_user_login->kuantitas;
        $produk_user_login->save();
      } else {
        Keranjang::create([
          'kasir_id'  => auth()->user()->id,
          'produk_id' => $request->produk_id,
          'kuantitas' => $request->kuantitas,
          'sub_total' => $request->harga_jual * $request->kuantitas,
        ]);
      }
    }

    return response()->json([
      'success' => $success,
      'message' => $success == true ? 'Produk berhasil ditambahkan.' : 'Produk Tidak Cukup atau Kehabisan Stok!.',
    ]);
  }

  public function hapusItem(Request $request)
  {
    //cari item produk dikeranjang
    $keranjang = Keranjang::with('produk')
      ->whereId($request->keranjang_id)
      ->where('kasir_id', auth()->user()->id)
      ->first();

    //delete item produk dikeranjang
    $keranjang->delete();
    $this->keranjangs = Keranjang::with('produk')->where('kasir_id', auth()->user()->id)->latest()->get();
    $this->keranjangs_all = Keranjang::all();

    if ($this->keranjangs->count() == 0 &&  $this->keranjangs_all->count() == 0) Keranjang::truncate();

    return response()->json(['message' => 'Produk berhasil dihapus.']);
  }

  public function kosongkanKeranjang()
  {
    if ($this->keranjangs_all->count() == $this->keranjangs->count()) Keranjang::truncate();
    else Keranjang::where('kasir_id', auth()->user()->id)->delete();

    return response()->json(['message' => 'Transaksi berhasil dibatalkan.']);
  }

  public function simpan(Request $request)
  {
    /* algorithm generate no invoice */
    $length = 10;
    $random = '';
    for ($i = 0; $i < $length; $i++) {
      $random .= rand(0, 1) ? rand(0, 9) : chr(rand(ord('a'), ord('z')));
    }

    //generate no invoice
    $faktur = 'TRX-' . Str::upper($random);

    //insert transaction
    $transaksi = Transaksi::create([
      'kasir_id'      => auth()->user()->id,
      'pelanggan_id'  => $request->pelanggan_id,
      'faktur'        => $faktur,
      'uang_tunai'    => $request->uang_tunai,
      'uang_kembali'  => $request->uang_kembali,
      'diskon'        => $request->diskon,
      'total'         => $request->total,
      'grand_total'   => $request->grand_total,
    ]);

    //insert transaction detail
    foreach ($this->keranjangs as $keranjang) {
      //insert transaction detail
      $transaksi->details()->create([
        'transaksi_id'  => $transaksi->id,
        'produk_id'     => $keranjang->produk_id,
        'kuantitas'     => $keranjang->kuantitas,
        'sub_total'     => $keranjang->sub_total,
      ]);

      //get price
      $total_harga_beli = $keranjang->produk->harga_beli * $keranjang->kuantitas;
      $total_harga_jual = $keranjang->produk->harga_jual * $keranjang->kuantitas;

      //get profits
      $keuntungan = $total_harga_jual - $total_harga_beli;

      //insert provits
      $transaksi->keuntungans()->create([
        'transaksi_id'  => $transaksi->id,
        'total'         => $keuntungan,
      ]);

      //update stock product
      $produk = Produk::find($keranjang->produk_id);
      $produk->stok = $produk->stok - $keranjang->kuantitas;
      $produk->save();
    }

    if ($this->keranjangs_all->count() == $this->keranjangs->count()) Keranjang::truncate();
    else Keranjang::where('kasir_id', auth()->user()->id)->delete();

    return response()->json([
      'message' => 'Transaksi berhasil disimpan.',
      'data'    => $transaksi
    ]);
  }

  public function print(Request $request)
  {
    $toko = $this->toko;
    $transaksi = Transaksi::with('details.produk', 'kasir', 'pelanggan')->where('faktur', $request->faktur)->firstOrFail();
    return view('print.nota', compact('toko', 'transaksi'));
  }
}
