<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('transaksi_details', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('transaksi_id');
      $table->unsignedBigInteger('produk_id');
      $table->integer('kuantitas')->unsigned()->default(1);
      $table->bigInteger('sub_total')->unsigned();
      $table->timestamps();

      $table->foreign('transaksi_id')->references('id')->on('transaksis');
      $table->foreign('produk_id')->references('id')->on('produks');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('transaksi_details');
  }
};
