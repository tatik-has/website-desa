<?php

namespace App\LogicTier\Controllers\Masyarakat;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Request;
use App\LogicTier\Services\AuthService;
use Illuminate\Support\Facades\Auth;

class AuthController extends BaseController
{
    protected $authService;

    // Injeksi AuthService ke dalam Controller
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Tampilkan halaman login (Method yang tadinya error/hilang)
     */
    public function showLogin()
    {
        return view('presentation_tier.masyarakat.auth.login');
    }

    /**
     * Tampilkan halaman register
     */
    public function showRegister()
    {
        return view('presentation_tier.masyarakat.auth.register');
    }

    /**
     * Proses Login menggunakan Service
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Panggil logika login dari Logic Tier (Service)
        $result = $this->authService->attemptLogin($credentials);

        if (!$result) {
            return back()->with('error', 'Email atau password salah.');
        }

        $user = $result['user'];

        // Cek verifikasi jika yang login adalah user masyarakat
        if ($result['role'] === 'user' && !$user->is_verified) {
            $request->session()->put('email_for_verification', $user->email);
            return redirect()->route('verify.form')->with('error', 'Akun Anda belum terverifikasi.');
        }

        // Login menggunakan Guard yang sesuai (admin atau web)
        Auth::guard($result['guard'])->login($user);
        $request->session()->regenerate();

        // Simpan data ke session untuk tampilan header/dashboard
        session([
            'user_name' => ($result['role'] === 'admin') ? $user->nama : $user->name,
            'user_role' => $result['role']
        ]);
        
        // Logika pengalihan ke dashboard masyarakat (/dashboard) jika role adalah user
        return ($result['role'] === 'admin') 
            ? redirect()->intended('/admin/dashboard') 
            : redirect()->intended('/dashboard');
    }

    /**
     * Proses Registrasi menggunakan Service
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nik' => 'required|string|size:16|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        try {
            // Jalankan logika registrasi di Service
            $user = $this->authService->registerUser($request->all());
            
            $request->session()->put('email_for_verification', $user->email);
            return redirect()->route('verify.form')->with('success', 'Registrasi berhasil! Cek email untuk kode verifikasi.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengirim email verifikasi. Cek koneksi internet Anda.');
        }
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        session()->forget(['user_name', 'user_role']);

        return redirect('/login');
    }
}