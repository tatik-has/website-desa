<?php

namespace App\LogicTier\Controllers\Masyarakat;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller as BaseController;
use App\DataTier\Models\PermohonanKTM;
use App\LogicTier\Events\SuratDiajukan;
use Illuminate\Support\Facades\Auth;


class SKTMController extends BaseController
{
    public function create()
    {
        return view('presentation_tier.masyarakat.permohonan.ktm');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik' => 'required|digits:16|unique:permohonan_ktm,nik',
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'nomor_telp' => 'required|string|max:15',
            'alamat_lengkap' => 'required|string',
            'keperluan' => 'required|string',
            'penghasilan' => 'required|numeric|min:0',
            'jumlah_tanggungan' => 'required|integer|min:0',
            'ktp' => 'required|image|mimes:jpeg,png,jpg|max:1024',
            'kk' => 'required|image|mimes:jpeg,png,jpg|max:1024',
            'surat_pengantar_rt_rw' => 'required|image|mimes:jpeg,png,jpg|max:1024',
            'foto_rumah' => 'required|image|mimes:jpeg,png,jpg|max:1024',
            'declaration' => 'accepted',
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
            // === BAGIAN YANG DIPERBAIKI ===
            $request->only([
                'nik', 'nama', 'jenis_kelamin', 'nomor_telp', 'alamat_lengkap',
                'keperluan', 'penghasilan', 'jumlah_tanggungan' // <-- INI YANG TERLEWAT
            ]),
            $dokumenPaths
        );

        // Simpan ke database dan ambil hasilnya
        $permohonan = PermohonanKTM::create($dataToStore);

        // PICU EVENT
        event(new SuratDiajukan(
            $permohonan,
            'SKTM'
        ));

        return redirect()
            ->back()
            ->with('success', 'Permohonan SKTM Anda telah berhasil dikirim! Mohon tunggu informasi selanjutnya.');
    }
}
