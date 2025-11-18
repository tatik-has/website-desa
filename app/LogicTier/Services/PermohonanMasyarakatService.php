<?php

namespace App\LogicTier\Services;

// Panggil semua Model dari DataTier
use App\DataTier\Models\PermohonanDomisili;
use App\DataTier\Models\PermohonanKTM;
use App\DataTier\Models\PermohonanSKU;

// Panggil komponen lain yang dibutuhkan
use App\LogicTier\Events\SuratDiajukan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermohonanMasyarakatService
{
    /**
     * Logika dari DomisiliController@store
     * (Saya copy-paste utuh kode Anda)`
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

    /**
     * Logika dari SKTMController@store
     * (Diperbaiki validasi nomor_telp dan file upload)
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
                        'application/octet-stream' // Kadang file dari HP menggunakan ini
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

    /**
     * Logika dari SKUController@store
     * (Saya copy-paste utuh kode Anda)
     */
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

    /**
     * Logika dari SuratController@history
     */
    public function getHistory(int $userId)
    {
        $domisili = PermohonanDomisili::where('user_id', $userId)->latest()->get();
        $ktm = PermohonanKTM::where('user_id', $userId)->latest()->get();
        $sku = PermohonanSKU::where('user_id', $userId)->latest()->get();

        return collect()
            ->merge($domisili->map(function ($item) {
                $item->jenis_surat = 'Keterangan Domisili';
                return $item;
            }))
            ->merge($ktm->map(function ($item) {
                $item->jenis_surat = 'Keterangan Tidak Mampu';
                return $item;
            }))
            ->merge($sku->map(function ($item) {
                $item->jenis_surat = 'Keterangan Usaha';
                return $item;
            }))
            ->sortByDesc('created_at');
    }
}