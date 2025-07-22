<?php


namespace App\Http\Controllers;

use App\Models\Cours;
use App\Models\Classe;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class CoursController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'coordinateur') {
            $cours = Cours::whereHas('classe', function($q) use ($user) {
                $q->where('coordinateur_id', $user->id);
            })->with(['classe', 'professeur', 'matiere', 'types'])->orderBy('date')->get();
            return view('coordinateur.cours.index', compact('cours'));
        }

        if ($user->role === 'professeur') {
            $cours = Cours::where('professeur_id', $user->id)
                ->with(['classe', 'professeur', 'matiere'])
                ->orderBy('date')
                ->get();
            return view('professeur.presences.index', compact('cours'));
        }

        return abort(403);
    }

    public function create()
    {
        $classes = Classe::all();
        $professeurs = User::where('role', 'professeur')->get();
        $matieres = \App\Models\Matiere::all();
        $types = \App\Models\TypeCours::all();
        return view('coordinateur.cours.create', compact('classes', 'professeurs', 'matieres', 'types'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'classe_id' => 'required|exists:classes,id',
            'professeur_id' => 'required|exists:users,id',
            'matiere_id' => 'required|exists:matieres,id',
            'type_cours_id' => 'required|exists:type_cours,id',
            'date' => 'required|date',
            'heure_debut' => 'required',
            'heure_fin' => 'required',
        ]);

        $cours = \App\Models\Cours::create([
            'classe_id' => $request->classe_id,
            'professeur_id' => $request->professeur_id,
            'matiere_id' => $request->matiere_id,
            'date' => $request->date,
            'heure_debut' => $request->heure_debut,
            'heure_fin' => $request->heure_fin,
            'etat' => 'programmé'
        ]);

        // Associer le type de cours
        $cours->types()->attach($request->type_cours_id);

        return redirect()->route('emploi-du-temps.index')->with('success', 'Le cours a été ajouté avec succès.');
    }

    public function edit($id)
    {
        $emploi_du_temps = Cours::with('types')->findOrFail($id);
        $classes = Classe::all();
        $professeurs = User::where('role', 'professeur')->get();
        $matieres = \App\Models\Matiere::all();
        $typesCours = \App\Models\TypeCours::all();
        return view('coordinateur.cours.edit', [
            'cours' => $emploi_du_temps,
            'classes' => $classes,
            'professeurs' => $professeurs,
            'matieres' => $matieres,
            'typesCours' => $typesCours,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'classe_id' => 'required|exists:classes,id',
            'professeur_id' => 'required|exists:users,id',
            'matiere_id' => 'required|exists:matieres,id',
            'date' => 'required|date',
            'heure_debut' => 'required',
            'heure_fin' => 'required',
            'etat' => 'required|in:programmé,annulé,reporté',
            'type_cours_id' => 'required|exists:type_cours,id',
        ]);

        $cours = Cours::findOrFail($id);
        $cours->update($request->only([
            'classe_id',
            'professeur_id',
            'matiere_id',
            'date',
            'heure_debut',
            'heure_fin',
            'etat'
        ]));

        // Mettre à jour le type de cours
        $cours->types()->sync([$request->type_cours_id]);

        return redirect()->route('emploi-du-temps.index')->with('success', 'Cours modifié avec succès.');
    }

    public function destroy(Cours $emploi_du_temps)
    {
        $emploi_du_temps->delete();
        return redirect()->route('emploi-du-temps.index')->with('success', 'Cours supprimé.');
    }
}
