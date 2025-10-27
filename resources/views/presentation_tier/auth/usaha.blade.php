@extends('presentation_tier.auth.layout')

@section('content')
    {{-- PERBAIKAN 1: Gunakan file CSS yang sesuai untuk SKU --}}
    <link rel="stylesheet" href="{{ asset('presentation_tier/css/usaha.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <main class="form-page-container">
        <div class="form-wrapper">
            <div class="form-title">
                <h2>Formulir Permohonan</h2>
                <h1>Surat Keterangan Usaha (SKU)</h1>
            </div>

            <!-- pemberitahuan eror -->
            @if ($errors->any())
                <div class="alert alert-danger"
                    style="background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
                    <strong>Terdapat kesalahan pada input Anda:</strong>
                    <ul style="list-style-type: disc; margin-left: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            <form action="{{ route('sku.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Bagian Data Pemohon (sudah benar) --}}
                <h3 class="form-section-title">Data Pemohon (Pemilik Usaha)</h3>
                <div class="form-group">
                    <label for="nik">NIK</label>
                    <input type="number" id="nik" name="nik" placeholder="Masukkan NIK Anda" required>
                </div>
                <div class="form-group">
                    <label for="nama">Nama Lengkap</label>
                    <input type="text" id="nama" name="nama" placeholder="Masukkan Nama Sesuai KTP" required>
                </div>
                <div class="form-group">
                    <label for="alamat_ktp">Alamat Sesuai KTP</label>
                    <input type="text" id="alamat_ktp" name="alamat_ktp" placeholder="Masukkan Alamat Lengkap Sesuai KTP"
                        required>
                </div>
                <div class="form-group">
                    <label for="nomor_telp">Nomor Telp/Whatsapp Aktif</label>
                    <input type="number" id="nomor_telp" name="nomor_telp" placeholder="Contoh: 081234567890" required>
                </div>

                {{-- Bagian Data Usaha (sudah benar) --}}
                <h3 class="form-section-title">Data Usaha</h3>
                <div class="form-group">
                    <label for="nama_usaha">Nama Usaha</label>
                    <input type="text" id="nama_usaha" name="nama_usaha" placeholder="Contoh: Warung Berkah, Jaya Laundry"
                        required>
                </div>
                <div class="form-group">
                    <label for="jenis_usaha">Jenis Usaha</label>
                    <input type="text" id="jenis_usaha" name="jenis_usaha"
                        placeholder="Contoh: Toko Kelontong, Jasa Jahit, Katering" required>
                </div>
                <div class="form-group">
                    <label for="alamat_usaha">Alamat Lengkap Tempat Usaha</label>
                    <textarea id="alamat_usaha" name="alamat_usaha" rows="3"
                        placeholder="Masukkan alamat lengkap lokasi usaha Anda" required></textarea>
                </div>
                <div class="form-group">
                    <label for="lama_usaha">Lama Usaha Berdiri</label>
                    <input type="text" id="lama_usaha" name="lama_usaha" placeholder="Contoh: 3 Tahun, Sejak 2022" required>
                </div>

                {{-- Bagian Dokumen (sudah benar) --}}
                <h3 class="form-section-title">Dokumen Pendukung</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label for="ktp">Scan/Foto KTP</label>
                        <div class="file-upload-wrapper">
                            <input type="file" id="ktp" name="ktp" class="file-input" required>
                            <button type="button" class="file-choose-btn">Choose File</button>
                            <span class="file-name-display">No File Chosen</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="kk">Scan/Foto KK</label>
                        <div class="file-upload-wrapper">
                            <input type="file" id="kk" name="kk" class="file-input" required>
                            <button type="button" class="file-choose-btn">Choose File</button>
                            <span class="file-name-display">No File Chosen</span>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="surat_pengantar">Surat Pengantar RT/RW</label>
                        <div class="file-upload-wrapper">
                            <input type="file" id="surat_pengantar" name="surat_pengantar" class="file-input" required>
                            <button type="button" class="file-choose-btn">Choose File</button>
                            <span class="file-name-display">No File Chosen</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="foto_usaha">Foto Tempat Usaha (Opsional)</label>
                        <div class="file-upload-wrapper">
                            <input type="file" id="foto_usaha" name="foto_usaha" class="file-input">
                            <button type="button" class="file-choose-btn">Choose File</button>
                            <span class="file-name-display">No File Chosen</span>
                        </div>
                    </div>
                </div>

                <button type="submit" class="submit-btn">
                    Kirim Permohonan
                    <i class="fas fa-arrow-right"></i>
                </button>
            </form>
        </div>
    </main>

    <script>
        // Script untuk menampilkan nama file yang dipilih (ini sudah benar)
        document.querySelectorAll('.file-input').forEach(input => {
            const wrapper = input.closest('.file-upload-wrapper');
            const display = wrapper.querySelector('.file-name-display');

            input.addEventListener('change', (e) => {
                const fileName = e.target.files[0] ? e.target.files[0].name : 'No File Chosen';
                display.textContent = fileName;
            });
        });

        // PERBAIKAN 2: TAMBAHKAN SCRIPT INI UNTUK MENGAKTIFKAN TOMBOL "CHOOSE FILE"
        document.querySelectorAll('.file-choose-btn').forEach(button => {
            button.addEventListener('click', () => {
                // Cari input file yang ada di sebelahnya, lalu picu event 'click'
                const wrapper = button.closest('.file-upload-wrapper');
                const input = wrapper.querySelector('.file-input');
                input.click();
            });
        });
    </script>
@endsection