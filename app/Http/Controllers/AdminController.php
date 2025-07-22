<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
        /**
     * Affiche la liste des utilisateurs.
     */
    public function index()
    {
        $users = User::with('classe')->paginate(20);
        return view('admin.list-users', compact('users'));
    }



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

    /**
     * Affiche le formulaire d'édition d'un utilisateur.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.edit-user', compact('user'));
    }

    /**
     * Met à jour un utilisateur existant.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|string',
            'matricule' => 'required|string|unique:users,matricule,' . $user->id,
            'photo' => 'nullable|image|max:2048',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if ($request->role === 'admin' && $user->role !== 'admin' && User::where('role', 'admin')->count() >= 2) {
            return back()->withErrors(['role' => 'Il y a déjà 2 administrateurs.']);
        }

        if ($request->hasFile('photo')) {
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }
            $user->photo = $request->file('photo')->store('photos', 'public');
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];
        $user->matricule = $validated['matricule'];
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        $user->save();

        return redirect()->route('admin.users.edit', $user->id)->with('success', 'Utilisateur mis à jour avec succès.');
    }


      public function destroy($id)
    {
        $user = User::findOrFail($id);
        // Ne pas supprimer le dernier admin
        if ($user->role === 'admin' && User::where('role', 'admin')->count() <= 1) {
            return back()->withErrors(['role' => 'Impossible de supprimer le dernier administrateur.']);
        }
        if ($user->photo) {
            Storage::disk('public')->delete($user->photo);
        }
        $user->delete();
        return redirect()->route('admin.dashboard')->with('success', 'Utilisateur supprimé avec succès.');
    }
}
