<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiwayatPoliController extends Controller
{
    /**
     * Display a listing of patient's examination history.
     */
    public function index()
    {
        $pasienId = Auth::id();

        // Efficient eager loading to avoid N+1 query
        $riwayats = DaftarPoli::with([
            'jadwalPeriksa.dokter.poli',
            'periksa.detailPeriksas.obat'
        ])
            ->where('id_pasien', $pasienId)
            ->latest('id')
            ->paginate(10);

        return view('pasien.riwayat.index', compact('riwayats'));
    }

    /**
     * Display detailed examination result for a completed registration.
     */
    public function detail(int $id)
    {
        $pasienId = Auth::id();

        $daftarPoli = DaftarPoli::with([
            'jadwalPeriksa.dokter.poli',
            'periksa.detailPeriksas.obat'
        ])
            ->where('id_pasien', $pasienId)
            ->findOrFail($id);

        if (!$daftarPoli->periksa) {
            return redirect()->route('pasien.riwayat.index')
                ->with('error', 'Pemeriksaan untuk pendaftaran ini belum dilakukan oleh dokter.');
        }

        return view('pasien.riwayat.detail', compact('daftarPoli'));
    }
}
