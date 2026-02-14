@extends('layouts.app')

@section('title', 'Data Supplier')

@section('content')
<div class="bg-white p-6 rounded-lg shadow">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Data Supplier</h1>
        @if(auth()->user()->isAdmin())
        <a href="/supplier/create" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Tambah Supplier
        </a>
        @endif
    </div>

    <table class="w-full border-collapse border border-slate-300">
        <thead>
            <tr class="bg-slate-200">
                <th class="border border-slate-300 px-4 py-2">No</th>
                <th class="border border-slate-300 px-4 py-2">Nama Supplier</th>
                <th class="border border-slate-300 px-4 py-2">Alamat</th>
                <th class="border border-slate-300 px-4 py-2">Telepon</th>
                <th class="border border-slate-300 px-4 py-2">Email</th>
                @if(auth()->user()->isAdmin())
                <th class="border border-slate-300 px-4 py-2">Aksi</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse($suppliers as $supplier)
            <tr>
                <td class="border border-slate-300 px-4 py-2 text-center">{{ $loop->iteration }}</td>
                <td class="border border-slate-300 px-4 py-2">{{ $supplier->nama_supplier }}</td>
                <td class="border border-slate-300 px-4 py-2">{{ $supplier->alamat ?? '-' }}</td>
                <td class="border border-slate-300 px-4 py-2">{{ $supplier->telepon ?? '-' }}</td>
                <td class="border border-slate-300 px-4 py-2">{{ $supplier->email ?? '-' }}</td>
                @if(auth()->user()->isAdmin())
                <td class="border border-slate-300 px-4 py-2 text-center">
                    <a href="/supplier/{{ $supplier->id }}/edit" class="text-blue-600 hover:underline">Edit</a>
                    <form action="/supplier/{{ $supplier->id }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline ml-2" onclick="return confirm('Yakin?')">Hapus</button>
                    </form>
                </td>
                @endif
            </tr>
            @empty
            <tr>
                <td colspan="6" class="border border-slate-300 px-4 py-2 text-center text-slate-500">Belum ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
