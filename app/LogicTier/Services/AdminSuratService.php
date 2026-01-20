<?php

namespace App\LogicTier\Services;

use App\DataTier\Models\PermohonanDomisili;
use App\DataTier\Models\PermohonanKTM;
use App\DataTier\Models\PermohonanSKU;
use App\DataTier\Models\User;
use App\Notifications\SuratSelesaiNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminSuratService
{
    private function getModelClass(string $type)
    {
        return match ($type) {
            'domisili' => PermohonanDomisili::class,
            'ktm' => PermohonanKTM::class,
            'sku' => PermohonanSKU::class,
            default => null
        };
    }

    public function getGroupedPermohonan(): array
    {
        $domisiliGrouped = PermohonanDomisili::with('user')->whereNull('archived_at')->latest()->get()->groupBy('status');
        $ktmGrouped = PermohonanKTM::with('user')->whereNull('archived_at')->latest()->get()->groupBy('status');
        $skuGrouped = PermohonanSKU::with('user')->whereNull('archived_at')->latest()->get()->groupBy('status');

        return compact('domisiliGrouped', 'ktmGrouped', 'skuGrouped');
    }


    public function getAllPermohonan(): array
    {
        // Mengambil semua permohonan dari ketiga tabel tanpa filter archived
        $domisili = PermohonanDomisili::with('user')
            ->whereNull('archived_at')
            ->latest()
            ->get()
            ->map(function($item) {
                $item->jenis_surat = 'Domisili';
                $item->type = 'domisili';
                return $item;
            });

        $ktm = PermohonanKTM::with('user')
            ->whereNull('archived_at')
            ->latest()
            ->get()
            ->map(function($item) {
                $item->jenis_surat = 'SKTM';
                $item->type = 'ktm';
                return $item;
            });

        $sku = PermohonanSKU::with('user')
            ->whereNull('archived_at')
            ->latest()
            ->get()
            ->map(function($item) {
                $item->jenis_surat = 'SKU';
                $item->type = 'sku';
                return $item;
            });

        // Gabungkan semua permohonan dan urutkan berdasarkan tanggal terbaru
        $allPermohonan = $domisili->concat($ktm)->concat($sku)->sortByDesc('created_at');

        return [
            'permohonan' => $allPermohonan,
            'total' => $allPermohonan->count()
        ];
    }

    public function updateStatus(Request $request, string $type, int $id)
    {
        $modelClass = $this->getModelClass($type);
        if (!$modelClass) { abort(404); }

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
            if ($permohonan->path_surat_jadi) { Storage::delete($permohonan->path_surat_jadi); }
            $permohonan->path_surat_jadi = $request->file('surat_jadi')->store('public/surat_selesai');
            $permohonan->keterangan_penolakan = null;
        } else {
            $permohonan->keterangan_penolakan = null;
        }

        $permohonan->save();

        if (($permohonan->status == 'Selesai' || $permohonan->status == 'Ditolak') && $permohonan->user_id) {
            $user = User::find($permohonan->user_id);
            if ($user) { $user->notify(new SuratSelesaiNotification($permohonan)); }
        }
        return true;
    }

    public function getPermohonanDetail(string $jenis, int $id): ?array
    {
        $modelClass = $this->getModelClass($jenis);
        if (!$modelClass) { return null; }

        $permohonan = $modelClass::with('user')->findOrFail($id);
        $jenisSurat = match ($jenis) {
            'domisili' => 'Keterangan Domisili',
            'ktm' => 'Keterangan Tidak Mampu',
            'sku' => 'Keterangan Usaha',
        };
        $title = match ($jenis) {
            'domisili' => 'Keterangan Domisili',
            'ktm' => 'Keterangan Tidak Mampu (SKTM)',
            'sku' => 'Keterangan Usaha (SKU)',
        };

        return ['permohonan' => $permohonan, 'jenis_surat' => $jenisSurat, 'title' => $title];
    }

    public function archivePermohonan(string $type, int $id): bool
    {
        $modelClass = $this->getModelClass($type);
        if (!$modelClass) { return false; }
        $permohonan = $modelClass::findOrFail($id);
        $permohonan->archived_at = now();
        return $permohonan->save();
    }

    public function autoArchiveOldPermohonan(): int
    {
        $threshold = now()->subDays(15);
        $count = 0;
        $count += PermohonanDomisili::whereIn('status', ['Selesai', 'Ditolak'])->whereNull('archived_at')->where('updated_at', '<=', $threshold)->update(['archived_at' => now()]);
        $count += PermohonanKTM::whereIn('status', ['Selesai', 'Ditolak'])->whereNull('archived_at')->where('updated_at', '<=', $threshold)->update(['archived_at' => now()]);
        $count += PermohonanSKU::whereIn('status', ['Selesai', 'Ditolak'])->whereNull('archived_at')->where('updated_at', '<=', $threshold)->update(['archived_at' => now()]);
        return $count;
    }
}