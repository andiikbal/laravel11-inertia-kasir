<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Toko;
use Illuminate\Support\Facades\Storage;

class TokoController extends Controller
{
  private $toko;

  public function __construct() {}

  public function index()
  {
    $this->toko = Toko::findOrFail(1);
    return Inertia::render('Apps/Toko/Index', ['toko'  => $this->toko]);
  }

  public function upload(Request $request, Toko $toko)
  {
    $logo_validation = '';
    if ($request->file('logo')) {
      $logo_validation = 'image|mimes:jpeg,jpg,png|max:1000';
    } else {
      $logo_validation = 'required';
    }

    $request->validate([
      'logo' => $logo_validation,
    ]);

    $logo_lama = basename($toko->logo);
    $logo_baru = $logo_lama;

    if ($logo_lama != 'default.png') Storage::disk('local')->delete('public/toko/' . $logo_lama);

    $logo = $request->file('logo');
    $logo_baru = $logo->hashName();
    $logo->storeAs('public/toko', $logo_baru);

    $toko->update([
      'logo'    => $logo_baru,
    ]);
  }

  public function update(Request $request, Toko $toko)
  {
    $request->validate([
      'nama_toko'     => 'required',
      'alamat_toko'   => 'required',
      'no_telepon'    => 'required',
      'keterangan'    => 'nullable|max:120',
    ]);

    $toko->update([
      'nama'          => $request->nama_toko,
      'alamat'        => $request->alamat_toko,
      'no_telepon'    => $request->no_telepon,
      'keterangan'    => $request->keterangan == "" ? null : $request->keterangan,
    ]);
  }
}
