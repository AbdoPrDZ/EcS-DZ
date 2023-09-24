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
    Schema::create('templates', function (Blueprint $table) {
      $table->id();
      $table->integer('programmer_id')->nullable();
      $table->string('name');
      $table->string('content');
      $table->json('fields')->default('{}');
      $table->double('price')->default(0);
      $table->boolean('is_mail_template')->default(false);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('templates');
  }
};
