<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambahkan archived_at ke permohonan_domisili
        if (Schema::hasTable('permohonan_domisili')) {
            Schema::table('permohonan_domisili', function (Blueprint $table) {
                if (!Schema::hasColumn('permohonan_domisili', 'archived_at')) {
                    $table->timestamp('archived_at')->nullable()->after('path_surat_jadi');
                }
            });
        }

        // Tambahkan archived_at ke permohonan_ktm
        if (Schema::hasTable('permohonan_ktm')) {
            Schema::table('permohonan_ktm', function (Blueprint $table) {
                if (!Schema::hasColumn('permohonan_ktm', 'archived_at')) {
                    $table->timestamp('archived_at')->nullable()->after('path_surat_jadi');
                }
            });
        }

        // Tambahkan archived_at ke permohonan_sku
        if (Schema::hasTable('permohonan_sku')) {
            Schema::table('permohonan_sku', function (Blueprint $table) {
                if (!Schema::hasColumn('permohonan_sku', 'archived_at')) {
                    $table->timestamp('archived_at')->nullable()->after('path_surat_jadi');
                }
            });
        }
    }

    public function down(): void
    {
        Schema::table('permohonan_domisili', function (Blueprint $table) {
            $table->dropColumn('archived_at');
        });

        Schema::table('permohonan_ktm', function (Blueprint $table) {
            $table->dropColumn('archived_at');
        });

        Schema::table('permohonan_sku', function (Blueprint $table) {
            $table->dropColumn('archived_at');
        });
    }
};