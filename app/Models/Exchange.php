<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Linking;

class Exchange extends Model
{
  use HasFactory, Linking;

  protected $fillable = [
    'name',
    'from_wallet_id',
    'to_wallet_id',
    'balance',
    'status',
  ];

  protected $casts = [
    'balance' => 'double',
  ];

  protected $links = [
    'from_wallet_id' => Wallet::class,
    'to_wallet_id' => Wallet::class,
  ];
}
