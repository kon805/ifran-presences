<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\User;
use App\Models\Presence;
use App\Models\Justification;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistiques pour le coordinateur
        $stats = [
            'absences_non_justifiees' => Presence::where('statut', 'absent')
                ->whereDoesntHave('justification', function($query) {
                    $query->where('justifiee', true);
                })
                ->count(),

            'absences_justifiees' =>Justification ::where('justifiee', true) ->count(),

            'absences_total' => Presence::where('statut', 'absent')->count(),

            'etudiants_dropped' => User::where('role', 'etudiant')
                ->whereHas('matieres', function($query) {
                    $query->where('dropped', true);
                })
                ->distinct()
                ->count(),

            'nombre_classes' => Classe::count(),

            'nombre_professeurs' => User::where('role', 'professeur')->count(),

            // Statistiques supplémentaires
            'total_etudiants' => User::where('role', 'etudiant')->count(),

            'taux_absenteisme' => round(
                (Presence::where('statut', 'absent')->count() / max(Presence::count(), 1)) * 100,
                1
            ),
        ];

        return view('coordinateur.dashboard',compact ('stats'));
    }
}
