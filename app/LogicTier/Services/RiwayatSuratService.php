<?php

namespace App\LogicTier\Services;

use App\DataTier\Models\Surat;
use Illuminate\Support\Facades\Auth;

class RiwayatSuratService
{
    public function getRiwayatByUser()
    {
        return Surat::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
