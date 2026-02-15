@extends('layouts.app')

@section('title', 'Detail Penjualan')

@section('content')
<div class="bg-white p-6 rounded-lg shadow max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Detail Transaksi Penjualan</h1>
        <a href="/penjualan" class="bg-slate-300 text-slate-700 px-4 py-2 rounded hover:bg-slate-400">Kembali</a>
    </div>

    <div class="grid grid-cols-2 gap-4 mb-6">
        <div>
            <p class="text-slate-600">No Nota</p>
            <p class="font-bold">{{ $penjualan->no_nota }}</p>
        </div>
        <div>
            <p class="text-slate-600">Tanggal</p>
            <p class="font-bold">{{ $penjualan->tanggal_penjualan->format('d/m/Y') }}</p>
        </div>
        <div>
            <p class="text-slate-600">Pelanggan</p>
            <p class="font-bold">{{ $penjualan->pelanggan->nama_pelanggan ?? 'Pelanggan Umum' }}</p>
        </div>
        <div>
            <p class="text-slate-600">Kasir</p>
            <p class="font-bold">{{ $penjualan->user->name }}</p>
        </div>
    </div>

    <h3 class="text-lg font-bold mb-3">Detail Obat</h3>
    <table class="w-full border-collapse border border-slate-300 mb-6">
        <thead>
            <tr class="bg-slate-200">
                <th class="border border-slate-300 px-4 py-2">No</th>
                <th class="border border-slate-300 px-4 py-2">Nama Obat</th>
                <th class="border border-slate-300 px-4 py-2">Jumlah</th>
                <th class="border border-slate-300 px-4 py-2">Harga Jual</th>
                <th class="border border-slate-300 px-4 py-2">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($penjualan->penjualanDetails as $detail)
            <tr>
                <td class="border border-slate-300 px-4 py-2 text-center">{{ $loop->iteration }}</td>
                <td class="border border-slate-300 px-4 py-2">{{ $detail->obat->nama_obat }}</td>
                <td class="border border-slate-300 px-4 py-2 text-center">{{ $detail->jumlah }}</td>
                <td class="border border-slate-300 px-4 py-2 text-right">Rp {{ $detail->harga_jual }}</td>
                <td class="border border-slate-300 px-4 py-2 text-right">Rp {{ $detail->subtotal }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="bg-slate-100 font-bold">
                <td colspan="4" class="border border-slate-300 px-4 py-2 text-right">TOTAL</td>
                <td class="border border-slate-300 px-4 py-2 text-right">Rp {{ $penjualan->total_penjualan }}</td>
            </tr>
        </tfoot>
    </table>
</div>
@endsection
