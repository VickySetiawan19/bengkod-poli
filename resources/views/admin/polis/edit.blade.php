<x-layouts.app title="Edit Poliklinik">
    <div class="max-w-2xl mx-auto space-y-6">
        {{-- Header --}}
        <div class="flex items-center gap-3">
            <a href="{{ route('polis.index') }}"
                class="flex items-center justify-center w-10 h-10 rounded-xl bg-slate-100 text-slate-500 hover:bg-slate-200 transition">
                <i class="fas fa-arrow-left text-sm"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-800 flex items-center gap-2">
                    <i class="fas fa-pen-to-square text-primary"></i> Edit Poliklinik
                </h1>
                <p class="text-xs text-slate-500 mt-0.5">
                    Perbarui nama atau deskripsi informasi layanan poliklinik.
                </p>
            </div>
        </div>

        {{-- Card Form --}}
        <div class="card bg-base-100 shadow-sm rounded-2xl border border-slate-200/80">
            <div class="card-body p-6 sm:p-8">
                <form action="{{ route('polis.update', $poli->id) }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')

                    {{-- Nama Poli --}}
                    <div class="form-control">
                        <label class="label pb-1">
                            <span class="text-sm font-bold text-slate-700">
                                Nama Poliklinik <span class="text-red-500">*</span>
                            </span>
                        </label>
                        <input type="text" 
                               name="nama_poli" 
                               value="{{ old('nama_poli', $poli->nama_poli) }}"
                               placeholder="Masukkan nama poli..."
                               class="input input-bordered w-full rounded-xl text-sm @error('nama_poli') input-error @enderror"
                               required>
                        @error('nama_poli')
                            <label class="label pt-1">
                                <span class="label-text-alt text-red-500 text-xs">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    {{-- Keterangan --}}
                    <div class="form-control">
                        <label class="label pb-1">
                            <span class="text-sm font-bold text-slate-700">
                                Keterangan & Lingkup Pelayanan
                            </span>
                        </label>
                        <textarea name="keterangan" 
                                  rows="4" 
                                  placeholder="Deskripsikan layanan kesehatan yang disediakan pada poliklinik ini..."
                                  class="textarea textarea-bordered w-full rounded-xl text-sm leading-relaxed resize-none @error('keterangan') textarea-error @enderror">{{ old('keterangan', $poli->keterangan) }}</textarea>
                        @error('keterangan')
                            <label class="label pt-1">
                                <span class="label-text-alt text-red-500 text-xs">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    {{-- Buttons --}}
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <a href="{{ route('polis.index') }}"
                            class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-sm font-semibold transition">
                            Batal
                        </a>
                        <button type="submit"
                            class="btn btn-primary rounded-xl text-sm font-semibold gap-2 shadow-sm">
                            <i class="fas fa-floppy-disk"></i>
                            Perbarui Poli
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>