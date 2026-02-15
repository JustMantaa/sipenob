@extends('layouts.app')

@section('title', 'Data Pelanggan')

@section('content')
<div class="bg-white p-6 rounded-lg shadow">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Data Pelanggan</h1>
        @if(auth()->user()->isAdmin())
        <a href="/pelanggan/create" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Tambah Pelanggan
        </a>
        @endif
    </div>

    <table class="w-full border-collapse border border-slate-300">
        <thead>
            <tr class="bg-slate-200">
                <th class="border border-slate-300 px-4 py-2">No</th>
                <th class="border border-slate-300 px-4 py-2">Nama Pelanggan</th>
                <th class="border border-slate-300 px-4 py-2">Alamat</th>
                <th class="border border-slate-300 px-4 py-2">Telepon</th>
                @if(auth()->user()->isAdmin())
                <th class="border border-slate-300 px-4 py-2">Aksi</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse($pelanggans as $pelanggan)
            <tr>
                <td class="border border-slate-300 px-4 py-2 text-center">{{ $loop->iteration }}</td>
                <td class="border border-slate-300 px-4 py-2">{{ $pelanggan->nama_pelanggan }}</td>
                <td class="border border-slate-300 px-4 py-2">{{ $pelanggan->alamat ?? '-' }}</td>
                <td class="border border-slate-300 px-4 py-2">{{ $pelanggan->telepon ?? '-' }}</td>
                @if(auth()->user()->isAdmin())
                <td class="border border-slate-300 px-4 py-2 text-center">
                    <a href="/pelanggan/{{ $pelanggan->id }}/edit" class="text-blue-600 hover:underline">Edit</a>
                    <form action="/pelanggan/{{ $pelanggan->id }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline ml-2" onclick="return confirm('Yakin?')">Hapus</button>
                    </form>
                </td>
                @endif
            </tr>
            @empty
            <tr>
                <td colspan="{{ auth()->user()->isAdmin() ? 5 : 4 }}" class="border border-slate-300 px-4 py-2 text-center text-slate-500">Belum ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
