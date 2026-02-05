{{-- File: resources/views/presentation_tier/admin/dashboard.blade.php --}}
@extends('presentation_tier.admin.partials.layout')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin/dashboard-admin.css') }}">

<div class="dashboard-content">
    <div class="dashboard-header">
        <h2>Selamat Datang, Admin!</h2>
        <p>Berikut adalah ringkasan data di sistem Anda.</p>
    </div>

    {{-- Widget Cards --}}
    <div class="widget-container">
        <div class="widget-card widget-warning">
            <div class="widget-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="widget-info">
                <h4>Permohonan Masuk</h4>
                <p class="widget-number">{{ $totalDiproses }}</p>
                <small>Menunggu diproses</small>
            </div>
        </div>
        <div class="widget-card widget-success">
            <div class="widget-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="widget-info">
                <h4>Surat Disetujui</h4>
                <p class="widget-number">{{ $totalSelesai }}</p>
                <small>Berhasil diselesaikan</small>
            </div>
        </div>
        <div class="widget-card widget-info">
            <div class="widget-icon">
                <i class="fas fa-file-alt"></i>
            </div>
            <div class="widget-info">
                <h4>Total Pengajuan Surat</h4>
                <p class="widget-number">{{ $totalDitolak }}</p>
                <small>Semua permohonan</small>
            </div>
        </div>
    </div>

    {{-- Quick Actions & Recent Activity Section --}}
    <div class="dashboard-grid">
        {{-- Quick Actions --}}
        <div class="dashboard-card quick-actions">
            <h3>
                <i class="fas fa-bolt"></i> Aksi Cepat
            </h3>
            <div class="action-buttons">
                <a href="{{ route('admin.surat.index') }}" class="action-btn btn-primary">
                    <i class="fas fa-inbox"></i>
                    <span>Lihat Permohonan</span>
                </a>
                <a href="{{ route('admin.laporan') }}" class="action-btn btn-secondary">
                    <i class="fas fa-chart-line"></i>
                    <span>Buat Laporan</span>
                </a>
                <a href="{{ route('admin.arsip') }}" class="action-btn btn-info">
                    <i class="fas fa-archive"></i>
                    <span>Arsip Surat</span>
                </a>
            </div>
        </div>

        {{-- Statistics Chart --}}
        <div class="dashboard-card statistics-card">
            <h3>
                <i class="fas fa-chart-pie"></i> Statistik Permohonan
            </h3>
            <div class="chart-container">
                <canvas id="statusChart"></canvas>
            </div>
            <div class="stats-legend">
                <div class="legend-item">
                    <span class="legend-dot bg-warning"></span>
                    <span>Diproses: {{ $totalDiproses }}</span>
                </div>
                <div class="legend-item">
                    <span class="legend-dot bg-success"></span>
                    <span>Selesai: {{ $totalSelesai }}</span>
                </div>
                <div class="legend-item">
                    <span class="legend-dot bg-danger"></span>
                    <span>Ditolak: {{ $totalDitolak }}</span>
                </div>
            </div>
        </div>

        {{-- Recent Activity --}}
        <div class="dashboard-card recent-activity">
            <h3>
                <i class="fas fa-history"></i> Aktivitas Terkini
            </h3>
            @if(isset($recentPermohonan) && count($recentPermohonan) > 0)
                <div class="activity-list">
                    @foreach($recentPermohonan as $item)
                        <div class="activity-item">
                            <div class="activity-icon">
                                <i class="fas fa-file"></i>
                            </div>
                            <div class="activity-details">
                                <p class="activity-title">{{ $item->jenis_surat }}</p>
                                <p class="activity-user">{{ $item->user->name ?? 'User' }}</p>
                                <p class="activity-time">{{ $item->created_at->diffForHumans() }}</p>
                            </div>
                            <div class="activity-status status-{{ strtolower($item->status) }}">
                                {{ $item->status }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="no-data">Belum ada aktivitas terkini</p>
            @endif
            <a href="{{ route('admin.semuaPermohonan') }}" class="view-all">
                Lihat Semua <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        {{-- System Information --}}
        <div class="dashboard-card system-info">
            <h3>
                <i class="fas fa-info-circle"></i> Informasi Sistem
            </h3>
            <div class="info-list">
                <div class="info-item">
                    <span class="info-label">Total User:</span>
                    <span class="info-value">{{ $totalUsers ?? 0 }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Total Admin:</span>
                    <span class="info-value">{{ $totalAdmins ?? 0 }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Permohonan Hari Ini:</span>
                    <span class="info-value">{{ $todayPermohonan ?? 0 }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Arsip Total:</span>
                    <span class="info-value">{{ $totalArsip ?? 0 }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
    // Chart for statistics
    const ctx = document.getElementById('statusChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Diproses', 'Selesai', 'Ditolak'],
                datasets: [{
                    data: [{{ $totalDiproses }}, {{ $totalSelesai }}, {{ $totalDitolak }}],
                    backgroundColor: ['#f39c12', '#27ae60', '#e74c3c'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }
</script>
@endpush
@endsection