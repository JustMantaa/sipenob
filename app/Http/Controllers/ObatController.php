<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\RelasionalObat;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ObatController extends Controller
{
    public function index()
    {
        $obats = Obat::with(['relasionalObat', 'suppliers'])->orderBy('kode_obat')->get();
        return view('obat.index', compact('obats'));
    }

    public function create()
    {
        $kategoris = RelasionalObat::orderBy('nama_kategori')->get();
        return view('obat.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'relasional_obat_id' => 'required|exists:relasional_obats,id',
            'kode_obat' => 'required|string|max:255|unique:obats,kode_obat',
            'nama_obat' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'stok' => 'required|integer|min:0',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'tanggal_kadaluarsa' => 'nullable|date',
        ]);

        Obat::create($validated);

        return redirect('/obat')->with('success', 'Data obat berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $obat = Obat::findOrFail($id);
        $kategoris = RelasionalObat::orderBy('nama_kategori')->get();
        $obat->load('suppliers');
        return view('obat.edit', compact('obat', 'kategoris'));
    }

    public function update(Request $request, $id)
    {
        $obat = Obat::findOrFail($id);

        $validated = $request->validate([
            'relasional_obat_id' => 'required|exists:relasional_obats,id',
            'kode_obat' => ['required', 'string', 'max:255', Rule::unique('obats', 'kode_obat')->ignore($obat->id)],
            'nama_obat' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'stok' => 'required|integer|min:0',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'tanggal_kadaluarsa' => 'nullable|date',
        ]);

        $obat->update($validated);

        return redirect('/obat')->with('success', 'Data obat berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $obat = Obat::findOrFail($id);
        $obat->delete();

        return redirect('/obat')->with('success', 'Data obat berhasil dihapus.');
    }

}
