<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Linking;

class Wallet extends Model
{
  use HasFactory, Linking;

  protected $fillable = [
    'user_id',
    'user_model',
    'balance',
    'out_balance',
    'in_balance',
    'status',
  ];

  protected $casts = [
    'balance' => 'double',
    'out_balance' => 'double',
    'in_balance' => 'double',
  ];

  protected $links = [
    'user_id' => '->user_model',
  ];
}
