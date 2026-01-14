<?php

namespace App\LogicTier\Services;

use App\DataTier\Models\User;
use App\DataTier\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    public function registerUser(array $data)
    {
        $verificationCode = rand(100000, 999999);

        $user = User::create([
            'name' => $data['name'],
            'nik' => $data['nik'],
            'desa' => $data['desa'],
            'alamat' => $data['alamat'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'verification_code' => $verificationCode,
            'is_verified' => false,
        ]);

        Mail::raw("Halo, {$user->name}!\n\nKode verifikasi akun Anda adalah: $verificationCode", function ($message) use ($user) {
            $message->to($user->email)->subject('Verifikasi Akun Anda');
        });

        return $user;
    }

    public function attemptLogin(array $credentials)
    {
        // Cek Admin
        $admin = Admin::where('email', $credentials['email'])->first();
        if ($admin && Hash::check($credentials['password'], $admin->password)) {
            return ['user' => $admin, 'role' => 'admin', 'guard' => 'admin'];
        }

        // Cek User
        $user = User::where('email', $credentials['email'])->first();
        if ($user && Hash::check($credentials['password'], $user->password)) {
            return ['user' => $user, 'role' => 'user', 'guard' => 'web'];
        }

        return null;
    }
}