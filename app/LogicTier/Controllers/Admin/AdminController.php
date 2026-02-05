<?php

namespace App\LogicTier\Controllers\Admin;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Request;
//  Pastikan menggunakan AdminSuratService sesuai struktur Logic Tier Anda
use App\LogicTier\Services\AdminSuratService;

class AdminController extends BaseController
{
    protected $permohonanService;

    // Injeksi AdminSuratService ke dalam constructor
    public function __construct(AdminSuratService $service)
    {
        $this->permohonanService = $service;
    }

    /**
     * Menampilkan daftar permohonan surat yang dikelompokkan berdasarkan status
     * Bagian dari Logic Tier untuk memproses alur data operasional
     */
    public function showPermohonanSurat()
    {
        $groupedData = $this->permohonanService->getGroupedPermohonan();
        return view('presentation_tier.admin.permohonan.permohonan-surat', $groupedData);
    }


    public function semuaPermohonan()
    {
        // Panggil logika dari Service
        $allData = $this->permohonanService->getAllPermohonan();
        
        return view('presentation_tier.admin.permohonan.permohonan-surat', $allData);
    }

    /**
     * Memperbarui status permohonan (Diterima, Ditolak)
     * === PERUBAHAN: Tambahkan "Diterima", hapus "Selesai" dari sini ===
     */
    public function updateStatusPermohonan(Request $request, string $type, int $id)
    {
        $request->validate([
            'status' => 'required|in:Diproses,Diterima,Ditolak',
            'keterangan_penolakan' => 'required_if:status,Ditolak|string|nullable',
        ]);

        // Teruskan ke Logic Tier (Service)
        $this->permohonanService->updateStatus($request, $type, $id);

        return redirect()->route('admin.surat.index')->with('success', 'Status permohonan berhasil diperbarui!');
    }

    // === TAMBAHAN: Method baru untuk mengirim surat yang sudah jadi ===
    /**
     * Admin upload PDF surat yang sudah jadi → status otomatis menjadi "Selesai"
     * Notifikasi dikirim ke masyarakat dari method ini
     */
    public function kirimSurat(Request $request, string $type, int $id)
    {
        $request->validate([
            'surat_jadi' => 'required|file|mimes:pdf|max:2048',
        ]);

        $this->permohonanService->kirimSurat($request, $type, $id);

        return redirect()->route('admin.surat.index')->with('success', 'Surat berhasil dikirim dan status telah diperbarui menjadi Selesai!');
    }
    // === AKHIR TAMBAHAN ===

    /**
     * Menampilkan detail surat (KTM, SKU, Domisili)
     */
    public function showDetailSurat($jenis, $id)
    {
        $data = $this->permohonanService->getPermohonanDetail($jenis, $id);

        if (!$data) {
            abort(404, 'Data permohonan tidak ditemukan.');
        }

        return view('presentation_tier.admin.permohonan.detail-surat', $data);
    }

    /**
     * Method khusus rute detail yang mengarah secara spesifik (opsional jika rute dipisah)
     */
    public function showKtmDetail($id) { return $this->showDetailSurat('ktm', $id); }
    public function showSkuDetail($id) { return $this->showDetailSurat('sku', $id); }
    public function showDomisiliDetail($id) { return $this->showDetailSurat('domisili', $id); }

    /**
     * Aksi untuk mengarsipkan surat secara manual
     */
    public function archivePermohonan(string $type, int $id)
    {
        $result = $this->permohonanService->archivePermohonan($type, $id);

        if ($result) {
            return redirect()->back()->with('success', 'Permohonan berhasil diarsipkan!');
        }

        return redirect()->back()->with('error', 'Gagal mengarsipkan permohonan.');
    }

    /**
     * Menjalankan fungsi arsip otomatis via Logic Tier
     */
    public function runAutoArchive()
    {
        $count = $this->permohonanService->autoArchiveOldPermohonan();
        return redirect()->back()->with('success', "Berhasil mengarsipkan {$count} permohonan lama.");
    }
}