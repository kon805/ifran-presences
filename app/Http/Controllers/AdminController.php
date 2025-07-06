<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    /**
     * Affiche le formulaire de création d'utilisateur.
     */
    public function create()
    {
        return view('admin.create-user');
    }

    /**
     * Enregistre un nouvel utilisateur.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string',
            'matricule' => 'required|string|unique:users',
            'photo' => 'nullable|image|max:2048',
        ]);

         if ($request->role === 'admin' && User::where('role', 'admin')->count() >= 2) {
            return back()->withErrors(['role' => 'Il y a déjà 2 administrateurs.']);
        }

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('photos', 'public');
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'matricule' => $validated['matricule'],
            'photo' => $photoPath,
        ]);
        return back()->with('success', 'Utilisateur créé avec succès.');


    }
}
