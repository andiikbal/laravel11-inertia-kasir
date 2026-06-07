<?php

namespace App\Http\Controllers\Apps;

use Inertia\Inertia;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Models\User as Pengguna;
use App\Http\Controllers\Controller;
use App\Models\Toko;
use Spatie\Permission\Models\Role as Peran;

class PenggunaController extends Controller
{
  private $toko, $success, $message;

  public function __construct()
  {
    $this->toko = Toko::findOrFail(1);
  }

  private function penggunas()
  {
    return Pengguna::when(request()->q, function ($penggunas) {
      $penggunas = $penggunas->where('name', 'like', '%' . request()->q . '%');
    })->with('roles')->latest()->paginate(5);
  }

  public function index()
  {
    return Inertia::render('Apps/Penggunas/Index', [
      'penggunas' => $this->penggunas(),
      'toko'      => $this->toko
    ]);
  }

  public function create()
  {
    return Inertia::render('Apps/Penggunas/Create', [
      'perans'  => Peran::all(),
      'toko'    => $this->toko
    ]);
  }

  public function store(Request $request)
  {
    $request->validate([
      'nama_lengkap'  => 'required',
      // 'alamat_email'  => 'required|email:rfc,dns|unique:users,email',
      'alamat_email'  => 'required|email:rfc|unique:users,email',
      'kata_sandi'    => 'required|confirmed',
      'peran'         => 'required'
    ]);

    $pengguna = Pengguna::create([
      'name'     => $request->nama_lengkap,
      'email'    => $request->alamat_email,
      'password' => bcrypt($request->kata_sandi)
    ]);

    //assign roles to user
    $pengguna->assignRole($request->peran);

    // return redirect()->route('apps.penggunas.index');
  }

  public function edit($id)
  {
    //get user
    $pengguna = Pengguna::with('roles')->findOrFail($id);

    //get roles
    $perans = Peran::all();

    return Inertia::render('Apps/Penggunas/Edit', [
      'pengguna'  => $pengguna,
      'perans'    => $perans,
      'toko'      => $this->toko
    ]);
  }

  public function update(Request $request, Pengguna $pengguna)
  {
    $request->validate([
      'nama_lengkap'  => 'required',
      // 'alamat_email'  => 'required|email:rfc,dns|unique:users,email,' . $pengguna->id,
      'alamat_email'  => 'required|email:rfc|unique:users,email,' . $pengguna->id,
      'kata_sandi'    => 'nullable|confirmed',
      'peran'         => 'required'
    ]);

    if ($request->kata_sandi == '') {
      $pengguna->update([
        'name'     => $request->nama_lengkap,
        'email'    => $request->alamat_email
      ]);
    } else {
      $pengguna->update([
        'name'     => $request->nama_lengkap,
        'email'    => $request->alamat_email,
        'password' => bcrypt($request->kata_sandi)
      ]);
    }

    //assign roles to user
    $pengguna->syncRoles($request->peran);

    return redirect()->route('apps.penggunas.index');
  }

  public function _check(Pengguna $pengguna)
  {
    if ($pengguna->id == auth()->user()->id) {
      $this->success = false;
      $this->message = 'Pengguna ' . $pengguna->name . ' sedang aktif.';
    } else {
      $transaksi = Transaksi::where('kasir_id', $pengguna->id)->first();
      if ($transaksi) {
        $this->success = false;
        $this->message = 'Pengguna ' . $pengguna->name . ' digunakan pada data transaksi.';
      } else {
        $this->success = true;
        $this->message = 'Anda tidak dapat mengembalikan data ini!';
      }
    }

    return response()->json([
      'success'   => $this->success,
      'message'   => $this->message,
    ]);
  }

  public function destroy(Pengguna $pengguna)
  {
    $pengguna->delete();
  }
}
