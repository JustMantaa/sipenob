<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    public function index()
    {
        $pelanggans = Pelanggan::orderBy('nama_pelanggan')->get();
        return view('pelanggan.index', compact('pelanggans'));
    }

    public function create()
    {
        return view('pelanggan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:20',
        ]);

        Pelanggan::create($validated);

        return redirect('/pelanggan')->with('success', 'Data pelanggan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        return view('pelanggan.edit', compact('pelanggan'));
    }

    public function update(Request $request, $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);

        $validated = $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:20',
        ]);

        $pelanggan->update($validated);

        return redirect('/pelanggan')->with('success', 'Data pelanggan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        $pelanggan->delete();

        return redirect('/pelanggan')->with('success', 'Data pelanggan berhasil dihapus.');
    }

    public function storeAjax(Request $request)
    {
        try {
            $validated = $request->validate([
                'nama_pelanggan' => 'required|string|max:255',
                'alamat' => 'nullable|string',
                'telepon' => 'nullable|string|max:20',
            ]);

            $pelanggan = Pelanggan::create($validated);

            return response()->json([
                'success' => true,
                'pelanggan' => $pelanggan,
                'message' => 'Pelanggan berhasil ditambahkan'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
