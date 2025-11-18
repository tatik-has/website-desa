<?php

namespace App\LogicTier\Controllers\Admin;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon; // Ini tetap perlu
use Illuminate\Support\Facades\Response; // Ini tetap perlu

// 1. PANGGIL "PEKERJA" (SERVICE) KITA
use App\LogicTier\Services\PermohonanAdminService;

class AdminController extends BaseController
{
    // 2. Siapkan variabel untuk menampung service
    protected $permohonanService;

    // 3. Buat __construct untuk "Dependency Injection"
    // Laravel akan otomatis mengisi $service
    public function __construct(PermohonanAdminService $service)
    {
        $this->permohonanService = $service;
    }

    /**
     * Method index sekarang RAMPING
     */
    public function index()
    {
        // 4. Suruh service bekerja
        $summary = $this->permohonanService->getDashboardSummary();

        // 5. Kirim data ke view (data $summary sudah dalam bentuk array)
        return view('presentation_tier.admin.dashboard', $summary);
    }
    
    /**
     * Method showPermohonanSurat sekarang RAMPING
     */
    public function showPermohonanSurat()
    {
        // Suruh service bekerja
        $groupedData = $this->permohonanService->getGroupedPermohonan();

        // Kirim ke view (data $groupedData sudah dalam bentuk array)
        return view('presentation_tier.admin.permohonan.permohonan-surat', $groupedData);
    }

    /**
     * Method updateStatusPermohonan sekarang RAMPING
     */
    public function updateStatusPermohonan(Request $request, string $type, int $id)
    {
        // 1. Validasi tetap di Controller (Tugas Mandor)
        $request->validate([
            'status' => 'required|in:Diproses,Selesai,Ditolak',
            'keterangan_penolakan' => 'required_if:status,ditolak,Ditolak|string|nullable',
            'surat_jadi' => 'required_if:status,selesai,Selesai|file|mimes:pdf|max:2048',
        ]);

        // 2. Suruh service bekerja (upload, save db, notif)
        $this->permohonanService->updateStatus($request, $type, $id);

        // 3. Kasih respon (Tugas Mandor)
        return redirect()->route('admin.surat.index')->with('success', 'Status permohonan berhasil diperbarui!');
    }

    /**
     * Method semuaPermohonan sekarang RAMPING
     */
    public function semuaPermohonan()
    {
        // Suruh service bekerja
        $allData = $this->permohonanService->getAllPermohonan();
        
        // Kirim ke view
        return view('presentation_tier.admin.semua-permohonan', $allData);
    }

    /**
     * Method showDetailSurat sekarang RAMPING
     */
    public function showDetailSurat($jenis, $id)
    {
        // Suruh service bekerja
        $data = $this->permohonanService->getPermohonanDetail($jenis, $id);

        if (!$data) {
            abort(404, 'Jenis surat tidak ditemukan.');
        }

        // Kirim ke view
        return view('presentation_tier.admin.permohonan.detail-surat', $data);
    }

    /**
     * Method showDomisiliDetail sekarang RAMPING
     * (Kita tetap biarkan ada sesuai janji "tidak mengurangi kode")
     */
    public function showDomisiliDetail($id)
    {
        // Suruh service bekerja
        $data = $this->permohonanService->getPermohonanDetail('domisili', $id);
        
        return view('presentation_tier.admin.permohonan.detail-surat', $data);
    }

    /**
     * Method showKtmDetail sekarang RAMPING
     */
    public function showKtmDetail($id)
    {
        // Suruh service bekerja
        $data = $this->permohonanService->getPermohonanDetail('ktm', $id);
        
        return view('presentation_tier.admin.permohonan.detail-surat', $data);
    }

    /**
     * Method showSkuDetail sekarang RAMPING
     */
    public function showSkuDetail($id)
    {
        // Suruh service bekerja
        $data = $this->permohonanService->getPermohonanDetail('sku', $id);
        
        return view('presentation_tier.admin.permohonan.detail-surat', $data);
    }
    
    /**
     * Method showLaporan sekarang RAMPING
     */
    public function showLaporan(Request $request)
    {
        // 1. Ambil input (Tugas Mandor)
        $tanggalMulai = $request->input('tanggal_mulai', Carbon::now()->subDays(30)->toDateString());
        $tanggalAkhir = $request->input('tanggal_akhir', Carbon::now()->toDateString());
        $statusFilter = $request->input('status', 'semua');

        // 2. Suruh service mengambil data
        $allPermohonan = $this->permohonanService->getLaporanData($tanggalMulai, $tanggalAkhir, $statusFilter);

        // 3. Logika 'export' adalah 'Response', jadi tetap di Controller (Tugas Mandor)
        if ($request->has('export') && $request->export == 'word') {
            
            $html = view('presentation_tier.admin.permohonan.laporan-word', compact(
                'allPermohonan', 'tanggalMulai', 'tanggalAkhir', 'statusFilter'
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

        // 4. Tampilkan view laporan biasa
        return view('presentation_tier.admin.permohonan.laporan', compact(
            'allPermohonan', 'tanggalMulai', 'tanggalAkhir', 'statusFilter'
        ));
    }

    // ============================================================
    // ✅ FITUR ARSIP - TAMBAHAN BARU
    // ============================================================

    /**
     * Arsipkan permohonan secara manual
     */
    public function archivePermohonan(string $type, int $id)
    {
        // Suruh service mengarsipkan
        $result = $this->permohonanService->archivePermohonan($type, $id);

        if ($result) {
            return redirect()->back()->with('success', 'Permohonan berhasil diarsipkan!');
        }

        return redirect()->back()->with('error', 'Gagal mengarsipkan permohonan.');
    }

    /**
     * Jalankan arsip otomatis (bisa dipanggil manual atau via scheduler)
     */
    public function runAutoArchive()
    {
        // Suruh service menjalankan arsip otomatis
        $count = $this->permohonanService->autoArchiveOldPermohonan();
        
        return redirect()->back()->with('success', "Berhasil mengarsipkan {$count} permohonan yang sudah lebih dari 15 hari!");
    }

    /**
     * Tampilkan halaman arsip
     */
    public function showArsip()
    {
        // Suruh service mengambil data arsip
        $archivedData = $this->permohonanService->getArchivedPermohonan();
        
        // Kirim ke view
        return view('presentation_tier.admin.permohonan.arsip', $archivedData);
    }
}