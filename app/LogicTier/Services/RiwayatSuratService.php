<?php

namespace App\LogicTier\Services;

use App\DataTier\Models\PermohonanDomisili;
use App\DataTier\Models\PermohonanKTM;
use App\DataTier\Models\PermohonanSKU;
use Illuminate\Support\Facades\Auth;

class RiwayatSuratService
{
    public function getRiwayatByUser()
    {
        $userId = Auth::id();

        // Ambil data dari tabel PermohonanDomisili
        $domisili = PermohonanDomisili::where('user_id', $userId)
            ->get()
            ->map(function($item) {
                $item->jenis_surat = 'Surat Keterangan Domisili';
                $item->type = 'domisili';
                return $item;
            });

        // Ambil data dari tabel PermohonanKTM
        $ktm = PermohonanKTM::where('user_id', $userId)
            ->get()
            ->map(function($item) {
                $item->jenis_surat = 'Surat Keterangan Tidak Mampu (SKTM)';
                $item->type = 'ktm';
                return $item;
            });

        // Ambil data dari tabel PermohonanSKU
        $sku = PermohonanSKU::where('user_id', $userId)
            ->get()
            ->map(function($item) {
                $item->jenis_surat = 'Surat Keterangan Usaha (SKU)';
                $item->type = 'sku';
                return $item;
            });

        // Gabungkan semua koleksi dan urutkan berdasarkan tanggal terbaru
        return $domisili->concat($ktm)->concat($sku)->sortByDesc('created_at');
    }
}