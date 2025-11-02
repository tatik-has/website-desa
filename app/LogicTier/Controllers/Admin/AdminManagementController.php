<?php

namespace App\LogicTier\Controllers\Admin;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Request;
use App\DataTier\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Auth\Events\Registered;

class AdminManagementController extends BaseController
{
    /**
     * Tampilkan daftar admin (hanya superadmin)
     */
    public function index()
    {
        if (Auth::guard('admin')->user()->role !== 'superadmin') {
            abort(403, 'Akses ditolak. Hanya superadmin yang dapat mengakses halaman ini.');
        }

        $admins = Admin::orderBy('created_at', 'desc')->get();

        return view('presentation_tier.admin.manajemen-admin.index', compact('admins'));
    }

    /**
     * Tampilkan form tambah admin
     */
    public function create()
    {
        if (Auth::guard('admin')->user()->role !== 'superadmin') {
            abort(403, 'Akses ditolak.');
        }

        return view('presentation_tier.admin.manajemen-admin.create');
    }

    /**
     * Simpan admin baru
     */
    public function store(Request $request)
    {
        if (Auth::guard('admin')->user()->role !== 'superadmin') {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,superadmin',
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'role.required' => 'Role wajib dipilih.',
        ]);

        $admin = Admin::create([  // <--- TAMBAHKAN INI
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        event(new Registered($admin)); // <-- Sekarang $admin sudah ada

        // (Opsional) Ganti pesan suksesnya agar lebih jelas
        return redirect()->route('admin.manajemen-admin.index')
            ->with('success', 'Admin berhasil ditambahkan! Email verifikasi telah dikirim.');
    }

    /**
     * Tampilkan form edit admin
     */
    public function edit($id)
    {
        if (Auth::guard('admin')->user()->role !== 'superadmin') {
            abort(403, 'Akses ditolak.');
        }

        $admin = Admin::findOrFail($id);

        return view('presentation_tier.admin.manajemen-admin.edit', compact('admin'));
    }

    /**
     * Update data admin
     */
    public function update(Request $request, $id)
    {
        if (Auth::guard('admin')->user()->role !== 'superadmin') {
            abort(403, 'Akses ditolak.');
        }

        $admin = Admin::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('admins')->ignore($admin->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,superadmin',
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'role.required' => 'Role wajib dipilih.',
        ]);

        $admin->nama = $request->nama;
        $admin->email = $request->email;
        $admin->role = $request->role;

        // Update password hanya jika diisi
        if ($request->filled('password')) {
            $admin->password = Hash::make($request->password);
        }

        $admin->save();

        return redirect()->route('admin.manajemen-admin.index')
            ->with('success', 'Data admin berhasil diperbarui!');
    }

    /**
     * Hapus admin
     */
    public function destroy($id)
    {
        if (Auth::guard('admin')->user()->role !== 'superadmin') {
            abort(403, 'Akses ditolak.');
        }

        $admin = Admin::findOrFail($id);

        // Cegah menghapus akun sendiri
        if ($admin->id === Auth::guard('admin')->id()) {
            return redirect()->route('admin.manajemen-admin.index')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri!');
        }

        $admin->delete();

        return redirect()->route('admin.manajemen-admin.index')
            ->with('success', 'Admin berhasil dihapus!');
    }
}