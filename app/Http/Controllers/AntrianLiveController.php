<?php

namespace App\Http\Controllers;

use App\Models\DaftarPoli;
use App\Models\JadwalPeriksa;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AntrianLiveController extends Controller
{
    /**
     * Return live status of queues for all active doctor schedules and the current user's active queue.
     */
    public function status(): JsonResponse
    {
        $jadwals = JadwalPeriksa::with(['dokter.poli'])
            ->where('status', 'aktif')
            ->get()
            ->map(function ($j) {
                return [
                    'id' => $j->id,
                    'hari' => $j->hari,
                    'jam_mulai' => $j->jam_mulai,
                    'jam_selesai' => $j->jam_selesai,
                    'antrian_sekarang' => (int) $j->antrian_sekarang,
                    'dokter_nama' => $j->dokter->nama ?? '-',
                    'poli_id' => $j->dokter->id_poli ?? null,
                    'poli_nama' => $j->dokter->poli->nama_poli ?? '-',
                ];
            });

        $userActiveQueue = null;
        if (Auth::check() && Auth::user()->role === 'pasien') {
            $activePendaftaran = DaftarPoli::with(['jadwalPeriksa.dokter.poli', 'periksa'])
                ->where('id_pasien', Auth::id())
                ->active()
                ->latest()
                ->first();

            if ($activePendaftaran) {
                // Auto sync status_periksa if queue number is actively being served
                $currentServed = (int) ($activePendaftaran->jadwalPeriksa->antrian_sekarang ?? 0);
                if ($currentServed === (int) $activePendaftaran->no_antrian && $activePendaftaran->status_periksa === 'menunggu') {
                    $activePendaftaran->update(['status_periksa' => 'sedang_diperiksa']);
                    $activePendaftaran->status_periksa = 'sedang_diperiksa';
                }

                $userActiveQueue = [
                    'id' => $activePendaftaran->id,
                    'no_antrian' => (int) $activePendaftaran->no_antrian,
                    'status_periksa' => $activePendaftaran->status_periksa,
                    'keluhan' => $activePendaftaran->keluhan,
                    'poli' => $activePendaftaran->jadwalPeriksa->dokter->poli->nama_poli ?? '-',
                    'dokter' => $activePendaftaran->jadwalPeriksa->dokter->nama ?? '-',
                    'hari' => $activePendaftaran->jadwalPeriksa->hari ?? '-',
                    'jam_mulai' => $activePendaftaran->jadwalPeriksa->jam_mulai ?? '-',
                    'jam_selesai' => $activePendaftaran->jadwalPeriksa->jam_selesai ?? '-',
                    'antrian_sekarang' => (int) ($activePendaftaran->jadwalPeriksa->antrian_sekarang ?? 0),
                    'status_pembayaran' => $activePendaftaran->periksa->status_pembayaran ?? 'belum_periksa',
                ];
            }
        }

        return response()->json([
            'success' => true,
            'timestamp' => now()->toIso8601String(),
            'jadwals' => $jadwals,
            'user_active_queue' => $userActiveQueue,
        ]);
    }
}
