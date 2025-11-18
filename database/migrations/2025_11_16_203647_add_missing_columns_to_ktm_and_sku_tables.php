<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambahkan kolom yang hilang di permohonan_ktm
        if (Schema::hasTable('permohonan_ktm')) {
            Schema::table('permohonan_ktm', function (Blueprint $table) {
                // Cek apakah kolom sudah ada sebelum menambahkan
                if (!Schema::hasColumn('permohonan_ktm', 'keterangan_penolakan')) {
                    $table->text('keterangan_penolakan')->nullable()->after('status');
                }
                if (!Schema::hasColumn('permohonan_ktm', 'path_surat_jadi')) {
                    $table->string('path_surat_jadi')->nullable()->after('keterangan_penolakan');
                }
            });
        }

        // Tambahkan kolom yang hilang di permohonan_sku
        if (Schema::hasTable('permohonan_sku')) {
            Schema::table('permohonan_sku', function (Blueprint $table) {
                if (!Schema::hasColumn('permohonan_sku', 'keterangan_penolakan')) {
                    $table->text('keterangan_penolakan')->nullable()->after('status');
                }
                if (!Schema::hasColumn('permohonan_sku', 'path_surat_jadi')) {
                    $table->string('path_surat_jadi')->nullable()->after('keterangan_penolakan');
                }
            });
        }
    }

    public function down(): void
    {
        Schema::table('permohonan_ktm', function (Blueprint $table) {
            $table->dropColumn(['keterangan_penolakan', 'path_surat_jadi']);
        });

        Schema::table('permohonan_sku', function (Blueprint $table) {
            $table->dropColumn(['keterangan_penolakan', 'path_surat_jadi']);
        });
    }
};