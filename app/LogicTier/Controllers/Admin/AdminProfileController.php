<?php
// File: app/LogicTier/Controllers/Admin/AdminProfileController.php

namespace App\LogicTier\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class AdminProfileController extends Controller
{
    /**
     * Menampilkan halaman profil admin.
     */
    public function show()
    {
        $admin = Auth::guard('admin')->user();
        return view('presentation_tier.admin.profile.show', compact('admin'));
    }

    /**
     * Memperbarui data profil admin.
     */
    public function update(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        // Validasi
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                // Pastikan email unik, KECUALI untuk admin ini sendiri
                Rule::unique('admins')->ignore($admin->id),
            ],
            'current_password' => 'nullable|string',
            'new_password' => 'nullable|string|min:8|confirmed',
        ]);

        // Update nama dan email
        $admin->name = $validated['name'];
        $admin->email = $validated['email'];

        // Logika untuk ganti password
        if ($request->filled('new_password')) {
            // Cek apakah password lama sesuai
            if (!Hash::check($request->current_password, $admin->password)) {
                return back()->withErrors([
                    'current_password' => 'Password lama yang Anda masukkan salah.'
                ])->withInput();
            }

            // Update password baru
            $admin->password = Hash::make($validated['new_password']);
        }

        $admin->save();

        return redirect()->route('admin.profile.show')
                         ->with('success', 'Profil berhasil diperbarui!');
    }
}