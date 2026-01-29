<?php

namespace App\LogicTier\Controllers\Masyarakat;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

//  Gunakan SuratDomisiliService
use App\LogicTier\Services\SuratDomisiliService;

class DomisiliController extends BaseController
{
    protected $domisiliService;

    //  Injeksi SuratDomisiliService
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
        try {
            // Logika simpan di Service
            $this->domisiliService->storeDomisili($request);

            // Kirim pesan sukses dengan with() untuk session flash
            return redirect()->route('dashboard')
                ->with('success', 'Pengajuan Surat Keterangan Domisili berhasil diajukan! Admin akan segera memproses permohonan Anda.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}