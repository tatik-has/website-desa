<?php

namespace App\LogicTier\Controllers\Masyarakat;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

// ✅ PERBAIKAN: Gunakan SuratDomisiliService
use App\LogicTier\Services\SuratDomisiliService;

class DomisiliController extends BaseController
{
    protected $domisiliService;

    // ✅ PERBAIKAN: Injeksi SuratDomisiliService
    public function __construct(SuratDomisiliService $service)
    {
        $this->domisiliService = $service;
    }

    public function showForm()
    {
        return view('presentation_tier.masyarakat.permohonan.domisili');
    }

    public function store(Request $request): RedirectResponse
    {
        // ✅ Panggil method dari service yang benar
        $this->domisiliService->storeDomisili($request);

        return redirect()->route('dashboard')->with('success', 'Permohonan Surat Keterangan Domisili berhasil diajukan!');
    }
}