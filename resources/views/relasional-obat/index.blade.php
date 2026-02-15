@extends('layouts.app')

@section('title', 'Kategori Obat')

@section('content')
<div class="bg-white p-6 rounded-lg shadow">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Data Kategori Obat</h1>
        @if(auth()->user()->isAdmin())
        <a href="/relasional-obat/create" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Tambah Kategori
        </a>
        @endif
    </div>

    <table class="w-full border-collapse border border-slate-300">
        <thead>
            <tr class="bg-slate-200">
                <th class="border border-slate-300 px-4 py-2">No</th>
                <th class="border border-slate-300 px-4 py-2">Nama Kategori</th>
                <th class="border border-slate-300 px-4 py-2">Keterangan</th>
                @if(auth()->user()->isAdmin())
                <th class="border border-slate-300 px-4 py-2">Aksi</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse($kategoris as $kategori)
            <tr>
                <td class="border border-slate-300 px-4 py-2 text-center">{{ $loop->iteration }}</td>
                <td class="border border-slate-300 px-4 py-2">{{ $kategori->nama_kategori }}</td>
                <td class="border border-slate-300 px-4 py-2">{{ $kategori->keterangan ?? '-' }}</td>
                @if(auth()->user()->isAdmin())
                <td class="border border-slate-300 px-4 py-2 text-center">
                    <a href="/relasional-obat/{{ $kategori->id }}/edit" class="text-blue-600 hover:underline">Edit</a>
                    <form action="/relasional-obat/{{ $kategori->id }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline ml-2" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                    </form>
                </td>
                @endif
            </tr>
            @empty
            <tr>
                <td colspan="{{ auth()->user()->isAdmin() ? 4 : 3 }}" class="border border-slate-300 px-4 py-2 text-center text-slate-500">Belum ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
