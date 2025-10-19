<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Tambahan field baru
            $table->string('nik', 16)->unique()->nullable();
            $table->string('desa')->nullable();
            $table->text('alamat')->nullable();
            $table->string('verification_code')->nullable();
            $table->boolean('is_verified')->default(false);
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'nik',
                'desa',
                'alamat',
                'verification_code',
                'is_verified'
            ]);
        });
    }
};
