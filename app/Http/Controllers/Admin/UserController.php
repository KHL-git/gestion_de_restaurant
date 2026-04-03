<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Affiche la liste des utilisateurs
    public function index()
    {
        $users = User::all();
        return view('admin.users', compact('users'));
    }

    // Met à jour le rôle d'un utilisateur
    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:user,admin',
        ]);
        $user->role = $request->role;
        $user->save();
        return redirect()->back()->with('success', 'Rôle mis à jour !');
    }
}