<?php

namespace App\Http\Controllers\Leader;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $page = "user";

        $Id_User = session('Id_User');
        $user = User::find($Id_User);

        $users = User::all();
        return view('leaders.users.index', compact('page', 'user', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'Username_User' => 'required|unique:users,Username_User|max:20',
            'Name_User'     => 'required|string|max:100',
            'Password_User' => 'required'
        ]);

        User::create($validated);

        return redirect()->route('leader.users.index')->with('success', 'Data user berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'Username_User' => 'required|max:20|unique:users,Username_User,' . $id . ',Id_User',
            'Name_User'     => 'required|string|max:100',
            'Password_User' => 'required'
        ]);

        $user = User::findOrFail($id);
        $user->update($validated);

        return redirect()->route('leader.users.index')->with('success', 'Data user berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('leader.users.index')->with('success', 'Data user berhasil dihapus.');
    }
}
