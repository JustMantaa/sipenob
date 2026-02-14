<?php

namespace App\Http\Controllers;

use App\Models\RelasionalObat;
use Illuminate\Http\Request;

class RelasionalObatController extends Controller
{
    public function index()
    {
        $kategoris = RelasionalObat::orderBy('nama_kategori')->get();
        return view('relasional-obat.index', compact('kategoris'));
    }

    public function create()
    {
        return view('relasional-obat.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        RelasionalObat::create($validated);

        return redirect('/relasional-obat')->with('success', 'Kategori obat berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $kategori = RelasionalObat::findOrFail($id);
        return view('relasional-obat.edit', compact('kategori'));
    }

    public function update(Request $request, $id)
    {
        $kategori = RelasionalObat::findOrFail($id);

        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        $kategori->update($validated);

        return redirect('/relasional-obat')->with('success', 'Kategori obat berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kategori = RelasionalObat::findOrFail($id);
        $kategori->delete();

        return redirect('/relasional-obat')->with('success', 'Kategori obat berhasil dihapus.');
    }
}
