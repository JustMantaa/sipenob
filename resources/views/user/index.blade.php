@extends('layouts.app')

@section('title', 'Data Petugas')

@section('content')
<div class="bg-white p-6 rounded-lg shadow">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Data Petugas</h1>
        <a href="/user/create" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Tambah User
        </a>
    </div>

    <table class="w-full border-collapse border border-slate-300">
        <thead>
            <tr class="bg-slate-200">
                <th class="border border-slate-300 px-4 py-2">No</th>
                <th class="border border-slate-300 px-4 py-2">Nama</th>
                <th class="border border-slate-300 px-4 py-2">Email</th>
                <th class="border border-slate-300 px-4 py-2">Role</th>
                <th class="border border-slate-300 px-4 py-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr>
                <td class="border border-slate-300 px-4 py-2 text-center">{{ $loop->iteration }}</td>
                <td class="border border-slate-300 px-4 py-2">{{ $user->name }}</td>
                <td class="border border-slate-300 px-4 py-2">{{ $user->email }}</td>
                <td class="border border-slate-300 px-4 py-2 text-center">{{ $user->role }}</td>
                <td class="border border-slate-300 px-4 py-2 text-center">
                    <a href="/user/{{ $user->id }}/edit" class="text-blue-600 hover:underline">Edit</a>
                    <form action="/user/{{ $user->id }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline ml-2" onclick="return confirm('Yakin?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="border border-slate-300 px-4 py-2 text-center text-slate-500">Belum ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
