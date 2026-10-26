<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Obat;
use Illuminate\Http\Request;

class ObatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $obats = Obat::all();
        return view('admin.obat.index', compact('obats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.obat.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_obat' => 'required|string|max:255',
            'kemasan' => 'nullable|string|max:35',
            'harga' => 'required|numeric|min:0',
            'stok' => 'nullable|integer|min:0',
        ], [
            'nama_obat.required' => 'Nama obat wajib diisi.',
            'kemasan.max' => 'Kemasan maksimal 35 karakter.',
            'harga.required' => 'Harga obat wajib diisi.',
            'harga.numeric' => 'Harga harus berupa angka.',
            'harga.min' => 'Harga tidak boleh kurang dari 0.',
        ]);

        if (!isset($validated['stok'])) {
            $validated['stok'] = 50;
        }

        Obat::create($validated);

        return redirect()->route('obat.index')
            ->with('success', 'Data obat berhasil ditambahkan!')
            ->with('message', 'Data obat berhasil ditambahkan!')
            ->with('type', 'success');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $obat = Obat::findOrFail($id);
        return redirect()->route('obat.edit', $obat->id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $obat = Obat::findOrFail($id);
        return view('admin.obat.edit', compact('obat'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'nama_obat' => 'required|string|max:255',
            'kemasan' => 'nullable|string|max:35',
            'harga' => 'required|numeric|min:0',
            'stok' => 'nullable|integer|min:0',
        ], [
            'nama_obat.required' => 'Nama obat wajib diisi.',
            'kemasan.max' => 'Kemasan maksimal 35 karakter.',
            'harga.required' => 'Harga obat wajib diisi.',
            'harga.numeric' => 'Harga harus berupa angka.',
            'harga.min' => 'Harga tidak boleh kurang dari 0.',
        ]);

        $obat = Obat::findOrFail($id);
        $obat->update($validated);

        return redirect()->route('obat.index')
            ->with('success', 'Data obat berhasil diperbarui!')
            ->with('message', 'Data obat berhasil diperbarui!')
            ->with('type', 'success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $obat = Obat::findOrFail($id);
        $obat->delete();

        return redirect()->route('obat.index')
            ->with('success', 'Data obat berhasil dihapus!')
            ->with('message', 'Data obat berhasil dihapus!')
            ->with('type', 'success');
    }

    /**
     * Export medicine data including stock and price to Excel.
     */
    public function export()
    {
        $obats = Obat::all();

        $headers = ['No', 'Nama Obat', 'Kemasan', 'Harga (Rp)', 'Stok'];
        $rows = [];
        $no = 1;
        foreach ($obats as $o) {
            $rows[] = [
                $no++,
                $o->nama_obat,
                $o->kemasan ?? '-',
                $o->harga,
                $o->stok ?? 0,
            ];
        }

        return \App\Services\ExcelExportService::download('data_obat_' . date('Ymd_His'), $headers, $rows);
    }
}
