<?php

namespace App\Exports;

use App\Models\Keuntungan;
use App\Models\Toko;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class KeuntungansExport implements FromView
{
  private $toko;
  protected $tanggal_awal;
  protected $tanggal_akhir;

  public function __construct($tanggal_awal, $tanggal_akhir)
  {
    $this->tanggal_awal = $tanggal_awal;
    $this->tanggal_akhir = $tanggal_akhir;
    $this->toko = Toko::findOrFail(1);
  }

  public function view(): View
  {
    return view('exports.keuntungans', [
      'toko'        => $this->toko,
      'keuntungans' => Keuntungan::with('transaksi')->whereDate('created_at', '>=', $this->tanggal_awal)->whereDate('created_at', '<=', $this->tanggal_akhir)->get(),
      'total'       => Keuntungan::whereDate('created_at', '>=', $this->tanggal_awal)->whereDate('created_at', '<=', $this->tanggal_akhir)->sum('total')
    ]);
  }
}
