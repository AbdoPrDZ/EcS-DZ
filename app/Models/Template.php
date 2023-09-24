<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Linking;

class Template extends Model
{
  use HasFactory, Linking;

  protected $fillable = [
    'programmer_id',
    'name',
    'content',
    'fields',
    'price',
    'is_mail_template',
  ];

  protected $casts = [
    'fields' => 'json',
    'price' => 'double',
    'is_mail_template' => 'boolean',
  ];

  protected $links = [
    'programmer_id' => Programmer::class,
  ];
}
