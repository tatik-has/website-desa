<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Database\Eloquent\Model; // Import Model dasar

class SuratSelesaiNotification extends Notification implements ShouldQueue // Tambahkan ShouldQueue
{
    use Queueable;

    protected $permohonan;

    /**
     * Buat instance notifikasi baru.
     */
    public function __construct(Model $permohonan)
    {
        $this->permohonan = $permohonan;
    }

    /**
     * Tentukan channel pengiriman notifikasi.
     */
    public function via(object $notifiable): array
    {
        // === PERUBAHAN ===
        // Tambahkan 'broadcast' agar notifikasi bisa real-time (jika Anda pakai)
        return ['database', 'broadcast']; 
    }

    // === PERUBAHAN: FUNGSI BARU UNTUK PESAN DINAMIS ===
    /**
     * Dapatkan pesan notifikasi yang dinamis berdasarkan status.
     */
    private function getPesan()
    {
        $jenisSurat = $this->getJenisSuratNama();
        $status = strtolower($this->permohonan->status);

        return match ($status) {
            'selesai' => "Selamat! Surat $jenisSurat Anda telah Selesai.",
            'ditolak' => "Maaf, pengajuan $jenisSurat Anda Ditolak. Cek riwayat untuk detail.",
            'diproses' => "Surat $jenisSurat Anda sedang Diproses oleh admin.",
            default => "Status $jenisSurat Anda telah diperbarui.",
        };
    }
    // === AKHIR PERUBAHAN ===

    /**
     * Ubah notifikasi menjadi array (untuk 'database').
     */
    public function toArray(object $notifiable): array
    {
        // === PERUBAHAN ===
        // Kita gunakan pesan dinamis dari getPesan()
        // dan sesuaikan key-nya agar konsisten
        return [
            'permohonan_id' => $this->permohonan->id,
            'jenis_surat' => $this->getJenisSuratNama(),
            'status' => $this->permohonan->status,
            'pesan' => $this->getPesan(), // Pesan dinamis
            'file_path' => $this->permohonan->status == 'Selesai' ? $this->permohonan->path_surat_jadi : null,
        ];
        // === AKHIR PERUBAHAN ===
    }

    // === TAMBAHAN: FUNGSI BARU UNTUK BROADCAST (PUSHER) ===
    /**
     * Dapatkan representasi broadcast notifikasi.
     */
    public function toBroadcast(object $notifiable): \Illuminate\Notifications\Messages\BroadcastMessage
    {
        return new \Illuminate\Notifications\Messages\BroadcastMessage([
            'message' => $this->getPesan(),
            'status' => $this->permohonan->status,
        ]);
    }
    // === AKHIR TAMBAHAN ===


    /**
     * Helper untuk mendapatkan nama surat
     * (Nama file model Anda 'PermohonanKtm' di file notif lama, saya ganti jadi 'PermohonanKTM')
     */
    private function getJenisSuratNama(): string
    {
         return match(get_class($this->permohonan)) {
            \App\DataTier\Models\PermohonanDomisili::class => 'Keterangan Domisili',
            \App\DataTier\Models\PermohonanSKU::class => 'Keterangan Usaha (SKU)',
            \App\DataTier\Models\PermohonanKTM::class => 'Keterangan Tidak Mampu (KTM)', // Pastikan nama Model ini benar
            default => 'Surat'
        };
    }
}
