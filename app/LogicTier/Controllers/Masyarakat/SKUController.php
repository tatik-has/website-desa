<?php

namespace App\LogicTier\Controllers\Masyarakat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as BaseController;
// HAPUS 'USE' MODEL
use Illuminate\Support\Facades\Auth; // Auth masih perlu
// HAPUS 'USE' EVENT

// 1. PANGGIL "PEKERJA" (SERVICE) KITA
use App\LogicTier\Services\PermohonanMasyarakatService;

class SKUController extends BaseController
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
        return view('presentation_tier.masyarakat.permohonan.usaha');
    }

    /**
     * Method store sekarang RAMPING
     */
    public function store(Request $request)
    {
        // 1. Suruh service bekerja (validasi, upload, save, event)
        $this-permohonanService->storeSku($request);

        // 2. Kasih respon (Tugas Mandor)
        // (Ini pesan sukses dari kode asli SKU/Domisili Anda, saya paskan)
        return redirect()->route('dashboard')->with('success', 'Permohonan Surat Keterangan Usaha berhasil diajukan!');
    }
}