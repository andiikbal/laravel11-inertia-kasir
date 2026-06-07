<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('transaksis', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('kasir_id');
      $table->unsignedBigInteger('pelanggan_id')->nullable();
      $table->string('faktur');
      $table->bigInteger('total')->unsigned();
      $table->bigInteger('diskon')->unsigned()->default(0);
      $table->bigInteger('grand_total')->unsigned();
      $table->bigInteger('uang_tunai')->unsigned();
      $table->bigInteger('uang_kembali')->unsigned()->default(0);
      $table->timestamps();

      $table->foreign('kasir_id')->references('id')->on('users');
      $table->foreign('pelanggan_id')->references('id')->on('pelanggans');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('transaksis');
  }
};
