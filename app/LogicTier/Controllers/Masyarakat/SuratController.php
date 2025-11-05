<?php

namespace App\LogicTier\Controllers\Masyarakat;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

// Model Surat masih perlu untuk method 'index' dan 'ajukan'
use App\DataTier\Models\Surat;

// HAPUS 'USE' MODEL PERMOHONAN (DOMISILI, KTM, SKU)

// === TAMBAHAN UNTUK NOTIFIKASI ===
// Ini semua masih perlu untuk method 'ajukan'
use App\DataTier\Models\Admin; 
use App\LogicTier\Notifications\SuratBaruNotification; 
use Illuminate\Support\Facades\Notification; 
use Illuminate\Support\Facades\Log; 

// 1. PANGGIL "PEKERJA" (SERVICE) KITA
use App\LogicTier\Services\PermohonanMasyarakatService;


class SuratController extends BaseController
{
    // 2. Siapkan variabel service
    protected $permohonanService;

    // 3. Buat __construct
    public function __construct(PermohonanMasyarakatService $service)
    {
        $this->permohonanService = $service;
    }

    /**
     * TIDAK BERUBAH. Ini method sederhana, tidak perlu service.
     */
    public function index()
    {
        $surats = Surat::where('user_id', Auth::id())->get();
        return view('presentation_tier.dashboard', compact('surats'));
    }

   
    /**
     * TIDAK BERUBAH. Ini method sederhana.
     */
    public function showPengajuanForm()
    {
        return view('presentation_tier.masyarakat.permohonan.pengajuan');
    }

    /**
     * TIDAK BERUBAH. Logika ini spesifik & sederhana.
     */
    public function ajukan($jenis): RedirectResponse
    {
        $user = Auth::user();

        $surat = Surat::create([
            'user_id' => $user->id,
            'nama_pemohon' => $user->name,
            'jenis_surat' => $jenis,
            'keterangan' => 'Permohonan sedang diproses',
        ]);

        try {
            $admins = Admin::all();
            if ($admins->isNotEmpty()) {
                Notification::send($admins, new SuratBaruNotification($surat, $user));
            }
        } catch (\Exception $e) {
            Log::error('Gagal mengirim notifikasi admin: ' . $e->getMessage());
        }

        return redirect('/dashboard')->with('success', 'Surat berhasil diajukan!');
    }


    /**
     * Method history sekarang RAMPING
     */
    public function history()
    {
        $userId = Auth::id();

        // 4. Suruh service bekerja
        $allPermohonan = $this->permohonanService->getHistory($userId);

        // 5. Kirim ke view
        return view('presentation_tier.history', compact('allPermohonan'));
    }
}