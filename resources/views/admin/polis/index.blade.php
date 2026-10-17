<x-layouts.app title="Data Poliklinik">
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 flex items-center gap-2.5">
                    <i class="fas fa-hospital text-primary"></i> Data Poliklinik
                </h1>
                <p class="text-sm text-slate-500 mt-1">
                    Kelola daftar poli spesialis, deskripsi layanan kesehatan, dan dokter yang bertugas.
                </p>
            </div>

            <a href="{{ route('polis.create') }}" class="btn btn-primary rounded-xl text-sm font-semibold gap-2 shadow-sm">
                <i class="fas fa-plus"></i>
                Tambah Poli
            </a>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="card bg-base-100 shadow-sm border border-slate-200/80 rounded-2xl p-5 flex flex-row items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Total Poliklinik</span>
                    <span class="text-3xl font-extrabold text-slate-800 font-mono mt-1 block">{{ $polis->count() }}</span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl">
                    <i class="fas fa-hospital"></i>
                </div>
            </div>

            <div class="card bg-base-100 shadow-sm border border-slate-200/80 rounded-2xl p-5 flex flex-row items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Dokter Terdaftar</span>
                    <span class="text-3xl font-extrabold text-slate-800 font-mono mt-1 block">{{ $polis->sum('dokters_count') }}</span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center text-xl">
                    <i class="fas fa-user-doctor"></i>
                </div>
            </div>
        </div>

        {{-- Table Card --}}
        <div class="card bg-base-100 shadow-sm rounded-2xl border border-slate-200/80 overflow-hidden">
            <div class="card-body p-0">
                <div class="overflow-x-auto">
                    <table class="table w-full">
                        {{-- Head --}}
                        <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-4 w-16 text-center">No</th>
                                <th class="px-6 py-4">Nama Poli</th>
                                <th class="px-6 py-4">Keterangan Layanan</th>
                                <th class="px-6 py-4 text-center">Dokter Bertugas</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>

                        {{-- Body --}}
                        <tbody class="divide-y divide-slate-100 text-sm">
                            @forelse($polis as $index => $poli)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="px-6 py-4 text-center font-semibold text-slate-400 text-xs">
                                    {{ $index + 1 }}
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-sm shrink-0 border border-indigo-100">
                                            <i class="fas fa-stethoscope"></i>
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-800 text-base">
                                                {{ $poli->nama_poli }}
                                            </div>
                                            <span class="text-xs text-slate-400">ID: POLI-{{ str_pad($poli->id, 3, '0', STR_PAD_LEFT) }}</span>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-slate-600 max-w-md text-xs leading-relaxed">
                                    {{ $poli->keterangan ?: 'Belum ada deskripsi layanan untuk poliklinik ini.' }}
                                </td>

                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/80">
                                        <i class="fas fa-user-doctor text-[10px]"></i>
                                        {{ $poli->dokters_count }} Dokter
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    <div class="inline-flex items-center gap-2">
                                        {{-- Edit --}}
                                        <a href="{{ route('polis.edit', $poli->id) }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 
                                                   bg-amber-500 hover:bg-amber-600 text-white rounded-lg text-xs font-semibold transition shadow-sm">
                                            <i class="fas fa-pen-to-square text-xs"></i>
                                            Edit
                                        </a>

                                        {{-- Delete --}}
                                        <form action="{{ route('polis.destroy', $poli->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                onclick="return confirm('Yakin ingin menghapus poliklinik {{ $poli->nama_poli }}?')" 
                                                class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-red-500 hover:bg-red-600 text-white rounded-lg text-xs font-semibold transition shadow-sm">
                                                <i class="fas fa-trash text-xs"></i>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-16 text-slate-400">
                                    <div class="w-14 h-14 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center text-2xl mx-auto mb-3">
                                        <i class="fas fa-hospital-user"></i>
                                    </div>
                                    <h3 class="font-bold text-slate-600 text-base">Belum Ada Data Poli</h3>
                                    <p class="text-xs text-slate-400 mt-1">Klik tombol "Tambah Poli" di atas untuk menambahkan poliklinik baru.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>