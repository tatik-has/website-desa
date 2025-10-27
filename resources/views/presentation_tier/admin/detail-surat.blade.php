@extends('presentation_tier.admin.layout')

@push('styles')
    <link rel="stylesheet" href="{{ asset('presentation_tier/css/detail-surat.css') }}">
@endpush

@section('content')
<div class="container-detail mt-4">
    <!-- Tombol Kembali -->
    <div class="mb-3">
        <a href="{{ route('admin.surat.index') }}" class="btn-back">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5M12 19l-7-7 7-7"/>
            </svg>
            Kembali ke Daftar Permohonan
        </a>
    </div>

    <div class="card-detail shadow-sm border-0">
        <div class="card-detail-header">
            <div class="header-content">
                <div class="header-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                        <line x1="16" y1="13" x2="8" y2="13"/>
                        <line x1="16" y1="17" x2="8" y2="17"/>
                    </svg>
                </div>
                <div class="header-text">
                    <h5 class="mb-0">📝 Detail Surat {{ strtoupper($title ?? 'SURAT') }}</h5>
                    <p class="header-subtitle">Informasi lengkap permohonan surat</p>
                </div>
            </div>
        </div>

        <div class="card-detail-body px-5 py-4">
            {{-- ============================================ --}}
            {{-- SECTION: DATA PEMOHON (SEMUA JENIS SURAT) --}}
            {{-- ============================================ --}}
            <h4 class="section-title">📋 Data Pemohon</h4>
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="info-item-modern">
                        <label>Nama Lengkap</label>
                        <p>{{ $permohonan->nama ?? '-' }}</p>
                    </div>
                    <div class="info-item-modern">
                        <label>NIK</label>
                        <p>{{ $permohonan->nik ?? '-' }}</p>
                    </div>

                    {{-- Jenis Kelamin (untuk SKTM dan Domisili) --}}
                    @if(isset($permohonan->jenis_kelamin))
                        <div class="info-item-modern">
                            <label>Jenis Kelamin</label>
                            <p>{{ $permohonan->jenis_kelamin }}</p>
                        </div>
                    @endif

                    {{-- Nomor Telepon --}}
                    @if(isset($permohonan->nomor_telp))
                        <div class="info-item-modern">
                            <label>Nomor Telepon / WhatsApp</label>
                            <p>{{ $permohonan->nomor_telp }}</p>
                        </div>
                    @endif
                </div>

                <div class="col-md-6">
                    {{-- Tanggal Pengajuan --}}
                    <div class="info-item-modern">
                        <label>Tanggal Pengajuan</label>
                        <p>{{ \Carbon\Carbon::parse($permohonan->created_at)->format('d-m-Y H:i') }} WIB</p>
                    </div>

                    {{-- Status --}}
                    <div class="info-item-modern">
                        <label>Status Permohonan</label>
                        <p>
                            <span class="status-badge 
                                @if($permohonan->status == 'Diproses') status-diproses
                                @elseif($permohonan->status == 'Selesai') status-selesai
                                @elseif($permohonan->status == 'Ditolak') status-ditolak
                                @else status-default @endif">
                                <span class="status-dot"></span>
                                {{ $permohonan->status }}
                            </span>
                        </p>
                    </div>

                    {{-- RT/RW untuk Domisili --}}
                    @if(isset($permohonan->rt_domisili) && isset($permohonan->rw_domisili))
                        <div class="info-item-modern">
                            <label>RT / RW Domisili</label>
                            <p>RT {{ $permohonan->rt_domisili }} / RW {{ $permohonan->rw_domisili }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Alamat (full width) --}}
            <div class="row mb-3">
                <div class="col-12">
                    @if(isset($permohonan->alamat_lengkap))
                        {{-- SKTM --}}
                        <div class="info-item-modern">
                            <label>Alamat Lengkap (sesuai KK)</label>
                            <p>{{ $permohonan->alamat_lengkap }}</p>
                        </div>
                    @elseif(isset($permohonan->alamat_ktp))
                        {{-- SKU atau Domisili --}}
                        <div class="info-item-modern">
                            <label>Alamat Sesuai KTP</label>
                            <p>{{ $permohonan->alamat_ktp }}</p>
                        </div>
                    @endif

                    @if(isset($permohonan->alamat_domisili))
                        {{-- Khusus Domisili --}}
                        <div class="info-item-modern">
                            <label>Alamat Domisili Sekarang</label>
                            <p>{{ $permohonan->alamat_domisili }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- ============================================ --}}
            {{-- SECTION: DATA SPESIFIK SKTM --}}
            {{-- ============================================ --}}
            @if($jenis_surat === 'SKTM')
                <hr class="divider-modern">
                <h4 class="section-title">💰 Data Ekonomi & Keperluan</h4>
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="info-item-modern">
                            <label>Keperluan Pembuatan SKTM</label>
                            <p>{{ $permohonan->keperluan ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-item-modern">
                            <label>Penghasilan Rata-rata / Bulan</label>
                            <p>Rp {{ number_format($permohonan->penghasilan ?? 0, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-item-modern">
                            <label>Jumlah Anggota Keluarga yang Ditanggung</label>
                            <p>{{ $permohonan->jumlah_tanggungan ?? '-' }} orang</p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- ============================================ --}}
            {{-- SECTION: DATA SPESIFIK SKU --}}
            {{-- ============================================ --}}
            @if($jenis_surat === 'SKU')
                <hr class="divider-modern">
                <h4 class="section-title">🏪 Data Usaha</h4>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="info-item-modern">
                            <label>Nama Usaha</label>
                            <p>{{ $permohonan->nama_usaha ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-item-modern">
                            <label>Jenis Usaha</label>
                            <p>{{ $permohonan->jenis_usaha ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="info-item-modern">
                            <label>Alamat Lengkap Tempat Usaha</label>
                            <p>{{ $permohonan->alamat_usaha ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-item-modern">
                            <label>Lama Usaha Berdiri</label>
                            <p>{{ $permohonan->lama_usaha ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- ============================================ --}}
            {{-- SECTION: DOKUMEN PENDUKUNG --}}
            {{-- ============================================ --}}
            <hr class="divider-modern">
            <h4 class="section-title">📎 Dokumen Pendukung</h4>
            <div class="document-grid">
                {{-- KTP (semua jenis surat) --}}
                @if(isset($permohonan->path_ktp) && $permohonan->path_ktp)
                    <div class="document-item">
                        <div class="document-preview">
                            <img src="{{ Storage::url($permohonan->path_ktp) }}" alt="KTP">
                            <div class="document-overlay">
                                <a href="{{ Storage::url($permohonan->path_ktp) }}" target="_blank" class="btn-view">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                    Lihat
                                </a>
                            </div>
                        </div>
                        <p class="document-label">📄 Scan/Foto KTP</p>
                    </div>
                @endif

                {{-- KK (semua jenis surat) --}}
                @if(isset($permohonan->path_kk) && $permohonan->path_kk)
                    <div class="document-item">
                        <div class="document-preview">
                            <img src="{{ Storage::url($permohonan->path_kk) }}" alt="KK">
                            <div class="document-overlay">
                                <a href="{{ Storage::url($permohonan->path_kk) }}" target="_blank" class="btn-view">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                    Lihat
                                </a>
                            </div>
                        </div>
                        <p class="document-label">📄 Scan/Foto Kartu Keluarga</p>
                    </div>
                @endif

                {{-- Surat Pengantar RT/RW (SKTM) --}}
                @if($jenis_surat === 'SKTM' && isset($permohonan->path_surat_pengantar_rt_rw) && $permohonan->path_surat_pengantar_rt_rw)
                    <div class="document-item">
                        <div class="document-preview">
                            <img src="{{ Storage::url($permohonan->path_surat_pengantar_rt_rw) }}" alt="Surat Pengantar">
                            <div class="document-overlay">
                                <a href="{{ Storage::url($permohonan->path_surat_pengantar_rt_rw) }}" target="_blank" class="btn-view">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                    Lihat
                                </a>
                            </div>
                        </div>
                        <p class="document-label">📄 Surat Pengantar RT/RW</p>
                    </div>
                @endif

                {{-- Foto Rumah (SKTM) --}}
                @if($jenis_surat === 'SKTM' && isset($permohonan->path_foto_rumah) && $permohonan->path_foto_rumah)
                    <div class="document-item">
                        <div class="document-preview">
                            <img src="{{ Storage::url($permohonan->path_foto_rumah) }}" alt="Foto Rumah">
                            <div class="document-overlay">
                                <a href="{{ Storage::url($permohonan->path_foto_rumah) }}" target="_blank" class="btn-view">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                    Lihat
                                </a>
                            </div>
                        </div>
                        <p class="document-label">🏠 Foto Rumah Tampak Depan</p>
                    </div>
                @endif

                {{-- Surat Pengantar RT/RW (SKU) --}}
                @if($jenis_surat === 'SKU' && isset($permohonan->path_surat_pengantar) && $permohonan->path_surat_pengantar)
                    <div class="document-item">
                        <div class="document-preview">
                            <img src="{{ Storage::url($permohonan->path_surat_pengantar) }}" alt="Surat Pengantar">
                            <div class="document-overlay">
                                <a href="{{ Storage::url($permohonan->path_surat_pengantar) }}" target="_blank" class="btn-view">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                    Lihat
                                </a>
                            </div>
                        </div>
                        <p class="document-label">📄 Surat Pengantar RT/RW</p>
                    </div>
                @endif

                {{-- Foto Usaha (SKU) --}}
                @if($jenis_surat === 'SKU' && isset($permohonan->path_foto_usaha) && $permohonan->path_foto_usaha)
                    <div class="document-item">
                        <div class="document-preview">
                            <img src="{{ Storage::url($permohonan->path_foto_usaha) }}" alt="Foto Usaha">
                            <div class="document-overlay">
                                <a href="{{ Storage::url($permohonan->path_foto_usaha) }}" target="_blank" class="btn-view">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                    Lihat
                                </a>
                            </div>
                        </div>
                        <p class="document-label">🏪 Foto Tempat Usaha</p>
                    </div>
                @endif
            </div>

            {{-- ============================================ --}}
            {{-- SECTION: TOMBOL AKSI --}}
            {{-- ============================================ --}}
            <hr class="divider-modern">
            <div class="text-center mt-4">
                @if($permohonan->status == 'Selesai')
                    <a href="#" class="btn-print">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 6 2 18 2 18 9"/>
                            <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
                            <rect x="6" y="14" width="12" height="8"/>
                        </svg>
                        📄 Cetak Surat
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection