<?php

namespace App\Http\Controllers;

use App\Models\Matiere;
use Illuminate\Http\Request;

class MatiereController extends Controller
{
    public function index()
    {
        $matieres = Matiere::all();
        return view('admin.list-matieres', compact('matieres'));
    }

    public function create()
    {
        return view('admin.create-matiere');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
        ]);
        Matiere::create($validated);
        return redirect()->route('admin.matieres.index')->with('success', 'Matière créée avec succès.');
    }

    public function edit($id)
    {
        $matiere = Matiere::findOrFail($id);
        return view('admin.edit-matiere', compact('matiere'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
        ]);
        $matiere = Matiere::findOrFail($id);
        $matiere->update($validated);
        return redirect()->route('admin.matieres.index')->with('success', 'Matière mise à jour.');
    }

    public function destroy($id)
    {
        $matiere = Matiere::findOrFail($id);
        $matiere->delete();
        return redirect()->route('admin.matieres.index')->with('success', 'Matière supprimée.');
    }
}
