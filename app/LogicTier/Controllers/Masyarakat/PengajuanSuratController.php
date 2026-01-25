<?php

namespace App\LogicTier\Controllers\Masyarakat;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use App\LogicTier\Services\SuratDomisiliService; // Gunakan service untuk logika simpan

class PengajuanSuratController extends BaseController
{
    protected $suratService;

    public function __construct(SuratDomisiliService $service)
    {
        $this->suratService = $service;
    }

    public function showPengajuanForm()
    {
        return view('presentation_tier.masyarakat.permohonan.pengajuan');
    }

    public function ajukan($jenis): RedirectResponse
    {
        $user = Auth::user();

        // LOGIC TIER: Proses penyimpanan & notifikasi dipindahkan ke Service
        // Anda perlu membuat method 'prosesPengajuan' di SuratDomisiliService
        $this->suratService->prosesPengajuan($user, $jenis);

        return redirect('/dashboard')->with('success', 'Surat berhasil diajukan!');
    }
}