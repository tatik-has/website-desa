{{--
File ini akan menerima 3 variabel:
- $title: Judul tabel (contoh: "Permohonan Diproses")
- $permohonans: Data permohonan untuk ditampilkan
- $type: Jenis surat ('domisili', 'ktm', 'sku') untuk membuat URL update status yang benar
--}}

<div class="table-header">
    <h3>{{ $title }} ({{ $permohonans->count() }})</h3>
</div>
<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Pemohon</th>
            <th>NIK</th>
            <th>Tanggal</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($permohonans as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->user->name ?? $item->nama }}</td>
                <td>{{ $item->nik }}</td>
                <td>{{ $item->created_at->format('d M Y') }}</td>
                <td>
                    @if($item->status == 'Diproses')
                        <span class="status status-diproses">Diproses</span>
                    @elseif($item->status == 'Selesai')
                        <span class="status status-selesai">Selesai</span>
                    @elseif($item->status == 'Ditolak')
                        <span class="status status-ditolak">Ditolak</span>
                    @endif
                </td>
                <td class="action-buttons">
                    @php
                        // Membuat URL update status yang benar berdasarkan jenis surat
                        $updateUrl = route('admin.surat.updateStatus', ['type' => $type, 'id' => $item->id]);
                    @endphp

                    <a href="{{ $item->detail_route }}" class="btn btn-detail">Detail</a>

                    <form action="{{ $updateUrl }}" method="POST" style="display:inline;">
                        @csrf
                        <input type="hidden" name="status" value="Diproses">
                        <button type="submit" class="btn btn-proses" title="Set status ke Diproses">Proses</button>
                    </form>

                    <button type="button" class="btn btn-selesai" onclick="openSelesaiModal('{{ $updateUrl }}')"
                        title="Selesaikan Permohonan">Selesai</button>
                    <button type="button" class="btn btn-tolak" onclick="openTolakModal('{{ $updateUrl }}')"
                        title="Tolak Permohonan">Tolak</button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" style="text-align: center;">Tidak ada data.</td>
            </tr>
        @endforelse
    </tbody>
</table>