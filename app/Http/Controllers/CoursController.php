<?php

namespace App\Http\Controllers;

use App\Models\Cours;
use App\Models\Classe;
use App\Models\User;
use Illuminate\Http\Request;

class CoursController extends Controller
{
    public function index()
    {
        $cours = Cours::with(['classe', 'professeur'])->orderBy('date')->get();
        return view('coordinateur.cours.index', compact('cours'));
    }

    public function create()
    {
        $classes = Classe::all();
        $professeurs = User::where('role', 'professeur')->get();
        return view('coordinateur.cours.create', compact('classes', 'professeurs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'classe_id' => 'required|exists:classes,id',
            'professeur_id' => 'required|exists:users,id',
            'matiere' => 'required|string',
            'date' => 'required|date',
            'heure_debut' => 'required',
            'heure_fin' => 'required',
        ]);

        Cours::create($request->only('classe_id', 'professeur_id', 'matiere', 'date', 'heure_debut', 'heure_fin'));

        return back()->with('success', 'Cours ajouté.');
    }

    public function edit($id)
    {
        $emploi_du_temps = Cours::findOrFail($id);
        $classes = Classe::all();
        $professeurs = User::where('role', 'professeur')->get();
        return view('coordinateur.cours.edit', [
            'cours' => $emploi_du_temps,
            'classes' => $classes,
            'professeurs' => $professeurs,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'classe_id' => 'required|exists:classes,id',
            'professeur_id' => 'required|exists:users,id',
            'matiere' => 'required|string',
            'date' => 'required|date',
            'heure_debut' => 'required',
            'heure_fin' => 'required',
            'etat' => 'required|in:programmé,annulé,reporté',
        ]);

        $cours = Cours::findOrFail($id);
        $cours->update($request->only('classe_id', 'professeur_id', 'matiere', 'date', 'heure_debut', 'heure_fin', 'etat'));

        return redirect()->route('emploi-du-temps.index')->with('success', 'Cours modifié.');
    }

    public function destroy(Cours $emploi_du_temps)
    {
        $emploi_du_temps->delete();
        return redirect()->route('emploi-du-temps.index')->with('success', 'Cours supprimé.');
    }
}
