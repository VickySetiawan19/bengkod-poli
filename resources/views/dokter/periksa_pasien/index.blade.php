<x-layouts.app title="Memeriksa Pasien">
    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-base-content flex items-center gap-2">
                <i class="fas fa-user-check text-primary"></i> Daftar Antrian Pasien
            </h1>
            <p class="text-sm text-base-content/60">
                Pantau antrian pasien yang terdaftar pada jadwal praktik Anda dan lakukan pencatatan hasil pemeriksaan.
            </p>
        </div>


        {{-- Doctor Queue Control Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($myJadwals as $j)
                <div class="card bg-base-100 shadow border border-base-200">
                    <div class="card-body p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="badge badge-primary badge-sm font-semibold">{{ $j->hari }}</span>
                            <span class="text-xs text-base-content/60">{{ date('H:i', strtotime($j->jam_mulai)) }} - {{ date('H:i', strtotime($j->jam_selesai)) }} WIB</span>
                        </div>
                        <div class="flex items-baseline justify-between mt-1">
                            <div>
                                <span class="text-xs text-base-content/50 block">Nomor Dilayani:</span>
                                <span class="text-3xl font-black text-primary">#{{ $j->antrian_sekarang ?? 0 }}</span>
                            </div>
                            <form action="{{ route('periksa-pasien.call', $j->id) }}" method="POST" class="flex items-center gap-1">
                                @csrf
                                <input type="number" name="no_antrian" value="{{ ($j->antrian_sekarang ?? 0) + 1 }}" min="0" class="input input-xs input-bordered w-14 font-bold text-center">
                                <button type="submit" class="btn btn-xs btn-primary gap-1" title="Panggil Antrian Berikutnya">
                                    <i class="fas fa-bullhorn text-[10px]"></i> Panggil
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Queue List Table --}}
        <div class="card bg-base-100 shadow border border-base-200">
            <div class="card-body p-0">
                <div class="p-4 border-b border-base-200 flex items-center justify-between">
                    <h2 class="font-bold text-base flex items-center gap-2">
                        <i class="fas fa-list-ol text-primary"></i> Pasien Terdaftar Dalam Antrian
                    </h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="table w-full">
                        <thead class="bg-base-200/60 text-xs uppercase tracking-wider text-base-content/70">
                            <tr>
                                <th class="px-6 py-4 whitespace-nowrap">No. Antrian</th>
                                <th class="px-6 py-4 whitespace-nowrap min-w-[160px]">Nama Pasien</th>
                                <th class="px-6 py-4 whitespace-nowrap min-w-[140px]">Jadwal Praktik</th>
                                <th class="px-6 py-4 min-w-[140px]">Keluhan</th>
                                <th class="px-6 py-4 whitespace-nowrap">Status</th>
                                <th class="px-6 py-4 text-right whitespace-nowrap">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-base-200 text-sm">
                            @forelse($antrians as $antrian)
                                @php $pasien = $antrian->pasien; @endphp
                                <tr class="hover:bg-base-50/50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-lg font-black text-primary font-mono">
                                            #{{ $antrian->no_antrian }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-bold text-base-content whitespace-nowrap">{{ $pasien->nama ?? '-' }}</div>
                                        <div class="text-xs text-base-content/50 font-mono whitespace-nowrap">RM: {{ $pasien->no_rm ?? '-' }} | Telp: {{ $pasien->no_hp ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-xs whitespace-nowrap">
                                        <span class="font-semibold block whitespace-nowrap">{{ $antrian->jadwalPeriksa->hari ?? '-' }}</span>
                                        <span class="text-base-content/60 whitespace-nowrap">{{ date('H:i', strtotime($antrian->jadwalPeriksa->jam_mulai)) }} - {{ date('H:i', strtotime($antrian->jadwalPeriksa->jam_selesai)) }} WIB</span>
                                    </td>
                                    <td class="px-6 py-4 max-w-xs text-xs text-base-content/80">
                                        {{ $antrian->keluhan }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <x-badge.status :status="$antrian->status_periksa === 'selesai' || $antrian->periksa ? 'selesai' : 'menunggu'" />
                                    </td>
                                    <td class="px-6 py-4 text-right whitespace-nowrap">
                                        @if($antrian->periksa)
                                            <a href="{{ route('riwayat-pasien.detail', $antrian->periksa->id) }}" class="btn btn-xs btn-outline rounded-lg gap-1 whitespace-nowrap">
                                                <i class="fas fa-file-lines"></i> Hasil Periksa
                                            </a>
                                        @else
                                            <a href="{{ route('periksa-pasien.create', $antrian->id) }}" class="btn btn-xs btn-primary rounded-lg text-white font-medium gap-1.5 shadow-2xs whitespace-nowrap hover:shadow transition">
                                                <i class="fas fa-stethoscope text-[11px]"></i> Periksa Pasien
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-16 text-base-content/50">
                                        <i class="fas fa-inbox text-3xl mb-2 block opacity-30"></i>
                                        Belum ada pasien yang mendaftar ke jadwal Anda.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($antrians->hasPages())
                    <div class="p-4 border-t border-base-200">
                        {{ $antrians->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app>
