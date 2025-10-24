<?php
// File: app/Listeners/KirimNotifikasiKeUser.php

namespace App\Listeners;

use App\LogicTier\Events\StatusDiperbarui;
use App\DataTier\Models\User;
use App\Notifications\StatusUpdateNotification; // Notifikasi baru
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class KirimNotifikasiKeUser implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(StatusDiperbarui $event): void
    {
        // 1. Cari user yang mengajukan surat
        $user = User::find($event->userId);

        if ($user) {
            // 2. Buat notifikasi baru menggunakan event
            $notification = new StatusUpdateNotification($event);

            // 3. Kirim notifikasi ke user tersebut
            $user->notify($notification);
        }
    }
}