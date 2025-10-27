<?php

namespace App\Listeners;

use App\LogicTier\Events\StatusDiperbarui;
use App\DataTier\Models\User;
use App\Notifications\StatusUpdateNotification;

class KirimNotifikasiStatus
{
    /**
     * Handle the event.
     */
    public function handle(StatusDiperbarui $event): void
    {
        // Ambil user berdasarkan user_id dari event
        $user = User::find($event->userId);

        if ($user) {
            // Kirim notifikasi ke user
            $user->notify(new StatusUpdateNotification($event));
        }
    }
}
