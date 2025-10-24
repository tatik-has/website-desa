<?php
// File: app/LogicTier/Events/StatusDiperbarui.php

namespace App\LogicTier\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Database\Eloquent\Model; // <-- TAMBAHKAN INI

class StatusDiperbarui implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userId;
    public $message;
    public Model $permohonan; // <-- TAMBAHKAN INI

    /**
     * Buat instance event baru.
     * Ubah parameter untuk menerima Model
     */
    public function __construct(Model $permohonan) // <-- UBAH PARAMETER INI
    {
        $this->permohonan = $permohonan; // <-- TAMBAHKAN INI
        $this->userId = $permohonan->user_id; // <-- UBAH INI

        // Membuat pesan notifikasi yang lebih informatif
        $status = $permohonan->status; // Ambil dari model
        $keterangan = $permohonan->keterangan_penolakan; // Ambil dari model

        if ($status === 'Ditolak') {
            $this->message = "Permohonan Anda DITOLAK. Alasan: {$keterangan}";
        } elseif ($status === 'Selesai') {
            $this->message = "Selamat! Permohonan Anda telah SELESAI dan surat dapat diunduh.";
        } else {
            $this->message = "Status permohonan Anda telah diubah menjadi '{$status}'.";
        }
    }

    /**
     * Tentukan di channel mana event ini akan disiarkan.
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.' . $this->userId),
        ];
    }
}