@extends('presentation_tier.masyarakat.layout')

@section('content')
<link rel="stylesheet" href="{{ asset('presentation_tier/css/masyarakat/riwayat.css') }}">

<div class="riwayat-wrapper">
    <div class="container riwayat-container">
        <div class="riwayat-header">
            <h4 class="riwayat-title">
                <i class="fas fa-history"></i> Riwayat Pengajuan Surat
            </h4>
            <p class="riwayat-subtitle">Pantau status pengajuan surat Anda</p>
        </div>

        <div class="card riwayat-card">
            <div class="card-body">
                @if($riwayat->count() > 0)
                    <div class="table-responsive">
                        <table class="table riwayat-table">
                            <thead>
                                <tr>
                                    <th width="8%">No</th>
                                    <th width="40%">Jenis Surat</th>
                                    <th width="27%">Tanggal Pengajuan</th>
                                    <th width="25%">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($riwayat as $item)
                                    <tr class="riwayat-row">
                                        <td>
                                            <span class="riwayat-number">{{ $loop->iteration }}</span>
                                        </td>
                                        <td class="riwayat-jenis">
                                            <i class="fas fa-file-alt"></i> {{ $item->jenis_surat }}
                                        </td>
                                        <td class="riwayat-tanggal">
                                            <i class="far fa-calendar-alt"></i> {{ $item->created_at->format('d M Y') }}
                                        </td>
                                        <td>
                                            <span class="riwayat-status-badge
                                                @if($item->status == 'Menunggu') riwayat-status-menunggu
                                                @elseif($item->status == 'Disetujui') riwayat-status-disetujui
                                                @else riwayat-status-ditolak
                                                @endif">
                                                @if($item->status == 'Menunggu')
                                                    <i class="fas fa-clock"></i>
                                                @elseif($item->status == 'Disetujui')
                                                    <i class="fas fa-check-circle"></i>
                                                @else
                                                    <i class="fas fa-times-circle"></i>
                                                @endif
                                                {{ $item->status }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="riwayat-empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-inbox"></i>
                        </div>
                        <h5>Belum Ada Pengajuan</h5>
                        <p>Anda belum memiliki riwayat pengajuan surat.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection