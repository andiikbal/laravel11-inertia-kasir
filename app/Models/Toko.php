<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Toko extends Model
{
  use HasFactory;

  protected $fillable = [
    'logo',
    'nama',
    'alamat',
    'no_telepon',
    'keterangan',
  ];

  // Accessor
  protected function logo(): Attribute
  {
    return Attribute::make(
      get: fn($value) => url('/storage/toko/' . $value),
    );
  }
}
