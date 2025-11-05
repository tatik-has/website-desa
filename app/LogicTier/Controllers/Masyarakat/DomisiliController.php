<?php

namespace App\LogicTier\Controllers\Masyarakat;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Request;
// HAPUS 'USE' MODEL
use Illuminate\Support\Facades\Auth; // Auth masih perlu
use Illuminate\Http\RedirectResponse;
// HAPUS 'USE' EVENT
// HAPUS 'USE' NOTIFIKASI

// 1. PANGGIL "PEKERJA" (SERVICE) KITA
use App\LogicTier\Services\PermohonanMasyarakatService;


class DomisiliController extends BaseController
{
    // 2. Siapkan variabel service
    protected $permohonanService;

    // 3. Buat __construct
    public function __construct(PermohonanMasyarakatService $service)
    {
        $this->permohonanService = $service;
    }

    public function showForm()
    {
        return view('presentation_tier.masyarakat.permohonan.domisili');
    }

    /**
     * Method store sekarang RAMPING
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Suruh service bekerja (validasi, upload, save, event)
        $this->permohonanService->storeDomisili($request);

        // 2. Kasih respon (Tugas Mandor)
        return redirect()->route('dashboard')->with('success', 'Permohonan Surat Keterangan Domisili berhasil diajukan!');
    }
}