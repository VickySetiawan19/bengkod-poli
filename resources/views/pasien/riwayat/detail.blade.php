<x-layouts.app title="Rincian Hasil Pemeriksaan">
    <div class="max-w-4xl mx-auto space-y-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('pasien.riwayat.index') }}" class="btn btn-circle btn-ghost btn-sm">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-base-content flex items-center gap-2">
                    <i class="fas fa-file-waveform text-primary"></i> Detail Hasil Pemeriksaan
                </h1>
                <p class="text-sm text-base-content/60">
                    Informasi catatan dokter, rincian obat yang diresepkan, dan total biaya periksa.
                </p>
            </div>
        </div>

        @php
            $jadwal = $daftarPoli->jadwalPeriksa;
            $dokter = $jadwal->dokter ?? null;
            $poli = $dokter->poli ?? null;
            $periksa = $daftarPoli->periksa;
        @endphp

        {{-- Poli & Doctor Summary Card --}}
        <div class="card bg-base-100 shadow border border-base-200">
            <div class="card-body p-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-base-200 gap-2">
                    <div>
                        <span class="badge badge-primary badge-outline badge-sm font-semibold">{{ $poli->nama_poli ?? 'Poliklinik' }}</span>
                        <h2 class="text-xl font-bold text-base-content mt-1">Dr. {{ $dokter->nama ?? '-' }}</h2>
                    </div>
                    <div class="text-left sm:text-right">
                        <span class="text-xs text-base-content/50 block">Nomor Antrian:</span>
                        <span class="text-2xl font-black text-primary font-mono">#{{ $daftarPoli->no_antrian }}</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-4 text-xs">
                    <div>
                        <span class="text-base-content/50 block">Tanggal Periksa:</span>
                        <span class="font-bold text-sm text-base-content">{{ $periksa->tgl_periksa ? date('d F Y', strtotime($periksa->tgl_periksa)) : '-' }}</span>
                    </div>
                    <div>
                        <span class="text-base-content/50 block">Status Pembayaran:</span>
                        @if($periksa->status_pembayaran === 'lunas')
                            <span class="badge badge-success badge-sm font-bold">Lunas</span>
                        @elseif($periksa->status_pembayaran === 'menunggu_verifikasi')
                            <span class="badge badge-warning badge-sm font-bold">Menunggu Verifikasi</span>
                        @else
                            <span class="badge badge-error badge-sm font-bold">Belum Bayar</span>
                        @endif
                    </div>
                    <div>
                        <span class="text-base-content/50 block">Keluhan Anda:</span>
                        <span class="font-semibold text-base-content italic">"{{ $daftarPoli->keluhan }}"</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Doctor Notes Card --}}
        <div class="card bg-base-100 shadow border border-base-200">
            <div class="card-body p-6">
                <h3 class="font-bold text-base text-base-content flex items-center gap-2 mb-3">
                    <i class="fas fa-clipboard-check text-primary"></i> Catatan & Anjuran Dokter
                </h3>
                <div class="p-4 bg-base-200/50 rounded-xl border border-base-200 text-sm leading-relaxed text-base-content">
                    {{ $periksa->catatan }}
                </div>
            </div>
        </div>

        {{-- Medicines & Total Fee Card --}}
        <div class="card bg-base-100 shadow border border-base-200">
            <div class="card-body p-6">
                <h3 class="font-bold text-base text-base-content flex items-center gap-2 mb-4">
                    <i class="fas fa-pills text-primary"></i> Daftar Obat & Total Biaya Pemeriksaan
                </h3>

                <div class="overflow-x-auto">
                    <table class="table w-full text-sm">
                        <thead class="bg-base-200/50 text-xs uppercase">
                            <tr>
                                <th>Item Pelayanan / Nama Obat</th>
                                <th>Kemasan</th>
                                <th class="text-right">Harga</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-base-200">
                            <tr>
                                <td class="font-semibold text-base-content">
                                    <i class="fas fa-stethoscope text-primary mr-2"></i> Jasa Pelayanan Dokter & Pemeriksaan
                                </td>
                                <td>-</td>
                                <td class="text-right font-mono font-semibold">Rp 150.000</td>
                            </tr>
                            @forelse($periksa->detailPeriksas as $dp)
                                <tr>
                                    <td>
                                        <i class="fas fa-capsules text-teal-600 mr-2"></i> {{ $dp->obat->nama_obat ?? '-' }}
                                    </td>
                                    <td class="text-xs text-base-content/60">{{ $dp->obat->kemasan ?? '-' }}</td>
                                    <td class="text-right font-mono font-semibold">
                                        Rp {{ number_format($dp->obat->harga ?? 0, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-xs text-base-content/50 py-3 italic">
                                        Tidak ada resep obat tambahan pada pemeriksaan ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="bg-base-200/30">
                            <tr class="font-bold text-base text-primary">
                                <td colspan="2">Total Biaya Periksa:</td>
                                <td class="text-right font-mono text-xl">
                                    Rp {{ number_format($periksa->biaya_periksa, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                @if($periksa->status_pembayaran !== 'lunas')
                    <div class="mt-6 pt-4 border-t border-base-200 flex justify-end">
                        <a href="{{ route('pasien.pembayaran.index') }}" class="btn btn-warning gap-2">
                            <i class="fas fa-wallet"></i> Bayar & Unggah Bukti Transfer
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app>
