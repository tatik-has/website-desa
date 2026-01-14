@extends('presentation_tier.masyarakat.layout')

{{-- Memuat file CSS eksternal untuk halaman FAQ --}}
@push('styles')
    <link rel="stylesheet" href="{{ asset('presentation_tier/css/shared/faq.css') }}">
@endpush

{{-- Konten utama halaman --}}
@section('content')
<main>
    <div class="faq-container">
        <h1>Frequently Asked Questions (FAQ)</h1>
        <p class="subtitle">Temukan jawaban untuk pertanyaan umum tentang layanan surat Desa Pakning Asal.</p>

        {{-- Item FAQ 1 --}}
        <div class="faq-item">
            <button class="faq-question">
                <span>Apa itu Sistem Surat Desa Online ini?</span>
                <i class="fa fa-chevron-down"></i>
            </button>
            <div class="faq-answer">
                <p>Ini adalah platform web resmi Kantor Desa Pakning Asal yang dirancang untuk memudahkan warga dalam proses pengajuan berbagai jenis surat keterangan (seperti Surat Keterangan Tidak Mampu, Surat Keterangan Domisi, dan Surat Keterangan Usaha.) secara online.</p>
            </div>
        </div>

        {{-- Item FAQ 2 --}}
        <div class="faq-item">
            <button class="faq-question">
                <span>Bagaimana cara saya mengajukan surat?</span>
                <i class="fa fa-chevron-down"></i>
            </button>
            <div class="faq-answer">
                <p>Langkahnya sangat mudah:</p>
                <ol>
                    <li>Pastikan Anda sudah memiliki akun dan login.</li>
                    <li>Klik menu '<strong>Pengajuan</strong>' di navigasi atas.</li>
                    <li>Pilih jenis surat yang Anda butuhkan.</li>
                    <li>Isi formulir yang disediakan dengan data yang lengkap dan benar.</li>
                    <li>Unggah (upload) dokumen pendukung yang diminta (cth: scan KTP, KK).</li>
                    <li>Klik 'Kirim Pengajuan' dan tunggu proses verifikasi dari admin desa.</li>
                </ol>
            </div>
        </div>

        {{-- Item FAQ 3 --}}
        <div class="faq-item">
            <button class="faq-question">
                <span>Berapa lama proses pembuatan surat?</span>
                <i class="fa fa-chevron-down"></i>
            </button>
            <div class="faq-answer">
                <p>Waktu proses standar adalah <strong>1-2 hari kerja</strong> sejak pengajuan dikirimkan. Waktu ini digunakan oleh staf desa untuk memverifikasi data dan dokumen Anda. Anda akan mendapatkan notifikasi jika ada pembaruan status.</p>
            </div>
        </div>

        {{-- Item FAQ 4 --}}
        <div class="faq-item">
            <button class="faq-question">
                <span>Bagaimana saya tahu jika surat saya sudah selesai?</span>
                <i class="fa fa-chevron-down"></i>
            </button>
            <div class="faq-answer">
                <p>Anda akan menerima notifikasi status melalui ikon lonceng (<i class="fa fa-bell"></i>) di pojok kanan atas. Selain itu, Anda juga dapat melacak status pengajuan Anda kapan saja di halaman '<strong>Pengajuan</strong>'.</p>
            </div>
        </div>
        
        {{-- Item FAQ 5 --}}
        <div class="faq-item">
            <button class="faq-question">
                <span>Apa yang harus saya lakukan jika pengajuan saya ditolak?</span>
                <i class="fa fa-chevron-down"></i>
            </button>
            <div class="faq-answer">
                <p>Jika pengajuan ditolak, staf desa akan memberikan catatan alasan penolakan (misalnya: 'Data tidak lengkap' atau 'Scan KK tidak jelas'). Silakan <strong>ajukan kembali surat </strong> yang ingin di buat, kemudian kirimkan kembali untuk diproses..</p>
            </div>
        </div>

        {{-- Item FAQ 6 --}}
        <div class="faq-item">
            <button class="faq-question">
                <span>Siapa yang harus dihubungi jika saya mengalami masalah teknis?</span>
                <i class="fa fa-chevron-down"></i>
            </button>
            <div class="faq-answer">
                <p>Jika Anda mengalami kendala teknis dengan website atau memiliki pertanyaan yang tidak ada di daftar ini, silakan datang langsung ke <strong>Kantor Desa Pakning Asal</strong> pada jam kerja untuk mendapatkan bantuan.</p>
            </div>
        </div>

    </div>
</main>
@endsection

{{-- Menambahkan JavaScript untuk fungsionalitas accordion --}}
@push('scripts')
<script>
    // Menjalankan script setelah semua konten HTML dimuat
    document.addEventListener('DOMContentLoaded', function () {
        const faqItems = document.querySelectorAll('.faq-item');

        faqItems.forEach(item => {
            const questionButton = item.querySelector('.faq-question');

            questionButton.addEventListener('click', () => {
                // Cek apakah item yang diklik sudah aktif
                const isAlreadyActive = item.classList.contains('active');

                // Opsional: Tutup semua item lain saat membuka satu item
                faqItems.forEach(otherItem => {
                    otherItem.classList.remove('active');
                });

                // Buka/Tutup item yang diklik
                // Jika tadi belum aktif, sekarang aktifkan. Jika sudah aktif, biarkan tertutup (karena sudah di-remove di atas)
                if (!isAlreadyActive) {
                    item.classList.add('active');
                }
            });
        });
    });
</script>
@endpush