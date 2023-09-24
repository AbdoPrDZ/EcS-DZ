<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('exchanges', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->integer('from_wallet_id');
      $table->integer('to_wallet_id');
      $table->double('balance');
      $table->enum('status', ['waiting', 'received', 'refused'])->default('waiting');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('exchanges');
  }
};
