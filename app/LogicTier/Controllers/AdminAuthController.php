<?php

namespace App\LogicTier\Controllers;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\DataTier\Models\Admin;

class AdminAuthController extends BaseController
{
    /**
     * Tampilkan halaman login admin
     */
    public function showLogin()
    {
        // 1. Cek apakah admin SUDAH login
        if (Auth::guard('admin')->check()) {
            
            // 2. Jika sudah, REDIRECT (alihkan) ke route dashboard.
            // Biarkan AdminController yang mengurus tampilan dashboard.
            return redirect()->route('admin.dashboard'); 
            // atau return redirect('/admin/dashboard');
        }

        // 3. Jika BELUM login, tampilkan view LOGIN
        // (Pastikan nama file view-nya benar)
        return view('presentation_tier.auth.login'); 
    }
   
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $admin = Admin::where('email', $request->email)->first();

        if ($admin && Hash::check($request->password, $admin->password)) {
            Auth::guard('admin')->login($admin);
            $request->session()->regenerate(); // <--- ini penting

            return redirect()->intended('/admin/dashboard');
        }

        return back()->withInput()->withErrors([
            'email' => 'Email atau password salah.',
        ]);

    }

    /**
     * Logout admin
     */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin/login');
    }
}
