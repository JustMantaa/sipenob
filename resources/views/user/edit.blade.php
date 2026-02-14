@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="bg-white p-6 rounded-lg shadow max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Edit User Petugas</h1>

    <form action="/user/{{ $user->id }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block text-slate-700 font-semibold mb-2">Nama</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                class="w-full px-4 py-2 border {{ $errors->has('name') ? 'border-red-500' : 'border-slate-300' }} rounded focus:outline-none focus:border-blue-500">
            @error('name')
            <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-slate-700 font-semibold mb-2">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                class="w-full px-4 py-2 border {{ $errors->has('email') ? 'border-red-500' : 'border-slate-300' }} rounded focus:outline-none focus:border-blue-500">
            @error('email')
            <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div class="mb-4">
                <label class="block text-slate-700 font-semibold mb-2">Password Baru (opsional)</label>
                <input type="password" name="password"
                    class="w-full px-4 py-2 border {{ $errors->has('password') ? 'border-red-500' : 'border-slate-300' }} rounded focus:outline-none focus:border-blue-500">
                @error('password')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-slate-700 font-semibold mb-2">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation"
                    class="w-full px-4 py-2 border {{ $errors->has('password_confirmation') ? 'border-red-500' : 'border-slate-300' }} rounded focus:outline-none focus:border-blue-500">
                @error('password_confirmation')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="flex space-x-2">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Update</button>
            <a href="/user" class="bg-slate-300 text-slate-700 px-6 py-2 rounded hover:bg-slate-400">Batal</a>
        </div>
    </form>
</div>
@endsection
