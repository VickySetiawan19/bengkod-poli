<?php

namespace App\Notifications;

use App\Models\Periksa;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PembayaranDiverifikasiNotification extends Notification
{
    use Queueable;

    public Periksa $periksa;

    public function __construct(Periksa $periksa)
    {
        $this->periksa = $periksa;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $poli = $this->periksa->daftarPoli->jadwalPeriksa->dokter->poli->nama_poli ?? 'Poli';
        $biaya = number_format($this->periksa->biaya_periksa, 0, ',', '.');

        return [
            'periksa_id' => $this->periksa->id,
            'title' => 'Pembayaran Terverifikasi (Lunas)',
            'message' => "Pembayaran pemeriksaan {$poli} sebesar Rp {$biaya} telah berhasil diverifikasi oleh Admin. Status: LUNAS.",
            'action_url' => route('pasien.riwayat.detail', $this->periksa->id_daftar_poli),
            'type' => 'pembayaran_diverifikasi',
        ];
    }
}
