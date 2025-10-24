<?php
// File: app/Notifications/StatusUpdateNotification.php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\LogicTier\Events\StatusDiperbarui; // Import event

class StatusUpdateNotification extends Notification
{
    use Queueable;

    protected $message;
    protected $permohonan; // Tambahkan ini

    /**
     * Buat instance notifikasi baru.
     * Kita terima $event agar bisa mengakses $event->message
     * dan $event->permohonan (yang akan kita tambahkan)
     */
    public function __construct(StatusDiperbarui $event)
    {
        $this->message = $event->message; // Ambil pesan dari event
        $this->permohonan = $event->permohonan; // Ambil permohonan dari event
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database']; // Hanya simpan ke database
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        // Tentukan path file jika statusnya "Selesai"
        $filePath = null;
        if ($this->permohonan->status == 'Selesai') {
            $filePath = $this->permohonan->path_surat_jadi;
        }

        return [
            'pesan' => $this->message,
            'permohonan_id' => $this->permohonan->id,
            'jenis_surat' => $this->getJenisSurat(),
            'status' => $this->permohonan->status,
            'file_path' => $filePath, // Simpan path file PDF
        ];
    }

    /**
     * Helper untuk mendapat nama jenis surat
     */
    private function getJenisSurat(): string
    {
        return match(get_class($this->permohonan)) {
            \App\DataTier\Models\PermohonanDomisili::class => 'Keterangan Domisili',
            \App\DataTier\Models\PermohonanSKU::class => 'Keterangan Usaha (SKU)',
            \App\DataTier\Models\PermohonanKtm::class => 'Keterangan Tidak Mampu (KTM)',
            default => 'Surat'
        };
    }
}