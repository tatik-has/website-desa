<?php

namespace App\LogicTier\Services;

use App\DataTier\Models\PermohonanSKU;
use App\LogicTier\Events\SuratDiajukan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuratSkuService
{
    /**
     * Logika dari SKUController@store
     */
    public function storeSku(Request $request)
    {
        $request->validate([
            'nik' => 'required|digits:16',
            'nama' => 'required|string|max:255',
            'alamat_ktp' => 'required|string',
            'nomor_telp' => [
                'required',
                'string',
                'regex:/^(08|\+628)[0-9]{9,11}$/',
            ],
            'nama_usaha' => 'required|string|max:255',
            'jenis_usaha' => 'required|string|max:255',
            'alamat_usaha' => 'required|string',
            'lama_usaha' => 'required|string|max:50',
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
            'surat_pengantar' => [
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
            'foto_usaha' => [
                'nullable',
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

        $dokumenPaths = [];
        $filesToUpload = [
            'ktp' => 'path_ktp',
            'kk' => 'path_kk',
            'surat_pengantar' => 'path_surat_pengantar',
            'foto_usaha' => 'path_foto_usaha',
        ];

        foreach ($filesToUpload as $fileKey => $pathKey) {
            if ($request->hasFile($fileKey)) {
                $file = $request->file($fileKey);
                $filename = $request->nik . '-' . $fileKey . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('public/dokumen_sku', $filename);
                $dokumenPaths[$pathKey] = $path;
            }
        }

        $dataToStore = $request->only([
            'nik',
            'nama',
            'alamat_ktp',
            'nomor_telp',
            'nama_usaha',
            'jenis_usaha',
            'alamat_usaha',
            'lama_usaha'
        ]);

        $permohonan = PermohonanSKU::create(array_merge(
            [
                'user_id' => Auth::id(),
                'status' => 'Diproses',
            ],
            $dataToStore,
            $dokumenPaths
        ));

        event(new SuratDiajukan(
            $permohonan,
            'SKU'
        ));

        return $permohonan;
    }
}