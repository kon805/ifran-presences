<?php

namespace App\Http\Controllers;

use App\Models\Cours;
use App\Models\Presence;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class PresenceController extends Controller
{
    public function index()
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        $cours = Cours::query()
            ->where('professeur_id', $user->id)
            ->orderBy('date', 'desc')
            ->with(['classe', 'matiere', 'presences'])
            ->withCount('presences')
            ->paginate(15);

        return view('professeur.presences.index', compact('cours'));
    }

    public function edit(Cours $cours)
    {
        if ($cours->professeur_id !== \Illuminate\Support\Facades\Auth::id()) {
            abort(403);
        }

        // Empêche la modification si des présences existent déjà
        if ($cours->presences()->exists()) {
            abort(403, 'Les présences ont déjà été saisies et ne peuvent plus être modifiées.');
        }

        // Vérifie que le cours est dans une fenêtre de 14 jours après la date
        if (now()->diffInDays($cours->date, false) < -14) {
            abort(403, 'La période de saisie est expirée.');
        }

        $etudiants = $cours->classe->etudiants ?? [];

        return view('professeur.presences.edit', compact('cours', 'etudiants'));
    }

    public function store(Request $request, Cours $cours)
    {
        if ($cours->professeur_id !== \Illuminate\Support\Facades\Auth::id()) {
            abort(403);
        }

        // Empêche la création si des présences existent déjà
        if ($cours->presences()->exists()) {
            abort(403, 'Les présences ont déjà été saisies et ne peuvent plus être modifiées.');
        }

        $request->validate([
            'presences' => 'required|array'
        ]);

        DB::transaction(function () use ($request, $cours) {
            foreach ($request->presences as $etudiant_id => $statut) {
                Presence::create([
                    'cours_id' => $cours->id,
                    'etudiant_id' => $etudiant_id,
                    'statut' => $statut
                ]);
            }
        });

        return redirect()->route('presences.index')->with('success', 'Présences enregistrées.');
    }

    public function indexCoordinateur()
    {
        $user = \Illuminate\Support\Facades\Auth::user();

        $cours = Cours::query()
            ->whereHas('classe', function($query) use ($user) {
                $query->where('coordinateur_id', $user->id);
            })
            ->with(['classe', 'matiere', 'professeur', 'types', 'presences'])
            ->orderBy('date', 'desc')
            ->paginate(15);

        return view('coordinateur.presences.index', compact('cours'));
    }

    public function show(Cours $cours)
    {
        // Charger toutes les relations nécessaires
        $cours->load([
            'classe.etudiants',  // Les étudiants de la classe
            'matiere',           // La matière
            'professeur',        // Le professeur
            'presences'          // Les présences existantes
        ]);

        if (Auth::user()->role === 'coordinateur' &&
            $cours->classe &&
            $cours->classe->coordinateur_id !== Auth::id()) {
            abort(403);
        }


        return view('coordinateur.presences.show', compact('cours'));
    }
}
