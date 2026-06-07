<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('keuntungans', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('transaksi_id');
      $table->bigInteger('total')->unsigned();
      $table->timestamps();

      $table->foreign('transaksi_id')->references('id')->on('transaksis');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('keuntungans');
  }
};
