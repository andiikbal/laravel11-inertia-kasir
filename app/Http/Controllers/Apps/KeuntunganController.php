<?php

namespace App\Http\Controllers\Apps;

use App\Models\Toko;
use Inertia\Inertia;
use App\Models\Keuntungan;
use Illuminate\Http\Request;
use App\Exports\KeuntungansExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class KeuntunganController extends Controller
{
  private $toko;

  public function __construct()
  {
    $this->toko = Toko::findOrFail(1);
  }

  public function index()
  {
    return Inertia::render('Apps/Keuntungans/Index', [
      'toko'  => $this->toko,
    ]);
  }

  public function filter(Request $request)
  {
    //get data profits by range date
    $keuntungans = Keuntungan::with('transaksi')->whereDate('created_at', '>=', $request->tanggal_awal)->whereDate('created_at', '<=', $request->tanggal_akhir)->get();

    //get total profit by range date
    $total = Keuntungan::whereDate('created_at', '>=', $request->tanggal_awal)->whereDate('created_at', '<=', $request->tanggal_akhir)->sum('total');

    return response()->json([
      'success' => true,
      'data'    => [
        'keuntungans'  => $keuntungans,
        'total'         => (int) $total
      ],
    ]);
  }

  public function export(Request $request)
  {
    return Excel::download(new KeuntungansExport($request->tanggal_awal, $request->tanggal_akhir), 'keuntungans : ' . $request->tanggal_awal . ' — ' . $request->tanggal_akhir . '.xlsx');
  }

  public function pdf(Request $request)
  {
    //get data proftis by range date
    $keuntungans = Keuntungan::with('transaksi')->whereDate('created_at', '>=', $request->tanggal_awal)->whereDate('created_at', '<=', $request->tanggal_akhir)->get();

    //get total keuntungan by range date
    $total = Keuntungan::whereDate('created_at', '>=', $request->tanggal_awal)->whereDate('created_at', '<=', $request->tanggal_akhir)->sum('total');

    $toko = $this->toko;

    //load view PDF with data
    $pdf = PDF::loadView('exports.keuntungans', compact('toko', 'keuntungans', 'total'));

    //download PDF
    return $pdf->download('keuntungans : ' . $request->tanggal_awal . ' — ' . $request->tanggal_akhir . '.pdf');
  }
}
