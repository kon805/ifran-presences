<?php

namespace App\Http\Controllers;

use App\Models\Presence;
use App\Models\Justification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JustificationController extends Controller
{
    public function index()
    {
        $absences = Presence::whereIn('statut', ['absent', 'retard'])
            ->whereDoesntHave('justification')
            ->whereHas('cours', function ($query) {
                $query->whereHas('classe', function ($q) {
                    $q->where('coordinateur_id', Auth::id());
                });
            })
            ->with(['etudiant', 'cours.classe', 'cours.matiere', 'justification'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('coordinateur.justifications.index', compact('absences'));
    }

    public function create($presence_id)
    {
        $presence = Presence::findOrFail($presence_id);

        if ($presence->cours->classe->coordinateur_id !== Auth::id()) {
            abort(403, "Vous n'êtes pas autorisé à justifier cette absence.");
        }

        return view('coordinateur.justifications.create', compact('presence'));
    }

    public function store(Request $request, $presence_id)
    {
        $request->validate([
            'motif' => 'required|string|max:1000',
        ]);

        $presence = Presence::findOrFail($presence_id);

        if ($presence->cours->classe->coordinateur_id !== Auth::id()) {
            abort(403, "Vous n'êtes pas autorisé à justifier cette absence.");
        }

        Justification::create([
            'presence_id' => $presence_id,
            'coordinateur_id' => Auth::id(),
            'motif' => $request->motif,
            'justifiee' => true,
        ]);

        return redirect()->route('coordinateur.justifications.index')
            ->with('success', 'Absence justifiée avec succès.');
    }
}
