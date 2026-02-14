@extends('layouts.app')

@section('title', 'Edit Supplier')

@section('content')
<div class="bg-white p-6 rounded-lg shadow max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Edit Supplier</h1>

    <form action="/supplier/{{ $supplier->id }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block text-slate-700 font-semibold mb-2">Nama Supplier</label>
            <input type="text" name="nama_supplier" value="{{ old('nama_supplier', $supplier->nama_supplier) }}" required 
                class="w-full px-4 py-2 border border-slate-300 rounded focus:outline-none focus:border-blue-500">
        </div>

        <div class="mb-4">
            <label class="block text-slate-700 font-semibold mb-2">Alamat</label>
            <textarea name="alamat" rows="2" 
                class="w-full px-4 py-2 border border-slate-300 rounded focus:outline-none focus:border-blue-500">{{ old('alamat', $supplier->alamat) }}</textarea>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div class="mb-4">
                <label class="block text-slate-700 font-semibold mb-2">Telepon</label>
                <input type="text" name="telepon" value="{{ old('telepon', $supplier->telepon) }}" 
                    class="w-full px-4 py-2 border border-slate-300 rounded focus:outline-none focus:border-blue-500">
            </div>

            <div class="mb-4">
                <label class="block text-slate-700 font-semibold mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email', $supplier->email) }}" 
                    class="w-full px-4 py-2 border border-slate-300 rounded focus:outline-none focus:border-blue-500">
            </div>
        </div>

        <div class="flex space-x-2">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Update</button>
            <a href="/supplier" class="bg-slate-300 text-slate-700 px-6 py-2 rounded hover:bg-slate-400">Batal</a>
        </div>
    </form>
</div>
@endsection
