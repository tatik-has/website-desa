@extends('presentation_tier.masyarakat.layout')

@push('styles')
    {{-- Memuat CSS khusus untuk halaman ini saja --}}
    <link rel="stylesheet" href="{{ asset('presentation_tier/css/masyarakat/domisili.css') }}">
@endpush

@section('content')
    {{-- Semua kode
    <link> dan <nav> sudah dihapus karena diwarisi dari layout --}}
        <main class="form-page-container">
            <div class="form-wrapper">
                <div class="form-title">
                    <h2>Formulir Permohonan</h2>
                    <h1>Surat Keterangan Domisili</h1>
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


                <form action="{{ route('pengajuan.domisili.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <h3 class="form-section-title">Data Pemohon</h3>

                    {{-- ... Seluruh isi form Anda dari NIK sampai tombol submit ... --}}
                    <div class="form-group">
                        <label for="nik">NIK</label>
                        <input type="number" id="nik" name="nik" placeholder="Masukkan NIK" required>
                    </div>

                    <div class="form-group">
                        <label for="nama">Nama</label>
                        <input type="text" id="nama" name="nama" placeholder="Masukkan Nama" required>
                    </div>

                    <div class="form-group">
                        <label for="alamat-domisili">Alamat Domisili</label>
                        <input type="text" id="alamat-domisili" name="alamat_domisili" placeholder="Masukkan Alamat"
                            required>
                    </div>

                    <div class="form-group">
                        <label for="nomor-telp">Nomor Telp/Whatsapp</label>
                        <input type="text" id="nomor-telp" name="nomor_telp" placeholder="Contoh: 08123456789 atau +628123456789" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="rt-domisili">RT Domisili</label>
                            <input type="number" id="rt-domisili" name="rt_domisili" placeholder="Masukkan RT Domisili"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="rw-domisili">RW Domisili</label>
                            <input type="number" id="rw-domisili" name="rw_domisili" placeholder="Masukkan RW Domisili"
                                required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="jenis-kelamin">Jenis Kelamin</label>
                            <div class="select-wrapper">
                                <select id="jenis-kelamin" name="jenis_kelamin" required>
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="alamat-ktp">Alamat KTP</label>
                            <input type="text" id="alamat-ktp" name="alamat_ktp" placeholder="Masukkan Alamat KTP" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="ktp">Upload KTP (Foto/PDF/Word)</label>
                            <div class="file-upload-wrapper">
                                <input type="file" id="ktp" name="ktp" class="file-input" accept="image/jpeg,image/jpg,image/png,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document"
                                    capture="environment" required>
                                <button type="button" class="file-choose-btn">Choose File</button>
                                <span class="file-name-display">No File Chosen</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="kk">Upload KK (Foto/PDF/Word)</label>
                            <div class="file-upload-wrapper">
                                <input type="file" id="kk" name="kk" class="file-input" accept="image/jpeg,image/jpg,image/png,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document"
                                    capture="environment" required>
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
@endsection

    @push('scripts')
        {{-- Menambahkan JavaScript khusus untuk halaman ini ke dalam layout --}}
        <script>
            document.querySelectorAll('.file-choose-btn').forEach(button => {
                button.addEventListener('click', () => {
                    const fileInput = button.parentElement.querySelector('.file-input');
                    fileInput.click();
                });
            });

            document.querySelectorAll('.file-input').forEach(input => {
                const display = input.closest('.file-upload-wrapper').querySelector('.file-name-display');

                input.addEventListener('change', (e) => {
                    const fileName = e.target.files[0] ? e.target.files[0].name : 'No File Chosen';
                    display.textContent = fileName;
                });
            });
        </script>
    @endpush