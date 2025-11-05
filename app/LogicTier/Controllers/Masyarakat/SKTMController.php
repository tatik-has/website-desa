<?php

namespace App\LogicTier\Controllers\Masyarakat;

use Illuminate\Http\Request;
// HAPUS 'USE' STORAGE
use App\Http\Controllers\Controller as BaseController;
// HAPUS 'USE' MODEL
// HAPUS 'USE' EVENT
use Illuminate\Support\Facades\Auth; // Auth masih perlu

// 1. PANGGIL "PEKERJA" (SERVICE) KITA
use App\LogicTier\Services\PermohonanMasyarakatService;

class SKTMController extends BaseController
{
    // 2. Siapkan variabel service
    protected $permohonanService;

    // 3. Buat __construct
    public function __construct(PermohonanMasyarakatService $service)
    {
        $this->permohonanService = $service;
    }

    public function create()
    {
        return view('presentation_tier.masyarakat.permohonan.ktm');
    }

    /**
     * Method store sekarang RAMPING
     */
    public function store(Request $request)
    {
        // 1. Suruh service bekerja (validasi, upload, save, event)
        $this->permohonanService->storeKtm($request);

        // 2. Kasih respon (Tugas Mandor)
        // (Ini pesan sukses dari kode asli SKU/Domisili Anda, saya paskan)
        return redirect()->route('dashboard')->with('success', 'Permohonan Surat Keterangan Tidak Mampu berhasil diajukan!');
    }
}