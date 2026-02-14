<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\Pelanggan;
use App\Models\Obat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenjualanController extends Controller
{
    public function index()
    {
        $penjualans = Penjualan::with(['pelanggan', 'user'])
            ->orderBy('tanggal_penjualan', 'desc')
            ->get();
        return view('penjualan.index', compact('penjualans'));
    }

    public function create()
    {
        $pelanggans = Pelanggan::orderBy('nama_pelanggan')->get();
        $obats = Obat::with('relasionalObat')
            ->where('stok', '>', 0)
            ->orderBy('nama_obat')
            ->get();
        
        // Generate nomor nota
        $lastPenjualan = Penjualan::latest('id')->first();
        $nextNumber = $lastPenjualan ? intval(substr($lastPenjualan->no_nota, 3)) + 1 : 1;
        $noNota = 'PJ-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
        
        return view('penjualan.create', compact('pelanggans', 'obats', 'noNota'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'no_nota' => 'required|string|unique:penjualans,no_nota',
            'pelanggan_id' => 'nullable|exists:pelanggans,id',
            'tanggal_penjualan' => 'required|date',
            'obat_id' => 'required|array',
            'obat_id.*' => 'required|exists:obats,id',
            'jumlah' => 'required|array',
            'jumlah.*' => 'required|integer|min:1',
            'harga_jual' => 'required|array',
            'harga_jual.*' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // Validasi stok
            foreach ($validated['obat_id'] as $index => $obatId) {
                $obat = Obat::find($obatId);
                $jumlah = $validated['jumlah'][$index];
                
                if ($obat->stok < $jumlah) {
                    throw new \Exception("Stok {$obat->nama_obat} tidak mencukupi. Tersedia: {$obat->stok}");
                }
            }

            // Buat penjualan
            $penjualan = Penjualan::create([
                'no_nota' => $validated['no_nota'],
                'pelanggan_id' => $validated['pelanggan_id'],
                'user_id' => auth()->id(),
                'tanggal_penjualan' => $validated['tanggal_penjualan'],
                'total_penjualan' => 0,
            ]);

            $totalPenjualan = 0;

            // Buat detail penjualan dan update stok
            foreach ($validated['obat_id'] as $index => $obatId) {
                $jumlah = $validated['jumlah'][$index];
                $hargaJual = $validated['harga_jual'][$index];
                $subtotal = $jumlah * $hargaJual;

                PenjualanDetail::create([
                    'penjualan_id' => $penjualan->id,
                    'obat_id' => $obatId,
                    'jumlah' => $jumlah,
                    'harga_jual' => $hargaJual,
                    'subtotal' => $subtotal,
                ]);

                // Kurangi stok obat
                $obat = Obat::find($obatId);
                $obat->decrement('stok', $jumlah);

                $totalPenjualan += $subtotal;
            }

            // Update total penjualan
            $penjualan->update(['total_penjualan' => $totalPenjualan]);

            DB::commit();
            return redirect('/penjualan')->with('success', 'Transaksi penjualan berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan transaksi: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $penjualan = Penjualan::with(['pelanggan', 'user', 'penjualanDetails.obat'])
            ->findOrFail($id);
        return view('penjualan.show', compact('penjualan'));
    }

    public function destroy($id)
    {
        $penjualan = Penjualan::findOrFail($id);
        
        DB::beginTransaction();
        try {
            // Kembalikan stok obat
            foreach ($penjualan->penjualanDetails as $detail) {
                $obat = Obat::find($detail->obat_id);
                $obat->increment('stok', $detail->jumlah);
            }

            $penjualan->delete();
            DB::commit();
            return redirect('/penjualan')->with('success', 'Transaksi penjualan berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus transaksi: ' . $e->getMessage());
        }
    }
}
