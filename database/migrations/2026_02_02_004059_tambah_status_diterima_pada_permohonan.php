<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Tambahkan nilai 'Diterima' ke kolom status enum
     * di tiga tabel permohonan.
     */
    public function up(): void
    {
        Schema::table('permohonan_domisili', function (Blueprint $table) {
            $table->enum('status', ['Diproses', 'Diterima', 'Selesai', 'Ditolak'])->default('Diproses')->change();
        });

        Schema::table('permohonan_ktm', function (Blueprint $table) {
            $table->enum('status', ['Diproses', 'Diterima', 'Selesai', 'Ditolak'])->default('Diproses')->change();
        });

        Schema::table('permohonan_sku', function (Blueprint $table) {
            $table->enum('status', ['Diproses', 'Diterima', 'Selesai', 'Ditolak'])->default('Diproses')->change();
        });
    }

    /**
     * Kembalikan ke enum sebelumnya (tanpa 'Diterima').
     */
    public function down(): void
    {
        Schema::table('permohonan_domisili', function (Blueprint $table) {
            $table->enum('status', ['Diproses', 'Selesai', 'Ditolak'])->default('Diproses')->change();
        });

        Schema::table('permohonan_ktm', function (Blueprint $table) {
            $table->enum('status', ['Diproses', 'Selesai', 'Ditolak'])->default('Diproses')->change();
        });

        Schema::table('permohonan_sku', function (Blueprint $table) {
            $table->enum('status', ['Diproses', 'Selesai', 'Ditolak'])->default('Diproses')->change();
        });
    }
};