<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ClasseController extends Controller
{

    // Affiche la liste des classes

    public function index()
    {
        $classes = \App\Models\Classe::with('etudiants')->get();
        return view('coordinateur.list-classes', compact('classes'));
    }

    // Affiche les détails d'une classe
    public function show($id)
    {
        $classe = \App\Models\Classe::with('etudiants')->findOrFail($id);
        return view('coordinateur.show-classe', compact('classe'));
    }

    // Affiche le formulaire d'édition d'une classe
    public function edit($id)
    {
        $classe = \App\Models\Classe::with('etudiants')->findOrFail($id);
        $etudiants = \App\Models\User::where('role', 'etudiant')->get();
        return view('coordinateur.edit-classe', compact('classe', 'etudiants'));
    }

    // Met à jour la classe
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'etudiants' => 'required|array',
            'etudiants.*' => 'exists:users,id',
        ]);
        $classe = \App\Models\Classe::findOrFail($id);
        $classe->update(['nom' => $validated['nom']]);

        // Détacher tous les étudiants de cette classe
        \App\Models\User::where('classe_id', $classe->id)->update(['classe_id' => null]);
        // Assigner les nouveaux étudiants sélectionnés
        \App\Models\User::whereIn('id', $validated['etudiants'])->update(['classe_id' => $classe->id]);

        return redirect()->route('coordinateur.classes.index')->with('success', 'Classe modifiée avec succès.');
    }

    // Affiche le formulaire de création de classe avec la liste des étudiants
    public function create()
    {
        $etudiants = User::where('role', 'etudiant')->get();
        return view('coordinateur.create-classe', compact('etudiants'));
    }

    // Enregistre la classe et les étudiants sélectionnés
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'etudiants' => 'required|array',
            'etudiants.*' => 'exists:users,id',
        ]);

        $classe = Classe::create(['nom' => $validated['nom']]);

        // Met à jour la colonne classe_id pour chaque étudiant sélectionné
        \App\Models\User::whereIn('id', $validated['etudiants'])->update(['classe_id' => $classe->id]);

        return redirect()->route('coordinateur.classes.index')->with('success', 'Classe créée avec succès.');
    }

    // Supprime une classe
    public function destroy($id)
    {
        $classe = Classe::findOrFail($id);
        // Détacher tous les étudiants de cette classe
        \App\Models\User::where('classe_id', $classe->id)->update(['classe_id' => null]);
        $classe->delete();
        return redirect()->route('coordinateur.classes.index')->with('success', 'Classe supprimée avec succès.');
    }
}
