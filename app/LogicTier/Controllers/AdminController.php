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
use App\LogicTier\Events\StatusDiperbarui;

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

        $modelClass = match ($type) {
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

        // if ($permohonan->user_id) {
        //     event(new StatusDiperbarui($permohonan));
        // }

        if ($permohonan->status == 'Selesai' && $permohonan->user_id) {
            $user = User::find($permohonan->user_id);
            if ($user) {
                $user->notify(new SuratSelesaiNotification($permohonan));
            }
        }

        return redirect()->route('admin.surat.index')->with('success', 'Status permohonan berhasil diperbarui!');
    }

    public function semuaPermohonan()
    {
        $domisili = PermohonanDomisili::with('user')->latest()->get();
        $ktm = PermohonanKTM::with('user')->latest()->get();
        $sku = PermohonanSKU::with('user')->latest()->get();

        return view('presentation_tier.admin.semua-permohonan', compact('domisili', 'ktm', 'sku'));
    }

    // === TAMBAHAN: METHOD UNIVERSAL DETAIL SURAT ===
    public function showDetailSurat($jenis, $id)
    {
        $modelClass = match ($jenis) {
            'domisili' => PermohonanDomisili::class,
            'ktm' => PermohonanKTM::class,
            'sku' => PermohonanSKU::class,
            default => abort(404, 'Jenis surat tidak ditemukan.')
        };

        $permohonan = $modelClass::with('user')->findOrFail($id);

        $jenisSurat = match ($jenis) {
            'domisili' => 'Keterangan Domisili',
            'ktm' => 'Keterangan Tidak Mampu',
            'sku' => 'Keterangan Usaha (SKU)',
        };

        return view('presentation_tier.admin.detail-surat', [
            'permohonan' => $permohonan,
            'jenis_surat' => $jenisSurat,
        ]);
    }

    
public function showDomisiliDetail($id)
{
    $permohonan = PermohonanDomisili::with('user')->findOrFail($id);
    
    return view('presentation_tier.admin.detail-surat', [
        'permohonan' => $permohonan,
        'jenis_surat' => 'Domisili',
        'title' => 'Keterangan Domisili'
    ]);
}

public function showKtmDetail($id)
{
    $permohonan = PermohonanKTM::with('user')->findOrFail($id);
    
    return view('presentation_tier.admin.detail-surat', [
        'permohonan' => $permohonan,
        'jenis_surat' => 'SKTM',
        'title' => 'Keterangan Tidak Mampu (SKTM)'
    ]);
}

public function showSkuDetail($id)
{
    $permohonan = PermohonanSKU::with('user')->findOrFail($id);
    
    return view('presentation_tier.admin.detail-surat', [
        'permohonan' => $permohonan,
        'jenis_surat' => 'SKU',
        'title' => 'Keterangan Usaha (SKU)'
    ]);
}
}
