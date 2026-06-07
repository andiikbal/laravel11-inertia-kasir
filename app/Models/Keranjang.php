<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keranjang extends Model
{
  use HasFactory;

  protected $fillable = [
    'kasir_id',
    'produk_id',
    'kuantitas',
    'sub_total'
  ];

  public function produk()
  {
    return $this->belongsTo(Produk::class);
  }
}
