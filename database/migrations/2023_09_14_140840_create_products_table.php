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
    Schema::create('products', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('description');
      $table->json('details')->default('{}');
      $table->double('price');
      $table->double('commission');
      $table->integer('count');
      $table->json('images_urls')->default('[]');
      $table->enum('status', ['available', 'out_stock', 'sone'])->default('available');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('products');
  }
};
