<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Produk extends Model
{
    use HasFactory;

    protected $fillable = [
        'kategori_id',
        'gambar',
        'barcode',
        'nama',
        'keterangan',
        'harga_beli',
        'harga_jual',
        'stok',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    // Accessor
    protected function gambar(): Attribute
    {
        return Attribute::make(
            get: fn($value) => url('/storage/produks/' . $value),
        );
    }
}
