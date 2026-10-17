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
                                <th class="px-6 py-4 whitespace-nowrap">No</th>
                                <th class="px-6 py-4 whitespace-nowrap min-w-[160px]">Pasien</th>
                                <th class="px-6 py-4 whitespace-nowrap">Tgl Periksa</th>
                                <th class="px-6 py-4 min-w-[140px]">Keluhan</th>
                                <th class="px-6 py-4 min-w-[140px]">Catatan Dokter</th>
                                <th class="px-6 py-4 min-w-[180px]">Obat Diberikan</th>
                                <th class="px-6 py-4 whitespace-nowrap">Biaya</th>
                                <th class="px-6 py-4 whitespace-nowrap">Pembayaran</th>
                                <th class="px-6 py-4 text-right whitespace-nowrap">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-base-200 text-sm">
                            @forelse($riwayats as $index => $r)
                                @php
                                    $daftarPoli = $r->daftarPoli;
                                    $pasien = $daftarPoli->pasien ?? null;
                                @endphp
                                <tr class="hover:bg-base-50/50 transition">
                                    <td class="px-6 py-4 font-semibold text-base-content/60 whitespace-nowrap">
                                        {{ $riwayats->firstItem() + $index }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-bold text-base-content whitespace-nowrap">{{ $pasien->nama ?? '-' }}</div>
                                        <div class="text-xs text-base-content/50 font-mono whitespace-nowrap">RM: {{ $pasien->no_rm ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-xs whitespace-nowrap">
                                        {{ $r->tgl_periksa ? date('d M Y', strtotime($r->tgl_periksa)) : '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-xs max-w-xs text-base-content/70">
                                        {{ $daftarPoli->keluhan ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-xs max-w-xs">
                                        <p class="line-clamp-2">{{ $r->catatan }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-wrap gap-1 max-w-xs">
                                            @forelse($r->detailPeriksas as $dp)
                                                <x-badge.tag>
                                                    {{ $dp->obat->nama_obat ?? '-' }}
                                                </x-badge.tag>
                                            @empty
                                                <span class="text-xs text-base-content/40">-</span>
                                            @endforelse
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 font-bold text-base-content whitespace-nowrap font-mono">
                                        Rp {{ number_format($r->biaya_periksa, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <x-badge.status :status="$r->status_pembayaran" />
                                    </td>
                                    <td class="px-6 py-4 text-right whitespace-nowrap">
                                        <a href="{{ route('riwayat-pasien.detail', $r->id) }}" class="btn btn-xs btn-primary rounded-lg gap-1 font-medium whitespace-nowrap shadow-2xs">
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
