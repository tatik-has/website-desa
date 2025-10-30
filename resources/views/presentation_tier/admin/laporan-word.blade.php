{{-- resources/views/presentation_tier/admin/laporan-word.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Permohonan Surat</title>
    <style>
        /* CSS Sederhana agar rapi di Word */
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            margin: 0;
            padding: 0;
        }


        /* == BAGIAN JUDUL LAPORAN == */
        .content-title {
            text-align: center;
            margin-top: 30px; /* Jarak dari kop surat */
            margin-bottom: 25px;
        }
        .content-title h1 {
            font-size: 14pt;
            font-weight: bold;
            text-decoration: underline;
            margin: 0;
        }
        .content-title h2 {
            font-size: 12pt;
            font-weight: normal;
            margin: 5px 0 0 0;
        }

        /* == BAGIAN TABEL DATA == */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11pt; /* Ukuran font tabel sedikit lebih kecil */
        }
        .data-table th, .data-table td {
            border: 1px solid #000; /* Border hitam solid */
            padding: 7px;
            text-align: left;
            vertical-align: top;
        }
        .data-table th {
            background-color: #f2f2f2; /* Warna abu-abu sangat muda */
            font-weight: bold;
            text-align: center;
        }
        .data-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>

    <table style="width: 100%; border-bottom: 3px solid #000; padding-bottom: 5px;">
        <tr>
            <!-- <td style="width: 20%; text-align: center; vertical-align: middle;">
                <img src="{{ asset('images/logo.png') }}" alt="Logo Desa" width="90" style="width: 90px; height: auto;">
            </td> -->
            
            <td style="width: 80%; text-align: center; vertical-align: middle; line-height: 1.4;">
                
                {{-- Teks ini disesuaikan dari gambar contoh Anda --}}
                <h4 style="margin: 0; font-weight: bold; font-size: 16pt; font-family: 'Times New Roman', Times, serif;">
                    PEMERINTAH KABUPATEN BENGKALIS
                </h4>
                <h3 style="margin: 0; font-weight: bold; font-size: 18pt; font-family: 'Times New Roman', Times, serif;">
                    KEPALA DESA PAKNING ASAL
                </h3>
                <h4 style="margin: 0; font-weight: bold; font-size: 16pt; font-family: 'Times New Roman', Times, serif;">
                    KECAMATAN BUKIT BATU
                </h4>
                
                {{-- 
                  Untuk alamat, saya gabungkan JL dan KODE POS agar rapi.
                  Mengatur satu ke kiri dan satu ke kanan sangat sulit di Word.
                --}}
                <p style="margin: 0; font-size: 11pt; font-weight: normal; font-family: 'Times New Roman', Times, serif;">
                    JL. Sukajadi KODE POS : 28761
                </p>
                {{-- Anda bisa ganti dengan alamat yang lebih lengkap jika perlu --}}
                {{-- <p style="margin: 0; font-size: 11pt; font-weight: normal; font-family: 'Times New Roman', Times, serif;">
                    Jalan Jend. Sudirman No. 123, Pakning Asal, Kode Pos 28761
                </p> --}}
            </td>
        </tr>
    </table>


    <div class="content-title">
        {{-- Judul ini tetap Laporan, bukan Surat Keterangan --}}
        <h1>LAPORAN PERMOHONAN SURAT</h1>
        <h2>Periode {{ \Carbon\Carbon::parse($tanggalMulai)->format('d M Y') }} s.d. {{ \Carbon\Carbon::parse($tanggalAkhir)->format('d M Y') }}</h2>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>No.</th>
                <th>Tanggal Pengajuan</th>
                <th>Nama Pemohon</th>
                <th>Jenis Surat</th>
                <th>Status</th>
                <th>Tanggal Selesai/Ditolak</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($allPermohonan as $item)
                <tr>
                    <td style="text-align: center;">{{ $loop->iteration }}</td>
                    <td>{{ $item->created_at->format('d M Y, H:i') }}</td>
                    <td>{{ $item->user->name ?? $item->nama_lengkap }}</td>
                    <td>{{ $item->jenis_surat_label }}</td>
                    <td style="text-align: center;">{{ $item->status }}</td>
                    <td>
                        @if($item->status == 'Selesai' || $item->status == 'Ditolak')
                            {{ $item->updated_at->format('d M Y, H:i') }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 20px;">
                        Tidak ada data permohonan pada periode ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>