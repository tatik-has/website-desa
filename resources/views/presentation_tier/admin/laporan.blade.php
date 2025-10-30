{{-- resources/views/presentation_tier/admin/laporan.blade.php --}}

@extends('presentation_tier.admin.layout')

{{-- ========================================================== --}}
{{-- == PERUBAHAN DI SINI == --}}
{{-- ========================================================== --}}

{{-- CSS Khusus untuk halaman ini --}}
@push('styles')
    {{-- Kita ganti semua blok <style> dengan link ke file CSS baru --}}
    <link rel="stylesheet" href="{{ asset('presentation_tier/css/admin-laporan.css') }}">
@endpush

{{-- ========================================================== --}}
{{-- == AKHIR PERUBAHAN == --}}
{{-- ========================================================== --}}


@section('content')
<div class="report-page">
    <div class="report-header">
        <h2>Laporan Permohonan Surat</h2>
    </div>

    {{-- KARTU FILTER --}}
    <div class="filter-card">
        <form action="{{ route('admin.laporan') }}" method="GET" class="filter-form">
            <div class="filter-group">
                <label for="tanggal_mulai">Dari Tanggal</label>
                <input type="date" id="tanggal_mulai" name="tanggal_mulai" value="{{ $tanggalMulai }}">
            </div>
            <div class="filter-group">
                <label for="tanggal_akhir">Sampai Tanggal</label>
                <input type="date" id="tanggal_akhir" name="tanggal_akhir" value="{{ $tanggalAkhir }}">
            </div>
            <div class="filter-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-filter"></i> Filter
                </button>
                <button type="submit" name="export" value="word" class="btn btn-success">
                    <i class="fa-solid fa-file-word"></i> Unduh Laporan
                </button>
            </div>
        </form>
    </div>

    {{-- TABEL LAPORAN (Isi tabel ini tidak perlu diubah) --}}
    <div class="report-table-wrapper">
        <table class="report-table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Tanggal Pengajuan</th>
                    <th>Nama Pemohon</th>
                    <th>Jenis Surat</th>
                    <th>Status</th>
                    <th>Tanggal Selesai/Ditolak</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($allPermohonan as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->created_at->format('d M Y, H:i') }}</td>
                        <td>{{ $item->user->name ?? $item->nama_lengkap }}</td>
                        <td>{{ $item->jenis_surat_label }}</td>
                        <td>
                            @if($item->status == 'Selesai')
                                <span class="status status-selesai">Selesai</span>
                            @elseif($item->status == 'Diproses')
                                <span class="status status-diproses">Diproses</span>
                            @elseif($item->status == 'Ditolak')
                                <span class="status status-ditolak">Ditolak</span>
                            @endif
                        </td>
                        <td>
                            @if($item->status == 'Selesai' || $item->status == 'Ditolak')
                                {{ $item->updated_at->format('d M Y, H:i') }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="empty-state">
                            Tidak ada data permohonan pada rentang tanggal ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection