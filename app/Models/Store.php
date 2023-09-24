<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Linking;

class Store extends Model
{
  use HasFactory, Linking;

  protected $fillable = [
    'user_id',
    'name',
    'address',
    'phone',
    'images_urls',
    'wallet_id',
    'status',
  ];

  protected $casts = [
    'images_urls' => 'json',
  ];

  protected $links = [
    'user_id' => User::class,
    'wallet_id' => Wallet::class,
  ];
}
