<?php

namespace App\DataTier\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermohonanSKU extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung.
     */
    protected $table = 'permohonan_sku';

    protected $fillable = [
        'user_id',
        'nik',
        'nama',
        'alamat_ktp',
        'nomor_telp',
        'nama_usaha',
        'jenis_usaha',
        'alamat_usaha',
        'lama_usaha',
        'path_ktp',
        'path_kk',
        'path_surat_pengantar',
        'path_foto_usaha',
        'status',
        'keterangan_penolakan',
        'path_surat_jadi',
    ];

    public function user()
    {
        return $this->belongsTo(\App\DataTier\Models\User::class, 'user_id');
    }
}