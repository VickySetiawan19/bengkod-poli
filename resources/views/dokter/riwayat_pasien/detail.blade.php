<x-layouts.app title="Detail Pemeriksaan Pasien">
    <div class="max-w-4xl mx-auto space-y-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('riwayat-pasien.index') }}" class="btn btn-circle btn-ghost btn-sm">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-base-content flex items-center gap-2">
                    <i class="fas fa-file-medical text-primary"></i> Rincian Pemeriksaan Pasien
                </h1>
                <p class="text-sm text-base-content/60">
                    Detail rekam medis dan resep obat yang telah diberikan.
                </p>
            </div>
        </div>

        @php
            $daftarPoli = $periksa->daftarPoli;
            $pasien = $daftarPoli->pasien ?? null;
        @endphp

        {{-- Patient & Registration Info --}}
        <div class="card bg-base-100 shadow border border-base-200">
            <div class="card-body p-6">
                <div class="flex items-center justify-between pb-4 border-b border-base-200">
                    <div>
                        <span class="text-xs text-base-content/50 uppercase font-semibold">Identitas Pasien</span>
                        <h2 class="text-xl font-bold text-base-content">{{ $pasien->nama ?? '-' }}</h2>
                        <span class="text-xs font-mono text-primary font-semibold">No. RM: {{ $pasien->no_rm ?? '-' }}</span>
                    </div>
                    <div class="text-right">
                        <span class="text-xs text-base-content/50 block mb-1">Status Pembayaran:</span>
                        <x-badge.status :status="$periksa->status_pembayaran" size="sm" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-4 text-xs">
                    <div>
                        <span class="text-base-content/50 block">No. KTP / NIK:</span>
                        <span class="font-semibold text-base-content">{{ $pasien->no_ktp ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="text-base-content/50 block">No. HP / WhatsApp:</span>
                        <span class="font-semibold text-base-content">{{ $pasien->no_hp ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="text-base-content/50 block">Tanggal Periksa:</span>
                        <span class="font-semibold text-base-content">{{ $periksa->tgl_periksa ? date('d F Y', strtotime($periksa->tgl_periksa)) : '-' }}</span>
                    </div>
                </div>

                <div class="mt-4 pt-4 border-t border-base-200 text-xs">
                    <span class="text-base-content/50 block font-semibold">Keluhan Saat Mendaftar:</span>
                    <p class="text-base-content/80 mt-1 italic">"{{ $daftarPoli->keluhan }}"</p>
                </div>
            </div>
        </div>

        {{-- Doctor Notes & Diagnosis --}}
        <div class="card bg-base-100 shadow border border-base-200">
            <div class="card-body p-6">
                <h3 class="font-bold text-base text-base-content flex items-center gap-2 mb-3">
                    <i class="fas fa-clipboard-list text-primary"></i> Catatan & Diagnosa Dokter
                </h3>
                <div class="p-4 bg-base-200/40 rounded-xl border border-base-200 text-sm leading-relaxed text-base-content/90">
                    {{ $periksa->catatan }}
                </div>
            </div>
        </div>

        {{-- Prescribed Medicines & Cost Breakdown --}}
        <div class="card bg-base-100 shadow border border-base-200">
            <div class="card-body p-6">
                <h3 class="font-bold text-base text-base-content flex items-center gap-2 mb-4">
                    <i class="fas fa-pills text-primary"></i> Daftar Obat & Rincian Biaya
                </h3>

                <div class="overflow-x-auto">
                    <table class="table w-full text-sm">
                        <thead class="bg-base-200/50 text-xs uppercase">
                            <tr>
                                <th>Item Pelayanan / Obat</th>
                                <th>Kemasan</th>
                                <th class="text-right">Harga</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-base-200">
                            <tr>
                                <td class="font-semibold">
                                    <i class="fas fa-user-doctor text-primary mr-2"></i> Jasa Pelayanan Dokter & Pemeriksaan
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
                                <td colspan="2">Total Biaya Pemeriksaan:</td>
                                <td class="text-right font-mono text-lg">
                                    Rp {{ number_format($periksa->biaya_periksa, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
