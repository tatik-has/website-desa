<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::table('permohonan_domisili', function (Blueprint $table) {
            // Tambahkan kolom keterangan_penolakan setelah kolom status
            $table->text('keterangan_penolakan')->nullable()->after('status');
        });
    }

    /**
     * Batalkan migrasi.
     */
    public function down(): void
    {
        Schema::table('permohonan_domisili', function (Blueprint $table) {
            $table->dropColumn('keterangan_penolakan');
        });
    }
};
