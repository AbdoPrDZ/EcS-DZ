<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\NewAccessToken;
use App\Models\Traits\Linking;
use DateTimeInterface;
use Str;

class Admin extends Authenticatable
{
  use HasApiTokens, HasFactory, Notifiable, Linking;

  public $links = [
    'user_id' => User::class,
    'wallet_id' => Wallet::class,
  ];

  protected $fillable = [
    'user_id',
    'username',
    'roles',
    'permissions',
    'wallet_id',
    'password',
    'status',
  ];

  protected $hidden = [
    'password',
    'remember_token',
  ];

  protected $casts = [
    'roles' => 'json',
    'permissions' => 'json',
    'password' => 'hashed',
  ];

  /**
   * Create a new personal access token for the user.
   *
   * @param  string  $name
   * @param  array  $abilities
   * @param  \DateTimeInterface|null  $expiresAt
   * @return \Laravel\Sanctum\NewAccessToken
   */
  public function createToken(string $name, array $abilities = ['*'], array $data = [], DateTimeInterface $expiresAt = null) {
    $plainTextToken = sprintf(
      '%s%s%s',
      config('sanctum.token_prefix', ''),
      $tokenEntropy = Str::random(40),
      hash('crc32b', $tokenEntropy)
    );

    $token = $this->tokens()->create([
      'name' => $name,
      'token' => hash('sha256', $plainTextToken),
      'abilities' => $abilities,
      'data' => $data,
      'expires_at' => $expiresAt,
    ]);

    return new NewAccessToken($token, $token->getKey().'|'.$plainTextToken);
  }
}
