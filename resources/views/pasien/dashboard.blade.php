<x-layouts.app title="Dashboard Pasien & Antrian Real-Time">
    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-base-content flex items-center gap-2">
                <i class="fas fa-hospital-user text-primary"></i> Dashboard Pasien
            </h1>
            <p class="text-sm text-base-content/60">
                Informasi nomor antrian Anda, live update nomor yang sedang dilayani, dan pendaftaran periksa poliklinik.
            </p>
        </div>


        {{-- ================= 1. BANNER ANTRIAN AKTIF ================= --}}
        <div id="bannerAntrianContainer">
            @if($activePendaftaran)
                @php
                    $jadwalAktif = $activePendaftaran->jadwalPeriksa;
                    $dokterAktif = $jadwalAktif->dokter ?? null;
                    $poliAktif = $dokterAktif->poli ?? null;
                    $periksaAktif = $activePendaftaran->periksa;
                @endphp
                <div class="card bg-gradient-to-r from-primary to-indigo-700 text-primary-content shadow-xl rounded-2xl overflow-hidden border border-white/20">
                    <div class="card-body p-6 sm:p-8">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                            
                            {{-- Info Poli & Dokter --}}
                            <div class="space-y-2">
                                <div class="flex items-center gap-2">
                                    <span class="badge badge-warning badge-sm font-bold uppercase tracking-wider">Antrian Aktif Anda</span>
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-white/20 text-white">
                                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                                        <span class="w-2 h-2 rounded-full bg-emerald-400 -ml-3.5"></span>
                                        LIVE UPDATE
                                    </span>
                                </div>
                                <h2 class="text-2xl sm:text-3xl font-black tracking-tight" id="activePoliName">
                                    {{ $poliAktif->nama_poli ?? 'Poliklinik' }}
                                </h2>
                                <p class="text-sm text-white/90 font-medium" id="activeDokterName">
                                    <i class="fas fa-user-doctor mr-1"></i> Dr. {{ $dokterAktif->nama ?? '-' }}
                                </p>
                                <div class="text-xs text-white/80 flex items-center gap-3 pt-1" id="activeJadwalInfo">
                                    <span><i class="fas fa-calendar-day mr-1"></i> {{ $jadwalAktif->hari ?? '-' }}</span>
                                    <span>&bull;</span>
                                    <span><i class="fas fa-clock mr-1"></i> {{ date('H:i', strtotime($jadwalAktif->jam_mulai)) }} - {{ date('H:i', strtotime($jadwalAktif->jam_selesai)) }} WIB</span>
                                </div>
                                <div class="pt-2 text-xs text-white/70 italic">
                                    Keluhan: "{{ $activePendaftaran->keluhan }}"
                                </div>
                            </div>

                            {{-- Queue Numbers Comparison --}}
                            <div class="flex items-center gap-4 bg-white/10 backdrop-blur-md p-4 rounded-2xl border border-white/20 self-stretch md:self-auto justify-around">
                                
                                {{-- Pasien's Queue Number --}}
                                <div class="text-center px-4">
                                    <span class="text-xs font-semibold text-white/80 uppercase tracking-wider block">No. Anda</span>
                                    <div class="text-4xl sm:text-5xl font-black font-mono tracking-tight text-white my-1" id="activeUserNumber">
                                        #{{ $activePendaftaran->no_antrian }}
                                    </div>
                                    <span class="text-[11px] text-white/80 font-medium">Antrian Anda</span>
                                </div>

                                <div class="h-12 w-[1px] bg-white/20"></div>

                                {{-- Currently Served Number --}}
                                <div class="text-center px-4">
                                    <span class="text-xs font-semibold text-warning uppercase tracking-wider block">Sedang Dilayani</span>
                                    <div class="text-4xl sm:text-5xl font-black font-mono tracking-tight text-warning my-1" id="activeSedangDilayani">
                                        #{{ $jadwalAktif->antrian_sekarang ?? 0 }}
                                    </div>
                                    <span class="text-[11px] text-white/80 font-medium">Di Ruang Poli</span>
                                </div>

                            </div>

                        </div>

                        {{-- Status Message / Action --}}
                        <div class="mt-4 pt-4 border-t border-white/15 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-circle-info text-warning"></i>
                                @if($periksaAktif && $periksaAktif->status_pembayaran !== 'lunas')
                                    <span>Pemeriksaan selesai. Menunggu pelunasan pembayaran tagihan periksa.</span>
                                @elseif($activePendaftaran->status_periksa === 'sedang_diperiksa')
                                    <span>Nomor Anda sedang dipanggil / diperiksa oleh dokter di ruang praktik.</span>
                                @else
                                    <span>Mohon menunggu di ruang tunggu poliklinik hingga nomor antrian Anda dipanggil.</span>
                                @endif
                            </div>
                            @if($periksaAktif && $periksaAktif->status_pembayaran !== 'lunas')
                                <a href="{{ route('pasien.pembayaran.index') }}" class="btn btn-warning btn-xs rounded-full font-bold gap-1 shadow-2xs whitespace-nowrap active:scale-[0.98] transition">
                                    <i class="fas fa-wallet text-[11px]"></i> Bayar Sekarang &rarr;
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                {{-- Empty state when no active queue --}}
                <div class="card bg-base-100 shadow border border-base-200">
                    <div class="card-body p-6 flex flex-col sm:flex-row items-center gap-4 text-center sm:text-left">
                        <div class="w-14 h-14 rounded-2xl bg-primary/10 text-primary flex items-center justify-center text-2xl shrink-0">
                            <i class="fas fa-ticket"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-bold text-base text-base-content">Tidak Ada Antrian Aktif</h3>
                            <p class="text-xs text-base-content/60 mt-0.5">
                                Anda belum memiliki antrian aktif saat ini. Silakan pilih jadwal dokter pada form di bawah untuk mendaftar periksa.
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {{-- ================= 2. FORM PENDAFTARAN POLI ================= --}}
            <div class="lg:col-span-1">
                <div class="card bg-base-100 shadow border border-base-200 sticky top-20">
                    <div class="card-body p-6">
                        <h2 class="card-title text-base flex items-center gap-2 mb-1">
                            <i class="fas fa-clipboard-plus text-primary"></i> Daftar Periksa Poli
                        </h2>
                        <p class="text-xs text-base-content/60 mb-4">
                            Pilih jadwal praktik dokter spesialis dan isi keluhan Anda.
                        </p>

                        @if($activePendaftaran)
                            {{-- Constraint: Pasien tidak dapat mendaftar ke dua poli sekaligus --}}
                            <div class="alert alert-warning text-xs leading-relaxed rounded-xl mb-2">
                                <i class="fas fa-triangle-exclamation text-base"></i>
                                <div>
                                    <span class="font-bold block">Pendaftaran Terkunci:</span>
                                    Sesuai ketentuan sistem, pasien tidak dapat mendaftar ke dua poli sekaligus. Anda baru dapat mendaftar kembali setelah seluruh proses pemeriksaan dan pembayaran selesai.
                                </div>
                            </div>
                        @else
                            <form action="{{ route('pasien.daftar-poli.store') }}" method="POST" class="space-y-3.5">
                                @csrf

                                {{-- 1. Filter Poliklinik --}}
                                <div class="form-control">
                                    <label class="label pb-1">
                                        <span class="label-text font-bold text-xs text-base-content/80 flex items-center gap-1.5">
                                            <i class="fas fa-hospital text-primary text-[11px]"></i> 1. Poliklinik
                                        </span>
                                    </label>
                                    <div class="relative">
                                        <select id="filterPoli" class="w-full bg-base-100 border border-base-300 rounded-xl px-3.5 py-2.5 pr-10 text-xs font-semibold text-base-content focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none appearance-none transition shadow-2xs cursor-pointer">
                                            <option value="">-- Semua Poliklinik --</option>
                                            @foreach($polis as $p)
                                                <option value="{{ $p->id }}">{{ $p->nama_poli }}</option>
                                            @endforeach
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3.5 text-base-content/40">
                                            <i class="fas fa-chevron-down text-xs"></i>
                                        </div>
                                    </div>
                                </div>

                                {{-- 2. Pilih Dokter & Jadwal Praktik --}}
                                <div class="form-control">
                                    <label class="label pb-1">
                                        <span class="label-text font-bold text-xs text-base-content/80 flex items-center gap-1.5">
                                            <i class="fas fa-user-doctor text-primary text-[11px]"></i> 2. Dokter & Jadwal <span class="text-error">*</span>
                                        </span>
                                    </label>
                                    <div class="relative">
                                        <select name="id_jadwal" id="selectJadwal" class="w-full bg-base-100 border border-base-300 rounded-xl px-3.5 py-2.5 pr-10 text-xs font-medium text-base-content focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none appearance-none transition shadow-2xs cursor-pointer @error('id_jadwal') border-error @enderror" required>
                                            <option value="" disabled selected>-- Pilih Dokter & Hari Praktik --</option>
                                            @foreach($jadwals as $jadwal)
                                                <option value="{{ $jadwal->id }}" 
                                                        data-poli-id="{{ $jadwal->dokter->id_poli ?? '' }}"
                                                        {{ old('id_jadwal') == $jadwal->id ? 'selected' : '' }}>
                                                    [{{ $jadwal->dokter->poli->nama_poli ?? 'Poli' }}] Dr. {{ $jadwal->dokter->nama ?? '-' }} ({{ $jadwal->hari }}, {{ date('H:i', strtotime($jadwal->jam_mulai)) }}-{{ date('H:i', strtotime($jadwal->jam_selesai)) }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3.5 text-base-content/40">
                                            <i class="fas fa-chevron-down text-xs"></i>
                                        </div>
                                    </div>
                                    @error('id_jadwal')
                                        <label class="label pt-1"><span class="label-text-alt text-error text-xs">{{ $message }}</span></label>
                                    @enderror
                                </div>


                                {{-- 3. Keluhan --}}
                                <div class="form-control">
                                    <label class="label pb-1">
                                        <span class="label-text font-bold text-xs text-base-content/80 flex items-center gap-1.5">
                                            <i class="fas fa-notes-medical text-primary text-[11px]"></i> 3. Keluhan Gejala Medis <span class="text-error">*</span>
                                        </span>
                                    </label>
                                    <textarea name="keluhan" 
                                              rows="3" 
                                              class="textarea textarea-bordered w-full rounded-xl text-xs leading-relaxed @error('keluhan') textarea-error @enderror" 
                                              placeholder="Jelaskan secara singkat gejala atau keluhan kesehatan yang Anda alami..." 
                                              required>{{ old('keluhan') }}</textarea>
                                    @error('keluhan')
                                        <label class="label pt-1"><span class="label-text-alt text-error text-xs">{{ $message }}</span></label>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-primary btn-sm w-full gap-2 rounded-xl font-bold shadow-sm">
                                    <i class="fas fa-paper-plane"></i> Ambil Nomor Antrian
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ================= 3. TABEL DAFTAR JADWAL POLIKLINIK & DOKTER ================= --}}
            <div class="lg:col-span-2">
                <div class="card bg-base-100 shadow border border-base-200">
                    <div class="card-body p-0">
                        <div class="p-5 border-b border-base-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                            <div>
                                <h2 class="font-bold text-base flex items-center gap-2">
                                    <i class="fas fa-table-list text-primary"></i> Tabel Jadwal Poliklinik & Antrian Real-Time
                                </h2>
                                <p class="text-xs text-base-content/60">
                                    Daftar dokter dan nomor antrian yang sedang dipanggil saat ini di setiap poliklinik.
                                </p>
                            </div>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 self-start sm:self-auto">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                                Live Sync
                            </span>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="table w-full">
                                <thead class="bg-base-200/60 text-xs uppercase tracking-wider text-base-content/70">
                                    <tr>
                                        <th class="px-5 py-3.5">No</th>
                                        <th class="px-5 py-3.5 whitespace-nowrap">Poli</th>
                                        <th class="px-5 py-3.5">Dokter</th>
                                        <th class="px-5 py-3.5">Hari</th>
                                        <th class="px-5 py-3.5">Jam Periksa</th>
                                        <th class="px-5 py-3.5 text-center">Sedang Dilayani</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-base-200 text-sm" id="jadwalTableBody">
                                    @forelse($jadwals as $index => $jadwal)
                                        <tr class="hover:bg-base-50/50 transition" id="jadwal-row-{{ $jadwal->id }}">
                                            <td class="px-5 py-3.5 font-semibold text-base-content/60 text-xs">
                                                {{ $index + 1 }}
                                            </td>
                                            <td class="px-5 py-3.5 whitespace-nowrap">
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200/80 whitespace-nowrap shadow-2xs">
                                                    <i class="fas fa-hospital text-[10px] text-indigo-500"></i>
                                                    {{ $jadwal->dokter->poli->nama_poli ?? 'Poliklinik' }}
                                                </span>
                                            </td>
                                            <td class="px-5 py-3.5 font-bold text-base-content">
                                                Dr. {{ $jadwal->dokter->nama ?? '-' }}
                                            </td>
                                            <td class="px-5 py-3.5 font-medium text-xs">
                                                {{ $jadwal->hari }}
                                            </td>
                                            <td class="px-5 py-3.5 text-xs text-base-content/70">
                                                {{ date('H:i', strtotime($jadwal->jam_mulai)) }} - {{ date('H:i', strtotime($jadwal->jam_selesai)) }} WIB
                                            </td>
                                            <td class="px-5 py-3.5 text-center">
                                                <span class="badge badge-lg bg-indigo-50 border-indigo-200 text-indigo-700 font-mono font-black text-sm px-3 queue-number-badge" id="antrian-sekarang-{{ $jadwal->id }}">
                                                    #{{ $jadwal->antrian_sekarang ?? 0 }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-12 text-base-content/50">
                                                <i class="fas fa-calendar-xmark text-3xl mb-2 block opacity-30"></i>
                                                Belum ada jadwal dokter aktif saat ini.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- ================= 4. REAL-TIME LIVE UPDATE & FORM FILTER SCRIPT ================= --}}
    <script>
        // Interactive Poli Filter & Schedule Selection Handler
        document.addEventListener('DOMContentLoaded', function () {
            const filterPoli = document.getElementById('filterPoli');
            const selectJadwal = document.getElementById('selectJadwal');

            if (filterPoli && selectJadwal) {
                filterPoli.addEventListener('change', function () {
                    const selectedPoliId = this.value;
                    let hasMatchingSelected = false;

                    Array.from(selectJadwal.options).forEach(opt => {
                        if (!opt.value) return; // skip placeholder
                        const match = !selectedPoliId || opt.dataset.poliId === selectedPoliId;
                        opt.hidden = !match;
                        opt.disabled = !match;
                        if (match && opt.selected) {
                            hasMatchingSelected = true;
                        }
                    });

                    // If currently selected option is now hidden, reset to placeholder
                    if (!hasMatchingSelected && selectJadwal.value) {
                        selectJadwal.value = '';
                    }
                });

                selectJadwal.addEventListener('change', function () {
                    const opt = this.selectedOptions ? this.selectedOptions[0] : null;
                    if (opt && opt.dataset.poliId && filterPoli) {
                        filterPoli.value = opt.dataset.poliId;
                    }
                });
            }
        });

        // Real-time synchronization polling every 3 seconds to guarantee updates without reloading page
        function fetchLiveAntrian() {
            fetch("{{ route('api.antrian.status') }}")
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.jadwals) {
                        data.jadwals.forEach(j => {
                            const badge = document.getElementById('antrian-sekarang-' + j.id);
                            if (badge) {
                                const newText = '#' + j.antrian_sekarang;
                                if (badge.innerText !== newText) {
                                    badge.innerText = newText;
                                    badge.classList.add('scale-125', 'bg-warning', 'text-warning-content');
                                    setTimeout(() => {
                                        badge.classList.remove('scale-125', 'bg-warning', 'text-warning-content');
                                    }, 800);
                                }
                            }
                        });

                        // Update banner active queue currently served if banner exists
                        const activeSedangDilayani = document.getElementById('activeSedangDilayani');
                        if (activeSedangDilayani && data.user_active_queue) {
                            const newActiveServed = '#' + data.user_active_queue.antrian_sekarang;
                            if (activeSedangDilayani.innerText !== newActiveServed) {
                                activeSedangDilayani.innerText = newActiveServed;
                                activeSedangDilayani.classList.add('scale-110');
                                setTimeout(() => activeSedangDilayani.classList.remove('scale-110'), 600);
                            }
                        }
                    }
                })
                .catch(err => {
                    console.log('Live sync tick', err);
                });
        }

        // Start live sync cycle
        setInterval(fetchLiveAntrian, 3000);
    </script>
</x-layouts.app>