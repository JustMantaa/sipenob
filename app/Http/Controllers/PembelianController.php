<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\PembelianDetail;
use App\Models\Supplier;
use App\Models\Obat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PembelianController extends Controller
{
    public function index()
    {
        $pembelians = Pembelian::with(['supplier', 'user'])
            ->orderBy('tanggal_pembelian', 'desc')
            ->get();
        return view('pembelian.index', compact('pembelians'));
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('nama_supplier')->get();
        $obats = Obat::with('relasionalObat')->orderBy('nama_obat')->get();
        
        // Generate nomor faktur
        $lastPembelian = Pembelian::latest('id')->first();
        $nextNumber = $lastPembelian ? intval(substr($lastPembelian->no_faktur, 3)) + 1 : 1;
        $noFaktur = 'PB-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
        
        return view('pembelian.create', compact('suppliers', 'obats', 'noFaktur'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'no_faktur' => 'required|string|unique:pembelians,no_faktur',
            'supplier_id' => 'required|exists:suppliers,id',
            'tanggal_pembelian' => 'required|date',
            'obat_id' => 'required|array',
            'obat_id.*' => 'required|exists:obats,id',
            'jumlah' => 'required|array',
            'jumlah.*' => 'required|integer|min:1',
            'harga_beli' => 'required|array',
            'harga_beli.*' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // Buat pembelian
            $pembelian = Pembelian::create([
                'no_faktur' => $validated['no_faktur'],
                'supplier_id' => $validated['supplier_id'],
                'user_id' => auth()->id(),
                'tanggal_pembelian' => $validated['tanggal_pembelian'],
                'total_pembelian' => 0,
            ]);

            $totalPembelian = 0;

            // Buat detail pembelian dan update stok
            foreach ($validated['obat_id'] as $index => $obatId) {
                $jumlah = $validated['jumlah'][$index];
                $hargaBeli = $validated['harga_beli'][$index];
                $subtotal = $jumlah * $hargaBeli;

                PembelianDetail::create([
                    'pembelian_id' => $pembelian->id,
                    'obat_id' => $obatId,
                    'jumlah' => $jumlah,
                    'harga_beli' => $hargaBeli,
                    'subtotal' => $subtotal,
                ]);

                // Update stok obat
                $obat = Obat::find($obatId);
                $obat->increment('stok', $jumlah);

                $totalPembelian += $subtotal;
            }

            // Update total pembelian
            $pembelian->update(['total_pembelian' => $totalPembelian]);

            DB::commit();
            return redirect('/pembelian')->with('success', 'Transaksi pembelian berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan transaksi: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $pembelian = Pembelian::with(['supplier', 'user', 'pembelianDetails.obat'])
            ->findOrFail($id);
        return view('pembelian.show', compact('pembelian'));
    }

    public function destroy($id)
    {
        $pembelian = Pembelian::findOrFail($id);
        
        DB::beginTransaction();
        try {
            // Kurangi stok obat
            foreach ($pembelian->pembelianDetails as $detail) {
                $obat = Obat::find($detail->obat_id);
                $obat->decrement('stok', $detail->jumlah);
            }

            $pembelian->delete();
            DB::commit();
            return redirect('/pembelian')->with('success', 'Transaksi pembelian berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus transaksi: ' . $e->getMessage());
        }
    }
}
