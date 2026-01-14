<?php

namespace App\LogicTier\Services;

use App\DataTier\Models\PermohonanDomisili;
use App\DataTier\Models\PermohonanKTM;
use App\DataTier\Models\PermohonanSKU;
use Illuminate\Support\Carbon;

class AdminLaporanService
{
    /**
     * Logika dari getLaporanData
     */
    public function getLaporanData(string $tanggalMulai, string $tanggalAkhir, string $statusFilter)
    {
        $start = Carbon::parse($tanggalMulai)->startOfDay();
        $end = Carbon::parse($tanggalAkhir)->endOfDay();

        $domisiliQuery = PermohonanDomisili::with('user')->whereBetween('created_at', [$start, $end]);
        $ktmQuery = PermohonanKTM::with('user')->whereBetween('created_at', [$start, $end]);
        $skuQuery = PermohonanSKU::with('user')->whereBetween('created_at', [$start, $end]);

        if ($statusFilter !== 'semua') {
            $status = ucfirst(strtolower($statusFilter));
            $domisiliQuery->where('status', $status);
            $ktmQuery->where('status', $status);
            $skuQuery->where('status', $status);
        }

        $domisili = $domisiliQuery->get()->map(function ($item) {
            $item->jenis_surat_label = 'Keterangan Domisili';
            return $item;
        });

        $ktm = $ktmQuery->get()->map(function ($item) {
            $item->jenis_surat_label = 'Keterangan Tidak Mampu';
            return $item;
        });

        $sku = $skuQuery->get()->map(function ($item) {
            $item->jenis_surat_label = 'Keterangan Usaha';
            return $item;
        });

        return collect()->merge($domisili)->merge($ktm)->merge($sku)->sortByDesc('created_at');
    }

    /**
     * Logika dari getArchivedPermohonan
     */
    public function getArchivedPermohonan(): array
    {
        $domisili = PermohonanDomisili::with('user')->whereNotNull('archived_at')->latest('archived_at')->get();
        $ktm = PermohonanKTM::with('user')->whereNotNull('archived_at')->latest('archived_at')->get();
        $sku = PermohonanSKU::with('user')->whereNotNull('archived_at')->latest('archived_at')->get();

        return compact('domisili', 'ktm', 'sku');
    }
}