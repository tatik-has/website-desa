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
        // Menambahkan kolom ke tabel permohonan_sku
        Schema::table('permohonan_sku', function (Blueprint $table) {
            $table->text('keterangan_penolakan')->nullable()->after('status');
            $table->string('path_surat_jadi')->nullable()->after('keterangan_penolakan');
        });

        // Menambahkan kolom ke tabel permohonan_ktm
        Schema::table('permohonan_ktm', function (Blueprint $table) {
            $table->text('keterangan_penolakan')->nullable()->after('status');
            $table->string('path_surat_jadi')->nullable()->after('keterangan_penolakan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Method untuk membatalkan migrasi (jika diperlukan)
        Schema::table('permohonan_sku', function (Blueprint $table) {
            $table->dropColumn(['keterangan_penolakan', 'path_surat_jadi']);
        });

        Schema::table('permohonan_ktm', function (Blueprint $table) {
            $table->dropColumn(['keterangan_penolakan', 'path_surat_jadi']);
        });
    }
};