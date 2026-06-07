<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

class Transaksi extends Model
{
  use HasFactory;

  protected $fillable = [
    'kasir_id',
    'pelanggan_id',
    'faktur',
    'uang_tunai',
    'uang_kembali',
    'diskon',
    'total',
    'grand_total'
  ];

  public function details()
  {
    return $this->hasMany(TransaksiDetail::class);
  }

  public function pelanggan()
  {
    return $this->belongsTo(Pelanggan::class);
  }

  public function kasir()
  {
    return $this->belongsTo(User::class, 'kasir_id');
  }

  public function keuntungans()
  {
    return $this->hasMany(Keuntungan::class);
  }

  // Accessor
  protected function createdAt(): Attribute
  {
    return Attribute::make(
      get: fn($value) => Carbon::parse($value)->format('d-M-Y H:i:s'),
    );
  }
}
