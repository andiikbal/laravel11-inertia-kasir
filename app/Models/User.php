<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends Authenticatable
{
  use HasFactory, Notifiable, HasRoles;

  protected $fillable = [
    'photo',
    'name',
    'email',
    'password',
  ];

  protected $hidden = [
    'password',
    'remember_token',
    'two_factor_secret',
    'two_factor_recovery_codes',
    'two_factor_confirmed_at'
  ];

  protected function casts(): array
  {
    return [
      'email_verified_at' => 'datetime',
      'password' => 'hashed',
    ];
  }

  public function getPermissionArray()
  {
    return $this->getAllPermissions()->mapWithKeys(function ($pr) {
      return [$pr['name'] => true];
    });
  }

  // Accessor
  protected function photo(): Attribute
  {
    return Attribute::make(
      get: fn($value) => url('/storage/users/' . $value),
    );
  }
}
