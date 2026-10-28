<x-layouts.app title="Dokter Dashboard">
    <div class="space-y-6">

        {{-- ================= HEADER ================= --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-base-content flex items-center gap-2">
                    <i class="fas fa-stethoscope text-primary"></i> Dashboard Dokter
                </h1>
                <p class="text-sm text-base-content/60">
                    Selamat bertugas, <strong>Dr. {{ auth()->user()->nama }}</strong>. Pantau jadwal praktik dan giliran antrian pasien.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('periksa-pasien.index') }}" class="btn btn-primary btn-sm rounded-xl gap-2 shadow-sm">
                    <i class="fas fa-user-doctor text-xs"></i> Ruang Periksa
                </a>
                <a href="{{ route('jadwal-periksa.index') }}" class="btn btn-outline btn-sm rounded-xl gap-2">
                    <i class="fas fa-calendar-alt text-xs"></i> Jadwal Praktik
                </a>
            </div>
        </div>

        {{-- ================= ACTIVE JADWAL CALLOUT BANNER ================= --}}
        @if($activeJadwal)
            <div class="card bg-gradient-to-r from-primary to-indigo-700 text-primary-content shadow-xl rounded-2xl overflow-hidden border border-white/20">
                <div class="card-body p-6 sm:p-7">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">

                        {{-- Left: Jadwal Info --}}
                        <div class="space-y-2">
                            <div class="flex items-center gap-2">
                                <span class="badge badge-warning badge-sm font-bold uppercase tracking-wider">Jadwal Aktif</span>
                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-white/20 text-white">
                                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                                    <span class="w-2 h-2 rounded-full bg-emerald-400 -ml-3.5"></span>
                                    PRAKTIK DIBUKA
                                </span>
                            </div>
                            <h2 class="text-2xl sm:text-3xl font-black tracking-tight">
                                Hari {{ $activeJadwal->hari }}
                            </h2>
                            <p class="text-sm text-white/90 font-medium">
                                <i class="fas fa-clock mr-1"></i>
                                {{ date('H:i', strtotime($activeJadwal->jam_mulai)) }} - {{ date('H:i', strtotime($activeJadwal->jam_selesai)) }} WIB
                            </p>
                            <p class="text-xs text-white/70">
                                Gunakan tombol panggil untuk memperbarui nomor antrian di layar pasien secara real-time.
                            </p>
                        </div>

                        {{-- Right: Current Queue Caller --}}
                        <div class="flex flex-col sm:flex-row items-center gap-4 bg-white/10 backdrop-blur-md p-4 rounded-2xl border border-white/20">
                            <div class="text-center px-4">
                                <span class="text-xs font-semibold text-warning uppercase tracking-wider block">Sedang Dilayani</span>
                                <div class="text-4xl sm:text-5xl font-black font-mono tracking-tight text-white my-1">
                                    #{{ $activeJadwal->antrian_sekarang ?? 0 }}
                                </div>
                                <span class="text-[11px] text-white/80 font-medium">Nomor Saat Ini</span>
                            </div>

                            <div class="hidden sm:block h-12 w-[1px] bg-white/20"></div>

                            {{-- Panggil Form --}}
                            <form action="{{ route('periksa-pasien.call', $activeJadwal->id) }}" method="POST" class="flex flex-col gap-2 w-full sm:w-auto">
                                @csrf
                                <input type="hidden" name="no_antrian" value="{{ ($activeJadwal->antrian_sekarang ?? 0) + 1 }}">
                                <button type="submit" class="btn btn-warning btn-sm font-bold gap-2 shadow-md hover:scale-[1.02] active:scale-[0.98] transition">
                                    <i class="fas fa-bullhorn text-xs"></i> Panggil Berikutnya (#{{ ($activeJadwal->antrian_sekarang ?? 0) + 1 }})
                                </button>
                                <a href="{{ route('periksa-pasien.index') }}" class="btn btn-ghost btn-xs text-white hover:bg-white/10">
                                    Pilih Nomor Manual &rarr;
                                </a>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-info shadow-sm rounded-2xl border border-info/20">
                <i class="fas fa-info-circle text-lg"></i>
                <div class="flex-1">
                    <h2 class="font-bold text-sm">Tidak Ada Jadwal Aktif Saat Ini</h2>
                    <p class="text-xs opacity-80">Anda belum mengaktifkan jadwal praktik. Aktifkan jadwal Anda di menu Jadwal Periksa agar pasien dapat mendaftar antrian.</p>
                </div>
                <a href="{{ route('jadwal-periksa.index') }}" class="btn btn-primary btn-sm rounded-xl">
                    Buka Jadwal &rarr;
                </a>
            </div>
        @endif

        {{-- ================= KPI METRIC CARDS ================= --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

            {{-- Antrian Hari Ini --}}
            <div class="card bg-base-100 shadow-sm border border-base-200 rounded-2xl">
                <div class="card-body p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-xs font-semibold text-base-content/60 uppercase tracking-wider block">Antrian Hari Ini</span>
                            <span class="text-3xl font-black text-primary mt-1 block">{{ $totalAntrianHariIni }}</span>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center text-xl">
                            <i class="fas fa-users-line"></i>
                        </div>
                    </div>
                    <div class="mt-3 pt-3 border-t border-base-200 flex items-center justify-between text-xs text-base-content/60">
                        <span>Pendaftar hari ini</span>
                        <a href="{{ route('periksa-pasien.index') }}" class="text-primary font-medium hover:underline">Kelola &rarr;</a>
                    </div>
                </div>
            </div>

            {{-- Pasien Diperiksa --}}
            <div class="card bg-base-100 shadow-sm border border-base-200 rounded-2xl">
                <div class="card-body p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-xs font-semibold text-base-content/60 uppercase tracking-wider block">Pasien Diperiksa</span>
                            <span class="text-3xl font-black text-emerald-600 mt-1 block">{{ $totalPasienDiperiksa }}</span>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
                            <i class="fas fa-clipboard-check"></i>
                        </div>
                    </div>
                    <div class="mt-3 pt-3 border-t border-base-200 flex items-center justify-between text-xs text-base-content/60">
                        <span>Total rekam medis terisi</span>
                        <a href="{{ route('riwayat-pasien.index') }}" class="text-emerald-600 font-medium hover:underline">Riwayat &rarr;</a>
                    </div>
                </div>
            </div>

            {{-- Jadwal Praktik Anda --}}
            <div class="card bg-base-100 shadow-sm border border-base-200 rounded-2xl">
                <div class="card-body p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-xs font-semibold text-base-content/60 uppercase tracking-wider block">Jadwal Praktik</span>
                            <span class="text-3xl font-black text-base-content mt-1 block">{{ $myJadwals->count() }}</span>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                    </div>
                    <div class="mt-3 pt-3 border-t border-base-200 flex items-center justify-between text-xs text-base-content/60">
                        <span>Sesi praktik terdaftar</span>
                        <a href="{{ route('jadwal-periksa.index') }}" class="text-indigo-600 font-medium hover:underline">Atur Jadwal &rarr;</a>
                    </div>
                </div>
            </div>

        </div>

        {{-- ================= TWO COLUMN GRID ================= --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Left 2 Cols: Antrian Pasien Menunggu --}}
            <div class="lg:col-span-2 card bg-base-100 shadow-sm border border-base-200 rounded-2xl overflow-hidden">
                <div class="p-5 border-b border-base-200 flex items-center justify-between">
                    <div>
                        <h2 class="font-bold text-base text-base-content flex items-center gap-2">
                            <i class="fas fa-user-clock text-primary"></i> Antrian Pasien Menunggu
                        </h2>
                        <p class="text-xs text-base-content/60">Pasien yang sedang menunggu giliran pemeriksaan</p>
                    </div>
                    <a href="{{ route('periksa-pasien.index') }}" class="text-xs text-primary font-semibold hover:underline">
                        Lihat Semua &rarr;
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="table w-full">
                        <thead class="bg-base-200/60 text-xs uppercase tracking-wider text-base-content/70">
                            <tr>
                                <th class="px-5 py-3">No. Antrian</th>
                                <th class="px-5 py-3">Pasien</th>
                                <th class="px-5 py-3">Keluhan</th>
                                <th class="px-5 py-3">Status</th>
                                <th class="px-5 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-base-200">
                            @forelse($antrianAktifList as $antrian)
                                <tr class="hover:bg-base-200/30 transition">
                                    <td class="px-5 py-3 font-mono font-black text-lg text-primary">
                                        #{{ $antrian->no_antrian }}
                                    </td>
                                    <td class="px-5 py-3">
                                        <div class="font-semibold text-base-content">{{ $antrian->pasien->nama ?? 'Pasien' }}</div>
                                        <div class="text-xs text-base-content/50">No. RM: {{ $antrian->pasien->no_rm ?? '-' }}</div>
                                    </td>
                                    <td class="px-5 py-3 text-xs text-base-content/80 max-w-xs truncate">
                                        {{ $antrian->keluhan }}
                                    </td>
                                    <td class="px-5 py-3">
                                        @if($antrian->status_periksa === 'sedang_diperiksa')
                                            <span class="badge badge-warning badge-sm">Sedang Diperiksa</span>
                                        @else
                                            <span class="badge badge-ghost badge-sm">Menunggu</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3 text-right">
                                        <a href="{{ route('periksa-pasien.create', $antrian->id) }}" class="btn btn-primary btn-xs rounded-lg font-semibold gap-1 shadow-2xs">
                                            <i class="fas fa-stethoscope text-[10px]"></i> Periksa
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-8 text-base-content/50">
                                        <i class="fas fa-mug-hot text-3xl mb-2 opacity-30"></i>
                                        <p class="text-sm">Tidak ada pasien dalam antrian saat ini</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Right 1 Col: Riwayat Periksa Terkini --}}
            <div class="card bg-base-100 shadow-sm border border-base-200 rounded-2xl overflow-hidden">
                <div class="p-5 border-b border-base-200 flex items-center justify-between">
                    <div>
                        <h2 class="font-bold text-base text-base-content flex items-center gap-2">
                            <i class="fas fa-history text-emerald-600"></i> Pasien Selesai Terkini
                        </h2>
                        <p class="text-xs text-base-content/60">Pemeriksaan terbaru Anda</p>
                    </div>
                    <a href="{{ route('riwayat-pasien.index') }}" class="text-xs text-primary font-semibold hover:underline">
                        Riwayat &rarr;
                    </a>
                </div>

                <div class="p-4 space-y-3">
                    @forelse($recentSelesai as $periksa)
                        <div class="p-3 bg-base-200/50 rounded-xl border border-base-200 space-y-1.5">
                            <div class="flex items-center justify-between">
                                <span class="font-semibold text-sm text-base-content">
                                    {{ $periksa->daftarPoli->pasien->nama ?? 'Pasien' }}
                                </span>
                                <span class="text-[10px] text-base-content/50">
                                    {{ $periksa->created_at ? $periksa->created_at->diffForHumans() : '-' }}
                                </span>
                            </div>
                            <p class="text-xs text-base-content/70 italic line-clamp-2">
                                Catatan: "{{ $periksa->catatan ?? '-' }}"
                            </p>
                            <div class="pt-1 flex items-center justify-between border-t border-base-200/50 text-xs">
                                <span class="font-bold text-emerald-600">
                                    Rp {{ number_format($periksa->biaya_periksa, 0, ',', '.') }}
                                </span>
                                <a href="{{ route('riwayat-pasien.detail', $periksa->id) }}" class="text-primary hover:underline font-medium text-[11px]">
                                    Detail &rarr;
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-base-content/50">
                            <i class="fas fa-folder-open text-3xl mb-2 opacity-30"></i>
                            <p class="text-xs">Belum ada pasien yang selesai diperiksa</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>
</x-layouts.app>
