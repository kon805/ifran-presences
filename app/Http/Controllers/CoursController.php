<?php


namespace App\Http\Controllers;

use App\Models\Cours;
use App\Models\Classe;
use App\Models\Matiere;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class CoursController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->role === 'coordinateur') {
            // Récupérer les données pour les filtres
            $classes = Classe::where('coordinateur_id', $user->id)->orderBy('nom')->get();
            $matieres = Matiere::orderBy('nom')->get();
            $professeurs = User::where('role', 'professeur')->orderBy('name')->get();

            // Construire la requête avec filtres
            $query = Cours::whereHas('classe', function($q) use ($user) {
                $q->where('coordinateur_id', $user->id);
            })->with(['classe', 'professeur', 'matiere', 'types']);

            // Appliquer les filtres
            if ($request->filled('classe')) {
                $query->where('classe_id', $request->classe);
            }

            if ($request->filled('matiere')) {
                $query->where('matiere_id', $request->matiere);
            }

            if ($request->filled('professeur')) {
                $query->where('professeur_id', $request->professeur);
            }

            if ($request->filled('date')) {
                $query->whereDate('date', $request->date);
            }

            // Récupérer les résultats
            $cours = $query->orderBy('date')->get();

            return view('coordinateur.cours.index', compact('cours', 'classes', 'matieres', 'professeurs'));
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
        // Temporairement, récupérer toutes les classes jusqu'à ce que la migration soit effectuée
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

    public function destroy($id)
    {
        $cours = Cours::findOrFail($id);
        $cours->delete();
        return redirect()->route('emploi-du-temps.index')->with('success', 'Cours supprimé avec succès.');
    }
}
