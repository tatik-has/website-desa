<?php

namespace App\LogicTier\Controllers\Masyarakat;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use App\DataTier\Models\Surat;
use App\LogicTier\Services\SuratDomisiliService; // Pastikan service ini punya method getHistory

class MasyarakatDashboardController extends BaseController
{
    protected $suratService;

    public function __construct(SuratDomisiliService $service)
    {
        $this->suratService = $service;
    }

    public function index()
    {
        // Menampilkan data ringkasan di dashboard
        $surats = Surat::where('user_id', Auth::id())->get();
        return view('presentation_tier.dashboard', compact('surats'));
    }

    public function history()
    {
        $userId = Auth::id();
        // Memanggil logika dari Service untuk mengambil riwayat
        $allPermohonan = $this->suratService->getHistory($userId);

        return view('presentation_tier.history', compact('allPermohonan'));
    }
}