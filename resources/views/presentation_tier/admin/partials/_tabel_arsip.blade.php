@if($permohonans->isEmpty())
    <div class="no-data-container" style="text-align: center; padding: 40px 0;">
        <p style="color: #888; margin-top: 10px;"> Tidak ada data arsip.</p>
    </div>
@else
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pemohon</th>
                <th>NIK</th>
                <th>Tanggal Pengajuan</th>
                <th>Tanggal Diarsipkan</th>
                <th>Status Akhir</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($permohonans as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>

                    @php
                        $detailUrl = match ($type) {
                            'domisili' => route('admin.domisili.show', $item->id),
                            'sku' => route('admin.sku.show', $item->id),
                            'ktm' => route('admin.ktm.show', $item->id),
                            default => '#',
                        };

                        // Route untuk Logic Tier Penghapusan
                        $deleteUrl = route('admin.surat.destroy', [$type, $item->id]);
                    @endphp

                    <td>
                        <a href="{{ $detailUrl }}" class="text-decoration-none text-primary">
                            {{ $item->user->name ?? $item->nama ?? '-' }}
                        </a>
                    </td>

                    <td>{{ $item->nik ?? '-' }}</td>
                    <td>{{ $item->created_at->format('d M Y, H:i') }}</td>
                    <td>{{ $item->archived_at ? $item->archived_at->format('d M Y, H:i') : '-' }}</td>

                    <td>
                        @if($item->status == 'Selesai')
                            <span class="status status-selesai">Selesai</span>
                        @elseif($item->status == 'Ditolak')
                            <span class="status status-ditolak">Ditolak</span>
                        @endif
                    </td>

                    <td class="action-buttons" style="display: flex; gap: 5px;">
                        <a href="{{ $detailUrl }}" class="btn btn-detail">Lihat Detail</a>
                        
                        <form action="{{ $deleteUrl }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini secara permanen? Tindakan ini tidak dapat dibatalkan.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="background-color: #dc3545; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer;">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif