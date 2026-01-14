<?php

namespace App\LogicTier\Services;

use App\DataTier\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;

class AdminManagementService
{
    /**
     * Cek apakah user yang login adalah superadmin
     */
    public function authorizeSuperadmin()
    {
        if (Auth::guard('admin')->user()->role !== 'superadmin') {
            abort(403, 'Akses ditolak. Hanya superadmin yang dapat mengakses halaman ini.');
        }
    }

    /**
     * Ambil semua data admin
     */
    public function getAllAdmins()
    {
        return Admin::orderBy('created_at', 'desc')->get();
    }

    /**
     * Simpan admin baru
     */
    public function storeAdmin(array $data)
    {
        $admin = Admin::create([
            'nama' => $data['nama'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
        ]);

        event(new Registered($admin));

        return $admin;
    }

    /**
     * Update data admin
     */
    public function updateAdmin(int $id, array $data)
    {
        $admin = Admin::findOrFail($id);
        $admin->nama = $data['nama'];
        $admin->email = $data['email'];
        $admin->role = $data['role'];

        if (!empty($data['password'])) {
            $admin->password = Hash::make($data['password']);
        }

        $admin->save();
        return $admin;
    }

    /**
     * Hapus admin
     */
    public function deleteAdmin(int $id)
    {
        $admin = Admin::findOrFail($id);

        // Cegah menghapus akun sendiri
        if ($admin->id === Auth::guard('admin')->id()) {
            return false;
        }

        return $admin->delete();
    }
}