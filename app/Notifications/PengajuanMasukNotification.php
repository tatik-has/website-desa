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
    protected $jenisSurat;
    protected $namaPemohon;

    /**
     * Create a new notification instance.
     *
     * @param \Illuminate\Database\Eloquent\Model $permohonan
     * @param string $jenisSurat
     */
    public function __construct(Model $permohonan, string $jenisSurat)
    {
        $this->permohonan = $permohonan;
        $this->jenisSurat = $jenisSurat;
        $this->namaPemohon = $permohonan->nama; // Asumsi semua model permohonan punya kolom 'nama'
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database']; // Kita akan simpan notifikasi di database
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        // Data ini yang akan disimpan di kolom 'data' pada tabel notifications
        return [
            'permohonan_id' => $this->permohonan->id,
            'jenis_surat'   => $this->jenisSurat,
            'nama_pemohon'  => $this->namaPemohon,
            'pesan'         => "Pengajuan {$this->jenisSurat} baru dari {$this->namaPemohon}."
        ];
    }
}