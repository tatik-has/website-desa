<?php

namespace App\LogicTier\Services;

use App\DataTier\Models\PermohonanDomisili;
use App\DataTier\Models\PermohonanKTM;
use App\DataTier\Models\PermohonanSKU;
use App\DataTier\Models\User;
use App\DataTier\Models\Admin;
use Illuminate\Support\Carbon;

class AdminDashboardService
{
    /**
     * Logika dari getDashboardSummary
     */
    public function getDashboardSummary(): array
    {
        $totalDiproses = PermohonanDomisili::where('status', 'Diproses')->count()
            + PermohonanKTM::where('status', 'Diproses')->count()
            + PermohonanSKU::where('status', 'Diproses')->count();

        $totalSelesai = PermohonanDomisili::where('status', 'Selesai')->count()
            + PermohonanKTM::where('status', 'Selesai')->count()
            + PermohonanSKU::where('status', 'Selesai')->count();

        $totalDitolak = PermohonanDomisili::where('status', 'Ditolak')->count()
            + PermohonanKTM::where('status', 'Ditolak')->count()
            + PermohonanSKU::where('status', 'Ditolak')->count();

        return [
            'totalDiproses' => $totalDiproses,
            'totalSelesai' => $totalSelesai,
            'totalDitolak' => $totalDitolak
        ];
    }

    /**
     * Logika dari getDashboardAdditionalData
     */
    public function getDashboardAdditionalData(): array
    {
        $recentPermohonan = collect();

        $domisiliRecent = PermohonanDomisili::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function($item) {
                $item->jenis_surat = 'Surat Domisili';
                return $item;
            });

        $ktmRecent = PermohonanKTM::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function($item) {
                $item->jenis_surat = 'Surat Keterangan Tidak Mampu';
                return $item;
            });

        $skuRecent = PermohonanSKU::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function($item) {
                $item->jenis_surat = 'Surat Keterangan Usaha';
                return $item;
            });

        $recentPermohonan = $domisiliRecent
            ->merge($ktmRecent)
            ->merge($skuRecent)
            ->sortByDesc('created_at')
            ->take(5);

        $totalUsers = User::count();
        $totalAdmins = Admin::count();
        
        $todayPermohonan = PermohonanDomisili::whereDate('created_at', Carbon::today())->count()
            + PermohonanKTM::whereDate('created_at', Carbon::today())->count()
            + PermohonanSKU::whereDate('created_at', Carbon::today())->count();

        $totalArsip = PermohonanDomisili::whereNotNull('archived_at')->count()
            + PermohonanKTM::whereNotNull('archived_at')->count()
            + PermohonanSKU::whereNotNull('archived_at')->count();

        return [
            'recentPermohonan' => $recentPermohonan,
            'totalUsers' => $totalUsers,
            'totalAdmins' => $totalAdmins,
            'todayPermohonan' => $todayPermohonan,
            'totalArsip' => $totalArsip,
        ];
    }
}