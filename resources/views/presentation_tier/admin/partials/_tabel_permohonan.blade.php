{{-- File: presentation_tier/admin/partials/_tabel_permohonan.blade.php --}}
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

                {{-- ===== NAMA PEMOHON (klik ke detail surat sesuai jenis) ===== --}}
                @php
                    $detailUrl = match ($type) {
                        'domisili' => route('admin.domisili.show', $item->id),
                        'sku' => route('admin.sku.show', $item->id),
                        'ktm' => route('admin.ktm.show', $item->id),
                        default => '#',
                    };
                @endphp

                <td>
                    <a href="{{ $detailUrl }}" class="text-decoration-none text-primary">
                        {{ $item->user->name ?? $item->nama ?? '-' }}
                    </a>
                </td>

                {{-- ===== NIK (klik ke detail juga) ===== --}}
                <td>
                    <a href="{{ $detailUrl }}" class="text-decoration-none text-dark">
                        {{ $item->nik ?? '-' }}
                    </a>
                </td>

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

                {{-- ===== Aksi ===== --}}
                <td class="action-buttons">
                    @php
                        $updateUrl = route('admin.surat.updateStatus', ['type' => $type, 'id' => $item->id]);
                    @endphp

                    {{-- Tombol Detail --}}
                    <a href="{{ $detailUrl }}" class="btn btn-detail">Detail</a>

                    <!-- {{-- Tombol Proses --}}
                    <form action="{{ $updateUrl }}" method="POST" style="display:inline;">
                        @csrf
                        <input type="hidden" name="status" value="Diproses">
                        <button type="submit" class="btn btn-proses" title="Set status ke Diproses">Proses</button>
                    </form> -->

                    {{-- Tombol Selesai (buka modal upload surat jadi) --}}
                    <button type="button" class="btn btn-selesai"
                        onclick="openSelesaiModal('{{ $updateUrl }}')" title="Selesaikan Permohonan">
                        Selesai
                    </button>

                    {{-- Tombol Tolak (buka modal alasan) --}}
                    <button type="button" class="btn btn-tolak"
                        onclick="openTolakModal('{{ $updateUrl }}')" title="Tolak Permohonan">
                        Tolak
                    </button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" style="text-align: center;">Tidak ada data.</td>
            </tr>
        @endforelse
    </tbody>
</table>
