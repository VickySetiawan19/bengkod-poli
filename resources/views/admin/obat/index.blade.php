<x-layouts.app title="Data Obat">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-slate-800">
            Data Obat
        </h2>

        <div class="flex items-center gap-2">
            <a href="{{ route('obat.export') }}" class="btn bg-emerald-600 hover:bg-emerald-700 text-white border-none rounded-lg px-4 gap-2 shadow-sm">
                <i class="fas fa-file-excel"></i>
                Export Excel
            </a>
            <a href="{{ route('obat.create') }}" class="btn bg-[#2d4499] hover:bg-[#1e2d6b] text-white border-none rounded-lg px-5 gap-2 shadow-sm">
                <i class="fas fa-plus"></i>
                Tambah Obat
            </a>
        </div>
    </div>

    {{-- Card Table --}}
    <div class="card bg-base-100 shadow-md rounded-2 border">
        <div class="card-body p-0">

            <div class="overflow-x-auto">
                <table class="table table-zebra w-full">

                    {{-- Head --}}
                    <thead class="bg-slate-100 text-slate-500 text-xs uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-4">Nama Obat</th>
                            <th class="px-6 py-4">Kemasan</th>
                            <th class="px-6 py-4">Harga</th>
                            <th class="px-6 py-4">Stok</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>

                    {{-- Body --}}
                    <tbody>
                        @forelse($obats as $obat)
                        <tr class="hover:bg-slate-50 transition">

                            <td class="px-6 py-4 font-semibold text-slate-800">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center font-bold text-xs">
                                        <i class="fas fa-pills"></i>
                                    </div>
                                    <span>{{ $obat->nama_obat }}</span>
                                </div>
                            </td>

                            <td class="px-6 py-4 text-slate-600">
                                {{ $obat->kemasan ?? '-' }}
                            </td>

                            <td class="px-6 py-4 font-semibold text-slate-800 font-mono whitespace-nowrap">
                                Rp {{ number_format($obat->harga, 0, ',', '.') }}
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="badge {{ ($obat->stok ?? 0) > 10 ? 'badge-success text-white' : (($obat->stok ?? 0) > 0 ? 'badge-warning text-slate-900' : 'badge-error text-white') }} badge-sm font-semibold rounded-full whitespace-nowrap shadow-2xs">
                                    {{ $obat->stok ?? 0 }} unit
                                </span>
                            </td>

                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">

                                    {{-- Edit --}}
                                    <a href="{{ route('obat.edit', $obat->id) }}" class="btn btn-sm bg-amber-500 hover:bg-amber-600 text-white border-none rounded-lg px-4">
                                        <i class="fas fa-pen-to-square"></i>
                                        Edit
                                    </a>

                                    {{-- Delete --}}
                                    <form action="{{ route('obat.destroy', $obat->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            onclick="return confirm('Yakin ingin menghapus obat ini?')" class="btn btn-sm bg-red-500 hover:bg-red-600 text-white border-none rounded-lg px-4">
                                            <i class="fas fa-trash"></i>
                                            Hapus
                                        </button>
                                    </form>

                                </div>
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-14 text-slate-400">
                                <i class="fas fa-inbox text-3xl mb-3 block"></i>
                                Belum ada data obat
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>
    </div>

</x-layouts.app>
