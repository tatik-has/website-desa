<?php

namespace App\DataTier\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermohonanDomisili extends Model
{
    use HasFactory;

    protected $table = 'permohonan_domisili';

    protected $fillable = [
        'user_id',
        'nik',
        'nama',
        'alamat_domisili',
        'nomor_telp',
        'rt_domisili',
        'rw_domisili',
        'jenis_kelamin',
        'alamat_ktp',
        'path_ktp',
        'path_kk',
        'status',
        'keterangan_penolakan',
        'path_surat_jadi',
        'archived_at', // Tambahkan ini
    ];

    // Tambahkan casting untuk archived_at
    protected $casts = [
        'archived_at' => 'datetime',
    ];
    
    public function user()
    {
        return $this->belongsTo(\App\DataTier\Models\User::class, 'user_id');
    }
}