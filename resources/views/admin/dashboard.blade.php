<x-layouts.app title="Admin Dashboard">
    <div class="space-y-6">

        {{-- ================= HEADER ================= --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-base-content flex items-center gap-2">
                    <i class="fas fa-chart-line text-primary"></i> Dashboard Administrasi
                </h1>
                <p class="text-sm text-base-content/60">
                    Ringkasan performa sistem poliklinik, data operasional, dan antrian pasien.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('dokter.create') }}" class="btn btn-primary btn-sm rounded-xl gap-2 shadow-sm">
                    <i class="fas fa-user-plus text-xs"></i> Tambah Dokter
                </a>
                <a href="{{ route('verifikasi-pembayaran.index') }}" class="btn btn-outline btn-sm rounded-xl gap-2">
                    <i class="fas fa-receipt text-xs"></i> Verifikasi Pembayaran
                </a>
            </div>
        </div>

        {{-- ================= NOTICE MENUNGGU VERIFIKASI ================= --}}
        @if($menungguVerifikasiCount > 0)
            <div class="alert alert-warning shadow-md rounded-2xl border border-warning/30 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-warning/20 text-warning-content flex items-center justify-center font-bold">
                        <i class="fas fa-bell text-lg animate-bounce"></i>
                    </div>
                    <div>
                        <h2 class="font-bold text-sm">Menunggu Verifikasi Pembayaran</h2>
                        <p class="text-xs text-base-content/70">Ada <strong>{{ $menungguVerifikasiCount }}</strong> tagihan pasien yang telah mengunggah bukti bayar dan menunggu konfirmasi admin.</p>
                    </div>
                </div>
                <a href="{{ route('verifikasi-pembayaran.index') }}" class="btn btn-warning btn-sm font-bold shadow-sm whitespace-nowrap">
                    Periksa Tagihan &rarr;
                </a>
            </div>
        @endif

        {{-- ================= KPI METRIC CARDS ================= --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

            {{-- Total Dokter --}}
            <a href="{{ route('dokter.index') }}" class="card bg-base-100 shadow-sm hover:shadow-md transition-all duration-200 border border-base-200 rounded-2xl group">
                <div class="card-body p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-xs font-semibold text-base-content/60 uppercase tracking-wider block">Total Dokter</span>
                            <span class="text-3xl font-black text-base-content mt-1 block">{{ $totalDokter }}</span>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                            <i class="fas fa-user-doctor"></i>
                        </div>
                    </div>
                    <div class="mt-3 pt-3 border-t border-base-200 flex items-center justify-between text-xs text-base-content/60">
                        <span>Dokter spesialis & umum</span>
                        <span class="text-indigo-600 font-medium group-hover:underline">Kelola &rarr;</span>
                    </div>
                </div>
            </a>

            {{-- Total Pasien --}}
            <a href="{{ route('pasien.index') }}" class="card bg-base-100 shadow-sm hover:shadow-md transition-all duration-200 border border-base-200 rounded-2xl group">
                <div class="card-body p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-xs font-semibold text-base-content/60 uppercase tracking-wider block">Total Pasien</span>
                            <span class="text-3xl font-black text-base-content mt-1 block">{{ $totalPasien }}</span>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                            <i class="fas fa-hospital-user"></i>
                        </div>
                    </div>
                    <div class="mt-3 pt-3 border-t border-base-200 flex items-center justify-between text-xs text-base-content/60">
                        <span>Pasien teregistrasi</span>
                        <span class="text-emerald-600 font-medium group-hover:underline">Kelola &rarr;</span>
                    </div>
                </div>
            </a>

            {{-- Poliklinik --}}
            <a href="{{ route('polis.index') }}" class="card bg-base-100 shadow-sm hover:shadow-md transition-all duration-200 border border-base-200 rounded-2xl group">
                <div class="card-body p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-xs font-semibold text-base-content/60 uppercase tracking-wider block">Poli Tersedia</span>
                            <span class="text-3xl font-black text-base-content mt-1 block">{{ $totalPoli }}</span>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                            <i class="fas fa-clinic-medical"></i>
                        </div>
                    </div>
                    <div class="mt-3 pt-3 border-t border-base-200 flex items-center justify-between text-xs text-base-content/60">
                        <span>Unit layanan poli</span>
                        <span class="text-sky-600 font-medium group-hover:underline">Kelola &rarr;</span>
                    </div>
                </div>
            </a>

            {{-- Total Pendapatan Lunas --}}
            <div class="card bg-base-100 shadow-sm border border-base-200 rounded-2xl">
                <div class="card-body p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-xs font-semibold text-base-content/60 uppercase tracking-wider block">Pendapatan Lunas</span>
                            <span class="text-2xl font-black text-emerald-600 mt-1 block">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</span>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl">
                            <i class="fas fa-wallet"></i>
                        </div>
                    </div>
                    <div class="mt-3 pt-3 border-t border-base-200 flex items-center justify-between text-xs text-base-content/60">
                        <span>Dari biaya periksa & obat</span>
                        <span class="badge badge-success badge-xs font-semibold">Lunas</span>
                    </div>
                </div>
            </div>

        </div>

        {{-- ================= TWO COLUMN GRID ================= --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Left 2 Cols: Pendaftaran Pasien Terkini --}}
            <div class="lg:col-span-2 card bg-base-100 shadow-sm border border-base-200 rounded-2xl overflow-hidden">
                <div class="p-5 border-b border-base-200 flex items-center justify-between">
                    <div>
                        <h2 class="font-bold text-base text-base-content flex items-center gap-2">
                            <i class="fas fa-list-check text-primary"></i> Pendaftaran Poli Terkini
                        </h2>
                        <p class="text-xs text-base-content/60">5 pasien pendaftaran antrian terbaru</p>
                    </div>
                    <a href="{{ route('pasien.index') }}" class="text-xs text-primary font-semibold hover:underline">
                        Semua Pasien &rarr;
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="table w-full">
                        <thead class="bg-base-200/60 text-xs uppercase tracking-wider text-base-content/70">
                            <tr>
                                <th class="px-5 py-3">No. Antrian</th>
                                <th class="px-5 py-3">Pasien</th>
                                <th class="px-5 py-3">Poli & Dokter</th>
                                <th class="px-5 py-3">Waktu</th>
                                <th class="px-5 py-3 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-base-200">
                            @forelse($recentPendaftarans as $daftar)
                                <tr class="hover:bg-base-200/30 transition">
                                    <td class="px-5 py-3 font-mono font-bold text-primary">
                                        #{{ $daftar->no_antrian }}
                                    </td>
                                    <td class="px-5 py-3">
                                        <div class="font-semibold text-base-content">{{ $daftar->pasien->nama ?? 'Pasien' }}</div>
                                        <div class="text-xs text-base-content/50">No. RM: {{ $daftar->pasien->no_rm ?? '-' }}</div>
                                    </td>
                                    <td class="px-5 py-3">
                                        <div class="font-medium text-base-content">{{ $daftar->jadwalPeriksa->dokter->poli->nama_poli ?? '-' }}</div>
                                        <div class="text-xs text-base-content/50">Dr. {{ $daftar->jadwalPeriksa->dokter->nama ?? '-' }}</div>
                                    </td>
                                    <td class="px-5 py-3 text-xs text-base-content/70">
                                        {{ $daftar->created_at->diffForHumans() }}
                                    </td>
                                    <td class="px-5 py-3 text-center">
                                        @if($daftar->status_periksa === 'selesai')
                                            <span class="badge badge-success badge-sm text-white">Selesai</span>
                                        @elseif($daftar->status_periksa === 'sedang_diperiksa')
                                            <span class="badge badge-warning badge-sm">Diperiksa</span>
                                        @else
                                            <span class="badge badge-ghost badge-sm">Menunggu</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-8 text-base-content/50">
                                        <i class="fas fa-inbox text-3xl mb-2 opacity-30"></i>
                                        <p class="text-sm">Belum ada pendaftaran pasien hari ini</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Right 1 Col: Quick Links & Pending Verifications --}}
            <div class="space-y-6">

                {{-- Card Tagihan Menunggu Verifikasi --}}
                <div class="card bg-base-100 shadow-sm border border-base-200 rounded-2xl overflow-hidden">
                    <div class="p-5 border-b border-base-200 flex items-center justify-between">
                        <div>
                            <h2 class="font-bold text-base text-base-content flex items-center gap-2">
                                <i class="fas fa-receipt text-warning"></i> Menunggu Verifikasi
                            </h2>
                            <p class="text-xs text-base-content/60">Pembayaran perlu konfirmasi</p>
                        </div>
                        <span class="badge badge-warning badge-sm font-bold">{{ $menungguVerifikasiCount }}</span>
                    </div>

                    <div class="p-4 space-y-3">
                        @forelse($recentVerifikasi as $periksa)
                            <div class="p-3 bg-base-200/50 rounded-xl flex items-center justify-between gap-3 border border-base-200">
                                <div>
                                    <div class="font-semibold text-sm text-base-content">
                                        {{ $periksa->daftarPoli->pasien->nama ?? 'Pasien' }}
                                    </div>
                                    <div class="text-xs text-base-content/60">
                                        Tagihan: <span class="font-bold text-primary">Rp {{ number_format($periksa->biaya_periksa, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="text-[10px] text-base-content/40 mt-0.5">
                                        {{ $periksa->updated_at->diffForHumans() }}
                                    </div>
                                </div>
                                <a href="{{ route('verifikasi-pembayaran.index') }}" class="btn btn-warning btn-xs rounded-lg font-bold shadow-2xs">
                                    Verifikasi
                                </a>
                            </div>
                        @empty
                            <div class="text-center py-6 text-base-content/50">
                                <i class="fas fa-check-double text-2xl mb-1 text-emerald-500 opacity-60"></i>
                                <p class="text-xs">Semua pembayaran telah diverifikasi</p>
                            </div>
                        @endforelse

                        @if($menungguVerifikasiCount > 5)
                            <div class="text-center pt-2">
                                <a href="{{ route('verifikasi-pembayaran.index') }}" class="text-xs text-primary font-semibold hover:underline">
                                    Lihat {{ $menungguVerifikasiCount - 5 }} tagihan lainnya &rarr;
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Master Data Shortcut Card --}}
                <div class="card bg-base-100 shadow-sm border border-base-200 rounded-2xl p-5">
                    <h2 class="font-bold text-sm text-base-content mb-3 flex items-center gap-2">
                        <i class="fas fa-bolt text-amber-500"></i> Pintasan Master Data
                    </h2>
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <a href="{{ route('dokter.index') }}" class="p-2.5 rounded-xl bg-base-200/60 hover:bg-base-200 font-medium text-base-content flex items-center gap-2 transition">
                            <i class="fas fa-user-doctor text-indigo-500"></i> Kelola Dokter
                        </a>
                        <a href="{{ route('pasien.index') }}" class="p-2.5 rounded-xl bg-base-200/60 hover:bg-base-200 font-medium text-base-content flex items-center gap-2 transition">
                            <i class="fas fa-hospital-user text-emerald-500"></i> Kelola Pasien
                        </a>
                        <a href="{{ route('polis.index') }}" class="p-2.5 rounded-xl bg-base-200/60 hover:bg-base-200 font-medium text-base-content flex items-center gap-2 transition">
                            <i class="fas fa-clinic-medical text-sky-500"></i> Kelola Poli
                        </a>
                        <a href="{{ route('obat.index') }}" class="p-2.5 rounded-xl bg-base-200/60 hover:bg-base-200 font-medium text-base-content flex items-center gap-2 transition">
                            <i class="fas fa-pills text-purple-500"></i> Data Obat ({{ $totalObat }})
                        </a>
                    </div>
                </div>

            </div>

        </div>

    </div>
</x-layouts.app>
