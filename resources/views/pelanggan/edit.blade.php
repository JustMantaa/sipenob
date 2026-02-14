@extends('layouts.app')

@section('title', 'Edit Pelanggan')

@section('content')
<div class="bg-white p-6 rounded-lg shadow max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Edit Pelanggan</h1>

    <form action="/pelanggan/{{ $pelanggan->id }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block text-slate-700 font-semibold mb-2">Nama Pelanggan</label>
            <input type="text" name="nama_pelanggan" value="{{ old('nama_pelanggan', $pelanggan->nama_pelanggan) }}" required 
                class="w-full px-4 py-2 border border-slate-300 rounded focus:outline-none focus:border-blue-500">
        </div>

        <div class="mb-4">
            <label class="block text-slate-700 font-semibold mb-2">Alamat</label>
            <textarea name="alamat" rows="2" 
                class="w-full px-4 py-2 border border-slate-300 rounded focus:outline-none focus:border-blue-500">{{ old('alamat', $pelanggan->alamat) }}</textarea>
        </div>

        <div class="mb-4">
            <label class="block text-slate-700 font-semibold mb-2">Telepon</label>
            <input type="text" name="telepon" value="{{ old('telepon', $pelanggan->telepon) }}" 
                class="w-full px-4 py-2 border border-slate-300 rounded focus:outline-none focus:border-blue-500">
        </div>

        <div class="flex space-x-2">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Update</button>
            <a href="/pelanggan" class="bg-slate-300 text-slate-700 px-6 py-2 rounded hover:bg-slate-400">Batal</a>
        </div>
    </form>
</div>
@endsection
