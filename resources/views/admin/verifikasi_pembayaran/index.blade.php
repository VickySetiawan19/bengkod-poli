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

        @if(session('success'))
            <div class="alert alert-success shadow-sm text-sm">
                <i class="fas fa-circle-check"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- Status Filter Tabs --}}
        <div class="tabs tabs-boxed bg-base-200 p-1 w-fit rounded-xl">
            <a href="{{ route('verifikasi-pembayaran.index', ['status' => 'menunggu_verifikasi']) }}" 
               class="tab {{ $status === 'menunggu_verifikasi' ? 'tab-active font-bold' : '' }}">
                Menunggu Verifikasi ({{ $countMenunggu }})
            </a>
            <a href="{{ route('verifikasi-pembayaran.index', ['status' => 'lunas']) }}" 
               class="tab {{ $status === 'lunas' ? 'tab-active font-bold' : '' }}">
                Lunas ({{ $countLunas }})
            </a>
            <a href="{{ route('verifikasi-pembayaran.index', ['status' => 'belum_bayar']) }}" 
               class="tab {{ $status === 'belum_bayar' ? 'tab-active font-bold' : '' }}">
                Belum Bayar ({{ $countBelumBayar }})
            </a>
            <a href="{{ route('verifikasi-pembayaran.index', ['status' => 'semua']) }}" 
               class="tab {{ $status === 'semua' ? 'tab-active font-bold' : '' }}">
                Semua Tagihan
            </a>
        </div>

        {{-- Table Card --}}
        <div class="card bg-base-100 shadow border border-base-200">
            <div class="card-body p-0">
                <div class="overflow-x-auto">
                    <table class="table w-full">
                        <thead class="bg-base-200/60 text-xs uppercase tracking-wider text-base-content/70">
                            <tr>
                                <th class="px-6 py-4">No</th>
                                <th class="px-6 py-4">Pasien</th>
                                <th class="px-6 py-4">Poli & Dokter</th>
                                <th class="px-6 py-4">Tgl Periksa</th>
                                <th class="px-6 py-4">Total Biaya</th>
                                <th class="px-6 py-4">Bukti Bayar</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-base-200 text-sm">
                            @forelse($tagihans as $index => $t)
                                @php
                                    $pasien = $t->daftarPoli->pasien ?? null;
                                    $jadwal = $t->daftarPoli->jadwalPeriksa ?? null;
                                @endphp
                                <tr class="hover:bg-base-50/50 transition">
                                    <td class="px-6 py-4 font-semibold text-base-content/60">
                                        {{ $tagihans->firstItem() + $index }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-base-content">{{ $pasien->nama ?? '-' }}</div>
                                        <div class="text-xs text-base-content/50 font-mono">RM: {{ $pasien->no_rm ?? '-' }} | HP: {{ $pasien->no_hp ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-primary">{{ $jadwal->dokter->poli->nama_poli ?? '-' }}</div>
                                        <div class="text-xs text-base-content/60">Dr. {{ $jadwal->dokter->nama ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-xs text-base-content/70">
                                        {{ $t->tgl_periksa ? date('d M Y', strtotime($t->tgl_periksa)) : '-' }}
                                    </td>
                                    <td class="px-6 py-4 font-bold text-base-content">
                                        Rp {{ number_format($t->biaya_periksa, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($t->bukti_pembayaran)
                                            <button type="button" 
                                                    onclick="openPreviewModal('{{ asset('storage/' . $t->bukti_pembayaran) }}', '{{ $pasien->nama ?? 'Pasien' }}')"
                                                    class="btn btn-xs btn-outline btn-info gap-1">
                                                <i class="fas fa-image"></i> Lihat Bukti
                                            </button>
                                        @else
                                            <span class="text-xs text-base-content/40 italic">Belum diunggah</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($t->status_pembayaran === 'lunas')
                                            <span class="badge badge-success badge-sm font-semibold gap-1">
                                                <i class="fas fa-check-circle text-xs"></i> Lunas
                                            </span>
                                            <div class="text-[10px] text-base-content/50 mt-0.5">
                                                {{ $t->tgl_bayar ? date('d/m/y H:i', strtotime($t->tgl_bayar)) : '' }}
                                            </div>
                                        @elseif($t->status_pembayaran === 'menunggu_verifikasi')
                                            <span class="badge badge-warning badge-sm font-semibold gap-1 animate-pulse">
                                                <i class="fas fa-clock text-xs"></i> Menunggu Verifikasi
                                            </span>
                                        @else
                                            <span class="badge badge-ghost badge-sm text-base-content/60">
                                                Belum Bayar
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        @if($t->status_pembayaran === 'menunggu_verifikasi')
                                            <form action="{{ route('verifikasi-pembayaran.konfirmasi', $t->id) }}" method="POST" onsubmit="return confirm('Konfirmasi pembayaran pasien ini sebagai LUNAS?')">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success text-white gap-1 shadow-sm">
                                                    <i class="fas fa-check"></i> Konfirmasi Lunas
                                                </button>
                                            </form>
                                        @elseif($t->status_pembayaran === 'lunas')
                                            <span class="text-xs font-semibold text-success flex items-center justify-end gap-1">
                                                <i class="fas fa-circle-check"></i> Terverifikasi
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
            <div class="bg-base-200 rounded-xl overflow-hidden flex items-center justify-center p-2 min-h-[300px]">
                <img id="modalImage" src="" alt="Bukti Pembayaran" class="max-h-[500px] w-auto object-contain rounded-lg shadow">
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
            document.getElementById('modalPasienName').innerText = pasienName;
            document.getElementById('modalImage').src = imgUrl;
            document.getElementById('modalDownloadLink').href = imgUrl;
            document.getElementById('previewModal').showModal();
        }
    </script>
</x-layouts.app>
