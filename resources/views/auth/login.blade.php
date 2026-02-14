@extends('layouts.app')

@section('title', 'Login - SIPENOB')

@section('content')
<div class="flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg w-96">
        <h2 class="text-2xl font-bold mb-6 text-center text-blue-600">Login SIPENOB</h2>
        
        @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ $errors->first() }}
        </div>
        @endif

        <form action="/login" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-slate-700 font-semibold mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required 
                    class="w-full px-4 py-2 border border-slate-300 rounded focus:outline-none focus:border-blue-500">
            </div>

            <div class="mb-6">
                <label class="block text-slate-700 font-semibold mb-2">Password</label>
                <input type="password" name="password" required 
                    class="w-full px-4 py-2 border border-slate-300 rounded focus:outline-none focus:border-blue-500">
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 font-semibold">
                Login
            </button>
        </form>
    </div>
</div>
@endsection
