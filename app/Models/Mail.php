<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Linking;

class Mail extends Model
{
  use HasFactory, Linking;

  protected $fillable = [
    'title',
    'template_id',
    'data',
    'targets',
    'status',
  ];

  protected $casts = [
    'data' => 'json',
    'targets' => 'json',
  ];

  protected $links = [
    'template_id' => Template::class,
  ];
}
