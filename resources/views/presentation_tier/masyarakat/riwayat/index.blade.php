@extends('presentation_tier.masyarakat.layout')

@section('content')
<link rel="stylesheet" href="{{ asset('presentation_tier/css/masyarakat/riwayat.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<div class="riwayat-wrapper">

    {{-- Dekoratif Lingkaran Latar Belakang --}}
    <div class="bg-circle bg-circle-1"></div>
    <div class="bg-circle bg-circle-2"></div>
    <div class="bg-circle bg-circle-3"></div>

    <div class="container riwayat-container">

        {{-- Header --}}
        <div class="riwayat-header">
            <h1 class="riwayat-title">Riwayat Pengajuan</h1>
            <p class="riwayat-subtitle">Pantau dan kelola semua pengajuan surat Anda di sini</p>
        </div>

        {{-- Kartu Utama --}}
        <div class="riwayat-card">

            {{-- Toolbar: Filter + Hapus Semua --}}
            <div class="riwayat-toolbar">
                <div class="toolbar-filters">
                    <button class="filter-btn active" data-filter="semua">Semua</button>
                    <button class="filter-btn" data-filter="Diproses">Diproses</button>
                    <button class="filter-btn" data-filter="Selesai">Selesai</button>
                    <button class="filter-btn" data-filter="Ditolak">Ditolak</button>
                </div>
                <div class="toolbar-count">
                    <span class="count-label">Total Pengajuan</span>
                    <span class="count-number">{{ $riwayat->count() }}</span>
                </div>
            </div>

            {{-- Divider --}}
            <div class="riwayat-divider"></div>

            {{-- Konten Tabel / List --}}
            <div class="riwayat-body">

                @if($riwayat->count() > 0)

                    <div class="riwayat-list" id="riwayatList">
                        @foreach ($riwayat as $item)
                        <div class="riwayat-item" data-status="{{ $item->status }}" style="animation-delay: {{ $loop->index * 0.07 }}s;">

                            {{-- Nomor / Ikon --}}
                            <div class="item-icon-wrap">
                                <div class="item-icon">
                                    @if($item->type == 'domisili')
                                        <i class="fas fa-home"></i>
                                    @elseif($item->type == 'ktm')
                                        <i class="fas fa-hand-holding-heart"></i>
                                    @elseif($item->type == 'sku')
                                        <i class="fas fa-briefcase"></i>
                                    @else
                                        <i class="fas fa-file-alt"></i>
                                    @endif
                                </div>
                            </div>

                            {{-- Konten Utama --}}
                            <div class="item-content">
                                <div class="item-top">
                                    <h5 class="item-title">{{ $item->jenis_surat }}</h5>
                                    <span class="item-status riwayat-status-{{ strtolower($item->status) }}">
                                        @if($item->status == 'Diproses')
                                            <i class="fas fa-spinner fa-spin"></i>
                                        @elseif($item->status == 'Selesai')
                                            <i class="fas fa-check-circle"></i>
                                        @elseif($item->status == 'Ditolak')
                                            <i class="fas fa-times-circle"></i>
                                        @else
                                            <i class="fas fa-clock"></i>
                                        @endif
                                        {{ $item->status }}
                                    </span>
                                </div>
                                <div class="item-meta">
                                    <span class="meta-date">
                                        <i class="far fa-calendar-alt"></i>
                                        {{ $item->created_at->format('d M Y') }}
                                    </span>
                                    <span class="meta-dot">•</span>
                                    <span class="meta-time">
                                        <i class="fas fa-clock"></i>
                                        {{ $item->created_at->format('H:i') }} WIB
                                    </span>
                                    @if($item->nama)
                                    <span class="meta-dot">•</span>
                                    <span class="meta-name">
                                        <i class="fas fa-user"></i>
                                        {{ $item->nama }}
                                    </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Aksi --}}
                            <div class="item-actions">
                                @if($item->status == 'Selesai' && $item->path_surat_jadi)
                                    <a href="{{ Storage::url($item->path_surat_jadi) }}"
                                       target="_blank"
                                       class="action-btn action-btn-unduh">
                                        <i class="fas fa-download"></i> Unduh
                                    </a>
                                @elseif($item->status == 'Ditolak')
                                    <button type="button"
                                            class="action-btn action-btn-alasan"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalAlasan{{ $item->id }}">
                                        <i class="fas fa-info-circle"></i> Alasan
                                    </button>
                                @elseif($item->status == 'Diproses')
                                    <span class="action-btn action-btn-proses">
                                        <i class="fas fa-hourglass-half"></i> Sedang Diproses
                                    </span>
                                @else
                                    <span class="action-btn action-btn-menunggu">
                                        <i class="fas fa-clock"></i> Menunggu
                                    </span>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Modal Alasan Penolakan --}}
                    @foreach ($riwayat as $item)
                        @if($item->status == 'Ditolak')
                        <div class="modal fade" id="modalAlasan{{ $item->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-alasan">
                                <div class="modal-content">
                                    <div class="modal-header-custom">
                                        <div class="modal-header-icon">
                                            <i class="fas fa-exclamation-triangle"></i>
                                        </div>
                                        <div class="modal-header-text">
                                            <h5>Keterangan Penolakan</h5>
                                            <p>{{ $item->jenis_surat }}</p>
                                        </div>
                                        <button type="button" class="modal-close-btn" data-bs-dismiss="modal">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    <div class="modal-body-custom">
                                        <div class="modal-info-row">
                                            <span class="modal-info-label"><i class="far fa-calendar-alt"></i> Tanggal Pengajuan</span>
                                            <span class="modal-info-value">{{ $item->created_at->format('d M Y, H:i') }} WIB</span>
                                        </div>
                                        <div class="modal-info-row">
                                            <span class="modal-info-label"><i class="fas fa-file-alt"></i> Jenis Surat</span>
                                            <span class="modal-info-value">{{ $item->jenis_surat }}</span>
                                        </div>
                                        <div class="modal-alasan-box">
                                            <div class="modal-alasan-label">
                                                <i class="fas fa-comment-alt"></i> Alasan Penolakan
                                            </div>
                                            <p class="modal-alasan-text">
                                                {{ $item->keterangan_penolakan ?? 'Tidak ada keterangan yang diberikan.' }}
                                            </p>
                                        </div>
                                        <div class="modal-saran-box">
                                            <i class="fas fa-lightbulb"></i>
                                            <div>
                                                <strong>Saran</strong>
                                                <p>Perbaiki dokumen atau data sesuai keterangan di atas, kemudian ajukan kembali permohonan Anda.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer-custom">
                                        <button type="button" class="btn-modal-secondary" data-bs-dismiss="modal">
                                            <i class="fas fa-times"></i> Tutup
                                        </button>
                                        <a href="{{ route('pengajuan.form') }}" class="btn-modal-primary">
                                            <i class="fas fa-redo"></i> Ajukan Ulang
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    @endforeach

                @else
                    {{-- Empty State --}}
                    <div class="riwayat-empty">
                        <div class="empty-icon-wrap">
                            <div class="empty-icon">
                                <i class="fas fa-folder-open"></i>
                            </div>
                        </div>
                        <h4 class="empty-title">Belum Ada Pengajuan</h4>
                        <p class="empty-desc">Anda belum pernah mengajukan surat apapun.<br>Mulai pengajuan pertama Anda sekarang.</p>
                        <a href="{{ route('pengajuan.form') }}" class="btn-empty-start">
                            <i class="fas fa-plus-circle"></i> Mulai Pengajuan
                        </a>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>

<script>
// ===== FILTER LOGIC =====
document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        // Aktifkan button yang diklik
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');

        const filter = this.dataset.filter;
        const items = document.querySelectorAll('.riwayat-item');

        items.forEach(item => {
            if (filter === 'semua' || item.dataset.status === filter) {
                item.style.display = 'flex';
                item.style.animation = 'none';
                // Trigger reflow
                void item.offsetWidth;
                item.style.animation = '';
            } else {
                item.style.display = 'none';
            }
        });
    });
});
</script>

@endsection