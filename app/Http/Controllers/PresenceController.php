<?php

namespace App\Http\Controllers;

use App\Models\Cours;
use App\Models\Presence;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PresenceController extends Controller
{
    public function index()
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        $cours = Cours::query()
            ->where('professeur_id', $user->id)
            ->orderBy('date', 'desc')
            ->with(['classe', 'matiere'])
            ->paginate(15);

        return view('professeur.presences.index', compact('cours'));
    }

    public function edit(Cours $cours)
    {
        if ($cours->professeur_id !== \Illuminate\Support\Facades\Auth::id()) {
            abort(403);
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

        $request->validate([
            'presences' => 'required|array'
        ]);

        DB::transaction(function () use ($request, $cours) {
            foreach ($request->presences as $etudiant_id => $statut) {
                Presence::updateOrCreate(
                    [
                        'cours_id' => $cours->id,
                        'etudiant_id' => $etudiant_id
                    ],
                    [
                        'statut' => $statut
                    ]
                );
            }
        });

        return redirect()->route('presences.index')->with('success', 'Présences enregistrées.');
    }
}
