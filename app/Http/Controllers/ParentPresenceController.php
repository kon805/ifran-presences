<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Presence;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

/**
 * Contrôleur pour la gestion des présences côté parent
 */
class ParentPresenceController extends Controller
{
    /**
     * Affiche la liste des présences des enfants du parent connecté
     * avec les statistiques détaillées par enfant
     */
    public function index()
    {
        // Récupérer le parent connecté
        $parent = Auth::user();

        // Récupérer les enfants du parent avec leurs présences des 3 derniers mois
        $enfants = User::whereHas('parents', function($query) use ($parent) {
                $query->where('user_id', $parent->id);
            })
            ->where('role', 'etudiant')
            ->with(['presences' => function($requete) {
                $requete->with(['cours.matiere', 'justification'])
                    ->whereHas('cours', function($sousRequete) {
                        $sousRequete->where('date', '>=', Carbon::now()->subMonths(3));
                    })
                    ->orderBy('created_at', 'desc');
            }])
            ->get();

        // Tableau pour stocker les statistiques de chaque enfant
        $statistiques = [];

        // Pour chaque enfant, calculer les statistiques de présence
        foreach ($enfants as $enfant) {
            // Récupérer l'historique des présences
            $historiquePresences = $enfant->presences;

            // Compter les absences justifiées (avec justificatif validé)
            $absencesJustifiees = $historiquePresences->filter(function($presence) {
                return !$presence->present && $presence->justification && $presence->justification->justifiee;
            })->count();

            // Compter les absences non justifiées (sans justificatif ou non validé)
            $absencesNonJustifiees = $historiquePresences->filter(function($presence) {
                return !$presence->present && (!$presence->justification || !$presence->justification->justifiee);
            })->count();

            // Analyser les présences par semaine
            $presencesParSemaine = $historiquePresences
                ->groupBy(function($presence) {
                    return Carbon::parse($presence->cours->date)->startOfWeek()->format('Y-m-d');
                })
                ->map(function($presencesSemaine) {
                    $total = $presencesSemaine->count();
                    $absents = $presencesSemaine->filter(function($presence) {
                        return !$presence->present;
                    })->count();
                    $presents = $total - $absents;

                    return [
                        'total' => $total,
                        'presents' => $presents,
                        'taux' => $total > 0 ? round(($presents / $total) * 100, 2) : 0
                    ];
                });

            // Calculer les totaux pour l'enfant
            $totalPresences = $historiquePresences->count();
            $totalAbsences = $absencesJustifiees + $absencesNonJustifiees;
            $totalPresentsEffectifs = $totalPresences - $totalAbsences;

            // Stocker les statistiques de l'enfant
            $statistiques[$enfant->id] = [
                'total_presences' => $totalPresences,
                'presences_effectives' => $totalPresentsEffectifs,
                'absences_justifiees' => $absencesJustifiees,
                'absences_non_justifiees' => $absencesNonJustifiees,
                'presences_par_semaine' => $presencesParSemaine,
                'taux_presence_global' => $totalPresences > 0 ? round(($totalPresentsEffectifs / $totalPresences) * 100, 2) : 0
            ];
        }

        // Retourner la vue avec les données
        return view('parent.presences.index', compact('enfants', 'statistiques'));
    }
}
