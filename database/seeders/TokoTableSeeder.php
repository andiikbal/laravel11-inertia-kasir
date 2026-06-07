<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Toko;

class TokoTableSeeder extends Seeder
{

  public function run(): void
  {
    Toko::create([
      'logo'        => 'default.png',
      'nama'        => 'Tokoku',
      'alamat'      => 'Alamat Tokoku',
      'no_telepon'  => '081322110011',
      'keterangan'  => 'Terima kasih telah berbelanja di Tokoku',
    ]);
  }
}
