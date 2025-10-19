@extends('presentation_tier.auth.layout')

@push('styles')
    <link rel="stylesheet" href="{{ asset('presentation_tier/css/history.css') }}">
@endpush

@section('content')
<div class="history-container">
    <h1>Riwayat Pengajuan Surat Anda</h1>

    @forelse ($allPermohonan as $permohonan)
        <div class="history-item">
            <h4>{{ $permohonan->jenis_surat }}</h4>
            <p>Tanggal Pengajuan: {{ $permohonan->created_at->format('d F Y') }}</p>
            <p>Status:
                <span class="status
                    @if($permohonan->status == 'Selesai') status-selesai @endif
                    @if($permohonan->status == 'Diproses') status-diproses @endif
                    @if($permohonan->status == 'Ditolak') status-ditolak @endif
                ">
                    {{ $permohonan->status }}
                </span>
            </p>

            {{-- Alasan penolakan --}}
            @if ($permohonan->status == 'Ditolak' && $permohonan->keterangan_penolakan)
                <p class="alasan-penolakan">
                    <strong>Alasan Penolakan:</strong> {{ $permohonan->keterangan_penolakan }}
                </p>
            @endif

            {{-- Tombol unduh jika selesai --}}
            @if ($permohonan->status == 'Selesai' && $permohonan->path_surat_jadi)
                <a href="{{ Storage::url($permohonan->path_surat_jadi) }}" class="btn-download" target="_blank">
                    Unduh Surat (PDF)
                </a>
            @endif
        </div>
    @empty
        <p>Anda belum pernah mengajukan surat.</p>
    @endforelse
</div>
@endsection
