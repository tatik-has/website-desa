<?php

namespace App\LogicTier\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SuratDiajukan implements ShouldBroadcast

{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Data yang akan dikirim bersama notifikasi.
     * Properti harus public agar bisa diakses oleh JavaScript di frontend.
     */
    public $namaPemohon;
    public $jenisSurat;
    public $message;

    /**
     * Buat instance event baru.
     */
    public function __construct($namaPemohon, $jenisSurat)
    {
        $this->namaPemohon = $namaPemohon;
        $this->jenisSurat = $jenisSurat;
        $this->message = "Permohonan {$this->jenisSurat} baru dari {$this->namaPemohon} telah masuk.";
    }

    /**
     * Tentukan di channel mana event ini akan disiarkan.
     */
    public function broadcastOn(): array
    {
        // Siarkan ke channel privat bernama 'admin-channel'.
        // Hanya admin yang bisa mendengarkan channel ini.
        return [
            new PrivateChannel('admin-channel'),
        ];
    }
}