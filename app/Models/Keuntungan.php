<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

class Keuntungan extends Model
{
  use HasFactory;

  protected $fillable = [
    'transaksi_id',
    'total'
  ];

  public function transaksi()
  {
    return $this->belongsTo(Transaksi::class);
  }

  // Accessor
  protected function createdAt(): Attribute
  {
    return Attribute::make(
      get: fn($value) => Carbon::parse($value)->format('d-M-Y H:i:s'),
    );
  }
}
