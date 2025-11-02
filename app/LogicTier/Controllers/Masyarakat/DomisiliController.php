<?php

namespace App\LogicTier\Controllers\Masyarakat;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Request;
use App\DataTier\Models\PermohonanDomisili;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\LogicTier\Events\SuratDiajukan; // Import event

// // === TAMBAHAN UNTUK NOTIFIKASI ===
// use App\DataTier\Models\Admin;
// use App\Notifications\PengajuanMasukNotification;
// use Illuminate\Support\Facades\Notification;
// use Illuminate\Support\Facades\Log;
// // === AKHIR TAMBAHAN ===

class DomisiliController extends BaseController
{
   
    public function showForm()
    {
        return view('presentation_tier.masyarakat.permohonan.domisili');
    }

    /**
     * Menyimpan data pengajuan Surat Keterangan Domisili.
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Validasi data dari form
        $validated = $request->validate([
            'nik' => 'required|digits:16',
            'nama' => 'required|string|max:255',
            'alamat_domisili' => 'required|string',
            'nomor_telp' => 'required|string',
            'rt_domisili' => 'required|numeric',
            'rw_domisili' => 'required|numeric',
            'jenis_kelamin' => 'required|string',
            'alamat_ktp' => 'required|string',
            'ktp' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'kk' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // 2. Simpan file ke storage
        $pathKTP = $request->file('ktp')->store('public/dokumen/ktp');
        $pathKK = $request->file('kk')->store('public/dokumen/kk');

        // 3. Simpan ke tabel permohonan_domisili
        $permohonan = PermohonanDomisili::create([
            'user_id' => Auth::id(),
            'nik' => $validated['nik'],
            'nama' => $validated['nama'],
            'alamat_domisili' => $validated['alamat_domisili'],
            'nomor_telp' => $validated['nomor_telp'],
            'rt_domisili' => $validated['rt_domisili'],
            'rw_domisili' => $validated['rw_domisili'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'alamat_ktp' => $validated['alamat_ktp'],
            'path_ktp' => $pathKTP,
            'path_kk' => $pathKK,
            'status' => 'Diproses',
        ]);

        // 4. PICU EVENT SETELAH DATA BERHASIL DIBUAT
        event(new SuratDiajukan(
            $permohonan,
            'Keterangan Domisili'
        ));


        // 5. Kembalikan ke dashboard
        return redirect()->route('dashboard')->with('success', 'Permohonan Surat Keterangan Domisili berhasil diajukan!');
    }
}
