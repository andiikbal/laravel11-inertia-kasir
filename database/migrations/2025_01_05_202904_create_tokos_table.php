<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('tokos', function (Blueprint $table) {
      $table->id();
      $table->string('logo')->default('default.png');
      $table->string('nama');
      $table->string('alamat');
      $table->string('no_telepon');
      $table->text('keterangan');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('tokos');
  }
};
