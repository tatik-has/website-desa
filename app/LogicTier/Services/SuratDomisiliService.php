<?php

namespace App\LogicTier\Services;

use App\DataTier\Models\PermohonanDomisili;
use App\LogicTier\Events\SuratDiajukan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuratDomisiliService
{
    /**
     * Logika dari DomisiliController@store
     */
    public function storeDomisili(Request $request)
    {
        $validated = $request->validate([
            'nik' => 'required|digits:16',
            'nama' => 'required|string|max:255',
            'alamat_domisili' => 'required|string',
            'nomor_telp' => [
                'required',
                'string',
                'regex:/^(08|\+628)[0-9]{9,11}$/',
            ],
            'rt_domisili' => 'required|numeric',
            'rw_domisili' => 'required|numeric',
            'jenis_kelamin' => 'required|string',
            'alamat_ktp' => 'required|string',
            'ktp' => [
                'required',
                'file',
                'max:2048',
                function ($attribute, $value, $fail) {
                    $allowedExtensions = ['jpeg', 'jpg', 'png', 'pdf', 'doc', 'docx'];
                    $allowedMimeTypes = [
                        'image/jpeg',
                        'image/jpg',
                        'image/pjpeg',
                        'image/png',
                        'image/x-png',
                        'application/pdf',
                        'application/x-pdf',
                        'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'application/octet-stream'
                    ];

                    $extension = strtolower($value->getClientOriginalExtension());
                    $mimeType = $value->getMimeType();

                    if (!in_array($extension, $allowedExtensions) && !in_array($mimeType, $allowedMimeTypes)) {
                        $fail("File $attribute harus berupa gambar (JPG/PNG), PDF, atau dokumen Word.");
                    }
                }
            ],
            'kk' => [
                'required',
                'file',
                'max:2048',
                function ($attribute, $value, $fail) {
                    $allowedExtensions = ['jpeg', 'jpg', 'png', 'pdf', 'doc', 'docx'];
                    $allowedMimeTypes = [
                        'image/jpeg',
                        'image/jpg',
                        'image/pjpeg',
                        'image/png',
                        'image/x-png',
                        'application/pdf',
                        'application/x-pdf',
                        'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'application/octet-stream'
                    ];

                    $extension = strtolower($value->getClientOriginalExtension());
                    $mimeType = $value->getMimeType();

                    if (!in_array($extension, $allowedExtensions) && !in_array($mimeType, $allowedMimeTypes)) {
                        $fail("File $attribute harus berupa gambar (JPG/PNG), PDF, atau dokumen Word.");
                    }
                }
            ],
        ], [
            'nomor_telp.regex' => 'Nomor telepon harus diawali dengan 08 atau +628 dan memiliki total 11-13 digit (contoh: 08123456789 atau +628123456789).',
        ]);

        $pathKTP = $request->file('ktp')->store('public/dokumen/ktp');
        $pathKK = $request->file('kk')->store('public/dokumen/kk');

        $permohonan = PermohonanDomisili::create([
            'user_id' => Auth::id(),
            'nik' => $validated['nik'],
            'nama' => $validated['nama'],
            'alamat_domisili' => $validated['alamat_domisili'],
            'nomor_telp' => $validated['nomor_telp'],
            'rt_domisili' => $validated['rt_domisili'],
            'rw_domisili' => $validated['rw_domisili'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'alamat_ktp' => $validated['alamat_ktp'],
            'path_ktp' => $pathKTP,
            'path_kk' => $pathKK,
            'status' => 'Diproses',
        ]);

        event(new SuratDiajukan(
            $permohonan,
            'Keterangan Domisili'
        ));

        return $permohonan;
    }
}