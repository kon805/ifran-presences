<?php

namespace App\Http\Controllers;

use App\Models\Matiere;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EtudiantMatiereController extends Controller
{
    public function index()
    {
        $etudiant = Auth::user();

        // Récupérer les matières de l'étudiant avec le statut dropped
        $matieres = Matiere::with(['professeurs' => function($query) {
                $query->select('users.id', 'users.name', 'users.email');
            }])
            ->with(['etudiants' => function($query) use ($etudiant) {
                $query->where('users.id', $etudiant->id);
            }])
            ->whereHas('etudiants', function($query) use ($etudiant) {
                $query->where('users.id', $etudiant->id);
            })
            ->orderBy('matieres.nom')
            ->paginate(15);

        return view('etudiant.matieres.index', compact('matieres'));
    }

    public function show($matiere)
    {
        $etudiant = Auth::user();
        $matiere = Matiere::with(['professeurs', 'cours' => function($query) use ($etudiant) {
            $query->whereHas('classe.etudiants', function($q) use ($etudiant) {
                $q->where('users.id', $etudiant->id);
            });
        }])->findOrFail($matiere);

        return view('etudiant.matieres.show', compact('matiere'));
    }
}
