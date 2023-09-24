<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void {
    Schema::create('admins', function (Blueprint $table) {
      $table->id();
      $table->integer('user_id');
      $table->string('username');
      $table->json('roles')->default('[]');
      $table->json('permissions')->default('[]');
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
    Schema::dropIfExists('admins');
  }
};
