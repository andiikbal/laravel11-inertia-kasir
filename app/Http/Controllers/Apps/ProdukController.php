<?php

namespace App\Http\Controllers\Apps;

use App\Models\Toko;
use Inertia\Inertia;
use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Http\Request;
use App\Models\TransaksiDetail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
  private $toko, $success, $message;

  public function __construct()
  {
    $this->toko = $this->toko = Toko::findOrFail(1);
  }

  private function produks()
  {
    return Produk::when(request()->q, function ($produks) {
      $produks = $produks->where('nama', 'like', '%' . request()->q . '%');
    })->latest()->paginate(5);
  }

  public function index()
  {
    return Inertia::render('Apps/Produks/Index', [
      'produks' => $this->produks(),
      'toko'    => $this->toko
    ]);
  }

  public function create()
  {
    return Inertia::render('Apps/Produks/Create', [
      'kategoris' => Kategori::all(),
      'toko'      => $this->toko
    ]);
  }

  public function store(Request $request)
  {
    $gambar_validation = '';
    if ($request->file('gambar')) {
      $gambar_validation = 'image|mimes:jpeg,jpg,png|max:1000';
    }

    $request->validate([
      'gambar'        => $gambar_validation,
      'barcode'       => 'required|unique:produks',
      'nama_produk'   => 'required|unique:produks,nama',
      'keterangan'    => 'required',
      'kategori'      => 'required',
      'harga_beli'    => 'required',
      'harga_jual'    => 'required|gt:harga_beli',
      'stok'          => 'required',
    ]);

    //upload image
    $gambar_baru = 'default.jpg';
    $gambar = $request->file('gambar');

    if ($gambar) {
      $gambar_baru = $gambar->hashName();
      $gambar->storeAs('public/produks', $gambar_baru);
    }

    //create product
    Produk::create([
      'gambar'        => $gambar_baru,
      'barcode'       => $request->barcode,
      'nama'          => $request->nama_produk,
      'keterangan'    => $request->keterangan,
      'kategori_id'   => $request->kategori,
      'harga_beli'    => $request->harga_beli,
      'harga_jual'    => $request->harga_jual,
      'stok'          => $request->stok,
    ]);

    // return redirect()->route('apps.produks.index');
  }

  public function show(string $id)
  {
    //
  }

  public function edit(Produk $produk)
  {
    return Inertia::render('Apps/Produks/Edit', [
      'produk'    => $produk->with('kategori')->findOrFail($produk->id),
      'kategoris' => Kategori::all(),
      'toko'      => $this->toko
    ]);
  }

  public function update(Request $request, Produk $produk)
  {
    $gambar_validation = '';
    if ($request->file('gambar')) {
      $gambar_validation = 'image|mimes:jpeg,jpg,png|max:1000';
    }

    $request->validate([
      'gambar'        => $gambar_validation,
      'barcode'       => 'required|unique:produks,barcode,' . $produk->id,
      'nama_produk'   => 'required|unique:produks,nama,' . $produk->id,
      'kategori'      => 'required',
      'keterangan'    => 'required',
      'harga_beli'    => 'required',
      'harga_jual'    => 'required|gt:harga_beli',
      'stok'          => 'required',
    ]);

    $gambar_lama = basename($produk->gambar);
    $gambar_baru = $gambar_lama;

    if ($request->file('gambar')) {
      if ($gambar_lama != 'default.jpg') {
        Storage::disk('local')->delete('public/produks/' . $gambar_lama);
      }

      $gambar = $request->file('gambar');
      $gambar_baru = $gambar->hashName();
      $gambar->storeAs('public/produks', $gambar_baru);
    }

    $produk->update([
      'gambar'        => $gambar_baru,
      'barcode'       => $request->barcode,
      'nama'          => $request->nama_produk,
      'keterangan'    => $request->keterangan,
      'kategori_id'   => $request->kategori,
      'harga_beli'    => $request->harga_beli,
      'harga_jual'    => $request->harga_jual,
      'stok'          => $request->stok,
    ]);

    //redirect
    // return redirect()->route('apps.produks.index');
  }

  public function _check(Produk $produk)
  {
    $transaksi_detail = TransaksiDetail::where('produk_id', $produk->id)->first();

    if ($transaksi_detail) {
      $this->success = false;
      $this->message = 'Produk ' . $transaksi_detail->produk->nama . ' digunakan pada data transaksi.';
    } else {
      $this->success = true;
      $this->message = 'Anda tidak dapat mengembalikan data ini!';
    }

    return response()->json([
      'success'   => $this->success,
      'message'   => $this->message,
    ]);
  }

  public function destroy(Produk $produk)
  {
    $gambar = basename($produk->gambar);
    if ($gambar != 'default.jpg') Storage::disk('local')->delete('public/produks/' . $gambar);
    $produk->delete();
  }
}
