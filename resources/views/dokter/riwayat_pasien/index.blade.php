<x-layouts.app title="Riwayat Pemeriksaan Pasien">
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-base-content flex items-center gap-2">
                    <i class="fas fa-clipboard-user text-primary"></i> Riwayat Pasien Diperiksa
                </h1>
                <p class="text-sm text-base-content/60">
                    Histori lengkap seluruh pasien yang pernah Anda periksa dan resep obat yang diberikan.
                </p>
            </div>
            <a href="{{ route('riwayat-pasien.export') }}" class="btn bg-emerald-600 hover:bg-emerald-700 text-white border-none rounded-xl gap-2 shadow-sm">
                <i class="fas fa-file-excel"></i>
                Export Excel
            </a>
        </div>

        <div class="card bg-base-100 shadow border border-base-200">
            <div class="card-body p-0">
                <div class="overflow-x-auto">
                    <table class="table w-full">
                        <thead class="bg-base-200/60 text-xs uppercase tracking-wider text-base-content/70">
                            <tr>
                                <th class="px-6 py-4">No</th>
                                <th class="px-6 py-4">Pasien</th>
                                <th class="px-6 py-4">Tgl Periksa</th>
                                <th class="px-6 py-4">Keluhan</th>
                                <th class="px-6 py-4">Catatan Dokter</th>
                                <th class="px-6 py-4">Obat Diberikan</th>
                                <th class="px-6 py-4">Biaya</th>
                                <th class="px-6 py-4">Pembayaran</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-base-200 text-sm">
                            @forelse($riwayats as $index => $r)
                                @php
                                    $daftarPoli = $r->daftarPoli;
                                    $pasien = $daftarPoli->pasien ?? null;
                                @endphp
                                <tr class="hover:bg-base-50/50 transition">
                                    <td class="px-6 py-4 font-semibold text-base-content/60">
                                        {{ $riwayats->firstItem() + $index }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-base-content">{{ $pasien->nama ?? '-' }}</div>
                                        <div class="text-xs text-base-content/50 font-mono">RM: {{ $pasien->no_rm ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-xs">
                                        {{ $r->tgl_periksa ? date('d M Y', strtotime($r->tgl_periksa)) : '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-xs max-w-xs text-base-content/70">
                                        {{ $daftarPoli->keluhan ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-xs max-w-xs">
                                        <p class="line-clamp-2">{{ $r->catatan }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-wrap gap-1">
                                            @forelse($r->detailPeriksas as $dp)
                                                <span class="badge badge-xs badge-outline">
                                                    {{ $dp->obat->nama_obat ?? '-' }}
                                                </span>
                                            @empty
                                                <span class="text-xs text-base-content/40">-</span>
                                            @endforelse
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 font-bold text-base-content">
                                        Rp {{ number_format($r->biaya_periksa, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($r->status_pembayaran === 'lunas')
                                            <span class="badge badge-success badge-sm font-semibold">Lunas</span>
                                        @elseif($r->status_pembayaran === 'menunggu_verifikasi')
                                            <span class="badge badge-warning badge-sm font-semibold">Verifikasi</span>
                                        @else
                                            <span class="badge badge-ghost badge-sm">Belum Bayar</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('riwayat-pasien.detail', $r->id) }}" class="btn btn-xs btn-primary gap-1">
                                            <i class="fas fa-eye text-xs"></i> Rincian
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-16 text-base-content/50">
                                        <i class="fas fa-inbox text-3xl mb-2 block opacity-30"></i>
                                        Belum ada riwayat pasien yang diperiksa.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($riwayats->hasPages())
                    <div class="p-4 border-t border-base-200">
                        {{ $riwayats->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app>
