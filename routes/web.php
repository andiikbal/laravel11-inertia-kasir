<?php

use App\Models\Toko;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Apps\IzinController;
use App\Http\Controllers\Apps\TokoController;
use App\Http\Controllers\Apps\PeranController;
use App\Http\Controllers\Apps\ProdukController;
use App\Http\Controllers\Apps\ProfilController;
use App\Http\Controllers\Apps\KategoriController;
use App\Http\Controllers\Apps\PenggunaController;
use App\Http\Controllers\Apps\DashboardController;
use App\Http\Controllers\Apps\PelangganController;
use App\Models\Kategori;
use App\Models\Pelanggan;
use App\Models\Produk;

// route home
Route::get('/', function () {
  return Auth::check() ? redirect(route('apps.profil.index')) : redirect('/login');
});
// })->middleware('guest');

//prefix "apps"
Route::prefix('apps')->group(function () {
  //middleware "auth"
  Route::group(['middleware' => ['auth']], function () {
    //route dashboard
    Route::get('dashboard', DashboardController::class)->name('apps.dashboard');

    // route profil
    Route::get('/profil', [ProfilController::class, 'index'])->name('apps.profil.index')->middleware('permission:profil.index');
    Route::put('/profil/{pengguna}/upload', [ProfilController::class, 'upload'])->name('apps.profil.upload')->middleware('permission:profil.edit');
    Route::put('/profil/{pengguna}', [ProfilController::class, 'update'])->name('apps.profil.update')->middleware('permission:profil.edit');

    // route toko
    Route::get('/toko', [TokoController::class, 'index'])->name('apps.toko.index')->middleware('permission:toko.index');
    Route::put('/toko/{toko}/upload', [TokoController::class, 'upload'])->name('apps.toko.upload')->middleware('permission:toko.edit');
    Route::put('/toko/{toko}', [TokoController::class, 'update'])->name('apps.toko.update')->middleware('permission:toko.edit');

    //route izin
    Route::get('/izins', IzinController::class)->name('apps.permissions.index')->middleware('permission:permissions.index');

    //route resource peran
    Route::resource('/perans', PeranController::class, ['as' => 'apps'])->middleware('permission:roles.index|roles.create|roles.edit|roles.delete');

    //route resource pengguna
    Route::resource('/penggunas', PenggunaController::class, ['as' => 'apps'])->middleware('permission:users.index|users.create|users.edit|users.delete');
    Route::post('/penggunas/{pengguna}', [PenggunaController::class, '_check']);

    //route resource kategori
    Route::resource('/kategoris', KategoriController::class, ['as' => 'apps'])->middleware('permission:kategoris.index|kategoris.create|kategoris.edit|kategoris.delete');
    Route::post('/kategoris/{kategori}', [KategoriController::class, '_check']);

    //route resource produk
    Route::resource('/produks', ProdukController::class, ['as' => 'apps'])->middleware('permission:produks.index|produks.create|produks.edit|produks.delete');
    Route::post('/produks/{produk}', [ProdukController::class, '_check']);

    //route resource pelanggan
    Route::resource('/pelanggans', PelangganController::class, ['as' => 'apps'])->middleware('permission:pelanggans.index|pelanggans.create|pelanggans.edit|pelanggans.delete');
    Route::post('/pelanggans/{pelanggan}', [PelangganController::class, '_check']);


    //route transaksi
    Route::get('/transaksis', [\App\Http\Controllers\Apps\TransaksiController::class, 'index'])->name('apps.transaksis.index');

    //route transaksi cariProduk
    Route::post('/transaksis/loadData', [\App\Http\Controllers\Apps\TransaksiController::class, 'loadData'])->name('apps.transaksis.loadData');

    //route transaksi cariProduk
    Route::post('/transaksis/cariProduk', [\App\Http\Controllers\Apps\TransaksiController::class, 'cariProduk'])->name('apps.transaksis.cariProduk');

    //route transaksi tambahItem
    Route::post('/transaksis/tambahItem', [\App\Http\Controllers\Apps\TransaksiController::class, 'tambahItem'])->name('apps.transaksis.tambahItem');

    //route transaksi hapusItem
    Route::post('/transaksis/hapusItem', [\App\Http\Controllers\Apps\TransaksiController::class, 'hapusItem'])->name('apps.transaksis.hapusItem');

    //route transaksi kosongkanKeranjang
    Route::post('/transaksis/kosongkanKeranjang', [\App\Http\Controllers\Apps\TransaksiController::class, 'kosongkanKeranjang'])->name('apps.transaksis.kosongkanKeranjang');

    //route transaksi simpan
    Route::post('/transaksis/simpan', [\App\Http\Controllers\Apps\TransaksiController::class, 'simpan'])->name('apps.transaksis.simpan');

    //route transaksi print
    Route::get('/transaksis/print', [\App\Http\Controllers\Apps\TransaksiController::class, 'print'])->name('apps.transaksis.print');

    //route penjualan index
    Route::get('/penjualans', [\App\Http\Controllers\Apps\PenjualanController::class, 'index'])->middleware('permission:penjualans.index')->name('apps.penjualans.index');

    //route penjualan filter
    Route::post('/penjualans', [\App\Http\Controllers\Apps\PenjualanController::class, 'filter'])->name('apps.penjualans.filter');

    //route penjualans export excel
    Route::get('/penjualans/export', [\App\Http\Controllers\Apps\PenjualanController::class, 'export'])->name('apps.penjualans.export');

    //route penjualans print pdf
    Route::get('/penjualans/pdf', [\App\Http\Controllers\Apps\PenjualanController::class, 'pdf'])->name('apps.penjualans.pdf');

    //route keuntungans index
    Route::get('/keuntungans', [\App\Http\Controllers\Apps\KeuntunganController::class, 'index'])->middleware('permission:keuntungans.index')->name('apps.profits.index');

    //route keuntungans filter
    Route::post('/keuntungans', [\App\Http\Controllers\Apps\KeuntunganController::class, 'filter'])->name('apps.keuntungans.filter');

    //route keuntungans export
    Route::get('/keuntungans/export', [\App\Http\Controllers\Apps\KeuntunganController::class, 'export'])->name('apps.keuntungans.export');

    //route keuntungans pdf
    Route::get('/keuntungans/pdf', [\App\Http\Controllers\Apps\KeuntunganController::class, 'pdf'])->name('apps.keuntungans.pdf');
  });
});
