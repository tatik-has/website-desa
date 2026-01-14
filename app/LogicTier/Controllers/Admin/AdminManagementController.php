<?php

namespace App\LogicTier\Controllers\Admin;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Request;
use App\DataTier\Models\Admin;
use App\LogicTier\Services\AdminManagementService;
use Illuminate\Validation\Rule;

class AdminManagementController extends BaseController
{
    protected $adminService;

    public function __construct(AdminManagementService $service)
    {
        $this->adminService = $service;
    }

    public function index()
    {
        $this->adminService->authorizeSuperadmin();
        $admins = $this->adminService->getAllAdmins();

        return view('presentation_tier.admin.manajemen-admin.index', compact('admins'));
    }

    public function create()
    {
        $this->adminService->authorizeSuperadmin();
        return view('presentation_tier.admin.manajemen-admin.create');
    }

    public function store(Request $request)
    {
        $this->adminService->authorizeSuperadmin();

        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,superadmin',
        ]);

        $this->adminService->storeAdmin($request->all());

        return redirect()->route('admin.manajemen-admin.index')
            ->with('success', 'Admin berhasil ditambahkan! Email verifikasi telah dikirim.');
    }

    public function edit($id)
    {
        $this->adminService->authorizeSuperadmin();
        $admin = Admin::findOrFail($id);

        return view('presentation_tier.admin.manajemen-admin.edit', compact('admin'));
    }

    public function update(Request $request, $id)
    {
        $this->adminService->authorizeSuperadmin();
        
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('admins')->ignore($id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,superadmin',
        ]);

        $this->adminService->updateAdmin($id, $request->all());

        return redirect()->route('admin.manajemen-admin.index')
            ->with('success', 'Data admin berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $this->adminService->authorizeSuperadmin();

        $result = $this->adminService->deleteAdmin($id);

        if ($result) {
            return redirect()->route('admin.manajemen-admin.index')
                ->with('success', 'Admin berhasil dihapus!');
        }

        return redirect()->route('admin.manajemen-admin.index')
            ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri!');
    }
}