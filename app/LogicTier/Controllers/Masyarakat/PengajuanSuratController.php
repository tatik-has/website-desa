<?php

namespace App\LogicTier\Controllers\Masyarakat;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class PengajuanSuratController extends BaseController
{
    public function showPengajuanForm()
    {
        return view('presentation_tier.masyarakat.permohonan.pengajuan');
    }

    // Method ini TIDAK DIPAKAI karena view langsung ke form
    // Tapi jika mau tetap ada, biarkan saja
    public function ajukan($jenis): RedirectResponse
    {
        // Validasi jenis surat
        $jenisValid = ['domisili', 'usaha', 'kematian', 'kelahiran'];
        
        if (!in_array($jenis, $jenisValid)) {
            return redirect('/dashboard')->with('error', 'Jenis surat tidak valid');
        }

        // Redirect ke form yang sesuai
        switch ($jenis) {
            case 'domisili':
                return redirect()->route('pengajuan.domisili.form');
            
            case 'usaha':
                return redirect()->route('sku.create');
            
            case 'kematian':
                return redirect()->route('kematian.form');
            
            case 'kelahiran':
                return redirect()->route('kelahiran.form');
            
            default:
                return redirect('/dashboard');
        }
    }
}