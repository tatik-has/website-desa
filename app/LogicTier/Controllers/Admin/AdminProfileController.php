<?php
// File: app/LogicTier/Controllers/Admin/AdminProfileController.php

namespace App\LogicTier\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdminProfileController extends Controller
{
    /**
     * Menampilkan halaman profil admin (read-only).
     */
    public function show()
    {
        $admin = Auth::guard('admin')->user();
        return view('presentation_tier.admin.profile.show', compact('admin'));
    }
}