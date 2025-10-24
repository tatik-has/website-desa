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
        Schema::create('permohonan_ktm', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            // Data Diri Pemohon
            $table->string('nik', 16)->unique();
            $table->string('nama');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->string('nomor_telp', 13);
            $table->text('alamat_lengkap');

            // Data Pendukung & Keperluan
            $table->text('keperluan');
            $table->bigInteger('penghasilan');
            $table->integer('jumlah_tanggungan');

            // Path Dokumen
            $table->string('path_ktp');
            $table->string('path_kk');
            $table->string('path_surat_pengantar_rt_rw');
            $table->string('path_foto_rumah');
            
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
        Schema::dropIfExists('permohonan_ktm');
    }
};