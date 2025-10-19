<?php

namespace App\LogicTier\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StatusDiperbarui implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Data yang akan dikirim ke user.
     */
    public $userId;
    public $message;

    /**
     * Buat instance event baru.
     */
    public function __construct($userId, $status, $keterangan)
    {
        $this->userId = $userId;

        // Membuat pesan notifikasi yang lebih informatif
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
        // Siarkan ke channel privat yang spesifik untuk user tertentu.
        // Contoh: 'user.1', 'user.42', dst. Ini memastikan hanya user yang
        // bersangkutan yang menerima notifikasi ini.
        return [
            new PrivateChannel('user.' . $this->userId),
        ];
    }
}