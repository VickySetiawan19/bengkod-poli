<x-layouts.app title="Pembayaran Pemeriksaan">
    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-base-content flex items-center gap-2">
                <i class="fas fa-wallet text-primary"></i> Tagihan & Pembayaran
            </h1>
            <p class="text-sm text-base-content/60">
                Lihat tagihan biaya periksa Anda dan unggah foto bukti transfer pembayaran untuk diverifikasi oleh admin.
            </p>
        </div>


        @if($errors->any())
            <div class="alert alert-error shadow-sm text-sm">
                <i class="fas fa-circle-xmark"></i>
                <ul class="list-disc pl-4">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Payment Instructions Card --}}
        <div class="card bg-primary/5 border border-primary/20">
            <div class="card-body p-5 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="space-y-1">
                    <h3 class="font-bold text-sm text-base-content flex items-center gap-2">
                        <i class="fas fa-building-columns text-primary"></i> Rekening Pembayaran Klinik
                    </h3>
                    <p class="text-xs text-base-content/70">
                        Transfer biaya periksa ke <strong>Bank BCA 123-456-7890 (a.n. Poliklinik App)</strong> atau <strong>Bank Mandiri 987-654-3210</strong>.
                    </p>
                </div>
                <div class="text-xs text-base-content/60 bg-base-100 px-3 py-2 rounded-lg border border-base-200">
                    Format file: JPG, PNG, WEBP (Maksimal 2MB)
                </div>
            </div>
        </div>

        {{-- Bills List Card --}}
        <div class="card bg-base-100 shadow border border-base-200">
            <div class="card-body p-0">
                <div class="overflow-x-auto">
                    <table class="table w-full">
                        <thead class="bg-base-200/60 text-xs uppercase tracking-wider text-base-content/70">
                            <tr>
                                <th class="px-6 py-4 whitespace-nowrap">No</th>
                                <th class="px-6 py-4 whitespace-nowrap min-w-[160px]">Poli & Dokter</th>
                                <th class="px-6 py-4 whitespace-nowrap">Tgl Periksa</th>
                                <th class="px-6 py-4 whitespace-nowrap">Total Tagihan</th>
                                <th class="px-6 py-4 whitespace-nowrap">Status Pembayaran</th>
                                <th class="px-6 py-4 whitespace-nowrap">Bukti Pembayaran</th>
                                <th class="px-6 py-4 text-right whitespace-nowrap">Aksi Upload</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-base-200 text-sm">
                            @forelse($tagihans as $index => $t)
                                @php
                                    $jadwal = $t->daftarPoli->jadwalPeriksa ?? null;
                                    $dokter = $jadwal->dokter ?? null;
                                    $poli = $dokter->poli ?? null;
                                @endphp
                                <tr class="hover:bg-base-50/50 transition">
                                    <td class="px-6 py-4 font-semibold text-base-content/60 whitespace-nowrap">
                                        {{ $tagihans->firstItem() + $index }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-bold text-base-content whitespace-nowrap">{{ $poli->nama_poli ?? 'Poliklinik' }}</div>
                                        <div class="text-xs text-base-content/60 whitespace-nowrap">Dr. {{ $dokter->nama ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-xs whitespace-nowrap">
                                        {{ $t->tgl_periksa ? date('d M Y', strtotime($t->tgl_periksa)) : '-' }}
                                    </td>
                                    <td class="px-6 py-4 font-bold text-base-content text-base font-mono whitespace-nowrap">
                                        Rp {{ number_format($t->biaya_periksa, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <x-badge.status :status="$t->status_pembayaran" />
                                        @if($t->tgl_bayar && $t->status_pembayaran === 'lunas')
                                            <div class="text-[10px] text-base-content/50 mt-0.5 whitespace-nowrap font-mono">
                                                {{ date('d/m/Y H:i', strtotime($t->tgl_bayar)) }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($t->bukti_pembayaran)
                                            <button type="button" 
                                                    onclick="previewBukti('{{ asset('storage/' . $t->bukti_pembayaran) }}')"
                                                    class="btn btn-xs btn-outline btn-info rounded-lg gap-1 whitespace-nowrap">
                                                <i class="fas fa-image text-xs"></i> Lihat Bukti
                                            </button>
                                        @else
                                            <span class="text-xs text-base-content/40 italic whitespace-nowrap">Belum diunggah</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right whitespace-nowrap">
                                        @if($t->status_pembayaran === 'lunas')
                                            <span class="text-xs text-success font-semibold flex items-center justify-end gap-1">
                                                <i class="fas fa-circle-check text-xs"></i> Selesai
                                            </span>
                                        @else
                                            {{-- Upload Form --}}
                                            <form action="{{ route('pasien.pembayaran.upload', $t->id) }}" method="POST" enctype="multipart/form-data" class="flex items-center justify-end gap-2">
                                                @csrf
                                                <input type="file" 
                                                       name="bukti_pembayaran" 
                                                       accept="image/*" 
                                                       class="file-input file-input-bordered file-input-xs w-44 rounded-lg" 
                                                       required>
                                                <button type="submit" class="btn btn-xs btn-primary rounded-lg text-white font-medium gap-1 shadow-2xs whitespace-nowrap" title="Unggah Bukti">
                                                    <i class="fas fa-upload text-[10px]"></i> {{ $t->bukti_pembayaran ? 'Ganti' : 'Upload' }}
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-16 text-base-content/50">
                                        <i class="fas fa-receipt text-3xl mb-2 block opacity-30"></i>
                                        Belum ada tagihan pemeriksaan yang harus dibayar.
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

    {{-- Proof Image Preview Dialog --}}
    <dialog id="buktiModal" class="modal">
        <div class="modal-box max-w-xl p-4">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            </form>
            <h3 class="font-bold text-base mb-3 flex items-center gap-2">
                <i class="fas fa-image text-primary"></i> Bukti Pembayaran Anda
            </h3>
            <div class="bg-base-200/60 rounded-xl overflow-hidden flex flex-col items-center justify-center p-3 min-h-[250px] relative">
                <div id="buktiLoadingSpinner" class="loading loading-spinner loading-md text-primary hidden"></div>
                <img id="buktiModalImage" 
                     src="" 
                     alt="Bukti Pembayaran" 
                     class="max-h-[450px] w-auto object-contain rounded-lg shadow transition"
                     onload="document.getElementById('buktiLoadingSpinner').classList.add('hidden')"
                     onerror="this.classList.add('hidden'); document.getElementById('buktiErrorMsg').classList.remove('hidden'); document.getElementById('buktiLoadingSpinner').classList.add('hidden')">
                <div id="buktiErrorMsg" class="hidden text-center py-8 text-base-content/60">
                    <i class="fas fa-triangle-exclamation text-3xl text-warning mb-2 block"></i>
                    <p class="font-semibold text-xs">Gambar bukti tidak dapat dimuat</p>
                    <p class="text-[11px] text-base-content/50 mt-0.5">Pastikan file tersimpan di storage atau silakan unggah ulang.</p>
                </div>
            </div>
            <div class="modal-action">
                <a id="buktiDownloadLink" href="" target="_blank" class="btn btn-primary btn-sm gap-1">
                    <i class="fas fa-arrow-up-right-from-square"></i> Ukuran Asli
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
        function previewBukti(url) {
            const img = document.getElementById('buktiModalImage');
            const err = document.getElementById('buktiErrorMsg');
            const spinner = document.getElementById('buktiLoadingSpinner');

            if (err) err.classList.add('hidden');
            if (img) {
                img.classList.remove('hidden');
                img.src = url;
            }
            if (spinner) spinner.classList.remove('hidden');

            document.getElementById('buktiDownloadLink').href = url;
            document.getElementById('buktiModal').showModal();
        }
    </script>
</x-layouts.app>
