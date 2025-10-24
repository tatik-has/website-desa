<?php

namespace App\Listeners;

use App\LogicTier\Events\SuratDiajukan; // Namespace event
use App\DataTier\Models\Admin;          // Model Admin
use App\Notifications\PengajuanMasukNotification; // Notifikasi yang digunakan
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class KirimNotifikasiKeAdmin implements ShouldQueue
{
    use InteractsWithQueue;
    
    // ... (construct biarkan) ...

    /**
     * Handle the event.
     */
    public function handle(SuratDiajukan $event): void
    {
        // 1. Ambil semua admin dari database
        $admins = Admin::all();

        // 2. Buat instance notifikasi HANYA dengan model permohonan
        //    (Parameter $event->jenisSurat sudah tidak diperlukan)
        $notification = new PengajuanMasukNotification($event->permohonan); // <-- UBAH BARIS INI

        // 3. Kirim notifikasi ke semua admin (tersimpan di database)
        Notification::send($admins, $notification);
    }
}