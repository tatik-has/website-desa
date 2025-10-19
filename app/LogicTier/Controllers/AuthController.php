<?php

namespace App\LogicTier\Controllers;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Request;
use App\DataTier\Models\User;
use App\DataTier\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends BaseController
{
    // Tampilkan halaman login
    public function showLogin()
    {
        return view('presentation_tier.auth.login');
    }

    // Proses login untuk admin & user
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // 1Coba login sebagai admin dengan guard admin
        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->intended('/admin/dashboard')->with('success', 'Selamat datang, Admin!');
        }


        // 2Kalau bukan admin, cek di tabel user
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if (!$user->is_verified && !$user->email_verified_at) {
                Auth::logout();
                return redirect('/verify')
                    ->with('error', 'Akun baru harus diverifikasi terlebih dahulu. Silakan cek email Anda.');
            }

            return redirect('/dashboard');
        }

        return back()->with('error', 'Login gagal, periksa email dan password Anda!');
    }

    // Tampilkan halaman register (khusus user)
    public function showRegister()
    {
        return view('presentation_tier.auth.register');
    }

    // Proses registrasi user baru
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nik' => 'required|digits:16|unique:users,nik',
            'desa' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6'
        ]);

        $verificationCode = rand(100000, 999999);

        $user = User::create([
            'name' => $request->name,
            'nik' => $request->nik,
            'desa' => $request->desa,
            'alamat' => $request->alamat,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'verification_code' => $verificationCode,
            'is_verified' => false,
        ]);

        // Kirim email verifikasi
        Mail::raw("Kode verifikasi Anda adalah: $verificationCode", function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Verifikasi Akun Anda');
        });

        return redirect('/verify')->with('success', 'Registrasi berhasil! Silakan cek email untuk kode verifikasi.');
    }

    public function logout()
    {
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
            return redirect('/login')->with('success', 'Admin berhasil logout.');
        }

        if (Auth::check()) {
            Auth::logout();
            return redirect('/login')->with('success', 'Berhasil logout.');
        }

        return redirect('/login');
    }

}
