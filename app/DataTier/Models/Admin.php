<?php

namespace App\DataTier\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $table = 'admins';

    protected $fillable = [
        'nama', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
}
