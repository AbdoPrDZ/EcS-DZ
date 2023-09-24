<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void {
    Schema::create('app_users', function (Blueprint $table) {
      $table->id();
      $table->integer('user_id');
      $table->string('username');
      $table->string('password');
      $table->rememberToken();
      $table->integer('wallet_id')->nullable();
      $table->enum('status', ['checking', 'active', 'offline', 'banned'])->default('checking');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void {
    Schema::dropIfExists('app_users');
  }
};
