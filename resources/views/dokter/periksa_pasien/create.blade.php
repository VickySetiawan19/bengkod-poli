<x-layouts.app title="Form Pemeriksaan Pasien">
    <div class="max-w-4xl mx-auto space-y-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('periksa-pasien.index') }}" class="btn btn-circle btn-ghost btn-sm">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-base-content flex items-center gap-2">
                    <i class="fas fa-stethoscope text-primary"></i> Pemeriksaan Pasien
                </h1>
                <p class="text-sm text-base-content/60">
                    Input hasil pemeriksaan medis, resep obat, dan biaya periksa otomatis dihitung.
                </p>
            </div>
        </div>

        {{-- Patient Info Card --}}
        <div class="card bg-base-100 shadow border border-base-200">
            <div class="card-body p-6">
                <div class="flex items-center justify-between pb-4 border-b border-base-200">
                    <div>
                        <span class="text-xs text-base-content/50 uppercase font-semibold">Data Pasien</span>
                        <h2 class="text-xl font-bold text-base-content mt-0.5">
                            {{ $daftarPoli->pasien->nama ?? '-' }}
                        </h2>
                    </div>
                    <div class="text-right">
                        <span class="text-xs text-base-content/50 block">Nomor Antrian:</span>
                        <span class="text-2xl font-black text-primary font-mono">#{{ $daftarPoli->no_antrian }}</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-4 text-xs">
                    <div>
                        <span class="text-base-content/60 block">Nomor Rekam Medis:</span>
                        <span class="font-bold text-sm font-mono text-base-content">{{ $daftarPoli->pasien->no_rm ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="text-base-content/60 block">Jadwal Periksa:</span>
                        <span class="font-semibold text-sm text-base-content">{{ $daftarPoli->jadwalPeriksa->hari ?? '-' }} ({{ date('H:i', strtotime($daftarPoli->jadwalPeriksa->jam_mulai)) }} - {{ date('H:i', strtotime($daftarPoli->jadwalPeriksa->jam_selesai)) }})</span>
                    </div>
                    <div>
                        <span class="text-base-content/60 block">Keluhan Utama:</span>
                        <span class="font-semibold text-sm text-base-content italic">"{{ $daftarPoli->keluhan }}"</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Examination Form Card --}}
        <div class="card bg-base-100 shadow border border-base-200">
            <div class="card-body p-6">
                <form action="{{ route('periksa-pasien.store', $daftarPoli->id) }}" method="POST" id="periksaForm" class="space-y-6">
                    @csrf

                    {{-- Tanggal Periksa --}}
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-bold text-sm">Tanggal Periksa <span class="text-error">*</span></span>
                        </label>
                        <input type="date" 
                               name="tgl_periksa" 
                               value="{{ old('tgl_periksa', date('Y-m-d')) }}" 
                               class="input input-bordered w-full max-w-xs @error('tgl_periksa') input-error @enderror"
                               required>
                        @error('tgl_periksa')
                            <label class="label">
                                <span class="label-text-alt text-error text-xs">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    {{-- Catatan Dokter --}}
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-bold text-sm">Catatan Pemeriksaan & Diagnosa Dokter <span class="text-error">*</span></span>
                        </label>
                        <textarea name="catatan" 
                                  rows="4" 
                                  class="textarea textarea-bordered w-full leading-relaxed @error('catatan') textarea-error @enderror" 
                                  placeholder="Tuliskan hasil diagnosa, tindakan medis, petunjuk pemakaian obat, dan anjuran untuk pasien..." 
                                  required>{{ old('catatan') }}</textarea>
                        @error('catatan')
                            <label class="label">
                                <span class="label-text-alt text-error text-xs">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    {{-- Resep Obat Multi-Select --}}
                    <div class="form-control">
                        <label class="label justify-between">
                            <span class="label-text font-bold text-sm">Pilih Resep Obat (Opsional)</span>
                            <span class="text-xs text-base-content/60">Bisa memilih lebih dari 1 obat</span>
                        </label>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-72 overflow-y-auto p-3 border border-base-200 rounded-xl bg-base-200/30">
                            @forelse($obats as $obat)
                                <label class="label cursor-pointer p-3 bg-base-100 rounded-lg border border-base-200 hover:border-primary transition-all flex items-start gap-3">
                                    <input type="checkbox" 
                                           name="id_obat[]" 
                                           value="{{ $obat->id }}" 
                                           data-harga="{{ $obat->harga }}" 
                                           class="checkbox checkbox-primary checkbox-sm mt-0.5 obat-checkbox"
                                           {{ in_array($obat->id, old('id_obat', [])) ? 'checked' : '' }}
                                           onchange="calculateTotal()">
                                    <div class="flex-1">
                                        <div class="font-bold text-sm text-base-content">{{ $obat->nama_obat }}</div>
                                        <div class="text-xs text-base-content/50">{{ $obat->kemasan ?? 'Tablet/Sirup' }}</div>
                                        <div class="flex items-center justify-between mt-1 pt-1 border-t border-base-200">
                                            <span class="font-bold text-xs text-primary">Rp {{ number_format($obat->harga, 0, ',', '.') }}</span>
                                            <span class="badge badge-xs {{ $obat->stok > 10 ? 'badge-success' : 'badge-warning' }}">
                                                Stok: {{ $obat->stok }}
                                            </span>
                                        </div>
                                    </div>
                                </label>
                            @empty
                                <div class="col-span-2 text-center py-6 text-base-content/50 text-xs">
                                    Belum ada data obat atau stok habis.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Perhitungan Biaya Otomatis Card --}}
                    <div class="card bg-primary/5 border border-primary/20 rounded-xl p-5">
                        <h3 class="font-bold text-sm mb-3 text-base-content flex items-center gap-2">
                            <i class="fas fa-calculator text-primary"></i> Rincian Biaya Pemeriksaan Otomatis
                        </h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-base-content/70">Jasa Pelayanan Dokter:</span>
                                <span class="font-semibold font-mono">Rp 150.000</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-base-content/70">Total Biaya Obat:</span>
                                <span class="font-semibold font-mono" id="labelBiayaObat">Rp 0</span>
                            </div>
                            <div class="divider my-1"></div>
                            <div class="flex justify-between text-base font-bold text-primary">
                                <span>Total Tagihan Pasien:</span>
                                <span class="font-mono text-xl" id="labelTotalBiaya">Rp 150.000</span>
                            </div>
                        </div>
                    </div>

                    {{-- Submit Buttons --}}
                    <div class="flex items-center justify-end gap-3 pt-2">
                        <a href="{{ route('periksa-pasien.index') }}" class="btn btn-ghost">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-primary gap-2">
                            <i class="fas fa-floppy-disk"></i> Simpan Hasil Pemeriksaan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function calculateTotal() {
            const baseFee = 150000;
            let obatTotal = 0;
            const checkboxes = document.querySelectorAll('.obat-checkbox:checked');
            
            checkboxes.forEach(cb => {
                const harga = parseFloat(cb.getAttribute('data-harga')) || 0;
                obatTotal += harga;
            });

            const grandTotal = baseFee + obatTotal;

            document.getElementById('labelBiayaObat').innerText = 'Rp ' + obatTotal.toLocaleString('id-ID');
            document.getElementById('labelTotalBiaya').innerText = 'Rp ' + grandTotal.toLocaleString('id-ID');
        }

        // Run calculation on initial load
        document.addEventListener('DOMContentLoaded', calculateTotal);
    </script>
</x-layouts.app>
