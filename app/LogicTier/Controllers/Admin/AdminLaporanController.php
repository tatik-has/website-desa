<?php

namespace App\LogicTier\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\LogicTier\Services\AdminLaporanService;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;

class AdminLaporanController extends Controller
{
    protected $permohonanService;

    public function __construct(AdminLaporanService $service)
    {
        $this->permohonanService = $service;
    }

    public function showLaporan(Request $request)
    {
        // Mengambil input filter dari request
        $tanggalMulai = $request->input('tanggal_mulai', Carbon::now()->subDays(30)->toDateString());
        $tanggalAkhir = $request->input('tanggal_akhir', Carbon::now()->toDateString());
        $statusFilter = $request->input('status', 'semua');

        // Panggil logika filter data dari Service
        $allPermohonan = $this->permohonanService->getLaporanData($tanggalMulai, $tanggalAkhir, $statusFilter);

        // Logika Export Word (Tetap di Controller karena berurusan dengan Response/Tampilan)
        if ($request->has('export') && $request->export == 'word') {
            $html = view('presentation_tier.admin.permohonan.laporan-word', compact(
                'allPermohonan',
                'tanggalMulai',
                'tanggalAkhir',
                'statusFilter'
            ))->render();

            $statusLabel = $statusFilter === 'semua' ? 'Semua' : ucfirst($statusFilter);
            $fileName = 'Laporan_Surat_' . $statusLabel . '_' . $tanggalMulai . '_sd_' . $tanggalAkhir . '.doc';

            $headers = [
                'Content-Type' => 'application/vnd.ms-word',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
                'Cache-Control' => 'max-age=0',
            ];

            return Response::make($html, 200, $headers);
        }

        return view('presentation_tier.admin.permohonan.laporan', compact('allPermohonan', 'tanggalMulai', 'tanggalAkhir', 'statusFilter'));
    }
    /**
     * Method untuk memproses pengarsipan permohonan
     */
    public function archivePermohonan(Request $request, $type, $id)
    {
        try {
            // Memanggil service untuk memproses pengarsipan
            $success = $this->permohonanService->archiveData($type, $id);

            if ($success) {
                return redirect()->back()->with('success', 'Data permohonan berhasil diarsipkan.');
            }

            return redirect()->back()->with('error', 'Jenis surat tidak valid.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Method untuk menampilkan halaman arsip (pindahan dari AdminController)
     */
    public function showArsip()
    {
        // Ambil data arsip dari Service
        $archivedData = $this->permohonanService->getArchivedPermohonan();

        return view('presentation_tier.admin.permohonan.arsip', $archivedData);
    }
}
