<?php

namespace App\LogicTier\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Database\Eloquent\Model; // Tambahkan import Model
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SuratDiajukan implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $permohonan;
    public $jenisSurat;
    public $message;

    public function __construct(Model $permohonan, $jenisSurat)
    {
        $this->permohonan = $permohonan;
        $this->jenisSurat = $jenisSurat;
        $this->message = "Permohonan {$this->jenisSurat} baru dari {$this->permohonan->nama} telah masuk.";
    }

    public function broadcastOn(): array
    {
        return [new PrivateChannel('admin-channel')];
    }

    public function broadcastAs(): string
    {
        return 'SuratDiajukan'; // 👈 nama yang akan didengar oleh Echo
    }

    public function broadcastWith(): array
    {
        return [
            'permohonan_id' => $this->permohonan->id,
            'jenis_surat' => $this->jenisSurat,
            'nama_pemohon' => $this->permohonan->nama,
            'url_detail' => "/admin/permohonan/{$this->getPermohonanType()}/{$this->permohonan->id}",
            'message' => $this->message,
        ];
    }

    private function getPermohonanType(): string
    {
        return match(get_class($this->permohonan)) {
            \App\DataTier\Models\PermohonanDomisili::class => 'domisili',
            \App\DataTier\Models\PermohonanKTM::class => 'ktm',
            \App\DataTier\Models\PermohonanSKU::class => 'sku',
            default => 'unknown'
        };
    }
}
