<?php

namespace App\Http\Controllers\Apps;

use App\Models\Toko;
use Inertia\Inertia;
use App\Exports\PenjualansExport;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class PenjualanController extends Controller
{
  private $toko;

  public function __construct()
  {
    $this->toko = Toko::findOrFail(1);
  }

  public function index()
  {
    return Inertia::render('Apps/Penjualans/Index', [
      'toko' => $this->toko
    ]);
  }

  public function filter(Request $request)
  {
    //get data sales by range date
    $penjualans = Transaksi::with('kasir', 'pelanggan')
      ->whereDate('created_at', '>=', $request->tanggal_awal)
      ->whereDate('created_at', '<=', $request->tanggal_akhir)
      ->get();

    //get total sales by range date
    $total = Transaksi::whereDate('created_at', '>=', $request->tanggal_awal)
      ->whereDate('created_at', '<=', $request->tanggal_akhir)
      ->sum('grand_total');

    return response()->json([
      'success' => true,
      'data'    => [
        'penjualans'  => $penjualans,
        'total'       => (int) $total
      ],
    ]);
  }

  public function export(Request $request)
  {
    return Excel::download(new PenjualansExport($request->tanggal_awal, $request->tanggal_akhir), 'penjualans : ' . $request->tanggal_awal . ' — ' . $request->tanggal_akhir . '.xlsx');
  }

  public function pdf(Request $request)
  {
    $toko = $this->toko;
    //get sales by range date
    $penjualans = Transaksi::with('kasir', 'pelanggan')->whereDate('created_at', '>=', $request->tanggal_awal)->whereDate('created_at', '<=', $request->tanggal_akhir)->get();

    //get total sales by range daate
    $total = Transaksi::whereDate('created_at', '>=', $request->tanggal_awal)->whereDate('created_at', '<=', $request->tanggal_akhir)->sum('grand_total');

    //load view PDF with data
    $pdf = PDF::loadView('exports.penjualans', compact('toko', 'penjualans', 'total'));

    //return PDF for preview / download
    return $pdf->download('penjualans : ' . $request->tanggal_awal . ' — ' . $request->tanggal_akhir . '.pdf');
  }
}
