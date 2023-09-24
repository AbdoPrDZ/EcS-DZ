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
    Schema::create('orders', function (Blueprint $table) {
      $table->id();
      $table->integer('user_id');
      $table->integer('product_id');
      $table->integer('count');
      $table->integer('price_exchange_id');
      $table->integer('delivery_exchange_id');
      $table->integer('commission_exchange_id');
      $table->double('total_price');
      $table->double('total_commission');
      $table->double('total_delivery_cost');
      $table->string('address');
      $table->string('phone');
      $table->json('delivery_steps')->default('{}');
      $table->string('current_delivery_step')->nullable();
      $table->enum('status', ['checking', 'sending', 'received', 'client_refuse', 'store_refuse'])->default('checking');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('orders');
  }
};
