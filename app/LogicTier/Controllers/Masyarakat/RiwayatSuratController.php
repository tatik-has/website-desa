<?php

namespace App\LogicTier\Controllers\Masyarakat;

use App\Http\Controllers\Controller;
use App\LogicTier\Services\RiwayatSuratService;

class RiwayatSuratController extends Controller
{
    protected $riwayatService;

    public function __construct(RiwayatSuratService $riwayatService)
    {
        $this->riwayatService = $riwayatService;
    }

    public function index()
    {
        $riwayat = $this->riwayatService->getRiwayatByUser();

        return view('presentation_tier.masyarakat.riwayat.index', compact('riwayat'));
    }
}
