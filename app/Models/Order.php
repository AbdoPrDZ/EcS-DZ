<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Linking;

class Order extends Model
{
  use HasFactory, Linking;

  protected $fillable = [
    'user_id',
    'product_id',
    'count',
    'price_exchange_id',
    'delivery_exchange_id',
    'commission_exchange_id',
    'total_price',
    'total_commission',
    'total_delivery_cost',
    'phone',
    'address',
    'delivery_steps',
    'current_delivery_step',
    'status',
  ];

  protected $casts = [
    'images_urls' => 'json',
    'total_price' => 'double',
    'total_commission' => 'double',
    'total_delivery_cost' => 'double',
    'delivery_steps' => 'json',
  ];

  protected $links = [
    'user_id' => User::class,
    'product_id' => Product::class,
    'price_exchange_id' => Exchange::class,
    'delivery_exchange_id' => Exchange::class,
  ];
}
