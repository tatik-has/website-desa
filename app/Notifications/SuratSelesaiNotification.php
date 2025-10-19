<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Database\Eloquent\Model; // Import Model dasar

class SuratSelesaiNotification extends Notification
{
    use Queueable;

    protected $permohonan;

    /**
     * Buat instance notifikasi baru.
     *
     * Hapus type-hint 'PermohonanDomisili' agar bisa menerima
     * model PermohonanSKU, PermohonanKtm, dll.
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
        return ['database']; // Kirim ke database
    }

    /**
     * Ubah notifikasi menjadi array.
     */
    public function toArray(object $notifiable): array
    {
        // Buat pesan dinamis berdasarkan jenis permohonan
        $jenisSurat = match(get_class($this->permohonan)) {
            \App\DataTier\Models\PermohonanDomisili::class => 'Keterangan Domisili',
            \App\DataTier\Models\PermohonanSKU::class => 'Keterangan Usaha (SKU)',
            \App\DataTier\Models\PermohonanKtm::class => 'Keterangan Tidak Mampu (KTM)',
            default => 'Surat'
        };

        return [
            'permohonan_id' => $this->permohonan->id,
            'jenis_surat' => $jenisSurat,
            'nama_pemohon' => $this->permohonan->nama,
            'pesan' => "Surat {$jenisSurat} Anda telah selesai dan siap untuk diunduh.",
            'file_path' => $this->permohonan->path_surat_jadi,
        ];
    }
}