@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="bg-white p-6 rounded-lg shadow">
    <h1 class="text-3xl font-bold mb-4">Dashboard SIPENOB</h1>
    <p class="text-slate-600">Selamat datang, <strong>{{ auth()->user()->name }}</strong> ({{ auth()->user()->role }})</p>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
        @if(auth()->user()->isAdmin())
        <div class="bg-blue-100 p-6 rounded-lg">
            <h3 class="text-xl font-semibold text-blue-800">Total Obat</h3>
            <p class="text-3xl font-bold text-blue-600 mt-2">{{ \App\Models\Obat::count() }}</p>
        </div>
        <div class="bg-green-100 p-6 rounded-lg">
            <h3 class="text-xl font-semibold text-green-800">Total Penjualan</h3>
            <p class="text-2xl font-bold text-green-600 mt-2">Rp {{ number_format(\App\Models\Penjualan::sum('total_penjualan'), 0, ',', '.') }}</p>
            <p class="text-sm text-green-700 mt-1">{{ \App\Models\Penjualan::count() }} transaksi</p>
        </div>
        <div class="bg-yellow-100 p-6 rounded-lg">
            <h3 class="text-xl font-semibold text-yellow-800">Total Pembelian</h3>
            <p class="text-2xl font-bold text-yellow-600 mt-2">Rp {{ number_format(\App\Models\Pembelian::sum('total_pembelian'), 0, ',', '.') }}</p>
            <p class="text-sm text-yellow-700 mt-1">{{ \App\Models\Pembelian::count() }} transaksi</p>
        </div>
        @else
        <div class="bg-green-100 p-6 rounded-lg">
            <h3 class="text-xl font-semibold text-green-800">Penjualan Hari Ini</h3>
            <p class="text-2xl font-bold text-green-600 mt-2">Rp {{ number_format(\App\Models\Penjualan::whereDate('tanggal_penjualan', today())->sum('total_penjualan'), 0, ',', '.') }}</p>
            <p class="text-sm text-green-700 mt-1">{{ \App\Models\Penjualan::whereDate('tanggal_penjualan', today())->count() }} transaksi</p>
        </div>
        <div class="bg-blue-100 p-6 rounded-lg">
            <h3 class="text-xl font-semibold text-blue-800">Obat Tersedia</h3>
            <p class="text-3xl font-bold text-blue-600 mt-2">{{ \App\Models\Obat::where('stok', '>', 0)->count() }}</p>
        </div>
        @endif
    </div>
</div>
@endsection
