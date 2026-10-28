<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;
use App\Models\JadwalPeriksa;
use App\Models\Poli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DaftarPoliController extends Controller
{
    /**
     * Display the Pasien Dashboard: active queue banner and schedule table.
     */
    public function index()
    {
        $pasien = Auth::user();

        // Check if patient has an active (ongoing) queue/registration
        $activePendaftaran = DaftarPoli::with(['jadwalPeriksa.dokter.poli', 'periksa'])
            ->where('id_pasien', $pasien->id)
            ->active()
            ->latest()
            ->first();

        // Active schedules of all doctors for the queue table
        $jadwals = JadwalPeriksa::with(['dokter.poli'])
            ->where('status', 'aktif')
            ->orderBy('hari')
            ->get();

        $polis = Poli::with(['dokters.jadwalPeriksas' => function ($q) {
            $q->where('status', 'aktif');
        }])->get();

        return view('pasien.dashboard', compact('pasien', 'activePendaftaran', 'jadwals', 'polis'));
    }

    /**
     * Store a new poli registration with anti-double-booking constraint.
     */
    public function store(Request $request)
    {
        $pasien = Auth::user();

        // Constraint: Pasien tidak dapat mendaftar ke dua poli sekaligus
        $hasActive = DaftarPoli::where('id_pasien', $pasien->id)->active()->exists();

        if ($hasActive) {
            return back()->with('error', 'Anda masih memiliki antrian aktif atau pemeriksaan yang belum diselesaikan/dilunasi. Anda baru dapat mendaftar kembali setelah seluruh proses selesai.');
        }

        $request->validate([
            'id_jadwal' => 'required|exists:jadwal_periksa,id',
            'keluhan' => 'required|string|max:500',
        ], [
            'id_jadwal.required' => 'Pilihlah jadwal periksa dokter yang tersedia.',
            'id_jadwal.exists' => 'Jadwal dokter tidak valid.',
            'keluhan.required' => 'Keluhan wajib diisi.',
        ]);

        $jadwal = JadwalPeriksa::findOrFail($request->id_jadwal);

        // Calculate queue number for this schedule
        $lastAntrian = DaftarPoli::where('id_jadwal', $jadwal->id)->max('no_antrian') ?? 0;
        $noAntrian = $lastAntrian + 1;

        DaftarPoli::create([
            'id_pasien' => $pasien->id,
            'id_jadwal' => $jadwal->id,
            'keluhan' => $request->keluhan,
            'no_antrian' => $noAntrian,
            'status_periksa' => 'menunggu',
        ]);

        return redirect()->route('pasien.dashboard')->with('success', "Pendaftaran poliklinik berhasil! Nomor antrian Anda adalah #{$noAntrian}.");
    }
}
