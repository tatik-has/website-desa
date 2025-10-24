<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Database\Eloquent\Model; 

class PengajuanMasukNotification extends Notification
{
    use Queueable;

    protected $permohonan;
    protected $jenisSuratKey; // <-- TAMBAHKAN INI
    protected $jenisSuratDisplay; // <-- TAMBAHKAN INI
    protected $namaPemohon;

    /**
     * Buat instance notifikasi baru.
     * Hapus parameter $jenisSurat, kita akan tentukan otomatis
     */
    public function __construct(Model $permohonan)
    {
        $this->permohonan = $permohonan;
        $this->namaPemohon = $permohonan->nama;
        
        // Tentukan jenis surat key dan display name secara otomatis
        list($this->jenisSuratKey, $this->jenisSuratDisplay) = $this->getPermohonanTypeInfo();
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database']; 
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        // Data ini yang akan disimpan di kolom 'data' pada tabel notifications
        return [
            'permohonan_id' => $this->permohonan->id,
            'jenis_surat'   => $this->jenisSuratDisplay, // Nama cantik (cth: Keterangan Domisili)
            'jenis_surat_key' => $this->jenisSuratKey, // Kunci rute (cth: domisili)
            'nama_pemohon'  => $this->namaPemohon,
            'pesan'         => "Pengajuan {$this->jenisSuratDisplay} baru dari {$this->namaPemohon}."
        ];
    }

    /**
     * Helper untuk menentukan jenis surat berdasarkan model
     */
    private function getPermohonanTypeInfo(): array
    {
        return match(get_class($this->permohonan)) {
            \App\DataTier\Models\PermohonanDomisili::class => ['domisili', 'Keterangan Domisili'],
            \App\DataTier\Models\PermohonanKtm::class      => ['ktm', 'SKTM'],
            \App\DataTier\Models\PermohonanSKU::class      => ['sku', 'Keterangan Usaha (SKU)'],
            default => ['unknown', 'Surat Tidak Dikenal']
        };
    }
}