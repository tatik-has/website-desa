<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration.
     */
    public function up(): void
    {
        Schema::create('permohonan_domisili', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nik', 16);
            $table->string('nama');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->text('alamat_ktp');
            $table->text('alamat_domisili');
            $table->string('rt_domisili', 3);
            $table->string('rw_domisili', 3);
            $table->string('nomor_telp');
            $table->string('path_ktp'); // path file KTP
            $table->string('path_kk');  // path file KK
            $table->string('status')->default('Diproses'); // status permohonan
            $table->timestamps();
        });
    }

    /**
     * Batalkan migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('permohonan_domisili');
    }
};

