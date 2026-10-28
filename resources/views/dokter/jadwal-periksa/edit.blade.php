<x-layouts.app title="Edit Jadwal Periksa">

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('jadwal-periksa.index') }}" class="btn btn-ghost btn-square btn-sm rounded-xl" aria-label="Kembali ke Jadwal Periksa">
            <i class="fas fa-arrow-left text-sm"></i>
        </a>

        <h2 class="text-2xl font-bold text-slate-800">
            Edit Jadwal Periksa
        </h2>
    </div>

    {{-- Card --}}
    <div class="card bg-base-100 shadow-sm rounded-2xl border border-base-200 max-w-2xl">
        <div class="card-body p-6 sm:p-8">

            <form action="{{ route('jadwal-periksa.update', $jadwal->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Hari --}}
                <div class="form-control mb-6">
                    <label class="label">
                        <span class="label-text font-semibold text-slate-700 text-sm">
                            Pilih Hari <span class="text-error">*</span>
                        </span>
                    </label>

                    <select name="hari" class="select select-bordered w-full rounded-xl @error('hari') select-error @enderror" required>
                        <option value="">-- Pilih Hari Praktik --</option>
                        @foreach($hariList as $hari)
                        <option value="{{ $hari }}" {{ old('hari', $jadwal->hari) == $hari ? 'selected' : '' }}>
                            {{ $hari }}
                        </option>
                        @endforeach
                    </select>

                    @error('hari')
                    <span class="text-xs text-error mt-1">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Grid Jam Mulai & Jam Selesai --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">

                    {{-- Jam Mulai --}}
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold text-slate-700 text-sm">
                                Jam Mulai <span class="text-error">*</span>
                            </span>
                        </label>

                        <input type="time" name="jam_mulai" 
                            value="{{ old('jam_mulai', \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i')) }}"
                            class="input input-bordered w-full rounded-xl @error('jam_mulai') input-error @enderror"
                            required>

                        @error('jam_mulai')
                        <span class="text-xs text-error mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Jam Selesai --}}
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold text-slate-700 text-sm">
                                Jam Selesai <span class="text-error">*</span>
                            </span>
                        </label>

                        <input type="time" name="jam_selesai" 
                            value="{{ old('jam_selesai', \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i')) }}"
                            class="input input-bordered w-full rounded-xl @error('jam_selesai') input-error @enderror"
                            required>

                        @error('jam_selesai')
                        <span class="text-xs text-error mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                </div>

                {{-- Buttons --}}
                <div class="flex items-center gap-3">
                    <button type="submit"
                        class="btn btn-primary rounded-xl px-6 shadow-sm">
                        <i class="fas fa-save mr-1"></i> Update Jadwal
                    </button>

                    <a href="{{ route('jadwal-periksa.index') }}"
                        class="btn btn-ghost rounded-xl px-6">
                        Batal
                    </a>
                </div>

            </form>

        </div>
    </div>

</x-layouts.app>
