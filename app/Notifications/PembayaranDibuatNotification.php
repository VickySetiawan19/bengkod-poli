<?php

namespace App\Notifications;

use App\Models\Periksa;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PembayaranDibuatNotification extends Notification
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
        $namaPasien = $this->periksa->daftarPoli->pasien->nama ?? 'Pasien';
        $biaya = number_format($this->periksa->biaya_periksa, 0, ',', '.');

        return [
            'periksa_id' => $this->periksa->id,
            'title' => 'Bukti Pembayaran Diunggah',
            'message' => "Pasien {$namaPasien} telah mengunggah bukti pembayaran sebesar Rp {$biaya}. Menunggu verifikasi admin.",
            'action_url' => route('riwayat-pasien.index'),
            'type' => 'pembayaran_dibuat',
        ];
    }
}
