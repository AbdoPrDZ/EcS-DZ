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
    Schema::create('programmers', function (Blueprint $table) {
      $table->id();
      $table->integer('user_id');
      $table->string('username');
      $table->json('templates_ids')->default('[]');
      $table->integer('wallet_id');
      $table->enum('status', ['active', 'offline', 'banned']);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('programmers');
  }
};
