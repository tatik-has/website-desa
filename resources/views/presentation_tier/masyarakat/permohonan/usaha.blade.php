@extends('presentation_tier.masyarakat.layout')

@section('content')
    {{-- PERBAIKAN 1: Gunakan file CSS yang sesuai untuk SKU --}}
    <link rel="stylesheet" href="{{ asset('presentation_tier/css/masyarakat/usaha.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <main class="form-page-container">
        <div class="form-wrapper">
            <div class="form-title">
                <h2>Formulir Permohonan</h2>
                <h1>Surat Keterangan Usaha (SKU)</h1>
            </div>

            <div style="margin-bottom: 15px;">
            <a href="{{ url()->previous() }}" class="back-btn">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>


            <div class="alert alert-info" style="background-color: #d1ecf1; color: #0c5460; padding: 15px; border-radius: 5px; margin-bottom: 20px; border-left: 4px solid #17a2b8;">
                <i class="fas fa-info-circle" style="margin-right: 8px;"></i>
                <strong>Informasi:</strong> Setelah formulir Surat Keterangan Usaha ini dikirim, admin akan segera memproses pengajuan Anda. Pastikan data usaha dan dokumen pendukung sudah lengkap.
            </div>

            <!-- pemberitahuan eror -->
            @if ($errors->any())
                <div class="alert alert-danger"
                    style="background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 15px; border-left: 4px solid #dc3545;">
                    <i class="fas fa-exclamation-triangle" style="margin-right: 8px;"></i>
                    <strong>Terdapat kesalahan pada input Anda:</strong>
                    <ul style="list-style-type: disc; margin-left: 20px; margin-top: 10px;">
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
                    <input type="number" id="nik" name="nik" placeholder="Masukkan NIK Anda" value="{{ old('nik') }}" required>
                </div>
                <div class="form-group">
                    <label for="nama">Nama Lengkap</label>
                    <input type="text" id="nama" name="nama" placeholder="Masukkan Nama Sesuai KTP" value="{{ old('nama') }}" required>
                </div>
                <div class="form-group">
                    <label for="alamat_ktp">Alamat Sesuai KTP</label>
                    <input type="text" id="alamat_ktp" name="alamat_ktp" placeholder="Masukkan Alamat Lengkap Sesuai KTP"
                        value="{{ old('alamat_ktp') }}" required>
                </div>
                <div class="form-group">
                    <label for="nomor_telp">Nomor Telp/Whatsapp Aktif</label>
                    <input type="text" id="nomor_telp" name="nomor_telp" placeholder="Contoh: 08123456789 atau +628123456789" value="{{ old('nomor_telp') }}" required>
                </div>

                {{-- Bagian Data Usaha (sudah benar) --}}
                <h3 class="form-section-title">Data Usaha</h3>
                <div class="form-group">
                    <label for="nama_usaha">Nama Usaha</label>
                    <input type="text" id="nama_usaha" name="nama_usaha" placeholder="Contoh: Warung Berkah, Jaya Laundry"
                        value="{{ old('nama_usaha') }}" required>
                </div>
                <div class="form-group">
                    <label for="jenis_usaha">Jenis Usaha</label>
                    <input type="text" id="jenis_usaha" name="jenis_usaha"
                        placeholder="Contoh: Toko Kelontong, Jasa Jahit, Katering" value="{{ old('jenis_usaha') }}" required>
                </div>
                <div class="form-group">
                    <label for="alamat_usaha">Alamat Lengkap Tempat Usaha</label>
                    <textarea id="alamat_usaha" name="alamat_usaha" rows="3"
                        placeholder="Masukkan alamat lengkap lokasi usaha Anda" required>{{ old('alamat_usaha') }}</textarea>
                </div>
                <div class="form-group">
                    <label for="lama_usaha">Lama Usaha Berdiri</label>
                    <div class="select-wrapper">
                        <select id="lama_usaha" name="lama_usaha" required>
                            <option value="">-- Pilih Lama Usaha --</option>
                            <option value="Kurang dari 6 bulan" {{ old('lama_usaha') == 'Kurang dari 6 bulan' ? 'selected' : '' }}>Kurang dari 6 bulan</option>
                            <option value="6 bulan" {{ old('lama_usaha') == '6 bulan' ? 'selected' : '' }}>6 bulan</option>
                            <option value="1 tahun" {{ old('lama_usaha') == '1 tahun' ? 'selected' : '' }}>1 tahun</option>
                            <option value="2 tahun" {{ old('lama_usaha') == '2 tahun' ? 'selected' : '' }}>2 tahun</option>
                            <option value="3 tahun" {{ old('lama_usaha') == '3 tahun' ? 'selected' : '' }}>3 tahun</option>
                            <option value="4 tahun" {{ old('lama_usaha') == '4 tahun' ? 'selected' : '' }}>4 tahun</option>
                            <option value="5 tahun" {{ old('lama_usaha') == '5 tahun' ? 'selected' : '' }}>5 tahun</option>
                            <option value="6 tahun" {{ old('lama_usaha') == '6 tahun' ? 'selected' : '' }}>6 tahun</option>
                            <option value="7 tahun" {{ old('lama_usaha') == '7 tahun' ? 'selected' : '' }}>7 tahun</option>
                            <option value="8 tahun" {{ old('lama_usaha') == '8 tahun' ? 'selected' : '' }}>8 tahun</option>
                            <option value="9 tahun" {{ old('lama_usaha') == '9 tahun' ? 'selected' : '' }}>9 tahun</option>
                            <option value="10 tahun" {{ old('lama_usaha') == '10 tahun' ? 'selected' : '' }}>10 tahun</option>
                            <option value="Lebih dari 10 tahun" {{ old('lama_usaha') == 'Lebih dari 10 tahun' ? 'selected' : '' }}>Lebih dari 10 tahun</option>
                        </select>
                    </div>
                </div>

                {{-- Bagian Dokumen (sudah benar) --}}
                <h3 class="form-section-title">Dokumen Pendukung</h3>
                <small style="color: #666; display: block; margin-top: 5px; margin-bottom: 15px;">
                    Format yang diterima: Foto (JPG/PNG), PDF, atau Word (DOC/DOCX)
                </small>
                <div class="form-row">
                    <div class="form-group">
                        <label for="ktp">Scan/Foto KTP</label>
                        <div class="file-upload-wrapper">
                            <input type="file" id="ktp" name="ktp" class="file-input" accept="image/jpeg,image/jpg,image/png,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" required>
                            <button type="button" class="file-choose-btn">Choose File</button>
                            <span class="file-name-display">No File Chosen</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="kk">Scan/Foto KK</label>
                        <div class="file-upload-wrapper">
                            <input type="file" id="kk" name="kk" class="file-input" accept="image/jpeg,image/jpg,image/png,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" required>
                            <button type="button" class="file-choose-btn">Choose File</button>
                            <span class="file-name-display">No File Chosen</span>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="surat_pengantar">Surat Pengantar RT/RW</label>
                        <div class="file-upload-wrapper">
                            <input type="file" id="surat_pengantar" name="surat_pengantar" class="file-input" accept="image/jpeg,image/jpg,image/png,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" required>
                            <button type="button" class="file-choose-btn">Choose File</button>
                            <span class="file-name-display">No File Chosen</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="foto_usaha">Foto Tempat Usaha (Opsional)</label>
                        <div class="file-upload-wrapper">
                            <input type="file" id="foto_usaha" name="foto_usaha" class="file-input" accept="image/jpeg,image/jpg,image/png,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
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