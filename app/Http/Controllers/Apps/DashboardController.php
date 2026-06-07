<?php

namespace App\Http\Controllers\Apps;

use Carbon\Carbon;
use App\Models\Toko;
use Inertia\Inertia;
use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\Keuntungan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
  private $toko;

  public function __invoke(Request $request)
  {
    $hari    = date('d');
    $seminggu = Carbon::now()->subDays(7); // 7 hari sebelum

    $grafik_penjualan_seminggu = DB::table('transaksis')
      ->addSelect(DB::raw('DATE(created_at) as tanggal, SUM(grand_total) as jumlah_keseluruhan'))
      ->where('created_at', '>=', $seminggu)
      ->groupBy('tanggal')
      ->get();

    if (count($grafik_penjualan_seminggu)) {
      foreach ($grafik_penjualan_seminggu as $hasil) {
        $tanggal_penjualan[]    = $hasil->tanggal;
        $jumlah_keseluruhan[]   = (int)$hasil->jumlah_keseluruhan;
      }
    } else {
      $tanggal_penjualan[]   = "";
      $jumlah_keseluruhan[]  = "";
    }

    $jumlah_transaksi_penjualan_hari_ini = Transaksi::whereDay('created_at', $hari)->count();
    $jumlah_penjualan_hari_ini = Transaksi::whereDay('created_at', $hari)->sum('grand_total');
    $jumlah_keuntungan_hari_ini = Keuntungan::whereDay('created_at', $hari)->sum('total');
    $stok_produk_kurang = Produk::with('kategori')->where('stok', '<=', 10)->get();
    $grafik_produk_terlaris = DB::table('transaksi_details')
      ->addSelect(DB::raw('produks.nama as nama, SUM(transaksi_details.kuantitas) as total'))
      ->join('produks', 'produks.id', '=', 'transaksi_details.produk_id')
      ->groupBy('transaksi_details.produk_id')
      ->orderBy('total', 'DESC')
      ->limit(5)
      ->get();

    if (count($grafik_produk_terlaris)) {
      foreach ($grafik_produk_terlaris as $data) {
        $produk[]   = $data->nama;
        $total[]    = (int)$data->total;
      }
    } else {
      $produk[]   = "";
      $total[]  = "";
    }

    $this->toko = Toko::findOrFail(1);

    return Inertia::render('Apps/Dashboard/Index', [
      'toko'                                  => $this->toko,
      'tanggal_penjualan'                     => $tanggal_penjualan,
      'jumlah_keseluruhan'                    => $jumlah_keseluruhan,
      'jumlah_transaksi_penjualan_hari_ini'   => (int) $jumlah_transaksi_penjualan_hari_ini,
      'jumlah_penjualan_hari_ini'             => (int) $jumlah_penjualan_hari_ini,
      'jumlah_keuntungan_hari_ini'            => (int) $jumlah_keuntungan_hari_ini,
      'stok_produk_kurang'                    => $stok_produk_kurang,
      'produk'                                => $produk,
      'total'                                 => $total
    ]);
  }
}
