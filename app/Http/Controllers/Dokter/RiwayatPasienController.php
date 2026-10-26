<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\Periksa;
use App\Services\ExcelExportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiwayatPasienController extends Controller
{
    /**
     * Display a listing of patients previously examined by the authenticated doctor.
     */
    public function index()
    {
        $dokterId = Auth::id();

        $riwayats = Periksa::with([
            'daftarPoli.pasien',
            'daftarPoli.jadwalPeriksa',
            'detailPeriksas.obat'
        ])
            ->whereHas('daftarPoli.jadwalPeriksa', function ($q) use ($dokterId) {
                $q->where('id_dokter', $dokterId);
            })
            ->latest('tgl_periksa')
            ->paginate(15);

        return view('dokter.riwayat_pasien.index', compact('riwayats'));
    }

    /**
     * Display examination detail.
     */
    public function detail(Periksa $periksa)
    {
        if ($periksa->daftarPoli->jadwalPeriksa->id_dokter !== Auth::id()) {
            abort(403, 'Akses tidak sah.');
        }

        $periksa->load(['daftarPoli.pasien', 'daftarPoli.jadwalPeriksa', 'detailPeriksas.obat']);

        return view('dokter.riwayat_pasien.detail', compact('periksa'));
    }

    /**
     * Export examination history of current doctor to Excel.
     */
    public function export()
    {
        $dokterId = Auth::id();

        $riwayats = Periksa::with([
            'daftarPoli.pasien',
            'daftarPoli.jadwalPeriksa',
            'detailPeriksas.obat'
        ])
            ->whereHas('daftarPoli.jadwalPeriksa', function ($q) use ($dokterId) {
                $q->where('id_dokter', $dokterId);
            })
            ->latest('tgl_periksa')
            ->get();

        $headers = [
            'No',
            'Nama Pasien',
            'No. RM',
            'No. KTP',
            'Tanggal Periksa',
            'Keluhan',
            'Catatan Dokter',
            'Obat Diberikan',
            'Biaya Periksa (Rp)',
            'Status Pembayaran'
        ];

        $rows = [];
        $no = 1;
        foreach ($riwayats as $r) {
            $daftarPoli = $r->daftarPoli;
            $pasien = $daftarPoli->pasien ?? null;

            $obatList = $r->detailPeriksas->map(function ($dp) {
                return $dp->obat->nama_obat ?? '-';
            })->implode(', ');

            $rows[] = [
                $no++,
                $pasien->nama ?? '-',
                $pasien->no_rm ?? '-',
                $pasien->no_ktp ?? '-',
                $r->tgl_periksa ? date('d-m-Y', strtotime($r->tgl_periksa)) : '-',
                $daftarPoli->keluhan ?? '-',
                $r->catatan ?? '-',
                $obatList ?: '-',
                $r->biaya_periksa,
                strtoupper(str_replace('_', ' ', $r->status_pembayaran)),
            ];
        }

        $filename = 'riwayat_pasien_' . date('Ymd_His');

        return ExcelExportService::download($filename, $headers, $rows);
    }
}
