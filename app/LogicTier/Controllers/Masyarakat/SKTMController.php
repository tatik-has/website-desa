<?php

namespace App\LogicTier\Controllers\Masyarakat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

//  PERBAIKAN: Gunakan SuratKtmService
use App\LogicTier\Services\SuratKtmService;

class SKTMController extends BaseController
{
    protected $ktmService;

    //  PERBAIKAN: Injeksi SuratKtmService
    public function __construct(SuratKtmService $service)
    {
        $this->ktmService = $service;
    }

    public function create()
    {
        return view('presentation_tier.masyarakat.permohonan.ktm');
    }

    public function store(Request $request)
    {
        // Panggil method dari service yang benar
        $this->ktmService->storeKtm($request);

        return redirect()->route('dashboard')->with('success', 'Permohonan Surat Keterangan Tidak Mampu berhasil diajukan!');
    }
}