<x-layouts.app title="Riwayat Pendaftaran Poliklinik">
    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-base-content flex items-center gap-2">
                <i class="fas fa-clock-rotate-left text-primary"></i> Riwayat Pendaftaran Poli
            </h1>
            <p class="text-sm text-base-content/60">
                Histori pendaftaran periksa Anda, nomor antrian, dan rincian hasil pemeriksaan dokter.
            </p>
        </div>


        <div class="card bg-base-100 shadow border border-base-200">
            <div class="card-body p-0">
                <div class="overflow-x-auto">
                    <table class="table w-full">
                        <thead class="bg-base-200/60 text-xs uppercase tracking-wider text-base-content/70">
                            <tr>
                                <th class="px-6 py-4 whitespace-nowrap">No. Antrian</th>
                                <th class="px-6 py-4 whitespace-nowrap">Nama Poli</th>
                                <th class="px-6 py-4 whitespace-nowrap min-w-[150px]">Dokter Pemeriksa</th>
                                <th class="px-6 py-4 whitespace-nowrap min-w-[140px]">Hari & Jam Praktik</th>
                                <th class="px-6 py-4 min-w-[140px]">Keluhan</th>
                                <th class="px-6 py-4 whitespace-nowrap">Status Pemeriksaan</th>
                                <th class="px-6 py-4 text-right whitespace-nowrap">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-base-200 text-sm">
                            @forelse($riwayats as $r)
                                @php
                                    $jadwal = $r->jadwalPeriksa;
                                    $dokter = $jadwal->dokter ?? null;
                                    $poli = $dokter->poli ?? null;
                                    $periksa = $r->periksa;
                                @endphp
                                <tr class="hover:bg-base-50/50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-lg font-black font-mono text-primary">
                                            #{{ $r->no_antrian }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200/80 whitespace-nowrap shadow-2xs">
                                            <i class="fas fa-hospital text-[10px] text-indigo-500"></i>
                                            {{ $poli->nama_poli ?? 'Poliklinik' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 font-bold text-base-content whitespace-nowrap">
                                        Dr. {{ $dokter->nama ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-xs whitespace-nowrap">
                                        <span class="font-semibold block whitespace-nowrap">{{ $jadwal->hari ?? '-' }}</span>
                                        <span class="text-base-content/60 whitespace-nowrap">{{ date('H:i', strtotime($jadwal->jam_mulai)) }} - {{ date('H:i', strtotime($jadwal->jam_selesai)) }} WIB</span>
                                    </td>
                                    <td class="px-6 py-4 text-xs max-w-xs text-base-content/70">
                                        {{ $r->keluhan }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <x-badge.status :status="$periksa || $r->status_periksa === 'selesai' ? 'selesai' : 'menunggu'" />
                                    </td>
                                    <td class="px-6 py-4 text-right whitespace-nowrap">
                                        @if($periksa || $r->status_periksa === 'selesai')
                                            <a href="{{ route('pasien.riwayat.detail', $r->id) }}" class="btn btn-xs btn-primary rounded-lg text-white font-medium gap-1 shadow-2xs whitespace-nowrap">
                                                <i class="fas fa-file-lines text-xs"></i> Detail
                                            </a>
                                        @else
                                            <span class="text-xs text-base-content/40 italic whitespace-nowrap">Belum diperiksa</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-16 text-base-content/50">
                                        <i class="fas fa-inbox text-3xl mb-2 block opacity-30"></i>
                                        Anda belum pernah mendaftar ke poliklinik.
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
