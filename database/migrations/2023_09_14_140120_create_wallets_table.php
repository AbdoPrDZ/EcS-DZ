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
    Schema::create('wallets', function (Blueprint $table) {
      $table->id();
      $table->integer('user_id');
      $table->string('user_model');
      $table->double('balance')->default(0);
      $table->double('out_balance')->default(0);
      $table->double('in_balance')->default(0);
      $table->enum('status', ['active', 'blocked', 'checking'])->default('checking');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('wallets');
  }
};
