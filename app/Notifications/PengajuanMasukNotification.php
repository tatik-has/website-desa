<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue; // 1. TAMBAHKAN INI
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Database\Eloquent\Model; 
use Illuminate\Notifications\Messages\BroadcastMessage; // 2. TAMBAHKAN INI

class PengajuanMasukNotification extends Notification implements ShouldQueue // 3. TAMBAHKAN ShouldQueue
{
    use Queueable;

    protected $permohonan;
    protected $jenisSuratKey; 
    protected $jenisSuratDisplay;
    protected $namaPemohon;

    /**
     * Buat instance notifikasi baru.
     */
    public function __construct(Model $permohonan)
    {
        $this->permohonan = $permohonan;
        // Ambil nama dari user yang terkait, atau dari kolom 'nama' jika user tidak ada
        $this->namaPemohon = $permohonan->user->name ?? $permohonan->nama ?? 'Pemohon';
        
        list($this->jenisSuratKey, $this->jenisSuratDisplay) = $this->getPermohonanTypeInfo();
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        // 4. TAMBAHKAN 'broadcast'
        return ['database', 'broadcast']; 
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'permohonan_id' => $this->permohonan->id,
            'jenis_surat'   => $this->jenisSuratDisplay,
            'jenis_surat_key' => $this->jenisSuratKey, 
            'nama_pemohon'  => $this->namaPemohon,
            'pesan'         => "Pengajuan {$this->jenisSuratDisplay} baru dari {$this->namaPemohon}."
        ];
    }

    // 5. TAMBAHKAN FUNGSI INI UNTUK PUSHER (REAL-TIME)
    /**
     * Get the broadcastable representation of the notification.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'message' => "Pengajuan {$this->jenisSuratDisplay} baru dari {$this->namaPemohon}.",
            'permohonan_id' => $this->permohonan->id,
            'jenis_surat_key' => $this->jenisSuratKey,
        ]);
    }


    /**
     * Helper untuk menentukan jenis surat berdasarkan model
     */
    private function getPermohonanTypeInfo(): array
    {
        // Pastikan path model ini sudah benar
        return match(get_class($this->permohonan)) {
            \App\DataTier\Models\PermohonanDomisili::class => ['domisili', 'Keterangan Domisili'],
            \App\DataTier\Models\PermohonanKTM::class      => ['ktm', 'SKTM'], // Saya ganti dari Ktm ke KTM
            \App\DataTier\Models\PermohonanSKU::class      => ['sku', 'Keterangan Usaha (SKU)'],
            default => ['unknown', 'Surat Tidak Dikenal']
        };
    }
}
