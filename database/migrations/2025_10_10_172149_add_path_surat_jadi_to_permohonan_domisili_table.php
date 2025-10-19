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
            // Tambahkan kolom ini setelah 'keterangan_penolakan'
            $table->string('path_surat_jadi')->nullable()->after('keterangan_penolakan');
        });
    }

    /**
     * Balikkan migrasi.
     */
    public function down(): void
    {
        Schema::table('permohonan_domisili', function (Blueprint $table) {
            $table->dropColumn('path_surat_jadi');
        });
    }
};
