<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;

class PersonalAccessToken extends SanctumPersonalAccessToken {
  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
    'data' => 'json',
    'abilities' => 'json',
    'last_used_at' => 'datetime',
    'expires_at' => 'datetime',
  ];

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name',
    'token',
    'data',
    'abilities',
    'expires_at',
  ];
}

