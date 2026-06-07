<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
  public function run(): void
  {
    //permission dashboard
    Permission::create(['name' => 'dashboard.index', 'guard_name' => 'web']);
    Permission::create(['name' => 'dashboard.grafik_penjualan', 'guard_name' => 'web']);
    Permission::create(['name' => 'dashboard.penjualan_hari_ini', 'guard_name' => 'web']);
    Permission::create(['name' => 'dashboard.keuntungan_hari_ini', 'guard_name' => 'web']);
    Permission::create(['name' => 'dashboard.produk_terlaris', 'guard_name' => 'web']);
    Permission::create(['name' => 'dashboard.stok_produk', 'guard_name' => 'web']);

    //permission profil
    Permission::create(['name' => 'profil.index', 'guard_name' => 'web']);
    Permission::create(['name' => 'profil.edit', 'guard_name' => 'web']);

    //permission toko
    Permission::create(['name' => 'toko.index', 'guard_name' => 'web']);
    Permission::create(['name' => 'toko.edit', 'guard_name' => 'web']);

    //permission kategoris
    Permission::create(['name' => 'kategoris.index', 'guard_name' => 'web']);
    Permission::create(['name' => 'kategoris.create', 'guard_name' => 'web']);
    Permission::create(['name' => 'kategoris.edit', 'guard_name' => 'web']);
    Permission::create(['name' => 'kategoris.delete', 'guard_name' => 'web']);

    //permission produks
    Permission::create(['name' => 'produks.index', 'guard_name' => 'web']);
    Permission::create(['name' => 'produks.create', 'guard_name' => 'web']);
    Permission::create(['name' => 'produks.edit', 'guard_name' => 'web']);
    Permission::create(['name' => 'produks.delete', 'guard_name' => 'web']);

    //permission pelanggans
    Permission::create(['name' => 'pelanggans.index', 'guard_name' => 'web']);
    Permission::create(['name' => 'pelanggans.create', 'guard_name' => 'web']);
    Permission::create(['name' => 'pelanggans.edit', 'guard_name' => 'web']);
    Permission::create(['name' => 'pelanggans.delete', 'guard_name' => 'web']);

    //permission transaksis
    Permission::create(['name' => 'transaksis.index', 'guard_name' => 'web']);

    //permissions penjualans
    Permission::create(['name' => 'penjualans.index', 'guard_name' => 'web']);

    //permissions keuntungans
    Permission::create(['name' => 'keuntungans.index', 'guard_name' => 'web']);

    //permission permissions
    Permission::create(['name' => 'permissions.index', 'guard_name' => 'web']);

    //permission roles
    Permission::create(['name' => 'roles.index', 'guard_name' => 'web']);
    Permission::create(['name' => 'roles.create', 'guard_name' => 'web']);
    Permission::create(['name' => 'roles.edit', 'guard_name' => 'web']);
    Permission::create(['name' => 'roles.delete', 'guard_name' => 'web']);

    //permission users
    Permission::create(['name' => 'users.index', 'guard_name' => 'web']);
    Permission::create(['name' => 'users.create', 'guard_name' => 'web']);
    Permission::create(['name' => 'users.edit', 'guard_name' => 'web']);
    Permission::create(['name' => 'users.delete', 'guard_name' => 'web']);
  }
}
