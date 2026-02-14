@extends('layouts.app')

@section('title', 'Edit Kategori Obat')

@section('content')
<div class="bg-white p-6 rounded-lg shadow max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Edit Kategori Obat</h1>

    <form action="/relasional-obat/{{ $kategori->id }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block text-slate-700 font-semibold mb-2">Nama Kategori</label>
            <input type="text" name="nama_kategori" value="{{ old('nama_kategori', $kategori->nama_kategori) }}" required 
                class="w-full px-4 py-2 border border-slate-300 rounded focus:outline-none focus:border-blue-500">
            @error('nama_kategori')
            <span class="text-red-600 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-slate-700 font-semibold mb-2">Keterangan</label>
            <textarea name="keterangan" rows="3" 
                class="w-full px-4 py-2 border border-slate-300 rounded focus:outline-none focus:border-blue-500">{{ old('keterangan', $kategori->keterangan) }}</textarea>
            @error('keterangan')
            <span class="text-red-600 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex space-x-2">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Update</button>
            <a href="/relasional-obat" class="bg-slate-300 text-slate-700 px-6 py-2 rounded hover:bg-slate-400">Batal</a>
        </div>
    </form>
</div>
@endsection
