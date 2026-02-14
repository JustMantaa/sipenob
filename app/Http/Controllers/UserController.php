<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'petugas')
            ->orderBy('name')
            ->get();

        return view('user.index', compact('users'));
    }

    public function create()
    {
        return view('user.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => 'petugas',
            'password' => Hash::make($validated['password']),
        ]);

        return redirect('/user')->with('success', 'Akun petugas berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = User::where('role', 'petugas')->findOrFail($id);
        return view('user.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::where('role', 'petugas')->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $payload = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        if (!empty($validated['password'])) {
            $payload['password'] = Hash::make($validated['password']);
        }

        $user->update($payload);

        return redirect('/user')->with('success', 'Akun petugas berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = User::where('role', 'petugas')->findOrFail($id);
        $user->delete();

        return redirect('/user')->with('success', 'Akun petugas berhasil dihapus.');
    }
}
