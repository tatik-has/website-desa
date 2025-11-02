<?php

namespace App\LogicTier\Controllers\Masyarakat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as BaseController;
use App\DataTier\Models\PermohonanSKU;
use Illuminate\Support\Facades\Auth;
use App\LogicTier\Events\SuratDiajukan; // Import event


class SKUController extends BaseController
{
    public function create()
    {
        return view('presentation_tier.masyarakat.permohonan.usaha');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|digits:16',
            'nama' => 'required|string|max:255',
            'alamat_ktp' => 'required|string',
            'nomor_telp' => 'required|string|max:15',
            'nama_usaha' => 'required|string|max:255',
            'jenis_usaha' => 'required|string|max:255',
            'alamat_usaha' => 'required|string',
            'lama_usaha' => 'required|string|max:50',
            'ktp' => 'required|file|mimes:jpg,jpeg,png|max:2048',
            'kk' => 'required|file|mimes:jpg,jpeg,png|max:2048',
            'surat_pengantar' => 'required|file|mimes:jpg,jpeg,png|max:2048',
            'foto_usaha' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
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

        // Simpan ke database dan ambil hasilnya
        $permohonan = PermohonanSKU::create(array_merge(
            [
                'user_id' => Auth::id(),
                'status' => 'Diproses',
            ],
            $dataToStore,
            $dokumenPaths
        ));

        // PICU EVENT
        event(new SuratDiajukan(
            $permohonan,
            'SKU'
        ));


        return redirect()->back()->with('success', 'Permohonan SKU Anda telah berhasil dikirim!');
    }
}
