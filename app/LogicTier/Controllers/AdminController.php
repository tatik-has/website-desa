<?php

namespace App\LogicTier\Controllers;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\DataTier\Models\PermohonanDomisili;
use App\DataTier\Models\PermohonanKTM;
use App\DataTier\Models\PermohonanSKU;
use App\DataTier\Models\User;
use App\Notifications\SuratSelesaiNotification;
use App\LogicTier\Events\StatusDiperbarui; // Import event baru

class AdminController extends BaseController
{
    public function index()
    {
        return view('presentation_tier.admin.dashboard');
    }

    public function showPermohonanSurat()
    {
        $domisiliGrouped = PermohonanDomisili::with('user')->latest()->get()->groupBy('status');
        $ktmGrouped = PermohonanKTM::with('user')->latest()->get()->groupBy('status');
        $skuGrouped = PermohonanSKU::with('user')->latest()->get()->groupBy('status');

        return view('presentation_tier.admin.permohonan-surat', compact(
            'domisiliGrouped',
            'ktmGrouped',
            'skuGrouped'
        ));
    }

    public function updateStatusPermohonan(Request $request, string $type, int $id)
    {
        $request->validate([
            'status' => 'required|in:Diproses,Selesai,Ditolak',
            'keterangan_penolakan' => 'required_if:status,ditolak,Ditolak|string|nullable',
            'surat_jadi' => 'required_if:status,selesai,Selesai|file|mimes:pdf|max:2048',
        ]);

        $modelClass = match($type) {
            'domisili' => PermohonanDomisili::class,
            'ktm' => PermohonanKTM::class,
            'sku' => PermohonanSKU::class,
            default => abort(404, 'Jenis permohonan tidak valid.')
        };

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

        // 🔔 PICU EVENT SETELAH STATUS BERHASIL DIUPDATE
        if ($permohonan->user_id) {
            event(new StatusDiperbarui(
                $permohonan->user_id,
                $permohonan->status,
                $permohonan->keterangan_penolakan ?? ''
            ));
        }

        // 🔔 Kirim notifikasi jika status selesai
        if ($permohonan->status == 'Selesai' && $permohonan->user_id) {
            $user = User::find($permohonan->user_id);
            if ($user) {
                $user->notify(new SuratSelesaiNotification($permohonan));
            }
        }

        return redirect()->route('admin.surat.index')->with('success', 'Status permohonan berhasil diperbarui!');
    }

    public function showDomisiliDetail(PermohonanDomisili $permohonanDomisili)
    {
        return view('presentation_tier.admin.detail-surat', [
            'permohonan' => $permohonanDomisili,
            'jenis_surat' => 'Keterangan Domisili'
        ]);
    }

    public function showKtmDetail(PermohonanKTM $permohonanKTM)
    {
        return view('presentation_tier.admin.detail-surat', [
            'permohonan' => $permohonanKTM,
            'jenis_surat' => 'Permohonan KTM'
        ]);
    }

    public function showSkuDetail(PermohonanSKU $permohonanSKU)
    {
        return view('presentation_tier.admin.detail-surat', [
            'permohonan' => $permohonanSKU,
            'jenis_surat' => 'Keterangan Usaha (SKU)'
        ]);
    }
}
