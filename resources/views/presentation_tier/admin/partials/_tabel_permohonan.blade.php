<div class="table-container">
    <div class="table-header">
        <h3>{{ $title }}</h3>
    </div>
    @if($permohonans->isEmpty())
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Akun</th>
                    <th>NIK</th>
                    <th>Tanggal Pengajuan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                   <td colspan="6">Tidak ada data.</td>
                </tr>
            </tbody>
        </table>
    @else
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Akun</th>
                    <th>NIK</th>
                    <th>Tanggal Pengajuan</th>
                    <th>Status</th>
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
                        @endphp

                        <td>
                            <a href="{{ $detailUrl }}" class="text-decoration-none">
                                {{ $item->user->name ?? $item->nama ?? '-' }}
                            </a>
                        </td>
                        <td>{{ $item->nik ?? '-' }}</td>
                        <td>{{ $item->created_at->format('d M Y, H:i') }}</td>
                        <td>
                            @if($item->status == 'Diproses')
                                <span class="status status-diproses">Diproses</span>
                            @elseif($item->status == 'Diterima')
                                <span class="status status-diterima">Diterima</span>
                            @elseif($item->status == 'Selesai')
                                <span class="status status-selesai">Selesai</span>
                            @elseif($item->status == 'Ditolak')
                                <span class="status status-ditolak">Ditolak</span>
                            @endif
                        </td>
                        <td class="action-buttons">
                            <a href="{{ $detailUrl }}" class="btn btn-detail">Lihat Detail</a>

                            {{-- Tombol Terima: Hanya tampil jika status "Diproses" --}}
                            {{-- === PERUBAHAN: Sekarang langsung POST status "Diterima", tanpa modal upload === --}}
                            @if($item->status == 'Diproses')
                                <form action="{{ route('admin.surat.updateStatus', [$type, $item->id]) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <input type="hidden" name="status" value="Diterima">
                                    <button type="submit"
                                        class="btn btn-selesai"
                                        onclick="return confirm('Apakah Anda yakin ingin menerima permohonan ini?')">
                                        Terima
                                    </button>
                                </form>
                            @endif
                            {{-- === AKHIR PERUBAHAN === --}}

                            {{-- Tombol Tolak: Hanya tampil jika status "Diproses" --}}
                            @if($item->status == 'Diproses')
                                <button type="button" 
                                    class="btn btn-tolak" 
                                    onclick="openTolakModal('{{ route('admin.surat.updateStatus', [$type, $item->id]) }}')">
                                    Tolak
                                </button>
                            @endif

                            {{-- === TAMBAHAN: Tombol Kirim Surat: Hanya tampil jika status "Diterima" === --}}
                            @if($item->status == 'Diterima')
                                <button type="button"
                                    class="btn btn-kirim-surat"
                                    onclick="openSelesaiModal('{{ route('admin.surat.kirimSurat', [$type, $item->id]) }}')">
                                    Kirim Surat
                                </button>
                            @endif
                            {{-- === AKHIR TAMBAHAN === --}}

                            {{-- Tombol Arsipkan: Hanya tampil jika status "Selesai" atau "Ditolak" --}}
                            @if($item->status == 'Selesai' || $item->status == 'Ditolak')
                                <form action="{{ route('admin.surat.archive', [$type, $item->id]) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" 
                                        class="btn btn-arsip" 
                                        onclick="return confirm('Apakah Anda yakin ingin mengarsipkan permohonan ini?')">
                                        Arsipkan
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>