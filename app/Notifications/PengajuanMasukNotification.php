<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Database\Eloquent\Model; 
use Illuminate\Notifications\Messages\BroadcastMessage;

class PengajuanMasukNotification extends Notification implements ShouldQueue
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
        $modelClass = get_class($this->permohonan);
        $modelBaseName = class_basename($modelClass); // Ambil nama class tanpa namespace
        
        // Cek dengan case-insensitive untuk menghindari masalah huruf besar/kecil
        $modelLower = strtolower($modelBaseName);
        
        if (str_contains($modelLower, 'domisili')) {
            return ['domisili', 'Keterangan Domisili'];
        } elseif (str_contains($modelLower, 'ktm')) {
            return ['ktm', 'Surat Keterangan Tidak Mampu (SKTM)'];
        } elseif (str_contains($modelLower, 'sku')) {
            return ['sku', 'Keterangan Usaha (SKU)'];
        } else {
            // Log untuk debugging jika masih ada yang tidak dikenali
            \Log::warning('Model tidak dikenali: ' . $modelClass);
            return ['unknown', 'Surat Tidak Dikenal'];
        }
    }
}