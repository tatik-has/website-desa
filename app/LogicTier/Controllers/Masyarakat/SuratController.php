<?php

namespace App\LogicTier\Controllers\Masyarakat;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
// Import Model dan Notification dihapus dari sini karena dipindahkan ke Service
use App\LogicTier\Services\SuratDomisiliService;

class SuratController extends BaseController
{
    protected $suratService;

    public function __construct(SuratDomisiliService $service)
    {
        $this->suratService = $service;
    }

    /**
     * Menampilkan dashboard masyarakat
     */
    public function index()
    {
        // Tetap di sini karena hanya mengambil data untuk Presentation Tier
        $surats = \App\DataTier\Models\Surat::where('user_id', Auth::id())->get();
        return view('presentation_tier.dashboard', compact('surats'));
    }

    public function showPengajuanForm()
    {
        return view('presentation_tier.masyarakat.permohonan.pengajuan');
    }

    /**
     * Proses pengajuan surat umum - SEKARANG RAMPING
     */
    public function ajukan($jenis): RedirectResponse
    {
        $user = Auth::user();

        // LOGIKA PINDAH KE SERVICE:
        // Pindahkan Surat::create dan Notification::send ke dalam method ini di Service
        $this->suratService->prosesPengajuanUmum($user, $jenis);

        return redirect('/dashboard')->with('success', 'Surat berhasil diajukan!');
    }

    public function history()
    {
        $userId = Auth::id();
        $allPermohonan = $this->suratService->getHistory($userId);

        return view('presentation_tier.history', compact('allPermohonan'));
    }
}