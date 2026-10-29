<?php

namespace App\Http\Controllers\Dokter;

use App\Events\AntrianUpdated;
use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;
use App\Models\DetailPeriksa;
use App\Models\JadwalPeriksa;
use App\Models\Obat;
use App\Models\Periksa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PeriksaPasienController extends Controller
{
    /**
     * Display a listing of patients queued for the authenticated doctor's schedules.
     */
    public function index()
    {
        $dokterId = Auth::id();

        // Get schedules of current doctor
        $jadwalIds = JadwalPeriksa::where('id_dokter', $dokterId)->pluck('id');

        $antrians = DaftarPoli::with(['pasien', 'jadwalPeriksa', 'periksa'])
            ->whereIn('id_jadwal', $jadwalIds)
            ->orderBy('id_jadwal')
            ->orderBy('no_antrian')
            ->paginate(15);

        $myJadwals = JadwalPeriksa::where('id_dokter', $dokterId)->get();

        return view('dokter.periksa_pasien.index', compact('antrians', 'myJadwals'));
    }

    /**
     * Update the currently called queue number for a schedule.
     */
    public function callNumber(Request $request, JadwalPeriksa $jadwal)
    {
        if ($jadwal->id_dokter !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'no_antrian' => 'required|integer|min:0',
        ]);

        $noAntrian = (int) $request->no_antrian;

        // Validation: Verify that the called queue number actually exists in this schedule
        $targetPendaftaran = DaftarPoli::with('pasien')
            ->where('id_jadwal', $jadwal->id)
            ->where('no_antrian', $noAntrian)
            ->first();

        if ($noAntrian > 0 && !$targetPendaftaran) {
            return back()->with('error', "Nomor antrian #{$noAntrian} belum terdaftar pada jadwal praktik ini.");
        }

        $jadwal->update([
            'antrian_sekarang' => $noAntrian,
        ]);

        // Auto update status_periksa to 'sedang_diperiksa' for the called queue number
        if ($targetPendaftaran && $targetPendaftaran->status_periksa === 'menunggu') {
            $targetPendaftaran->update(['status_periksa' => 'sedang_diperiksa']);
        }

        // Broadcast real-time update
        event(new AntrianUpdated($jadwal));

        $namaPasien = $targetPendaftaran && $targetPendaftaran->pasien ? " ({$targetPendaftaran->pasien->nama})" : '';
        return back()->with('success', "Nomor antrian #{$noAntrian}{$namaPasien} berhasil dipanggil.");
    }

    /**
     * Show form to examine a patient.
     */
    public function create(DaftarPoli $daftarPoli)
    {
        // Authorize: doctor must own this schedule
        if ($daftarPoli->jadwalPeriksa->id_dokter !== Auth::id()) {
            abort(403, 'Akses tidak sah.');
        }

        // Available medicines with stok > 0
        $obats = Obat::where('stok', '>', 0)->orderBy('nama_obat')->get();

        // Auto update queue being served to this patient's queue number
        $jadwal = $daftarPoli->jadwalPeriksa;
        if ($jadwal->antrian_sekarang < $daftarPoli->no_antrian) {
            $jadwal->update(['antrian_sekarang' => $daftarPoli->no_antrian]);
            event(new AntrianUpdated($jadwal));
        }

        if ($daftarPoli->status_periksa === 'menunggu') {
            $daftarPoli->update(['status_periksa' => 'sedang_diperiksa']);
        }

        return view('dokter.periksa_pasien.create', compact('daftarPoli', 'obats'));
    }

    /**
     * Store the examination results, medicine details, and auto-calculate total fees.
     */
    public function store(Request $request, DaftarPoli $daftarPoli)
    {
        if ($daftarPoli->jadwalPeriksa->id_dokter !== Auth::id()) {
            abort(403, 'Akses tidak sah.');
        }

        $request->validate([
            'tgl_periksa' => 'required|date',
            'catatan' => 'required|string',
            'id_obat' => 'nullable|array',
            'id_obat.*' => 'exists:obat,id',
        ], [
            'tgl_periksa.required' => 'Tanggal periksa wajib diisi.',
            'catatan.required' => 'Catatan dokter wajib diisi.',
            'id_obat.*.exists' => 'Obat yang dipilih tidak valid.',
        ]);

        DB::transaction(function () use ($request, $daftarPoli) {
            $baseCost = 150000; // Rp 150.000 jasa dokter
            $selectedObatIds = $request->input('id_obat', []);

            $totalObatCost = 0;
            if (!empty($selectedObatIds)) {
                $obats = Obat::whereIn('id', $selectedObatIds)->get();
                $totalObatCost = $obats->sum('harga');
            }

            $totalBiaya = $baseCost + $totalObatCost;

            // Create examination record
            $periksa = Periksa::create([
                'id_daftar_poli' => $daftarPoli->id,
                'tgl_periksa' => $request->tgl_periksa,
                'catatan' => $request->catatan,
                'biaya_periksa' => $totalBiaya,
                'status_pembayaran' => 'belum_bayar',
            ]);

            // Save medicine details and reduce medicine stock
            foreach ($selectedObatIds as $obatId) {
                DetailPeriksa::create([
                    'id_periksa' => $periksa->id,
                    'id_obat' => $obatId,
                ]);

                // Reduce medicine stock by 1
                $obatItem = Obat::find($obatId);
                if ($obatItem && $obatItem->stok > 0) {
                    $obatItem->decrement('stok', 1);
                }
            }

            // Update status_periksa on daftar_poli
            $daftarPoli->update([
                'status_periksa' => 'selesai',
            ]);

            // Update and broadcast antrian
            $jadwal = $daftarPoli->jadwalPeriksa;
            $jadwal->update([
                'antrian_sekarang' => $daftarPoli->no_antrian,
            ]);
            event(new AntrianUpdated($jadwal));
        });

        return redirect()->route('periksa-pasien.index')
            ->with('success', 'Pemeriksaan pasien berhasil disimpan. Biaya periksa otomatis dihitung (Jasa Rp 150.000 + Obat).');
    }
}
