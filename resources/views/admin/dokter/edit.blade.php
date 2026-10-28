<x-layouts.app title="Edit Dokter">

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('dokter.index') }}" class="btn btn-ghost btn-square btn-sm rounded-xl" aria-label="Kembali ke Data Dokter">
            <i class="fas fa-arrow-left text-sm"></i>
        </a>

        <h2 class="text-2xl font-bold text-slate-800">
            Edit Dokter
        </h2>
    </div>

    {{-- Card --}}
    <div class="card bg-base-100 shadow-sm rounded-2xl border border-base-200">
        <div class="card-body p-6 sm:p-8">

            <form action="{{ route('dokter.update', $dokter->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

                    {{-- Nama --}}
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold text-slate-700 text-sm">
                                Nama Dokter <span class="text-error">*</span>
                            </span>
                        </label>
                        <input type="text" name="nama" value="{{ old('nama', $dokter->nama) }}"
                            placeholder="Masukkan nama dokter..." class="input input-bordered w-full rounded-xl @error('nama') input-error @enderror" required>
                        @error('nama')
                        <span class="text-xs text-error mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold text-slate-700 text-sm">
                                Email <span class="text-error">*</span>
                            </span>
                        </label>
                        <input type="email" name="email" value="{{ old('email', $dokter->email) }}"
                            placeholder="Masukkan email..." class="input input-bordered w-full rounded-xl @error('email') input-error @enderror" required>
                        @error('email')
                        <span class="text-xs text-error mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- No KTP --}}
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold text-slate-700 text-sm">
                                No. KTP <span class="text-error">*</span>
                            </span>
                        </label>
                        <input type="text" inputmode="numeric" pattern="[0-9]{16}" maxlength="16" name="no_ktp" value="{{ old('no_ktp', $dokter->no_ktp) }}"
                            placeholder="16 digit NIK..." class="input input-bordered w-full rounded-xl @error('no_ktp') input-error @enderror" required>
                        @error('no_ktp')
                        <span class="text-xs text-error mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- No HP --}}
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold text-slate-700 text-sm">
                                No. HP <span class="text-error">*</span>
                            </span>
                        </label>
                        <input type="tel" name="no_hp" value="{{ old('no_hp', $dokter->no_hp) }}"
                            placeholder="Contoh: 081234567890" class="input input-bordered w-full rounded-xl @error('no_hp') input-error @enderror" required>
                        @error('no_hp')
                        <span class="text-xs text-error mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                </div>

                {{-- Alamat --}}
                <div class="form-control mb-6">
                    <label class="label">
                        <span class="label-text font-semibold text-slate-700 text-sm">
                            Alamat <span class="text-error">*</span>
                        </span>
                    </label>
                    <textarea name="alamat" rows="3" placeholder="Masukkan alamat..." class="textarea textarea-bordered w-full rounded-xl @error('alamat') textarea-error @enderror"
                        required>{{ old('alamat', $dokter->alamat) }}</textarea>
                    @error('alamat')
                    <span class="text-xs text-error mt-1">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Poli --}}
                <div class="form-control mb-6">
                    <label class="label">
                        <span class="label-text font-semibold text-slate-700 text-sm">
                            Poli <span class="text-error">*</span>
                        </span>
                    </label>
                    <select name="id_poli" class="select select-bordered w-full rounded-xl @error('id_poli') select-error @enderror" required>
                        <option value="">Pilih Poli</option>
                        @foreach($polis as $poli)
                        <option value="{{ $poli->id }}" {{ (string) old('id_poli', $dokter->id_poli) === (string) $poli->id ? 'selected' : '' }}>
                            {{ $poli->nama_poli }}
                        </option>
                        @endforeach
                    </select>
                    @error('id_poli')
                    <span class="text-xs text-error mt-1">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="form-control mb-8">
                    <label class="label">
                        <span class="label-text font-semibold text-slate-700 text-sm">
                            Password (Opsional)
                        </span>
                    </label>
                    <input type="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah..." class="input input-bordered w-full rounded-xl @error('password') input-error @enderror">
                    @error('password')
                    <span class="text-xs text-error mt-1">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Buttons --}}
                <div class="flex items-center gap-3">
                    <button type="submit" class="btn btn-primary rounded-xl px-6 shadow-sm">
                        <i class="fas fa-save mr-1"></i> Update
                    </button>

                    <a href="{{ route('dokter.index') }}" class="btn btn-ghost rounded-xl px-6">
                        Batal
                    </a>
                </div>

            </form>

        </div>
    </div>

</x-layouts.app>
