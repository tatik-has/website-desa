<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk menambah kolom nama_pemohon ke tabel surats.
     */
    public function up(): void
    {
        Schema::table('surats', function (Blueprint $table) {
            // Tambahkan kolom nama_pemohon setelah user_id
            $table->string('nama_pemohon')->after('user_id')->nullable();
        });
    }

    /**
     * Kembalikan perubahan (rollback).
     */
    public function down(): void
    {
        Schema::table('surats', function (Blueprint $table) {
            // Hapus kolom nama_pemohon jika migrasi di-rollback
            $table->dropColumn('nama_pemohon');
        });
    }
};
