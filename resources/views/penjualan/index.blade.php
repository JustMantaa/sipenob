@extends('layouts.app')

@section('title', 'Transaksi Penjualan')

@section('content')
<div class="bg-white p-6 rounded-lg shadow">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Transaksi Penjualan</h1>
        <a href="/penjualan/create" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Tambah Penjualan
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full border-collapse border border-slate-300 text-sm">
            <thead>
                <tr class="bg-slate-200">
                    <th class="border border-slate-300 px-4 py-2">No Nota</th>
                    <th class="border border-slate-300 px-4 py-2">Tanggal</th>
                    <th class="border border-slate-300 px-4 py-2">Pelanggan</th>
                    <th class="border border-slate-300 px-4 py-2">Total</th>
                    <th class="border border-slate-300 px-4 py-2">Kasir</th>
                    <th class="border border-slate-300 px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($penjualans as $penjualan)
                <tr>
                    <td class="border border-slate-300 px-4 py-2">{{ $penjualan->no_nota }}</td>
                    <td class="border border-slate-300 px-4 py-2">{{ $penjualan->tanggal_penjualan->format('d/m/Y') }}</td>
                    <td class="border border-slate-300 px-4 py-2">{{ $penjualan->pelanggan->nama_pelanggan ?? 'Umum' }}</td>
                    <td class="border border-slate-300 px-4 py-2 text-right">Rp {{ number_format($penjualan->total_penjualan, 0, ',', '.') }}</td>
                    <td class="border border-slate-300 px-4 py-2">{{ $penjualan->user->name }}</td>
                    <td class="border border-slate-300 px-4 py-2 text-center">
                        <a href="/penjualan/{{ $penjualan->id }}" class="text-blue-600 hover:underline">Detail</a>
                        @if(auth()->user()->isAdmin())
                        <form action="/penjualan/{{ $penjualan->id }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline ml-2" onclick="return confirm('Yakin? Stok akan dikembalikan.')">Hapus</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="border border-slate-300 px-4 py-2 text-center text-slate-500">Belum ada transaksi</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
