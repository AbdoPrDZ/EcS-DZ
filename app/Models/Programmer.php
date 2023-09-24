<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Linking;

class Programmer extends Model
{
  use HasFactory, Linking;

  protected $fillable = [
    'user_id',
    'username',
    'templates_ids',
    'wallet_id',
    'status',
  ];

  protected $casts = [
    'templates_ids' => 'json',
  ];

  protected $links = [
    'user_id' => User::class,
    'templates_ids' => Template::class,
    'wallet_id' => Wallet::class,
  ];
}
