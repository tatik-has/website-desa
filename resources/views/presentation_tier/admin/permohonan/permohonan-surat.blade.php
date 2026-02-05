@extends('presentation_tier.admin.partials.layout')

@push('styles')
    {{-- CSS utama untuk halaman ini, sekarang semua style ada di sini --}}
    <link rel="stylesheet" href="{{ asset('presentation_tier/css/admin/admin-permohonan.css') }}">
@endpush

@section('content')

    @if ($errors->any())
        <div class="alert alert-danger"
            style="background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
            <strong>Terjadi Kesalahan!</strong>
            <ul style="margin-top: 10px; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="main-container">
        <div class="page-header">
            <h1>Manajemen Permohonan Surat</h1>
            <p>Pilih jenis surat untuk melihat dan mengelola permohonan yang masuk.</p>
        </div>

        {{-- Tombol untuk Pindah Tab --}}
        <div class="tab-buttons">
            <button class="tab-btn active" data-tab="domisili">Keterangan Domisili</button>
            <button class="tab-btn" data-tab="sku">Keterangan Usaha (SKU)</button>
            <button class="tab-btn" data-tab="ktm">Permohonan KTM</button>
        </div>

        {{-- Konten dari setiap Tab --}}
        <div class="tab-content">
            {{-- TAB 1: DOMISILI --}}
            <div id="domisili" class="tab-pane active">
                @include('presentation_tier.admin.partials._tabel_permohonan', [
                    'title' => 'Permohonan Diproses',
                    'permohonans' => $domisiliGrouped['Diproses'] ?? collect(),
                    'type' => 'domisili'
                ])
                {{-- === TAMBAHAN: Tabel untuk status "Diterima" === --}}
                @include('presentation_tier.admin.partials._tabel_permohonan', [
                    'title' => 'Permohonan Diterima (Sedang Dibuat)',
                    'permohonans' => $domisiliGrouped['Diterima'] ?? collect(),
                    'type' => 'domisili'
                ])
                {{-- === AKHIR TAMBAHAN === --}}
                @include('presentation_tier.admin.partials._tabel_permohonan', [
                    'title' => 'Permohonan Selesai',
                    'permohonans' => $domisiliGrouped['Selesai'] ?? collect(),
                    'type' => 'domisili'
                ])
                @include('presentation_tier.admin.partials._tabel_permohonan', [
                    'title' => 'Permohonan Ditolak',
                    'permohonans' => $domisiliGrouped['Ditolak'] ?? collect(),
                    'type' => 'domisili'
                ])
            </div>

            {{-- TAB 2: SKU --}}
            <div id="sku" class="tab-pane">
                @include('presentation_tier.admin.partials._tabel_permohonan', [
                    'title' => 'Permohonan Diproses',
                    'permohonans' => $skuGrouped['Diproses'] ?? collect(),
                    'type' => 'sku'
                ])
                {{-- === TAMBAHAN: Tabel untuk status "Diterima" === --}}
                @include('presentation_tier.admin.partials._tabel_permohonan', [
                    'title' => 'Permohonan Diterima (Sedang Dibuat)',
                    'permohonans' => $skuGrouped['Diterima'] ?? collect(),
                    'type' => 'sku'
                ])
                {{-- === AKHIR TAMBAHAN === --}}
                @include('presentation_tier.admin.partials._tabel_permohonan', [
                    'title' => 'Permohonan Selesai',
                    'permohonans' => $skuGrouped['Selesai'] ?? collect(),
                    'type' => 'sku'
                ])
                 @include('presentation_tier.admin.partials._tabel_permohonan', [
                    'title' => 'Permohonan Ditolak',
                    'permohonans' => $skuGrouped['Ditolak'] ?? collect(),
                    'type' => 'sku'
                ])
            </div>

            {{-- TAB 3: KTM --}}
            <div id="ktm" class="tab-pane">
                @include('presentation_tier.admin.partials._tabel_permohonan', [
                    'title' => 'Permohonan Diproses',
                    'permohonans' => $ktmGrouped['Diproses'] ?? collect(),
                    'type' => 'ktm'
                ])
                {{-- === TAMBAHAN: Tabel untuk status "Diterima" === --}}
                @include('presentation_tier.admin.partials._tabel_permohonan', [
                    'title' => 'Permohonan Diterima (Sedang Dibuat)',
                    'permohonans' => $ktmGrouped['Diterima'] ?? collect(),
                    'type' => 'ktm'
                ])
                {{-- === AKHIR TAMBAHAN === --}}
                @include('presentation_tier.admin.partials._tabel_permohonan', [
                    'title' => 'Permohonan Selesai',
                    'permohonans' => $ktmGrouped['Selesai'] ?? collect(),
                    'type' => 'ktm'
                ])
                @include('presentation_tier.admin.partials._tabel_permohonan', [
                    'title' => 'Permohonan Ditolak',
                    'permohonans' => $ktmGrouped['Ditolak'] ?? collect(),
                    'type' => 'ktm'
                ])
            </div>
        </div>
    </div>

    {{-- ======================================================= --}}

                           {{-- === SEMUA MODAL DITEMPATKAN DI SINI === --}}
    {{-- ======================================================= --}}

    {{-- MODAL PENOLAKAN --}}
    <div id="tolakModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Alasan Penolakan</h3>
                <span class="close-btn" onclick="closeTolakModal()">&times;</span>
            </div>
            <div class="modal-body">
                <form id="formTolak" action="#" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="Ditolak">
                    <textarea name="keterangan_penolakan" placeholder="Tuliskan alasan penolakan di sini..." required></textarea>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-submit-tolak">Kirim Penolakan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- === PERUBAHAN: MODAL INI SEKARANG DIGUNAKAN UNTUK "KIRIM SURAT" (bukan "Terima") === --}}
    {{-- MODAL UPLOAD SURAT SELESAI (PDF) — Dipicu dari tombol "Kirim Surat" --}}
    <div id="selesaiModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Kirim Surat yang Sudah Jadi</h3>
                <span class="close-btn" onclick="closeSelesaiModal()">&times;</span>
            </div>
            <div class="modal-body">
                <form id="formSelesai" action="#" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="file-upload-area">
                        <input type="file" name="surat_jadi" id="surat_jadi_input" class="file-input" accept="application/pdf" required>
                        <label for="surat_jadi_input" class="file-drop-zone">
                            <i class="fas fa-cloud-upload-alt file-icon"></i>
                            <p class="file-instructions">Seret & lepas file PDF di sini, atau klik untuk memilih file.</p>
                            <span class="file-name-display">Belum ada file dipilih</span>
                        </label>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-submit-selesai">Kirim Surat</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- === AKHIR PERUBAHAN === --}}
@endsection

@push('scripts')
    {{-- Script untuk Tab --}}
    <script>
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

    {{-- Script untuk Modal --}}
    <script>
        // === MODAL PENOLAKAN ===
         const tolakModal = document.getElementById('tolakModal');
        const formTolak = document.getElementById('formTolak');

        function openTolakModal(url) {
            formTolak.action = url;
            tolakModal.style.display = "block";
        }

        function closeTolakModal() {
            tolakModal.style.display = "none";
            formTolak.reset();
        }

        // === MODAL KIRIM SURAT (sebelumnya modal "Selesai") ===
        const selesaiModal = document.getElementById('selesaiModal');
        const formSelesai = document.getElementById('formSelesai');
        const fileInput = document.getElementById('surat_jadi_input');
        const fileNameDisplay = document.querySelector('.file-name-display');

        function openSelesaiModal(url) {
            formSelesai.action = url;
            selesaiModal.style.display = "block";
        }

        function closeSelesaiModal() {
            selesaiModal.style.display = "none";
            fileNameDisplay.textContent = 'Belum ada file dipilih';
            formSelesai.reset();
        }

        fileInput.addEventListener('change', function() {
            fileNameDisplay.textContent = this.files.length > 0 ? this.files[0].name : 'Belum ada file dipilih';
        });

        // Tutup modal saat klik area luar
        window.addEventListener('click', function(event) {
            if (event.target === tolakModal) closeTolakModal();
            if (event.target === selesaiModal) closeSelesaiModal();
        });
    </script>
@endpush