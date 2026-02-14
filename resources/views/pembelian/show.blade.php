@extends('layouts.app')

@section('title', 'Detail Pembelian')

@section('content')
<div class="bg-white p-6 rounded-lg shadow max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Detail Transaksi Pembelian</h1>
        <a href="/pembelian" class="bg-slate-300 text-slate-700 px-4 py-2 rounded hover:bg-slate-400">Kembali</a>
    </div>

    <div class="grid grid-cols-2 gap-4 mb-6">
        <div>
            <p class="text-slate-600">No Faktur</p>
            <p class="font-bold">{{ $pembelian->no_faktur }}</p>
        </div>
        <div>
            <p class="text-slate-600">Tanggal</p>
            <p class="font-bold">{{ $pembelian->tanggal_pembelian->format('d/m/Y') }}</p>
        </div>
        <div>
            <p class="text-slate-600">Supplier</p>
            <p class="font-bold">{{ $pembelian->supplier->nama_supplier }}</p>
        </div>
        <div>
            <p class="text-slate-600">Petugas</p>
            <p class="font-bold">{{ $pembelian->user->name }}</p>
        </div>
    </div>

    <h3 class="text-lg font-bold mb-3">Detail Obat</h3>
    <table class="w-full border-collapse border border-slate-300 mb-6">
        <thead>
            <tr class="bg-slate-200">
                <th class="border border-slate-300 px-4 py-2">No</th>
                <th class="border border-slate-300 px-4 py-2">Nama Obat</th>
                <th class="border border-slate-300 px-4 py-2">Jumlah</th>
                <th class="border border-slate-300 px-4 py-2">Harga Beli</th>
                <th class="border border-slate-300 px-4 py-2">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pembelian->pembelianDetails as $detail)
            <tr>
                <td class="border border-slate-300 px-4 py-2 text-center">{{ $loop->iteration }}</td>
                <td class="border border-slate-300 px-4 py-2">{{ $detail->obat->nama_obat }}</td>
                <td class="border border-slate-300 px-4 py-2 text-center">{{ $detail->jumlah }}</td>
                <td class="border border-slate-300 px-4 py-2 text-right">Rp {{ number_format($detail->harga_beli, 0, ',', '.') }}</td>
                <td class="border border-slate-300 px-4 py-2 text-right">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="bg-slate-100 font-bold">
                <td colspan="4" class="border border-slate-300 px-4 py-2 text-right">TOTAL</td>
                <td class="border border-slate-300 px-4 py-2 text-right">Rp {{ number_format($pembelian->total_pembelian, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
</div>
@endsection
