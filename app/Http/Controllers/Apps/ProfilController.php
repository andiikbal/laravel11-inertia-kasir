<?php

namespace App\Http\Controllers\Apps;

use App\Models\Toko;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\User as Pengguna;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ProfilController extends Controller
{
  private $toko;

  public function __construct() {}

  public function index()
  {
    $this->toko = Toko::findOrFail(1);

    return Inertia::render('Apps/Profil/Index', [
      'toko'      => $this->toko
    ]);
  }

  public function upload(Request $request, Pengguna $pengguna)
  {
    $photo_validation = '';
    if ($request->file('photo')) {
      $photo_validation = 'image|mimes:jpeg,jpg,png|max:1000';
    } else {
      $photo_validation = 'required';
    }

    $request->validate([
      'photo' => $photo_validation,
    ]);

    $photo_lama = basename($pengguna->photo);
    $photo_baru = $photo_lama;

    if ($request->file('photo')) {
      if ($photo_lama != 'default.svg') {
        Storage::disk('local')->delete('public/users/' . $photo_lama);
      }

      $photo = $request->file('photo');
      $photo_baru = $photo->hashName();
      $photo->storeAs('public/users', $photo_baru);
    }

    $pengguna->update([
      'photo'    => $photo_baru,
    ]);
  }

  public function update(Request $request, Pengguna $pengguna)
  {
    $kata_sandi = trim($request->kata_sandi);
    $kata_sandi_confirmation = trim($request->kata_sandi_confirmation);
    $valid_kata_sandi = $kata_sandi == '' && $kata_sandi_confirmation == '' ? 'nullable' : 'confirmed';

    $request->validate([
      'nama_lengkap'  => 'required',
      // 'alamat_email'  => 'required|email:rfc,dns|unique:users,email,' . $pengguna->id,
      'alamat_email'  => 'required|email:rfc|unique:users,email,' . $pengguna->id,
      'kata_sandi'    => $valid_kata_sandi,
    ]);

    if ($valid_kata_sandi == 'nullable') {
      $pengguna->update([
        'name'     => trim($request->nama_lengkap),
        'email'    => trim($request->alamat_email)
      ]);
    } else {
      $pengguna->update([
        'name'     => trim($request->nama_lengkap),
        'email'    => trim($request->alamat_email),
        'password' => bcrypt(trim($request->kata_sandi))
      ]);
    }
  }
}
