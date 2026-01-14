<?php
// File: app/LogicTier/Controllers/Masyarakat/ProfileController.php

namespace App\LogicTier\Controllers\Masyarakat;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman profil user (read-only).
     */
    public function show()
    {
        $user = Auth::user();
        return view('presentation_tier.masyarakat.profile.show', compact('user'));
    }
}