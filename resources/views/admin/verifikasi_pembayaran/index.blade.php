<x-layouts.app title="Verifikasi Pembayaran Pasien">
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-base-content flex items-center gap-2">
                    <i class="fas fa-receipt text-primary"></i> Verifikasi Pembayaran
                </h1>
                <p class="text-sm text-base-content/60">
                    Kelola dan konfirmasi bukti pembayaran biaya periksa yang diunggah oleh pasien.
                </p>
            </div>
        </div>


        {{-- Status Filter Tabs as Ergonomic Pill Buttons --}}
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('verifikasi-pembayaran.index', ['status' => 'menunggu_verifikasi']) }}" 
               class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-semibold transition-all {{ $status === 'menunggu_verifikasi' ? 'bg-amber-500 text-white shadow-xs font-bold' : 'bg-base-100 hover:bg-base-200 border border-base-200 text-base-content/70' }}">
                <i class="fas fa-clock text-[10px]"></i> Menunggu Verifikasi ({{ $countMenunggu }})
            </a>
            <a href="{{ route('verifikasi-pembayaran.index', ['status' => 'lunas']) }}" 
               class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-semibold transition-all {{ $status === 'lunas' ? 'bg-emerald-600 text-white shadow-xs font-bold' : 'bg-base-100 hover:bg-base-200 border border-base-200 text-base-content/70' }}">
                <i class="fas fa-check-circle text-[10px]"></i> Lunas ({{ $countLunas }})
            </a>
            <a href="{{ route('verifikasi-pembayaran.index', ['status' => 'belum_bayar']) }}" 
               class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-semibold transition-all {{ $status === 'belum_bayar' ? 'bg-rose-600 text-white shadow-xs font-bold' : 'bg-base-100 hover:bg-base-200 border border-base-200 text-base-content/70' }}">
                <i class="fas fa-circle-exclamation text-[10px]"></i> Belum Bayar ({{ $countBelumBayar }})
            </a>
            <a href="{{ route('verifikasi-pembayaran.index', ['status' => 'semua']) }}" 
               class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-semibold transition-all {{ $status === 'semua' ? 'bg-primary text-white shadow-xs font-bold' : 'bg-base-100 hover:bg-base-200 border border-base-200 text-base-content/70' }}">
                <i class="fas fa-list text-[10px]"></i> Semua Tagihan
            </a>
        </div>

        {{-- Table Card --}}
        <div class="card bg-base-100 shadow border border-base-200">
            <div class="card-body p-0">
                <div class="overflow-x-auto">
                    <table class="table w-full">
                        <thead class="bg-base-200/60 text-xs uppercase tracking-wider text-base-content/70">
                            <tr>
                                <th class="px-6 py-4 whitespace-nowrap">No</th>
                                <th class="px-6 py-4 whitespace-nowrap min-w-[170px]">Pasien</th>
                                <th class="px-6 py-4 whitespace-nowrap min-w-[160px]">Poli & Dokter</th>
                                <th class="px-6 py-4 whitespace-nowrap">Tgl Periksa</th>
                                <th class="px-6 py-4 whitespace-nowrap">Total Biaya</th>
                                <th class="px-6 py-4 whitespace-nowrap">Bukti Bayar</th>
                                <th class="px-6 py-4 whitespace-nowrap">Status</th>
                                <th class="px-6 py-4 text-right whitespace-nowrap">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-base-200 text-sm">
                            @forelse($tagihans as $index => $t)
                                @php
                                    $pasien = $t->daftarPoli->pasien ?? null;
                                    $jadwal = $t->daftarPoli->jadwalPeriksa ?? null;
                                @endphp
                                <tr class="hover:bg-base-50/50 transition">
                                    <td class="px-6 py-4 font-semibold text-base-content/60 whitespace-nowrap">
                                        {{ $tagihans->firstItem() + $index }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-bold text-base-content whitespace-nowrap">{{ $pasien->nama ?? '-' }}</div>
                                        <div class="text-xs text-base-content/50 font-mono whitespace-nowrap">RM: {{ $pasien->no_rm ?? '-' }} | HP: {{ $pasien->no_hp ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-semibold text-primary whitespace-nowrap">{{ $jadwal->dokter->poli->nama_poli ?? '-' }}</div>
                                        <div class="text-xs text-base-content/60 whitespace-nowrap">Dr. {{ $jadwal->dokter->nama ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-xs text-base-content/70 whitespace-nowrap">
                                        {{ $t->tgl_periksa ? date('d M Y', strtotime($t->tgl_periksa)) : '-' }}
                                    </td>
                                    <td class="px-6 py-4 font-bold text-base-content whitespace-nowrap">
                                        Rp {{ number_format($t->biaya_periksa, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($t->bukti_pembayaran)
                                            <button type="button" 
                                                    onclick="openPreviewModal('{{ asset('storage/' . $t->bukti_pembayaran) }}', '{{ $pasien->nama ?? 'Pasien' }}')"
                                                    class="btn btn-xs btn-outline btn-info rounded-lg gap-1 whitespace-nowrap">
                                                <i class="fas fa-image text-xs"></i> Lihat Bukti
                                            </button>
                                        @else
                                            <span class="text-xs text-base-content/40 italic whitespace-nowrap">Belum diunggah</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <x-badge.status :status="$t->status_pembayaran" />
                                        @if($t->tgl_bayar && $t->status_pembayaran === 'lunas')
                                            <div class="text-[10px] text-base-content/50 mt-0.5 whitespace-nowrap font-mono">
                                                {{ date('d/m/y H:i', strtotime($t->tgl_bayar)) }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right whitespace-nowrap">
                                        @if($t->status_pembayaran === 'menunggu_verifikasi')
                                            <form action="{{ route('verifikasi-pembayaran.konfirmasi', $t->id) }}" method="POST" onsubmit="return confirm('Konfirmasi pembayaran pasien ini sebagai LUNAS?')">
                                                @csrf
                                                <button type="submit" class="btn btn-xs btn-success rounded-lg text-white font-medium gap-1 shadow-2xs whitespace-nowrap">
                                                    <i class="fas fa-check text-[10px]"></i> Konfirmasi Lunas
                                                </button>
                                            </form>
                                        @elseif($t->status_pembayaran === 'lunas')
                                            <span class="text-xs font-semibold text-success flex items-center justify-end gap-1">
                                                <i class="fas fa-circle-check text-xs"></i> Terverifikasi
                                            </span>
                                        @else
                                            <span class="text-xs text-base-content/40">Menunggu Bukti</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-16 text-base-content/50">
                                        <i class="fas fa-inbox text-3xl mb-2 block opacity-30"></i>
                                        Tidak ada tagihan atau pembayaran pada filter ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($tagihans->hasPages())
                    <div class="p-4 border-t border-base-200">
                        {{ $tagihans->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Image Preview Modal --}}
    <dialog id="previewModal" class="modal">
        <div class="modal-box max-w-2xl p-4">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            </form>
            <h3 class="font-bold text-lg mb-3 flex items-center gap-2">
                <i class="fas fa-receipt text-primary"></i> Bukti Pembayaran: <span id="modalPasienName"></span>
            </h3>
            <div class="bg-base-200/60 rounded-xl overflow-hidden flex flex-col items-center justify-center p-3 min-h-[300px] relative">
                <div id="adminLoadingSpinner" class="loading loading-spinner loading-md text-primary hidden"></div>
                <img id="modalImage" 
                     src="" 
                     alt="Bukti Pembayaran" 
                     class="max-h-[500px] w-auto object-contain rounded-lg shadow transition"
                     onload="document.getElementById('adminLoadingSpinner').classList.add('hidden')"
                     onerror="this.classList.add('hidden'); document.getElementById('adminErrorMsg').classList.remove('hidden'); document.getElementById('adminLoadingSpinner').classList.add('hidden')">
                <div id="adminErrorMsg" class="hidden text-center py-8 text-base-content/60">
                    <i class="fas fa-triangle-exclamation text-3xl text-warning mb-2 block"></i>
                    <p class="font-semibold text-xs">Gambar bukti tidak dapat dimuat</p>
                    <p class="text-[11px] text-base-content/50 mt-0.5">Pastikan tautan storage terhubung atau file belum terhapus.</p>
                </div>
            </div>
            <div class="modal-action">
                <a id="modalDownloadLink" href="" target="_blank" class="btn btn-primary btn-sm gap-1">
                    <i class="fas fa-arrow-up-right-from-square"></i> Buka Ukuran Asli
                </a>
                <form method="dialog">
                    <button class="btn btn-sm">Tutup</button>
                </form>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>

    <script>
        function openPreviewModal(imgUrl, pasienName) {
            const img = document.getElementById('modalImage');
            const err = document.getElementById('adminErrorMsg');
            const spinner = document.getElementById('adminLoadingSpinner');

            if (err) err.classList.add('hidden');
            if (img) {
                img.classList.remove('hidden');
                img.src = imgUrl;
            }
            if (spinner) spinner.classList.remove('hidden');

            document.getElementById('modalPasienName').innerText = pasienName;
            document.getElementById('modalDownloadLink').href = imgUrl;
            document.getElementById('previewModal').showModal();
        }
    </script>
</x-layouts.app>
