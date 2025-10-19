<?php
namespace App\DataTier\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'nik',
        'desa',
        'alamat',
        'email',
        'password',
        'verification_code',
        'is_verified',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'verification_code',
    ];
}
