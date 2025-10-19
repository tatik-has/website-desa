<?php

namespace App\Listeners;

use App\Events\SuratDiajukan; // Event yang sudah Anda buat
use App\Models\Admin;          // Model Admin Anda
use App\Notifications\PengajuanMasukNotification; // Notifikasi yang baru kita buat
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class KirimNotifikasiKeAdmin
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(SuratDiajukan $event): void
    {
        // 1. Ambil semua admin dari database
        $admins = Admin::all();

        // 2. Buat instance notifikasi dengan data dari event
        $notification = new PengajuanMasukNotification(
            $event->permohonan, 
            $event->jenisSurat
        );

        // 3. Kirim notifikasi ke semua admin
        Notification::send($admins, $notification);
    }
}