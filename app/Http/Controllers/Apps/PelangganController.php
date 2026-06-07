<?php

namespace App\Http\Controllers\Apps;

use Inertia\Inertia;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Toko;
use App\Models\Transaksi;

class PelangganController extends Controller
{
  private $toko, $success, $message;

  public function __construct()
  {
    $this->toko = Toko::findOrFail(1);
  }

  private function pelanggans()
  {
    return Pelanggan::when(request()->q, function ($pelanggans) {
      $pelanggans = $pelanggans->where('nama', 'like', '%' . request()->q . '%');
    })->latest()->paginate(5);
  }

  public function index()
  {
    return Inertia::render('Apps/Pelanggans/Index', [
      'pelanggans'  => $this->pelanggans(),
      'toko'        => $this->toko
    ]);
  }

  public function create()
  {
    return Inertia::render('Apps/Pelanggans/Create', ['toko' => $this->toko]);
  }

  public function store(Request $request)
  {
    $request->validate([
      'nama_lengkap'  => 'required',
      'no_telepon'    => 'required|numeric|unique:pelanggans',
      'alamat'        => 'required',
    ]);

    Pelanggan::create([
      'nama'          => $request->nama_lengkap,
      'no_telepon'    => $request->no_telepon,
      'alamat'        => $request->alamat,
    ]);

    return redirect()->route('apps.pelanggans.index');
  }

  public function show(string $id)
  {
    //
  }

  public function edit(Pelanggan $pelanggan)
  {
    return Inertia::render('Apps/Pelanggans/Edit', [
      'pelanggan' => $pelanggan,
      'toko'      => $this->toko
    ]);
  }

  public function update(Request $request, Pelanggan $pelanggan)
  {
    $request->validate([
      'nama_lengkap'  => 'required',
      'no_telepon'    => 'required|numeric|unique:pelanggans,no_telepon,' . $pelanggan->id,
      'alamat'        => 'required',
    ]);

    $pelanggan->update([
      'nama'          => $request->nama_lengkap,
      'no_telepon'    => $request->no_telepon,
      'alamat'        => $request->alamat,
    ]);

    return redirect()->route('apps.pelanggans.index');
  }

  public function _check(Pelanggan $pelanggan)
  {
    $transaksi = Transaksi::where('pelanggan_id', $pelanggan->id)->first();

    if ($transaksi) {
      $this->success = false;
      $this->message = 'Pelanggan ' . $pelanggan->nama . ' digunakan pada data transaksi.';
    } else {
      $this->success = true;
      $this->message = 'Anda tidak dapat mengembalikan data ini!';
    }

    return response()->json([
      'success'   => $this->success,
      'message'   => $this->message,
    ]);
  }

  public function destroy(Pelanggan $pelanggan)
  {
    $pelanggan->delete();
  }
}
