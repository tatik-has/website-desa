@extends('presentation_tier.masyarakat.layout')

@section('content')
    <link rel="stylesheet" href="{{ asset('presentation_tier/css/masyarakat/ktm.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.css">

    <nav class="navbar">
        {{-- ... Navbar Anda ... --}}
    </nav>

    <main class="form-page-container">
        <div class="form-wrapper">
            <div class="form-title">
                <h2>Formulir Permohonan</h2>
                <h1>Surat Keterangan Tidak Mampu (SKTM)</h1>
            </div>

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


            {{-- Pesan sukses/error --}}
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            {{-- Ini adalah duplikat, tapi saya biarkan sesuai kode asli Anda --}}
            @if($errors->any())
                <div class="alert alert-danger">
                    <strong>Terdapat kesalahan pada input Anda. Mohon periksa kembali.</strong>
                </div>
            @endif

            <form action="{{ route('sktm.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <h3 class="form-section-title">Data Diri Pemohon</h3>

                <div class="form-group">
                    <label for="nik">NIK</label>
                    <input type="number" id="nik" name="nik" placeholder="Masukkan 16 digit NIK Anda"
                        value="{{ old('nik') }}" required>
                    @error('nik') <span class="error-message">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="nama">Nama Lengkap (sesuai KTP)</label>
                    <input type="text" id="nama" name="nama" placeholder="Masukkan Nama Lengkap Anda"
                        value="{{ old('nama') }}" required>
                    @error('nama') <span class="error-message">{{ $message }}</span> @enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="jenis-kelamin">Jenis Kelamin</label>
                        <div class="select-wrapper">
                            <select id="jenis-kelamin" name="jenis_kelamin" required>
                                <option value="" disabled selected>Pilih Jenis Kelamin</option>
                                <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>
                                    Laki-laki</option>
                                <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>
                                    Perempuan</option>
                            </select>
                        </div>
                        @error('jenis_kelamin') <span class="error-message">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="nomor-telp">Nomor Telp/Whatsapp Aktif</label>
                        <input type="number" id="nomor-telp" name="nomor_telp" placeholder="Contoh: 081234567890"
                            value="{{ old('nomor_telp') }}" required>
                        @error('nomor_telp') <span class="error-message">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="alamat-lengkap">Alamat Lengkap (sesuai KK)</label>
                    <textarea id="alamat-lengkap" name="alamat_lengkap" rows="3"
                        placeholder="Masukkan alamat lengkap sesuai Kartu Keluarga"
                        required>{{ old('alamat_lengkap') }}</textarea>
                    @error('alamat_lengkap') <span class="error-message">{{ $message }}</span> @enderror
                </div>

                <h3 class="form-section-title">Data Pendukung & Keperluan</h3>

                <div class="form-group">
                    <label for="keperluan">Keperluan Pembuatan SKTM</label>
                    <textarea id="keperluan" name="keperluan" rows="3"
                        placeholder="Contoh: Pengajuan Beasiswa KIP Kuliah, Keringanan Biaya Rumah Sakit, dll."
                        required>{{ old('keperluan') }}</textarea>
                    @error('keperluan') <span class="error-message">{{ $message }}</span> @enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="penghasilan_display">Penghasilan Rata-rata / Bulan (Rp)</label>
                        <input type="text" id="penghasilan_display" placeholder="Contoh: 800.000"
                            value="{{ old('penghasilan') ? number_format(old('penghasilan'), 0, ',', '.') : '' }}" required>
                        <input type="hidden" id="penghasilan" name="penghasilan" value="{{ old('penghasilan') }}">
                        @error('penghasilan') <span class="error-message">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="jumlah-tanggungan">Jumlah Anggota Keluarga yg Ditanggung</label>
                        <input type="number" id="jumlah-tanggungan" name="jumlah_tanggungan" placeholder="Contoh: 4"
                            value="{{ old('jumlah_tanggungan') }}" required>
                        @error('jumlah_tanggungan') <span class="error-message">{{ $message }}</span> @enderror
                    </div>
                </div>

                <h3 class="form-section-title">Unggah Dokumen Persyaratan</h3>
                <p class="upload-note">Mohon unggah dokumen dalam format .JPG, .JPEG, atau .PNG. Ukuran maksimal 2MB.</p>

                <div class="form-row">
                    <div class="form-group">
                        <label for="ktp">Scan/Foto KTP</label>
                        <div class="file-upload-wrapper">
                            <input type="file" id="ktp" name="ktp" class="file-input" accept=".jpg,.jpeg,.png" required>
                            <button type="button" class="file-choose-btn">Pilih File</button>
                            <span class="file-name-display">Belum ada file</span>
                        </div>
                        @error('ktp') <span class="error-message">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="kk">Scan/Foto Kartu Keluarga (KK)</label>
                        <div class="file-upload-wrapper">
                            <input type="file" id="kk" name="kk" class="file-input" accept=".jpg,.jpeg,.png" required>
                            <button type="button" class="file-choose-btn">Pilih File</button>
                            <span class="file-name-display">Belum ada file</span>
                        </div>
                        @error('kk') <span class="error-message">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="surat-pengantar">Surat Pengantar RT/RW</label>
                        <div class="file-upload-wrapper">
                            <input type="file" id="surat-pengantar" name="surat_pengantar_rt_rw" class="file-input"
                                accept=".jpg,.jpeg,.png" required>
                            <button type="button" class="file-choose-btn">Pilih File</button>
                            <span class="file-name-display">Belum ada file</span>
                        </div>
                        @error('surat_pengantar_rt_rw') <span class="error-message">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="foto-rumah">Foto Rumah Tampak Depan</label>
                        <div class="file-upload-wrapper">
                            <input type="file" id="foto-rumah" name="foto_rumah" class="file-input" accept=".jpg,.jpeg,.png"
                                required>
                            <button type="button" class="file-choose-btn">Pilih File</button>
                            <span class="file-name-display">Belum ada file</span>
                        </div>
                        @error('foto_rumah') <span class="error-message">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group declaration">
                    <input type="checkbox" id="declaration" name="declaration" required>
                    <label for="declaration">Saya menyatakan bahwa seluruh data dan dokumen yang saya kirimkan adalah benar
                        dan dapat dipertanggungjawabkan. Jika ditemukan ketidaksesuaian, saya bersedia menerima sanksi
                        sesuai hukum yang berlaku.</label>
                    @error('declaration') <br><span class="error-message">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="submit-btn">
                    Kirim Permohonan
                    <i class="fas fa-arrow-right"></i>
                </button>
            </form>
        </div>
    </main>

    {{-- Script untuk tombol pilih file & tampilan nama file --}}
    <script>
        // ========= SCRIPT BARU UNTUK FORMAT RUPIAH (Penghasilan) =========
        const penghasilanDisplay = document.getElementById('penghasilan_display');
        const penghasilanHidden = document.getElementById('penghasilan');

        if (penghasilanDisplay && penghasilanHidden) {
            penghasilanDisplay.addEventListener('input', function (e) {
                // 1. Dapatkan nilai, hapus semua yg bukan angka (termasuk titik sebelumnya)
                let rawValue = e.target.value.replace(/[^0-9]/g, '');

                penghasilanHidden.value = rawValue === '' ? '' : rawValue;

                let formattedValue = rawValue === '' ? '' : rawValue.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

                // 4. Set nilai kembali ke input display
                e.target.value = formattedValue;
            });
        }


        // --- KODE BARU UNTUK MENGHUBUNGKAN TOMBOL ---
        const customButtons = document.querySelectorAll('.file-choose-btn');

        customButtons.forEach(button => {
            button.addEventListener('click', () => {
                const realInput = button.closest('.file-upload-wrapper').querySelector('.file-input');
                realInput.click();
            });
        });

        // Script ini tidak perlu diubah (untuk menampilkan nama file)
        document.querySelectorAll('.file-input').forEach(input => {
            const wrapper = input.closest('.file-upload-wrapper');
            const display = wrapper.querySelector('.file-name-display');
            const defaultText = 'Belum ada file';

            input.addEventListener('change', (e) => {
                const fileName = e.target.files[0] ? e.target.files[0].name : defaultText;
                display.textContent = fileName;
            });
        });
    </script>
@endsection