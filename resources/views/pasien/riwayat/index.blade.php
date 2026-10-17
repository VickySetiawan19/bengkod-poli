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
                                <th class="px-6 py-4">No. Antrian</th>
                                <th class="px-6 py-4">Nama Poli</th>
                                <th class="px-6 py-4">Dokter Pemeriksa</th>
                                <th class="px-6 py-4">Hari & Jam Praktik</th>
                                <th class="px-6 py-4">Keluhan</th>
                                <th class="px-6 py-4">Status Pemeriksaan</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
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
                                    <td class="px-6 py-4">
                                        <span class="text-lg font-black font-mono text-primary">
                                            #{{ $r->no_antrian }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="badge badge-primary badge-outline font-semibold">
                                            {{ $poli->nama_poli ?? 'Poliklinik' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 font-bold text-base-content">
                                        Dr. {{ $dokter->nama ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-xs">
                                        <span class="font-semibold block">{{ $jadwal->hari ?? '-' }}</span>
                                        <span class="text-base-content/60">{{ date('H:i', strtotime($jadwal->jam_mulai)) }} - {{ date('H:i', strtotime($jadwal->jam_selesai)) }} WIB</span>
                                    </td>
                                    <td class="px-6 py-4 text-xs max-w-xs text-base-content/70">
                                        {{ $r->keluhan }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($periksa || $r->status_periksa === 'selesai')
                                            <span class="badge badge-success badge-sm font-semibold gap-1">
                                                <i class="fas fa-check-circle text-xs"></i> Sudah Diperiksa
                                            </span>
                                        @else
                                            <span class="badge badge-warning badge-sm font-semibold gap-1">
                                                <i class="fas fa-hourglass-half text-xs"></i> Menunggu Panggilan
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        @if($periksa || $r->status_periksa === 'selesai')
                                            <a href="{{ route('pasien.riwayat.detail', $r->id) }}" class="btn btn-sm btn-primary text-white gap-1 shadow-sm">
                                                <i class="fas fa-file-lines text-xs"></i> Detail
                                            </a>
                                        @else
                                            <span class="text-xs text-base-content/40 italic">Belum diperiksa</span>
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
