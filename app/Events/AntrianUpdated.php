<?php

namespace App\Events;

use App\Models\JadwalPeriksa;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AntrianUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $jadwal_id;
    public int $antrian_sekarang;
    public ?int $poli_id;
    public string $nama_poli;
    public string $nama_dokter;

    public function __construct(JadwalPeriksa $jadwal)
    {
        $this->jadwal_id = $jadwal->id;
        $this->antrian_sekarang = (int) $jadwal->antrian_sekarang;
        $this->poli_id = $jadwal->dokter->id_poli ?? null;
        $this->nama_poli = $jadwal->dokter->poli->nama_poli ?? 'Poliklinik';
        $this->nama_dokter = $jadwal->dokter->nama ?? 'Dokter';
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('antrian-channel'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'AntrianUpdated';
    }

    public function broadcastWith(): array
    {
        return [
            'jadwal_id' => $this->jadwal_id,
            'antrian_sekarang' => $this->antrian_sekarang,
            'poli_id' => $this->poli_id,
            'nama_poli' => $this->nama_poli,
            'nama_dokter' => $this->nama_dokter,
            'timestamp' => now()->toIso8601String(),
        ];
    }
}
