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
        $cours = Cours::with(['classe', 'professeur', 'types', 'matiere'])
            ->orderBy('date', 'desc')
            ->paginate(15); // 15 cours par page

        return view('coordinateur.presences.index', compact('cours'));
    }

    public function show(Cours $cours)
    {
        // S'assurer que le cours existe et charger ses relations
        $cours->load(['classe.etudiants', 'professeur', 'presences', 'matiere']);

        if (!$cours->classe) {
            return redirect()->back()->withErrors(['error' => 'Aucune classe n\'est associée à ce cours.']);
        }

        return view('coordinateur.presences.show', compact('cours'));
    }

        public function edit(Cours $cours)
    {
        $cours->load(['classe.etudiants', 'presences']);

        // Vérifier si le cours est de type Workshop ou E-learning
        $typesAutorises = $cours->types->pluck('code')->toArray();
        if (!in_array('workshop', $typesAutorises) && !in_array('e-learning', $typesAutorises)) {
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
            // Mettre à jour les présences
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

            // Calculer et mettre à jour le statut dropped pour chaque étudiant
            $matiere = $cours->matiere;
            $etudiants = $cours->classe->etudiants;

            foreach ($etudiants as $etudiant) {
                // Calculer le taux de présence pour cet étudiant dans cette matière
                $totalCours = $matiere->cours()
                    ->whereHas('classe.etudiants', function ($query) use ($etudiant) {
                        $query->where('users.id', $etudiant->id);
                    })
                    ->count();

                $presences = $matiere->cours()
                    ->whereHas('presences', function ($query) use ($etudiant) {
                        $query->where('etudiant_id', $etudiant->id)
                            ->where('statut', 'present');
                    })
                    ->count();

                // Calculer le pourcentage de présence
                if ($totalCours > 0) {
                    $tauxPresence = ($presences / $totalCours) * 100;

                    // Mettre à jour le statut dropped si le taux est inférieur à 70%
                    DB::table('matiere_user')
                        ->where('user_id', $etudiant->id)
                        ->where('matiere_id', $matiere->id)
                        ->update(['dropped' => $tauxPresence < 70]);
                }
            }
        });

        return redirect()->route('coordinateur.presences.index')->with('success', 'Présences mises à jour et statuts dropped recalculés.');
    }
}
