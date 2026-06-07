<?php

namespace App\Exports;

use App\Models\Toko;
use App\Models\Transaksi;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PenjualansExport implements FromView
{
  private $toko;
  protected $tanggal_awal;
  protected $tanggal_akhir;

  public function __construct($tanggal_awal, $tanggal_akhir)
  {
    $this->tanggal_awal   = $tanggal_awal;
    $this->tanggal_akhir  = $tanggal_akhir;
    $this->toko = Toko::findOrFail(1);
  }

  public function view(): View
  {
    return view('exports.penjualans', [
      'toko'        => $this->toko,
      'penjualans'  => Transaksi::with('kasir', 'pelanggan')->whereDate('created_at', '>=', $this->tanggal_awal)->whereDate('created_at', '<=', $this->tanggal_akhir)->get(),
      'total'       => Transaksi::whereDate('created_at', '>=', $this->tanggal_awal)->whereDate('created_at', '<=', $this->tanggal_akhir)->sum('grand_total')
    ]);
  }
}
