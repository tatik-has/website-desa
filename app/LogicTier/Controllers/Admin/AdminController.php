<?php

namespace App\LogicTier\Controllers\Admin;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\DataTier\Models\PermohonanDomisili;
use App\DataTier\Models\PermohonanKTM;
use App\DataTier\Models\PermohonanSKU;
use App\DataTier\Models\User;
use App\Notifications\SuratSelesaiNotification;
use App\LogicTier\Events\StatusDiperbarui;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Response;

class AdminController extends BaseController
{
    public function index()
    {
        // 1. Menghitung total permohonan yang masih 'Diproses'
        $totalDiproses = PermohonanDomisili::where('status', 'Diproses')->count()
            + PermohonanKTM::where('status', 'Diproses')->count()
            + PermohonanSKU::where('status', 'Diproses')->count();

        // 2. Menghitung total permohonan yang sudah 'Selesai' (Disetujui)
        $totalSelesai = PermohonanDomisili::where('status', 'Selesai')->count()
            + PermohonanKTM::where('status', 'Selesai')->count()
            + PermohonanSKU::where('status', 'Selesai')->count();

        // 3. Menghitung total permohonan yang 'Ditolak'
        // (Saya asumsikan "Total Laporan" di dashboard Anda merujuk ke "Total Ditolak")
        $totalDitolak = PermohonanDomisili::where('status', 'Ditolak')->count()
            + PermohonanKTM::where('status', 'Ditolak')->count()
            + PermohonanSKU::where('status', 'Ditolak')->count();

        // 4. Kirim data ke view
        return view('presentation_tier.admin.dashboard', compact(
            'totalDiproses',
            'totalSelesai',
            'totalDitolak'
        ));
    }
    public function showPermohonanSurat()
    {
        $domisiliGrouped = PermohonanDomisili::with('user')->latest()->get()->groupBy('status');
        $ktmGrouped = PermohonanKTM::with('user')->latest()->get()->groupBy('status');
        $skuGrouped = PermohonanSKU::with('user')->latest()->get()->groupBy('status');

        return view('presentation_tier.admin.permohonan.permohonan-surat', compact(
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


        if (($permohonan->status == 'Selesai' || $permohonan->status == 'Ditolak') && $permohonan->user_id) {
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

        return view('presentation_tier.admin.permohonan.detail-surat', [
            'permohonan' => $permohonan,
            'jenis_surat' => $jenisSurat,
        ]);
    }


    public function showDomisiliDetail($id)
    {
        $permohonan = PermohonanDomisili::with('user')->findOrFail($id);

        return view('presentation_tier.admin.permohonan.detail-surat', [
            'permohonan' => $permohonan,
            'jenis_surat' => 'Domisili',
            'title' => 'Keterangan Domisili'
        ]);
    }

    public function showKtmDetail($id)
    {
        $permohonan = PermohonanKTM::with('user')->findOrFail($id);

        return view('presentation_tier.admin.permohonan.detail-surat', [
            'permohonan' => $permohonan,
            'jenis_surat' => 'SKTM',
            'title' => 'Keterangan Tidak Mampu (SKTM)'
        ]);
    }

    public function showSkuDetail($id)
    {
        $permohonan = PermohonanSKU::with('user')->findOrFail($id);

        return view('presentation_tier.admin.permohonan.detail-surat', [
            'permohonan' => $permohonan,
            'jenis_surat' => 'SKU',
            'title' => 'Keterangan Usaha (SKU)'
        ]);
    }
    public function showLaporan(Request $request)
    {
        $tanggalMulai = $request->input('tanggal_mulai', Carbon::now()->subDays(30)->toDateString());
        $tanggalAkhir = $request->input('tanggal_akhir', Carbon::now()->toDateString());
        $statusFilter = $request->input('status', 'semua'); // Tambahan filter status

        $start = Carbon::parse($tanggalMulai)->startOfDay();
        $end = Carbon::parse($tanggalAkhir)->endOfDay();

        // 1. Ambil data dari semua model permohonan dengan filter status
        $domisiliQuery = PermohonanDomisili::with('user')
            ->whereBetween('created_at', [$start, $end]);

        $ktmQuery = PermohonanKTM::with('user')
            ->whereBetween('created_at', [$start, $end]);

        $skuQuery = PermohonanSKU::with('user')
            ->whereBetween('created_at', [$start, $end]);

        // Terapkan filter status jika bukan "semua"
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

        // 2. Gabungkan semua data dan urutkan berdasarkan tanggal dibuat (terbaru dulu)
        $allPermohonan = collect()
            ->merge($domisili)
            ->merge($ktm)
            ->merge($sku)
            ->sortByDesc('created_at');

        // 3. Cek apakah ini permintaan ekspor Word
        if ($request->has('export') && $request->export == 'word') {
            // Render view khusus untuk Word
            $html = view('presentation_tier.admin.permohonan.laporan-word', compact(
                'allPermohonan',
                'tanggalMulai',
                'tanggalAkhir',
                'statusFilter'
            ))->render();

            // Buat nama file dengan status
            $statusLabel = $statusFilter === 'semua' ? 'Semua' : ucfirst($statusFilter);
            $fileName = 'Laporan_Surat_' . $statusLabel . '_' . $tanggalMulai . '_sd_' . $tanggalAkhir . '.doc';

            // Siapkan headers untuk memaksa download file .doc
            $headers = [
                'Content-Type' => 'application/vnd.ms-word',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
                'Cache-Control' => 'max-age=0',
            ];

            return Response::make($html, 200, $headers);
        }

        // 4. Jika bukan ekspor, tampilkan halaman laporan biasa
        return view('presentation_tier.admin.permohonan.laporan', compact(
            'allPermohonan',
            'tanggalMulai',
            'tanggalAkhir',
            'statusFilter'
        ));
    }
}
