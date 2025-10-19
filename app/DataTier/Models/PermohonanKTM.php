<?php

namespace App\DataTier\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermohonanKtm extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung.
     */
    protected $table = 'permohonan_ktm';

    /**
     * Atribut yang dapat diisi secara massal.
     */
    protected $fillable = [
        'user_id', // Ditambahkan agar bisa diisi
        'nik',
        'nama',
        'jenis_kelamin',
        'nomor_telp',
        'alamat_lengkap',
        'keperluan',
        'penghasilan',
        'jumlah_tanggungan',
        'path_ktp',
        'path_kk',
        'path_surat_pengantar_rt_rw',
        'path_foto_rumah',
        'status',
        'keterangan_penolakan',
        'path_surat_jadi',
    ];

    /**
     * Mendefinisikan relasi ke model User.
     */
    public function user()
    {
        return $this->belongsTo(\App\DataTier\Models\User::class, 'user_id');
    }
}