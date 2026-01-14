<?php

namespace App\LogicTier\Services;

use App\DataTier\Models\PermohonanKTM;
use App\LogicTier\Events\SuratDiajukan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuratKtmService
{
    /**
     * Logika dari SKTMController@store
     */
    public function storeKtm(Request $request)
    {
        $validated = $request->validate([
            'nik' => 'required|digits:16|unique:permohonan_ktm,nik',
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'nomor_telp' => [
                'required',
                'string',
                'regex:/^(\+62|62|08)[0-9]{9,12}$/',
            ],
            'alamat_lengkap' => 'required|string',
            'keperluan' => 'required|string',
            'penghasilan' => 'required|numeric|min:0',
            'jumlah_tanggungan' => 'required|integer|min:0',
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
                        $fail("File $attribute harus berupa gambar (JPG/PNG), PDF, atau dokumen Word. File yang diupload: $extension ($mimeType)");
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
                        $fail("File $attribute harus berupa gambar (JPG/PNG), PDF, atau dokumen Word. File yang diupload: $extension ($mimeType)");
                    }
                }
            ],
            'surat_pengantar_rt_rw' => [
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
                        $fail("File $attribute harus berupa gambar (JPG/PNG), PDF, atau dokumen Word. File yang diupload: $extension ($mimeType)");
                    }
                }
            ],
            'foto_rumah' => [
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
                        $fail("File $attribute harus berupa gambar (JPG/PNG), PDF, atau dokumen Word. File yang diupload: $extension ($mimeType)");
                    }
                }
            ],
            'declaration' => 'accepted',
        ], [
            'nomor_telp.regex' => 'Nomor telepon harus diawali dengan +62, 62, atau 08 dan memiliki total 11-14 karakter.',
        ]);

        $dokumenPaths = [];
        $filesToUpload = [
            'ktp' => 'path_ktp',
            'kk' => 'path_kk',
            'surat_pengantar_rt_rw' => 'path_surat_pengantar_rt_rw',
            'foto_rumah' => 'path_foto_rumah'
        ];

        foreach ($filesToUpload as $fileKey => $pathKey) {
            if ($request->hasFile($fileKey)) {
                $file = $request->file($fileKey);
                $filename = $request->nik . '-' . $fileKey . '-' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('public/dokumen_sktm', $filename);
                $dokumenPaths[$pathKey] = $path;
            }
        }

        $dataToStore = array_merge(
            [
                'user_id' => Auth::id(),
                'status' => 'Diproses',
            ],
            $request->only([
                'nik',
                'nama',
                'jenis_kelamin',
                'nomor_telp',
                'alamat_lengkap',
                'keperluan',
                'penghasilan',
                'jumlah_tanggungan'
            ]),
            $dokumenPaths
        );

        $permohonan = PermohonanKTM::create($dataToStore);

        event(new SuratDiajukan(
            $permohonan,
            'SKTM'
        ));

        return $permohonan;
    }
}