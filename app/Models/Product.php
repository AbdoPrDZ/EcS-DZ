<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
  use HasFactory;

  protected $fillable = [
    'name',
    'description',
    'details',
    'price',
    'commission',
    'count',
    'images_urls',
    'status',
  ];

  protected $casts = [
    'details' => 'json',
    'price' => 'double',
    'commission' => 'double',
    'images_urls' => 'json',
  ];
}
