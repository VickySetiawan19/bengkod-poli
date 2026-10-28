<x-layouts.app title="Edit Obat">

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('obat.index') }}" class="btn btn-ghost btn-square btn-sm rounded-xl" aria-label="Kembali ke Data Obat">
            <i class="fas fa-arrow-left text-sm"></i>
        </a>

        <h2 class="text-2xl font-bold text-slate-800">
            Edit Obat
        </h2>
    </div>

    {{-- Card --}}
    <div class="card bg-base-100 shadow-sm rounded-2xl border border-base-200">
        <div class="card-body p-6 sm:p-8">

            <form action="{{ route('obat.update', $obat->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Nama Obat --}}
                <div class="form-control mb-5">
                    <label class="label">
                        <span class="label-text font-semibold text-slate-700 text-sm">
                            Nama Obat <span class="text-error">*</span>
                        </span>
                    </label>

                    <input type="text" name="nama_obat" value="{{ old('nama_obat', $obat->nama_obat) }}"
                        placeholder="Masukkan nama obat..."
                        class="input input-bordered w-full rounded-xl @error('nama_obat') input-error @enderror"
                        required>

                    @error('nama_obat')
                    <span class="text-xs text-error mt-1">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Kemasan --}}
                <div class="form-control mb-5">
                    <label class="label">
                        <span class="label-text font-semibold text-slate-700 text-sm">
                            Kemasan
                        </span>
                    </label>

                    <input type="text" name="kemasan" value="{{ old('kemasan', $obat->kemasan) }}"
                        placeholder="Contoh: Strip @ 10 tablet, Botol 60 ml..."
                        maxlength="35"
                        class="input input-bordered w-full rounded-xl @error('kemasan') input-error @enderror">

                    @error('kemasan')
                    <span class="text-xs text-error mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-8">
                    {{-- Harga --}}
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold text-slate-700 text-sm">
                                Harga (Rp) <span class="text-error">*</span>
                            </span>
                        </label>

                        <input type="number" name="harga" value="{{ old('harga', $obat->harga) }}"
                            placeholder="Masukkan harga obat..."
                            min="0"
                            class="input input-bordered w-full rounded-xl @error('harga') input-error @enderror"
                            required>

                        @error('harga')
                        <span class="text-xs text-error mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Stok --}}
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold text-slate-700 text-sm">
                                Stok Obat <span class="text-error">*</span>
                            </span>
                        </label>

                        <input type="number" name="stok" value="{{ old('stok', $obat->stok ?? 50) }}"
                            placeholder="Masukkan jumlah stok obat..."
                            min="0"
                            class="input input-bordered w-full rounded-xl @error('stok') input-error @enderror"
                            required>

                        @error('stok')
                        <span class="text-xs text-error mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="flex items-center gap-3">
                    <button type="submit"
                        class="btn btn-primary rounded-xl px-6 shadow-sm">
                        <i class="fas fa-save mr-1"></i> Update
                    </button>

                    <a href="{{ route('obat.index') }}"
                        class="btn btn-ghost rounded-xl px-6">
                        Batal
                    </a>
                </div>

            </form>

        </div>
    </div>

</x-layouts.app>
