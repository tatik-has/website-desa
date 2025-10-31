<?php

namespace App\DataTier\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $table = 'admins';

    protected $fillable = [
        'nama', 'email', 'password', 'role', // Tambahkan role
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $attributes = [
        'role' => 'admin', // Default role adalah admin biasa
    ];
}