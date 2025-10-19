<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('permohonan_ktm', function (Blueprint $table) {
            // Menambahkan kolom user_id sebagai foreign key ke tabel users
            $table->foreignId('user_id')
                  ->nullable() // Boleh kosong (jika ada data lama)
                  ->constrained('users') // Terhubung ke tabel 'users'
                  ->after('id'); // Posisikan setelah kolom 'id'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permohonan_ktm', function (Blueprint $table) {
            // Hapus foreign key constraint dulu
            $table->dropForeign(['user_id']);
            // Hapus kolomnya
            $table->dropColumn('user_id');
        });
    }
};