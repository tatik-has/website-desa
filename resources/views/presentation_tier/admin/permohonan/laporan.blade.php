@extends('presentation_tier.admin.partials.layout')

@push('styles')
    <link rel="stylesheet" href="{{ asset('presentation_tier/css/admin/admin-laporan.css') }}">
@endpush

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
            {{-- FILTER STATUS BARU --}}
            <div class="filter-group">
                <label for="status">Status</label>
                <select id="status" name="status" class="status-select">
                    <option value="semua" {{ $statusFilter == 'semua' ? 'selected' : '' }}>Semua</option>
                    <option value="selesai" {{ $statusFilter == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="ditolak" {{ $statusFilter == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
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

    {{-- INFO FILTER AKTIF --}}
    @if($statusFilter !== 'semua')
    <div class="filter-info">
        <i class="fa-solid fa-info-circle"></i>
        Menampilkan data dengan status: <strong>{{ ucfirst($statusFilter) }}</strong>
    </div>
    @endif

    {{-- TABEL LAPORAN --}}
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
                            Tidak ada data permohonan dengan status "{{ ucfirst($statusFilter) }}" pada rentang tanggal ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection