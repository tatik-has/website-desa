<?php

namespace App\LogicTier\Services;

// Panggil semua Model dari DataTier
use App\DataTier\Models\PermohonanDomisili;
use App\DataTier\Models\PermohonanKTM;
use App\DataTier\Models\PermohonanSKU;
use App\DataTier\Models\User;

// Panggil komponen lain yang dibutuhkan
use App\Notifications\SuratSelesaiNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;

class PermohonanAdminService
{
    /**
     * Helper privat untuk mendapatkan Class Model dari string
     */
    private function getModelClass(string $type)
    {
        return match ($type) {
            'domisili' => PermohonanDomisili::class,
            'ktm' => PermohonanKTM::class,
            'sku' => PermohonanSKU::class,
            default => null
        };
    }

    /**
     * Logika dari AdminController@index
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

        // Kembalikan sebagai array
        return [
            'totalDiproses' => $totalDiproses,
            'totalSelesai' => $totalSelesai,
            'totalDitolak' => $totalDitolak
        ];
    }

    /**
     * Logika dari AdminController@showPermohonanSurat
     * HANYA menampilkan permohonan yang BELUM diarsipkan
     */
    public function getGroupedPermohonan(): array
    {
        $domisiliGrouped = PermohonanDomisili::with('user')
            ->whereNull('archived_at') // ✅ Filter hanya yang belum diarsipkan
            ->latest()
            ->get()
            ->groupBy('status');

        $ktmGrouped = PermohonanKTM::with('user')
            ->whereNull('archived_at') // ✅ Filter hanya yang belum diarsipkan
            ->latest()
            ->get()
            ->groupBy('status');

        $skuGrouped = PermohonanSKU::with('user')
            ->whereNull('archived_at') // ✅ Filter hanya yang belum diarsipkan
            ->latest()
            ->get()
            ->groupBy('status');

        return compact('domisiliGrouped', 'ktmGrouped', 'skuGrouped');
    }

    /**
     * Logika dari AdminController@updateStatusPermohonan
     * (Pekerjaan beratnya)
     */
    public function updateStatus(Request $request, string $type, int $id)
    {
        $modelClass = $this->getModelClass($type);
        if (!$modelClass) {
            abort(404, 'Jenis permohonan tidak valid.');
        }

        $permohonan = $modelClass::findOrFail($id);
        $newStatus = ucfirst(strtolower($request->status));
        $permohonan->status = $newStatus;

        if ($newStatus == 'Ditolak') {
            $permohonan->keterangan_penolakan = $request->keterangan_penolakan;
            if ($permohonan->path_surat_jadi) {
                Storage::delete($permohonan->path_surat_jadi);
                $permohonan->path_surat_jadi = null;
            }
        } elseif ($newStatus == 'Selesai' && $request->hasFile('surat_jadi')) {
            if ($permohonan->path_surat_jadi) {
                Storage::delete($permohonan->path_surat_jadi);
            }
            $path = $request->file('surat_jadi')->store('public/surat_selesai');
            $permohonan->path_surat_jadi = $path;
            $permohonan->keterangan_penolakan = null;
        } else {
            $permohonan->keterangan_penolakan = null;
        }

        $permohonan->save();

        if (($permohonan->status == 'Selesai' || $permohonan->status == 'Ditolak') && $permohonan->user_id) {
            $user = User::find($permohonan->user_id);
            if ($user) {
                $user->notify(new SuratSelesaiNotification($permohonan));
            }
        }

        return true; // Selesai
    }

    /**
     * Logika dari AdminController@semuaPermohonan
     */
    public function getAllPermohonan(): array
    {
        $domisili = PermohonanDomisili::with('user')->latest()->get();
        $ktm = PermohonanKTM::with('user')->latest()->get();
        $sku = PermohonanSKU::with('user')->latest()->get();

        return compact('domisili', 'ktm', 'sku');
    }

    /**
     * Logika dari AdminController@showDetailSurat (dan variannya)
     */
    /**
     * Logika dari AdminController@showDetailSurat (dan variannya)
     */
    public function getPermohonanDetail(string $jenis, int $id): ?array
    {
        $modelClass = $this->getModelClass($jenis);
        if (!$modelClass) {
            return null; // Jenis surat tidak valid
        }

        $permohonan = $modelClass::with('user')->findOrFail($id);

        // ✅ PERBAIKAN: Konsistenkan nama jenis_surat
        $jenisSurat = match ($jenis) {
            'domisili' => 'Keterangan Domisili',
            'ktm' => 'Keterangan Tidak Mampu',
            'sku' => 'Keterangan Usaha',  // ✅ TANPA "(SKU)"
        };

        $title = match ($jenis) {
            'domisili' => 'Keterangan Domisili',
            'ktm' => 'Keterangan Tidak Mampu (SKTM)',
            'sku' => 'Keterangan Usaha (SKU)',
        };

        return [
            'permohonan' => $permohonan,
            'jenis_surat' => $jenisSurat,  // ✅ Ini yang dipakai di view
            'title' => $title
        ];
    }

    /**
     * Logika dari AdminController@showLaporan
     * (Hanya mengambil data, logika 'export' tetap di Controller)
     */
    public function getLaporanData(string $tanggalMulai, string $tanggalAkhir, string $statusFilter)
    {
        $start = Carbon::parse($tanggalMulai)->startOfDay();
        $end = Carbon::parse($tanggalAkhir)->endOfDay();

        $domisiliQuery = PermohonanDomisili::with('user')
            ->whereBetween('created_at', [$start, $end]);

        $ktmQuery = PermohonanKTM::with('user')
            ->whereBetween('created_at', [$start, $end]);

        $skuQuery = PermohonanSKU::with('user')
            ->whereBetween('created_at', [$start, $end]);

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

        return collect()
            ->merge($domisili)
            ->merge($ktm)
            ->merge($sku)
            ->sortByDesc('created_at');
    }

    // ============================================================
    // ✅ METHOD ARSIP - TAMBAHAN BARU
    // ============================================================

    /**
     * Arsipkan permohonan secara manual
     */
    public function archivePermohonan(string $type, int $id): bool
    {
        $modelClass = $this->getModelClass($type);
        if (!$modelClass) {
            return false;
        }

        $permohonan = $modelClass::findOrFail($id);
        $permohonan->archived_at = now();
        $permohonan->save();

        return true;
    }

    /**
     * Arsipkan otomatis permohonan yang sudah Selesai/Ditolak > 15 hari
     * Method ini dipanggil via Scheduler atau manual
     */
    public function autoArchiveOldPermohonan(): int
    {
        $threshold = now()->subDays(15);
        $count = 0;

        // Arsipkan Domisili
        $count += PermohonanDomisili::whereIn('status', ['Selesai', 'Ditolak'])
            ->whereNull('archived_at')
            ->where('updated_at', '<=', $threshold)
            ->update(['archived_at' => now()]);

        // Arsipkan KTM
        $count += PermohonanKTM::whereIn('status', ['Selesai', 'Ditolak'])
            ->whereNull('archived_at')
            ->where('updated_at', '<=', $threshold)
            ->update(['archived_at' => now()]);

        // Arsipkan SKU
        $count += PermohonanSKU::whereIn('status', ['Selesai', 'Ditolak'])
            ->whereNull('archived_at')
            ->where('updated_at', '<=', $threshold)
            ->update(['archived_at' => now()]);

        return $count;
    }

    /**
     * Ambil data permohonan yang sudah diarsipkan
     */
    public function getArchivedPermohonan(): array
    {
        $domisili = PermohonanDomisili::with('user')
            ->whereNotNull('archived_at')
            ->latest('archived_at')
            ->get();

        $ktm = PermohonanKTM::with('user')
            ->whereNotNull('archived_at')
            ->latest('archived_at')
            ->get();

        $sku = PermohonanSKU::with('user')
            ->whereNotNull('archived_at')
            ->latest('archived_at')
            ->get();

        return compact('domisili', 'ktm', 'sku');
    }
}