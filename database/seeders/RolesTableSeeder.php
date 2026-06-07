<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
  public function run(): void
  {
    Role::create(['name' => 'admin']);
    Role::create(['name' => 'kasir']);
  }
}
