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
        Schema::create('permohonan_sku', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Data Pemohon
            $table->string('nik', 16);
            $table->string('nama');
            $table->text('alamat_ktp');
            $table->string('nomor_telp', 15);

            // Data Usaha
            $table->string('nama_usaha');
            $table->string('jenis_usaha');
            $table->text('alamat_usaha');
            $table->string('lama_usaha');

            // Dokumen Pendukung (menyimpan path file)
            $table->string('path_ktp');
            $table->string('path_kk');
            $table->string('path_surat_pengantar');
            $table->string('path_foto_usaha')->nullable(); // Opsional

            // Status Permohonan
            $table->enum('status', ['Diproses', 'Disetujui', 'Ditolak'])->default('Diproses');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permohonan_sku');
    }
};