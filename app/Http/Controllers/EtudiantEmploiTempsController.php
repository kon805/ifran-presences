<?php

namespace App\Http\Controllers;

use App\Models\Cours;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EtudiantEmploiTempsController extends Controller
{
    public function index()
    {
        $etudiant = Auth::user();
        $cours = Cours::whereHas('classe.etudiants', function($query) use ($etudiant) {
                $query->where('users.id', $etudiant->id);
            })
            ->with(['matiere', 'professeur', 'classe', 'types'])
            ->orderBy('date', 'asc')
            ->orderBy('heure_debut', 'asc')
            ->paginate(15);

        return view('etudiant.emploi-du-temps.index', compact('cours'));
    }
}
