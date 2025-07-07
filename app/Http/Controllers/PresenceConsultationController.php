<?php

namespace App\Http\Controllers;



use App\Models\Cours;
use Illuminate\Http\Request;
use App\Models\Presence;
use Illuminate\Support\Facades\DB;

class PresenceConsultationController extends Controller
{
    public function index()
    {
        $cours = Cours::with(['classe', 'professeur'])
            ->orderBy('date', 'desc')
            ->get();

        return view('coordinateur.presences.index', compact('cours'));
    }

    public function show(Cours $cours)
    {
        $cours->load(['classe.etudiants', 'professeur', 'presences']);

        return view('coordinateur.presences.show', compact('cours'));
    }

        public function edit(Cours $cours)
    {
        $cours->load(['classe.etudiants', 'presences']);

        if (!str_contains(strtolower($cours->matiere), 'workshop') && !str_contains(strtolower($cours->matiere), 'e-learning')) {
            abort(403, 'Seuls les cours Workshop ou E-learning sont modifiables par le coordinateur.');
        }

        return view('coordinateur.presences.edit', compact('cours'));
    }

        public function update(Request $request, Cours $cours)
    {
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

        return redirect()->route('coordinateur.presences.index')->with('success', 'Présences mises à jour.');
    }
}
