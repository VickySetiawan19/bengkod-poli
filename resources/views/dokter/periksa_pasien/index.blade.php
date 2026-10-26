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

        @if(session('success'))
            <div class="alert alert-success shadow-sm text-sm">
                <i class="fas fa-circle-check"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

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
                                <th class="px-6 py-4">No. Antrian</th>
                                <th class="px-6 py-4">Nama Pasien</th>
                                <th class="px-6 py-4">Jadwal Praktik</th>
                                <th class="px-6 py-4">Keluhan</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-base-200 text-sm">
                            @forelse($antrians as $antrian)
                                @php $pasien = $antrian->pasien; @endphp
                                <tr class="hover:bg-base-50/50 transition">
                                    <td class="px-6 py-4">
                                        <span class="text-lg font-black text-primary font-mono">
                                            #{{ $antrian->no_antrian }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-base-content">{{ $pasien->nama ?? '-' }}</div>
                                        <div class="text-xs text-base-content/50 font-mono">RM: {{ $pasien->no_rm ?? '-' }} | Telp: {{ $pasien->no_hp ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-xs">
                                        <span class="font-semibold block">{{ $antrian->jadwalPeriksa->hari ?? '-' }}</span>
                                        <span class="text-base-content/60">{{ date('H:i', strtotime($antrian->jadwalPeriksa->jam_mulai)) }} - {{ date('H:i', strtotime($antrian->jadwalPeriksa->jam_selesai)) }} WIB</span>
                                    </td>
                                    <td class="px-6 py-4 max-w-xs text-xs text-base-content/80">
                                        {{ $antrian->keluhan }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($antrian->status_periksa === 'selesai' || $antrian->periksa)
                                            <span class="badge badge-success badge-sm font-semibold gap-1">
                                                <i class="fas fa-check-circle text-xs"></i> Selesai
                                            </span>
                                        @else
                                            <span class="badge badge-warning badge-sm font-semibold gap-1">
                                                <i class="fas fa-hourglass-half text-xs"></i> Menunggu
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        @if($antrian->periksa)
                                            <a href="{{ route('riwayat-pasien.detail', $antrian->periksa->id) }}" class="btn btn-xs btn-outline gap-1">
                                                <i class="fas fa-file-lines"></i> Hasil Periksa
                                            </a>
                                        @else
                                            <a href="{{ route('periksa-pasien.create', $antrian->id) }}" class="btn btn-sm btn-primary text-white gap-1 shadow-sm">
                                                <i class="fas fa-stethoscope"></i> Periksa Pasien
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
