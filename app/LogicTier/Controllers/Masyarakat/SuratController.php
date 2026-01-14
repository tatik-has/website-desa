<?php

namespace App\LogicTier\Controllers\Masyarakat;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

use App\DataTier\Models\Surat;
use App\DataTier\Models\Admin; 
use App\LogicTier\Notifications\SuratBaruNotification; 
use Illuminate\Support\Facades\Notification; 
use Illuminate\Support\Facades\Log; 

// ✅ PERBAIKAN: Gunakan salah satu service hasil pemisahan (misal DomisiliService) 
// atau arahkan ke service yang menangani pengambilan data history.
use App\LogicTier\Services\SuratDomisiliService;

class SuratController extends BaseController
{
    protected $suratService;

    // ✅ PERBAIKAN: Gunakan service yang tersedia
    public function __construct(SuratDomisiliService $service)
    {
        $this->suratService = $service;
    }

    /**
     * Menampilkan dashboard masyarakat
     */
    public function index()
    {
        $surats = Surat::where('user_id', Auth::id())->get();
        return view('presentation_tier.dashboard', compact('surats'));
    }

    public function showPengajuanForm()
    {
        return view('presentation_tier.masyarakat.permohonan.pengajuan');
    }

    /**
     * Proses pengajuan surat umum
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
     * Method history menggunakan logic dari Service
     */
    public function history()
    {
        $userId = Auth::id();

        // ✅ PERBAIKAN: Pastikan di SuratDomisiliService atau Service lainnya 
        // Anda sudah memindahkan method getHistory() dari PermohonanMasyarakatService yang lama.
        $allPermohonan = $this->suratService->getHistory($userId);

        return view('presentation_tier.history', compact('allPermohonan'));
    }
}