<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Linking;

class User extends Model
{
  use HasFactory;

  protected $fillable = [
    'first_name',
    'last_name',
    'email',
    'email_verified_at',
    'phone',
    'address',
    'gander',
    'password',
    'status',
  ];

  protected $casts = [
    'email_verified_at' => 'datetime',
  ];
}
