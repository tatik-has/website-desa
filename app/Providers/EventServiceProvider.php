<?php
// File: app/Providers/EventServiceProvider.php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

// 1. Import Event dan Listener Anda
use App\LogicTier\Events\SuratDiajukan;
use App\Listeners\KirimNotifikasiKeAdmin;
use App\LogicTier\Events\StatusDiperbarui;
use App\Listeners\KirimNotifikasiKeUser; // <-- Ini file baru dari Langkah 3

class EventServiceProvider extends ServiceProvider
{
    /**
     * Pemetaan event ke listener untuk aplikasi.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        // 2. Daftarkan Event "SuratDiajukan"
        SuratDiajukan::class => [
            KirimNotifikasiKeAdmin::class,
        ],

        // 3. Daftarkan Event "StatusDiperbarui"
        StatusDiperbarui::class => [
            KirimNotifikasiKeUser::class, // <-- Ini file baru dari Langkah 3
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}