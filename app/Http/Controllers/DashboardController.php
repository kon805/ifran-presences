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
        // Statistiques générales
        $stats = [
            'absences_non_justifiees' => Presence::where('statut', 'absent')
                ->whereDoesntHave('justification', function($query) {
                    $query->where('justifiee', true);
                })
                ->count(),

            'absences_justifiees' => Justification::where('justifiee', true)->count(),

            'absences_total' => Presence::where('statut', 'absent')->count(),

            'presences_total' => Presence::count(),

            'etudiants_dropped' => User::where('role', 'etudiant')
                ->whereHas('matieres', function($query) {
                    $query->where('dropped', true);
                })
                ->distinct()
                ->count(),

            'nombre_classes' => Classe::count(),

            'nombre_professeurs' => User::where('role', 'professeur')->count(),

            'total_etudiants' => User::where('role', 'etudiant')->count(),

            'taux_absenteisme' => round(
                (Presence::where('statut', 'absent')->count() / max(Presence::count(), 1)) * 100,
                1
            ),
        ];

        // Statistiques par classe
        $statsParClasse = DB::table('classes')
            ->leftJoin('cours', 'classes.id', '=', 'cours.classe_id')
            ->leftJoin('presences', 'cours.id', '=', 'presences.cours_id')
            ->select(
                'classes.id',
                'classes.nom',
                DB::raw('COUNT(DISTINCT cours.id) as total_cours'),
                DB::raw('COUNT(CASE WHEN presences.statut = "absent" THEN 1 END) as total_absences'),
                DB::raw('COUNT(presences.id) as total_presences')
            )
            ->groupBy('classes.id', 'classes.nom')
            ->get()
            ->map(function($classe) {
                $tauxPresence = $classe->total_presences > 0 
                    ? round((($classe->total_presences - $classe->total_absences) / $classe->total_presences) * 100, 1)
                    : 0;

                // Récupérer le nombre d'étudiants
                $etudiants_count = DB::table('classe_user')
                    ->join('users', 'classe_user.user_id', '=', 'users.id')
                    ->where('classe_user.classe_id', $classe->id)
                    ->where('users.role', 'etudiant')
                    ->count();
                
                return [
                    'nom' => $classe->nom,
                    'etudiants_count' => $etudiants_count,
                    'taux_presence' => $tauxPresence,
                    'couleur' => $this->getColorForRate($tauxPresence)
                ];
            });

        // Statistiques par mois (6 derniers mois)
        $statsParMois = DB::table('presences')
            ->join('cours', 'presences.cours_id', '=', 'cours.id')
            ->where('cours.date', '>=', now()->subMonths(6))
            ->select(
                DB::raw('DATE_FORMAT(cours.date, "%Y-%m") as mois'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN presences.statut = "absent" THEN 1 ELSE 0 END) as absences'),
                DB::raw('SUM(CASE WHEN presences.statut IN ("présent", "retard") THEN 1 ELSE 0 END) as presences')
            )
            ->groupBy('mois')
            ->orderBy('mois', 'desc')
            ->get()
            ->map(function($stat) {
                $taux = $stat->total > 0 ? round(($stat->presences / $stat->total) * 100, 1) : 0;
                return [
                    'mois' => $stat->mois,
                    'taux_presence' => $taux,
                    'total' => $stat->total,
                    'presences' => $stat->presences,
                    'absences' => $stat->absences,
                    'couleur' => $this->getColorForRate($taux)
                ];
            });

        // Top 5 des matières avec le plus d'absences
        $topMatieres = DB::table('presences')
            ->join('cours', 'presences.cours_id', '=', 'cours.id')
            ->join('matieres', 'cours.matiere_id', '=', 'matieres.id')
            ->select(
                'matieres.nom',
                DB::raw('COUNT(*) as total_presences'),
                DB::raw('SUM(CASE WHEN presences.statut = "absent" THEN 1 ELSE 0 END) as total_absences')
            )
            ->groupBy('matieres.id', 'matieres.nom')
            ->orderByRaw('total_absences DESC')
            ->limit(5)
            ->get()
            ->map(function($matiere) {
                $taux = $matiere->total_presences > 0 
                    ? round(($matiere->total_absences / $matiere->total_presences) * 100, 1)
                    : 0;
                return [
                    'nom' => $matiere->nom,
                    'taux_absence' => $taux,
                    'total_absences' => $matiere->total_absences,
                    'total_presences' => $matiere->total_presences,
                    'couleur' => $this->getColorForRate(100 - $taux)
                ];
            });

        return view('coordinateur.dashboard', compact('stats', 'statsParClasse', 'statsParMois', 'topMatieres'));
    }

    /**
     * Retourne une couleur en fonction du taux
     */
    private function getColorForRate($rate)
    {
        if ($rate >= 90) return 'emerald';
        if ($rate >= 80) return 'green';
        if ($rate >= 70) return 'yellow';
        if ($rate >= 60) return 'orange';
        return 'red';
    }
}
