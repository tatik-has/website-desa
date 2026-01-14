<?php

namespace App\LogicTier\Controllers\Masyarakat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

// ✅ PERBAIKAN: Gunakan SuratSkuService
use App\LogicTier\Services\SuratSkuService;

class SKUController extends BaseController
{
    protected $skuService;

    // ✅ PERBAIKAN: Injeksi SuratSkuService
    public function __construct(SuratSkuService $service)
    {
        $this->skuService = $service;
    }

    public function create()
    {
        return view('presentation_tier.masyarakat.permohonan.usaha');
    }

    public function store(Request $request)
    {
        // ✅ Panggil method dari service yang benar
        $this->skuService->storeSku($request);

        return redirect()->route('dashboard')->with('success', 'Permohonan Surat Keterangan Usaha berhasil diajukan!');
    }
}