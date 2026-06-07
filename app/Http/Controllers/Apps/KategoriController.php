<?php

namespace App\Http\Controllers\Apps;

use App\Models\Toko;
use Inertia\Inertia;
use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class KategoriController extends Controller
{
  private $toko, $success, $message;

  public function __construct()
  {
    $this->toko = Toko::findOrFail(1);
  }

  private function kategoris()
  {
    return Kategori::when(request()->q, function ($kategoris) {
      $kategoris = $kategoris->where('nama', 'like', '%' . request()->q . '%');
    })->latest()->paginate(5);
  }

  public function index()
  {
    return Inertia::render('Apps/Kategoris/Index', [
      'kategoris' => $this->kategoris(),
      'toko'      => $this->toko
    ]);
  }

  public function create()
  {
    return Inertia::render('Apps/Kategoris/Create', ['toko' => $this->toko]);
  }

  public function store(Request $request)
  {
    $request->validate([
      'nama_kategori' => 'required|unique:kategoris,nama',
      'keterangan'    => 'max:200',
    ]);

    Kategori::create([
      'nama'          => trim($request->nama_kategori),
      'keterangan'    => trim($request->keterangan) == "" ? null : trim($request->keterangan),
    ]);

    return redirect()->route('apps.kategoris.index');
  }

  public function show(string $id)
  {
    //
  }

  public function edit(Kategori $kategori)
  {
    return Inertia::render('Apps/Kategoris/Edit', [
      'kategori'  => $kategori,
      'toko'      => $this->toko
    ]);
  }

  public function update(Request $request, Kategori $kategori)
  {
    $request->validate([
      'nama_kategori' => 'required|unique:kategoris,nama,' . $kategori->id,
      'keterangan'    => 'max:200',
    ]);

    $kategori->update([
      'nama'          => $request->nama_kategori,
      'keterangan'    => $request->keterangan,
    ]);

    return redirect()->route('apps.kategoris.index');
  }

  public function _check(Kategori $kategori)
  {
    $produk = Produk::where('kategori_id', $kategori->id)->first();

    if ($produk) {
      $this->success = false;
      $this->message = 'Kategori ' . $kategori->nama . ' digunakan pada data produk.';
    } else {
      $this->success = true;
      $this->message = 'Anda tidak dapat mengembalikan data ini!';
    }

    return response()->json([
      'success'   => $this->success,
      'message'   => $this->message,
    ]);
  }

  public function destroy(Kategori $kategori)
  {
    $kategori->delete();
  }
}
