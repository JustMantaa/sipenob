@extends('layouts.app')

@section('title', 'Tambah Obat')

@section('content')
<div class="bg-white p-6 rounded-lg shadow max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Tambah Data Obat</h1>

    <form action="/obat" method="POST">
        @csrf
        <div class="grid grid-cols-2 gap-4">
            <div class="mb-4">
                <label class="block text-slate-700 font-semibold mb-2">Kode Obat</label>
                <input type="text" name="kode_obat" value="{{ old('kode_obat') }}" required 
                    class="w-full px-4 py-2 border border-slate-300 rounded focus:outline-none focus:border-blue-500">
                @error('kode_obat')
                <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-slate-700 font-semibold mb-2">Kategori</label>
                <select name="relasional_obat_id" required 
                    class="w-full px-4 py-2 border border-slate-300 rounded focus:outline-none focus:border-blue-500">
                    <option value="">Pilih Kategori</option>
                    @foreach($kategoris as $kategori)
                    <option value="{{ $kategori->id }}" {{ old('relasional_obat_id') == $kategori->id ? 'selected' : '' }}>
                        {{ $kategori->nama_kategori }}
                    </option>
                    @endforeach
                </select>
                @error('relasional_obat_id')
                <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>
        </div>


        <div class="mb-4">
            <label class="block text-slate-700 font-semibold mb-2">Nama Obat</label>
            <input type="text" name="nama_obat" value="{{ old('nama_obat') }}" required 
                class="w-full px-4 py-2 border border-slate-300 rounded focus:outline-none focus:border-blue-500">
            @error('nama_obat')
            <span class="text-red-600 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-slate-700 font-semibold mb-2">Deskripsi</label>
            <textarea name="deskripsi" rows="2" 
                class="w-full px-4 py-2 border border-slate-300 rounded focus:outline-none focus:border-blue-500">{{ old('deskripsi') }}</textarea>
        </div>

        <div class="grid grid-cols-3 gap-4">
            <div class="mb-4">
                <label class="block text-slate-700 font-semibold mb-2">Stok</label>
                <input type="number" name="stok" value="{{ old('stok', 0) }}" required min="0" 
                    class="w-full px-4 py-2 border border-slate-300 rounded focus:outline-none focus:border-blue-500">
                @error('stok')
                <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-slate-700 font-semibold mb-2">Harga Beli</label>
                <input type="number" name="harga_beli" value="{{ old('harga_beli') }}" required min="0" step="0.01" 
                    class="w-full px-4 py-2 border border-slate-300 rounded focus:outline-none focus:border-blue-500">
                @error('harga_beli')
                <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-slate-700 font-semibold mb-2">Harga Jual</label>
                <input type="number" name="harga_jual" value="{{ old('harga_jual') }}" required min="0" step="0.01" 
                    class="w-full px-4 py-2 border border-slate-300 rounded focus:outline-none focus:border-blue-500">
                @error('harga_jual')
                <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-slate-700 font-semibold mb-2">Tanggal Kadaluarsa</label>
            <input type="date" name="tanggal_kadaluarsa" value="{{ old('tanggal_kadaluarsa') }}" 
                class="w-full px-4 py-2 border border-slate-300 rounded focus:outline-none focus:border-blue-500">
        </div>

        <div class="flex space-x-2">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Simpan</button>
            <a href="/obat" class="bg-slate-300 text-slate-700 px-6 py-2 rounded hover:bg-slate-400">Batal</a>
        </div>
    </form>
</div>
@endsection
