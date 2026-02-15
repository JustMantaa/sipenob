@extends('layouts.app')

@section('title', 'Data Obat')

@section('content')
<div class="bg-white p-6 rounded-lg shadow">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Data Obat</h1>
        @if(auth()->user()->isAdmin())
        <a href="/obat/create" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Tambah Obat
        </a>
        @endif
    </div>

    <div class="overflow-x-auto">
        <table class="w-full border-collapse border border-slate-300 text-sm">
            <thead>
                <tr class="bg-slate-200">
                    <th class="border border-slate-300 px-2 py-2">Kode</th>
                    <th class="border border-slate-300 px-2 py-2">Nama Obat</th>
                    <th class="border border-slate-300 px-2 py-2">Supplier</th>
                    <th class="border border-slate-300 px-2 py-2">Kategori</th>
                    <th class="border border-slate-300 px-2 py-2">Stok</th>
                    <th class="border border-slate-300 px-2 py-2">Harga Beli</th>
                    <th class="border border-slate-300 px-2 py-2">Harga Jual</th>
                    <th class="border border-slate-300 px-2 py-2">Kadaluarsa</th>
                    @if(auth()->user()->isAdmin())
                    <th class="border border-slate-300 px-2 py-2">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($obats as $obat)
                <tr>
                    <td class="border border-slate-300 px-2 py-2">{{ $obat->kode_obat }}</td>
                    <td class="border border-slate-300 px-2 py-2">{{ $obat->nama_obat }}</td>
                    <td class="border border-slate-300 px-2 py-2">
                        @if($obat->suppliers->isEmpty())
                            <span class="text-slate-500">-</span>
                        @else
                            {{ $obat->suppliers->pluck('nama_supplier')->implode(', ') }}
                        @endif
                    </td>
                    <td class="border border-slate-300 px-2 py-2">{{ $obat->relasionalObat->nama_kategori }}</td>
                    <td class="border border-slate-300 px-2 py-2 text-center">{{ $obat->stok }}</td>
                    <td class="border border-slate-300 px-2 py-2 text-right">Rp {{ $obat->harga_beli }}</td>
                    <td class="border border-slate-300 px-2 py-2 text-right">Rp {{ $obat->harga_jual }}</td>
                    <td class="border border-slate-300 px-2 py-2 text-center">{{ $obat->tanggal_kadaluarsa ? $obat->tanggal_kadaluarsa->format('d/m/Y') : '-' }}</td>
                    @if(auth()->user()->isAdmin())
                    <td class="border border-slate-300 px-2 py-2 text-center">
                        <a href="/obat/{{ $obat->id }}/edit" class="text-blue-600 hover:underline">Edit</a>
                        <form action="/obat/{{ $obat->id }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline ml-2" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                        </form>
                    </td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td colspan="{{ auth()->user()->isAdmin() ? 9 : 8 }}" class="border border-slate-300 px-4 py-2 text-center text-slate-500">Belum ada data obat</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
