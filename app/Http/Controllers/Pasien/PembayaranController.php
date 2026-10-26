<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\Periksa;
use App\Notifications\PembayaranDibuatNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PembayaranController extends Controller
{
    /**
     * Display a listing of patient's examination bills.
     */
    public function index()
    {
        $pasienId = Auth::id();

        // Retrieve examinations belonging to current logged-in patient
        $tagihans = Periksa::with([
            'daftarPoli.jadwalPeriksa.dokter.poli',
            'detailPeriksas.obat'
        ])
            ->whereHas('daftarPoli', function ($q) use ($pasienId) {
                $q->where('id_pasien', $pasienId);
            })
            ->latest('id')
            ->paginate(10);

        return view('pasien.pembayaran.index', compact('tagihans'));
    }

    /**
     * Upload payment proof for a specific examination bill.
     */
    public function uploadBukti(Request $request, Periksa $periksa)
    {
        $pasienId = Auth::id();

        // Authorize: ensure examination belongs to logged-in patient
        if ($periksa->daftarPoli->id_pasien !== $pasienId) {
            abort(403, 'Akses tidak sah ke tagihan ini.');
        }

        if ($periksa->status_pembayaran === 'lunas') {
            return back()->with('error', 'Tagihan ini telah berstatus Lunas.');
        }

        $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'bukti_pembayaran.required' => 'File bukti pembayaran wajib diunggah.',
            'bukti_pembayaran.image' => 'File harus berupa gambar.',
            'bukti_pembayaran.mimes' => 'Format file yang didukung: JPG, JPEG, PNG, WEBP.',
            'bukti_pembayaran.max' => 'Ukuran file gambar maksimal 2MB.',
        ]);

        // Delete previous proof file if exists
        if ($periksa->bukti_pembayaran && Storage::disk('public')->exists($periksa->bukti_pembayaran)) {
            Storage::disk('public')->delete($periksa->bukti_pembayaran);
        }

        $path = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');

        $periksa->update([
            'bukti_pembayaran' => $path,
            'status_pembayaran' => 'menunggu_verifikasi',
        ]);

        // Notify Doctor: Dokter menerima notifikasi ketika pasien yang ia periksa membuat pembayaran
        $dokter = $periksa->daftarPoli->jadwalPeriksa->dokter ?? null;
        if ($dokter) {
            $dokter->notify(new PembayaranDibuatNotification($periksa));
        }

        return redirect()->route('pasien.pembayaran.index')
            ->with('success', 'Bukti pembayaran berhasil diunggah! Menunggu verifikasi dari Admin.');
    }
}
