<?php

namespace App\LogicTier\Controllers;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

// Model utama surat
use App\DataTier\Models\Surat;

// Model tambahan untuk tiap jenis surat
use App\DataTier\Models\PermohonanDomisili;
use App\DataTier\Models\PermohonanKTM;
use App\DataTier\Models\PermohonanSKU;

// === TAMBAHAN UNTUK NOTIFIKASI ===
// Pastikan path ke Model Admin Anda sudah benar
use App\DataTier\Models\Admin; 
// Pastikan Anda sudah membuat file Notifikasi ini
use App\LogicTier\Notifications\SuratBaruNotification; 
use Illuminate\Support\Facades\Notification; // Import fasad Notifikasi
use Illuminate\Support\Facades\Log; // Untuk mencatat error jika notifikasi gagal
// === AKHIR TAMBAHAN ===


class SuratController extends BaseController
{
    /**
     * Menampilkan halaman dashboard berisi daftar surat milik user yang sedang login.
     */
    public function index()
    {
        $surats = Surat::where('user_id', Auth::id())->get();
        return view('presentation_tier.dashboard', compact('surats'));
    }

    /**
     * Menampilkan halaman untuk memilih jenis surat yang ingin diajukan.
     */
    public function showPengajuanForm()
    {
        return view('presentation_tier.auth.pengajuan');
    }

    /**
     * Menangani proses pengajuan surat umum (bukan surat domisili).
     */
    public function ajukan($jenis): RedirectResponse
    {
        $user = Auth::user();

        // Buat surat terlebih dahulu agar kita bisa mendapatkan ID-nya
        $surat = Surat::create([
            'user_id' => $user->id,
            'nama_pemohon' => $user->name,
            'jenis_surat' => $jenis,
            'keterangan' => 'Permohonan sedang diproses',
        ]);

        // === TAMBAHAN UNTUK NOTIFIKASI ADMIN ===
        try {
            $admins = Admin::all();

            if ($admins->isNotEmpty()) {
                // Kita kirim objek $surat dan $user agar data notifikasi bisa lengkap
                // File SuratBaruNotification akan kita buat di langkah selanjutnya
                Notification::send($admins, new SuratBaruNotification($surat, $user));
            }

        } catch (\Exception $e) {
            Log::error('Gagal mengirim notifikasi admin: ' . $e->getMessage());
        }
        // === AKHIR TAMBAHAN ===

        return redirect('/dashboard')->with('success', 'Surat berhasil diajukan!');
    }


    /**
     * Menampilkan riwayat semua permohonan user.
     */
    public function history()
    {
        $userId = Auth::id();

        $domisili = PermohonanDomisili::where('user_id', $userId)->latest()->get();
        $ktm = PermohonanKTM::where('user_id', $userId)->latest()->get();
        $sku = PermohonanSKU::where('user_id', $userId)->latest()->get();

        // Gabungkan semua permohonan dan tambahkan label jenis surat
        $allPermohonan = collect()
            ->merge($domisili->map(function ($item) {
                $item->jenis_surat = 'Keterangan Domisili';
                return $item;
            }))
            ->merge($ktm->map(function ($item) {
                $item->jenis_surat = 'Keterangan Tidak Mampu';
                return $item;
            }))
            ->merge($sku->map(function ($item) {
                $item->jenis_surat = 'Keterangan Usaha';
                return $item;
            }))
            ->sortByDesc('created_at');

        return view('presentation_tier.history', compact('allPermohonan'));
    }
}
