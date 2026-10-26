<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Periksa;
use App\Notifications\PembayaranDiverifikasiNotification;
use Illuminate\Http\Request;

class VerifikasiPembayaranController extends Controller
{
    /**
     * Display a listing of payments requiring verification or all payment histories.
     */
    public function index(Request $request)
    {
        $status = $request->query('status', 'menunggu_verifikasi');

        $query = Periksa::with([
            'daftarPoli.pasien',
            'daftarPoli.jadwalPeriksa.dokter.poli',
            'detailPeriksas.obat'
        ])->latest('id');

        if ($status !== 'semua') {
            $query->where('status_pembayaran', $status);
        }

        $tagihans = $query->paginate(15)->withQueryString();

        $countMenunggu = Periksa::where('status_pembayaran', 'menunggu_verifikasi')->count();
        $countLunas = Periksa::where('status_pembayaran', 'lunas')->count();
        $countBelumBayar = Periksa::where('status_pembayaran', 'belum_bayar')->count();

        return view('admin.verifikasi_pembayaran.index', compact('tagihans', 'status', 'countMenunggu', 'countLunas', 'countBelumBayar'));
    }

    /**
     * Confirm patient payment as LUNAS.
     */
    public function konfirmasi(Periksa $periksa)
    {
        $periksa->update([
            'status_pembayaran' => 'lunas',
            'tgl_bayar' => now(),
        ]);

        // Ensure registration status is marked selesai
        if ($periksa->daftarPoli) {
            $periksa->daftarPoli->update(['status_periksa' => 'selesai']);
        }

        // Send notification to the patient: Pasien menerima notifikasi ketika pembayarannya diverifikasi (lunas) oleh admin
        $pasien = $periksa->daftarPoli->pasien ?? null;
        if ($pasien) {
            $pasien->notify(new PembayaranDiverifikasiNotification($periksa));
        }

        return redirect()->route('verifikasi-pembayaran.index')
            ->with('success', 'Pembayaran berhasil diverifikasi dan status telah diubah menjadi LUNAS. Notifikasi telah dikirimkan ke pasien.');
    }
}
