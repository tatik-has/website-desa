<?php

namespace App\LogicTier\Controllers;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Request;
use App\DataTier\Models\User;
use App\DataTier\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class AuthController extends BaseController
{
    // ==========================
    // 🧩 REGISTER
    // ==========================
    public function showRegister()
    {
        return view('presentation_tier.auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nik' => 'required|string|size:16|unique:users',
            'desa' => 'required|string|max:255',
            'alamat' => 'required|string',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
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

        try {
            Mail::raw("Halo, {$user->name}!\n\nKode verifikasi akun Anda adalah: $verificationCode", function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Verifikasi Akun Anda');
            });
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengirim email verifikasi. Cek konfigurasi mail Anda.');
        }

        $request->session()->put('email_for_verification', $user->email);

        return redirect()->route('verify.form')->with('success', 'Registrasi berhasil! Cek email Anda untuk kode verifikasi.');
    }

    // ==========================
    // 🔐 LOGIN
    // ==========================
    public function showLogin()
    {
        return view('presentation_tier.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 🔍 Cek apakah login sebagai ADMIN
        $admin = Admin::where('email', $credentials['email'])->first();
        if ($admin && Hash::check($credentials['password'], $admin->password)) {
            Auth::guard('admin')->login($admin);
            $request->session()->regenerate();

            // ✅ Nama admin diambil dari kolom 'nama' tabel admins
            session(['user_name' => $admin->nama, 'user_role' => 'admin']);

            return redirect()->intended('/admin/dashboard')->with('success', 'Selamat datang, ' . $admin->nama . '!');
        }

        // 🔍 Kalau bukan admin, cek USER biasa
        $user = User::where('email', $credentials['email'])->first();
        if ($user && Hash::check($credentials['password'], $user->password)) {
            if (!$user->is_verified) {
                $request->session()->put('email_for_verification', $user->email);
                return redirect()->route('verify.form')->with('error', 'Akun Anda belum terverifikasi. Silakan masukkan kode.');
            }

            Auth::guard('web')->login($user);
            $request->session()->regenerate();

            // ✅ Nama user diambil dari kolom 'name' tabel users
            session(['user_name' => $user->name, 'user_role' => 'user']);

            return redirect()->intended('/dashboard')->with('success', 'Selamat datang, ' . $user->name . '!');
        }

        return back()->with('error', 'Email atau password salah.');
    }

    // ==========================
    // 🚪 LOGOUT
    // ==========================
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Hapus session custom juga
        session()->forget(['user_name', 'user_role']);

        return redirect('/login');
    }
}
