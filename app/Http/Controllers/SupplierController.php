<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::with('obats')->orderBy('nama_supplier')->get();
        return view('supplier.index', compact('suppliers'));
    }

    public function create()
    {
        return view('supplier.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_supplier' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        Supplier::create($validated);

        return redirect('/supplier')->with('success', 'Data supplier berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);
        $obats = Obat::with('relasionalObat')->orderBy('nama_obat')->get();
        $selectedObatIds = $supplier->obats()->pluck('obats.id')->toArray();

        return view('supplier.edit', compact('supplier', 'obats', 'selectedObatIds'));
    }

    public function update(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);

        $validated = $request->validate([
            'nama_supplier' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'obat_ids' => 'nullable|array',
            'obat_ids.*' => 'exists:obats,id',
        ]);

        $supplier->update($validated);

        $selectedObatIds = $validated['obat_ids'] ?? [];
        $supplier->obats()->sync($selectedObatIds);

        return redirect('/supplier')->with('success', 'Data supplier berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();

        return redirect('/supplier')->with('success', 'Data supplier berhasil dihapus.');
    }
}
