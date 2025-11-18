@extends('presentation_tier.admin.partials.layout')

@push('styles')
    <link rel="stylesheet" href="{{ asset('presentation_tier/css/admin/admin-permohonan.css') }}">
@endpush

@section('content')
<div class="main-container">
    <div class="page-header">
        <h1>Arsip Permohonan Surat</h1>
        <p>Permohonan yang telah diarsipkan (Selesai/Ditolak > 15 hari)</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success" style="background-color: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    {{-- Tombol Jalankan Arsip Otomatis --}}
    <div style="margin-bottom: 20px;">
        <form action="{{ route('admin.runAutoArchive') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="btn btn-primary" 
                onclick="return confirm('Jalankan arsip otomatis untuk permohonan Selesai/Ditolak yang sudah lebih dari 15 hari?')"
                style="background: linear-gradient(135deg, #007bff 0%, #0056b3 100%); color: white; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 500;">
                Jalankan Arsip Otomatis
            </button>
        </form>
    </div>

    {{-- Tab Buttons --}}
    <div class="tab-buttons">
        <button class="tab-btn active" data-tab="domisili">Keterangan Domisili</button>
        <button class="tab-btn" data-tab="sku">Keterangan Usaha (SKU)</button>
        <button class="tab-btn" data-tab="ktm">Permohonan KTM</button>
    </div>

    {{-- Tab Content --}}
    <div class="tab-content">
        {{-- TAB DOMISILI --}}
        <div id="domisili" class="tab-pane active">
            @include('presentation_tier.admin.partials._tabel_arsip', [
                'permohonans' => $domisili,
                'type' => 'domisili'
            ])
        </div>

        {{-- TAB SKU --}}
        <div id="sku" class="tab-pane">
            @include('presentation_tier.admin.partials._tabel_arsip', [
                'permohonans' => $sku,
                'type' => 'sku'
            ])
        </div>

        {{-- TAB KTM --}}
        <div id="ktm" class="tab-pane">
            @include('presentation_tier.admin.partials._tabel_arsip', [
                'permohonans' => $ktm,
                'type' => 'ktm'
            ])
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Script Tab Switching
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabPanes = document.querySelectorAll('.tab-pane');

    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabPanes.forEach(pane => pane.classList.remove('active'));
            button.classList.add('active');
            const targetPane = document.getElementById(button.dataset.tab);
            if (targetPane) {
                targetPane.classList.add('active');
            }
        });
    });
</script>
@endpush