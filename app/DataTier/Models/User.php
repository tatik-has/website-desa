<?php
namespace App\DataTier\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'nik',
        'desa',
        'alamat',
        'email',
        'password',
        'verification_code',
        'is_verified',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
