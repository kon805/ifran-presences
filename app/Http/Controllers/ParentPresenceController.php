<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Presence;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;

/**
 * Contrôleur pour la gestion des présences côté parent
 */
class ParentPresenceController extends Controller
{
    /**
     * Affiche le tableau de bord parent avec les statistiques de ses enfants
     */    public function dashboard()
    {
        // Récupérer le parent connecté
        $parent = Auth::user();

        // S'assurer que c'est bien un parent
        if ($parent->role !== 'parent') {
            return redirect()->route('dashboard')->with('error', 'Vous n\'avez pas accès à cette ressource.');
        }

        // Log pour déboguer
        Log::info('Dashboard - Parent connecté: ' . $parent->id . ' - ' . $parent->name);

        try {
            // Solution la plus simple possible : d'abord récupérer les IDs des enfants
            $enfantIds = DB::table('parents')
                ->where('user_id', $parent->id)
                ->pluck('etudiant_id')
                ->toArray();

            Log::info('Dashboard - IDs des enfants trouvés: ' . implode(', ', $enfantIds));

            // Puis récupérer les modèles User correspondants
            $enfants = User::whereIn('id', $enfantIds)
                ->where('role', 'etudiant')
                ->get();

            // Journaliser le résultat de cette première approche
            Log::info('Dashboard - Enfants trouvés via jointure Eloquent: ' . $enfants->count());

            // Approche 2: Si la première approche n'a pas marché, utiliser SQL brut
            if ($enfants->isEmpty()) {
                $parentEtudiants = DB::select('
                    SELECT u.*
                    FROM parents p
                    JOIN users u ON p.etudiant_id = u.id
                    WHERE p.user_id = ? AND u.role = ?
                ', [$parent->id, 'etudiant']);

                if (count($parentEtudiants) > 0) {
                    // Convertir les objets stdClass en modèles User
                    $enfantIds = array_map(function($etudiant) {
                        return $etudiant->id;
                    }, $parentEtudiants);

                    $enfants = User::whereIn('id', $enfantIds)->get();
                    Log::info('Dashboard - Enfants trouvés via SQL brut puis modèles User: ' . $enfants->count());
                }
            }

            // Si aucun enfant n'est trouvé, essayons en dernier recours la relation Eloquent
            if ($enfants->isEmpty()) {
                $enfants = $parent->enfants;
                Log::info('Dashboard - Enfants trouvés via relation Eloquent: ' . $enfants->count());
            }
        } catch (\Exception $e) {
            // En cas d'erreur, log l'exception et créer une collection vide
            Log::error('Exception lors de la récupération des enfants: ' . $e->getMessage());
            $enfants = collect();
        }

        // Log pour déboguer le nombre d'enfants trouvés final
        Log::info('Dashboard - Nombre final d\'enfants trouvés: ' . $enfants->count());

        // DÉBUG CRITIQUE : Afficher tous les enfants trouvés avec détails
        foreach ($enfants as $index => $enfant) {
            Log::info("ENFANT #{$index}: ID = {$enfant->id}, Nom = {$enfant->name}, Role = {$enfant->role}");
            // Vérifier que l'objet est bien un modèle User
            Log::info("Type d'objet: " . get_class($enfant));
        }

        // DÉBUG CRITIQUE : Vérifier si la table parents contient des entrées pour ce parent
        $parentRelationsCount = DB::table('parents')->where('user_id', $parent->id)->count();
        Log::info("Nombre de relations parent-enfant dans la table 'parents' pour le parent {$parent->id}: {$parentRelationsCount}");

        // DÉBUG CRITIQUE : Afficher toutes les relations parents-enfants
        $allParentRelations = DB::table('parents')->get();
        Log::info("Toutes les relations parents-enfants: " . json_encode($allParentRelations));

        // Version super simplifiée - nous allons juste récupérer le nombre d'absences
        // pour chaque enfant sans autres statistiques complexes
        $absences = [];

        foreach ($enfants as $enfant) {
            // Juste compter le nombre d'absences des 30 derniers jours
            $totalAbsences = DB::table('presences')
                ->join('cours', 'presences.cours_id', '=', 'cours.id')
                ->where('presences.etudiant_id', $enfant->id)
                ->where('cours.date', '>=', Carbon::now()->subDays(30))
                ->where('presences.statut', 'absent')
                ->count();

            // Compter les absences récentes (dernière semaine)
            $absencesRecentes = DB::table('presences')
                ->join('cours', 'presences.cours_id', '=', 'cours.id')
                ->where('presences.etudiant_id', $enfant->id)
                ->where('cours.date', '>=', Carbon::now()->subDays(7))
                ->where('presences.statut', 'absent')
                ->count();

            // Récupérer la date de la dernière absence
            $derniereAbsence = DB::table('presences')
                ->join('cours', 'presences.cours_id', '=', 'cours.id')
                ->where('presences.etudiant_id', $enfant->id)
                ->where('presences.statut', 'absent')
                ->orderBy('cours.date', 'desc')
                ->first();

            $absences[$enfant->id] = [
                'total_mois' => $totalAbsences,
                'absences_recentes' => $absencesRecentes,
                'derniere_absence' => $derniereAbsence ? Carbon::parse($derniereAbsence->date)->format('d/m/Y') : null,
                'derniere_absence_raw' => $derniereAbsence ? $derniereAbsence->date : null,
            ];

            Log::info("Absences récupérées pour {$enfant->name} (ID: {$enfant->id}): " .
                     "Total sur 30 jours: {$totalAbsences}, Récentes: {$absencesRecentes}");
        }

        return view('parent.dashboard', compact('enfants', 'absences'));
    }

    /**
     * Affiche la liste des présences des enfants du parent connecté
     * avec les statistiques détaillées par enfant
     */
    public function index()
    {
        // Récupérer le parent connecté
        $parent = Auth::user();

        // Log pour déboguer
        Log::info('Index - Parent connecté: ' . $parent->id . ' - ' . $parent->name);

        // Récupérer les enfants du parent via SQL brut pour être certain de la requête
        $enfantsIds = DB::table('parents')
            ->where('user_id', $parent->id)
            ->pluck('etudiant_id')
            ->toArray();

        Log::info('Index - Enfants IDs trouvés: ' . implode(', ', $enfantsIds));

        // Récupérer les étudiants et charger leurs présences
        $enfants = User::whereIn('id', $enfantsIds)
            ->where('role', 'etudiant')
            ->get();

        // Précharger les présences et les informations associées pour chaque enfant
        foreach ($enfants as $enfant) {
            $enfant->load(['presences' => function($query) {
                $query->with(['cours.matiere', 'justification'])
                      ->whereHas('cours', function($q) {
                          $q->where('date', '>=', Carbon::now()->subMonths(3));
                      })
                      ->orderBy('created_at', 'desc');
            }]);
        }

        // Log pour déboguer le nombre d'enfants trouvés
        Log::info('Index - Nombre d\'enfants trouvés: ' . $enfants->count());

        // Tableau pour stocker les statistiques de chaque enfant
        $statistiques = [];

        // Pour chaque enfant, calculer les statistiques de présence
        foreach ($enfants as $enfant) {
            // Récupérer le nombre total de présences enregistrées
            $totalPresences = DB::table('presences')
                ->join('cours', 'presences.cours_id', '=', 'cours.id')
                ->where('presences.etudiant_id', $enfant->id)
                ->where('cours.date', '>=', Carbon::now()->subMonths(3))
                ->count();

            // Récupérer le nombre d'absences justifiées
            $absencesJustifiees = DB::table('presences')
                ->join('cours', 'presences.cours_id', '=', 'cours.id')
                ->leftJoin('justifications', 'presences.id', '=', 'justifications.presence_id')
                ->where('presences.etudiant_id', $enfant->id)
                ->where('cours.date', '>=', Carbon::now()->subMonths(3))
                ->where('presences.statut', 'absent')
                ->where('justifications.justifiee', true)
                ->count();

            // Récupérer le nombre d'absences non justifiées
            $absencesNonJustifiees = DB::table('presences')
                ->join('cours', 'presences.cours_id', '=', 'cours.id')
                ->leftJoin('justifications', 'presences.id', '=', 'justifications.presence_id')
                ->where('presences.etudiant_id', $enfant->id)
                ->where('cours.date', '>=', Carbon::now()->subMonths(3))
                ->where('presences.statut', 'absent')
                ->where(function ($query) {
                    $query->whereNull('justifications.justifiee')
                          ->orWhere('justifications.justifiee', false);
                })
                ->count();

            // Récupérer le nombre de présences effectives (statut = présent ou retard)
            $presencesEffectives = DB::table('presences')
                ->join('cours', 'presences.cours_id', '=', 'cours.id')
                ->where('presences.etudiant_id', $enfant->id)
                ->where('cours.date', '>=', Carbon::now()->subMonths(3))
                ->whereIn('presences.statut', ['présent', 'retard'])
                ->count();

            // Calculer le taux de présence
            $tauxPresence = $totalPresences > 0 ? round(($presencesEffectives / $totalPresences) * 100, 2) : 0;

            // Analyser les présences par semaine
            $presencesParSemaine = [];
            if ($enfant->presences->count() > 0) {
                $presencesParSemaine = $enfant->presences
                    ->groupBy(function($presence) {
                        return Carbon::parse($presence->cours->date)->startOfWeek()->format('Y-m-d');
                    })
                    ->map(function($presencesSemaine) {
                        $total = $presencesSemaine->count();
                        $absents = $presencesSemaine->filter(function($presence) {
                            return $presence->statut === 'absent';
                        })->count();
                        $presents = $presencesSemaine->filter(function($presence) {
                            return $presence->statut === 'présent' || $presence->statut === 'retard';
                        })->count();

                        return [
                            'total' => $total,
                            'presents' => $presents,
                            'taux' => $total > 0 ? round(($presents / $total) * 100, 2) : 0
                        ];
                    });
            }

            // Analyser les présences par matière
            $presencesParMatiere = [];
            if ($enfant->presences->count() > 0) {
                $presencesParMatiere = $enfant->presences
                    ->groupBy(function($presence) {
                        return $presence->cours->matiere_id ?? 0;
                    })
                    ->map(function($presencesMatiere) {
                        if ($presencesMatiere->isEmpty() || !$presencesMatiere->first()->cours || !$presencesMatiere->first()->cours->matiere) {
                            return [
                                'nom' => 'Matière inconnue',
                                'total' => 0,
                                'presents' => 0,
                                'absents' => 0,
                                'taux' => 0,
                                'couleur' => 'bg-gray-500'
                            ];
                        }

                        $matiere = $presencesMatiere->first()->cours->matiere;
                        $total = $presencesMatiere->count();
                        $absents = $presencesMatiere->filter(function($presence) {
                            return $presence->statut === 'absent';
                        })->count();
                        $presents = $presencesMatiere->filter(function($presence) {
                            return $presence->statut === 'présent' || $presence->statut === 'retard';
                        })->count();

                        $taux = $total > 0 ? round(($presents / $total) * 100, 2) : 0;

                        return [
                            'nom' => $matiere->nom,
                            'total' => $total,
                            'presents' => $presents,
                            'absents' => $absents,
                            'taux' => $taux,
                            'couleur' => $this->getColorForAttendanceRate($taux)
                        ];
                    });
            }

            // Stocker les statistiques de l'enfant
            $statistiques[$enfant->id] = [
                'total_presences' => $totalPresences,
                'presences_effectives' => $presencesEffectives,
                'absences_justifiees' => $absencesJustifiees,
                'absences_non_justifiees' => $absencesNonJustifiees,
                'presences_par_semaine' => $presencesParSemaine,
                'presences_par_matiere' => $presencesParMatiere,
                'taux_presence_global' => $tauxPresence
            ];

            // Log des statistiques calculées pour chaque enfant
            Log::info("Index - Statistiques de {$enfant->name} (ID: {$enfant->id}): " .
                      "Total: {$totalPresences}, " .
                      "Présences: {$presencesEffectives}, " .
                      "Absences J: {$absencesJustifiees}, " .
                      "Absences NJ: {$absencesNonJustifiees}");
        }

        // Retourner la vue avec les données
        return view('parent.presences.index', compact('enfants', 'statistiques'));
    }

    /**
     * Affiche les détails des absences pour un étudiant spécifique
     *
     * @param User $etudiant L'étudiant dont on veut voir les absences
     * @return \Illuminate\View\View
     */
    public function showEtudiantAbsences(User $etudiant)
    {
        // Vérifier que l'utilisateur actuel est bien un parent de cet étudiant
        $parent = Auth::user();

        // Vérification avec SQL brut pour être sûr
        $estParent = DB::table('parents')
            ->where('user_id', $parent->id)
            ->where('etudiant_id', $etudiant->id)
            ->exists();

        if (!$estParent) {
            abort(403, "Vous n'avez pas l'autorisation de voir les absences de cet étudiant.");
        }

        // Log de débogage
        Log::info("Affichage des absences pour l'étudiant {$etudiant->name} (ID: {$etudiant->id}) par le parent {$parent->name} (ID: {$parent->id})");

        // Récupérer le nombre total de présences enregistrées
        $totalPresences = DB::table('presences')
            ->join('cours', 'presences.cours_id', '=', 'cours.id')
            ->where('presences.etudiant_id', $etudiant->id)
            ->where('cours.date', '>=', Carbon::now()->subMonths(3))
            ->count();

        // Récupérer le nombre d'absences justifiées
        $absencesJustifiees = DB::table('presences')
            ->join('cours', 'presences.cours_id', '=', 'cours.id')
            ->leftJoin('justifications', 'presences.id', '=', 'justifications.presence_id')
            ->where('presences.etudiant_id', $etudiant->id)
            ->where('cours.date', '>=', Carbon::now()->subMonths(3))
            ->where('presences.statut', 'absent')
            ->where('justifications.justifiee', true)
            ->count();

        // Récupérer le nombre d'absences non justifiées
        $absencesNonJustifiees = DB::table('presences')
            ->join('cours', 'presences.cours_id', '=', 'cours.id')
            ->leftJoin('justifications', 'presences.id', '=', 'justifications.presence_id')
            ->where('presences.etudiant_id', $etudiant->id)
            ->where('cours.date', '>=', Carbon::now()->subMonths(3))
            ->where('presences.statut', 'absent')
            ->where(function ($query) {
                $query->whereNull('justifications.justifiee')
                      ->orWhere('justifications.justifiee', false);
            })
            ->count();

        // Récupérer le nombre de présences effectives (statut = présent ou retard)
        $totalPresentsEffectifs = DB::table('presences')
            ->join('cours', 'presences.cours_id', '=', 'cours.id')
            ->where('presences.etudiant_id', $etudiant->id)
            ->where('cours.date', '>=', Carbon::now()->subMonths(3))
            ->whereIn('presences.statut', ['présent', 'retard'])
            ->count();

        // Calculer le taux de présence
        $tauxPresenceGlobal = $totalPresences > 0 ? round(($totalPresentsEffectifs / $totalPresences) * 100, 2) : 0;

        // Récupérer toutes les présences pour l'étudiant (avec eager loading)
        $presences = Presence::with(['cours.matiere', 'cours.classe', 'justification'])
            ->where('etudiant_id', $etudiant->id)
            ->whereHas('cours', function($query) {
                $query->where('date', '>=', Carbon::now()->subMonths(3));
            })
            ->get();

        // Analyser les présences par matière
        $presencesParMatiere = [];

        $matiereStats = DB::table('presences')
            ->join('cours', 'presences.cours_id', '=', 'cours.id')
            ->join('matieres', 'cours.matiere_id', '=', 'matieres.id')
            ->where('presences.etudiant_id', $etudiant->id)
            ->where('cours.date', '>=', Carbon::now()->subMonths(3))
            ->select(
                'matieres.id',
                'matieres.nom',
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN presences.statut = "absent" THEN 1 ELSE 0 END) as absents'),
                DB::raw('SUM(CASE WHEN presences.statut IN ("présent", "retard") THEN 1 ELSE 0 END) as presents')
            )
            ->groupBy('matieres.id', 'matieres.nom')
            ->get();

        foreach ($matiereStats as $stat) {
            $taux = $stat->total > 0 ? round(($stat->presents / $stat->total) * 100, 2) : 0;
            $presencesParMatiere[$stat->id] = [
                'nom' => $stat->nom,
                'total' => $stat->total,
                'presents' => $stat->presents,
                'absents' => $stat->absents,
                'taux' => $taux,
                'couleur' => $this->getColorForAttendanceRate($taux)
            ];
        }

        // Ne récupérer que les absences
        $absences = Presence::with(['cours.matiere', 'cours.classe', 'justification'])
            ->where('etudiant_id', $etudiant->id)
            ->where('statut', 'absent')
            ->whereHas('cours', function($query) {
                $query->where('date', '>=', Carbon::now()->subMonths(3));
            })
            ->orderBy('created_at', 'desc')
            ->get();

        Log::info("Statistiques pour {$etudiant->name}: Total présences: $totalPresences, Absences justifiées: $absencesJustifiees, Absences non justifiées: $absencesNonJustifiees");

        return view('parent.presences.etudiant', compact(
            'etudiant',
            'absences',
            'absencesJustifiees',
            'absencesNonJustifiees',
            'tauxPresenceGlobal',
            'presencesParMatiere'
        ));
    }

    /**
     * Retourne les étudiants assignés au parent connecté (format JSON)
     * Utilisé pour le menu déroulant dans la sidebar
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAssignedStudents()
    {
        try {
            // Vérifier que l'utilisateur est connecté et est un parent
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Utilisateur non connecté',
                    'error' => 'auth_required'
                ], 401);
            }

            $parent = Auth::user();

            if ($parent->role !== 'parent') {
                return response()->json([
                    'success' => false,
                    'message' => 'L\'utilisateur n\'est pas un parent',
                    'error' => 'not_parent'
                ], 403);
            }

            // Debug: afficher l'ID du parent
            Log::info('Parent ID: ' . $parent->id);

            // Vérifier d'abord si la table de relation existe
            if (!Schema::hasTable('parents')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Table de relation parents inexistante',
                    'error' => 'table_missing'
                ], 500);
            }

            // Utilisons la même requête SQL brute qui fonctionne dans dashboard
            $parentEtudiants = DB::select('
                SELECT e.*
                FROM parents p
                JOIN users e ON p.etudiant_id = e.id
                WHERE p.user_id = ? AND e.role = ?
            ', [$parent->id, 'etudiant']);

            // Debug: afficher les résultats bruts
            Log::info('API - Nombre d\'étudiants trouvés via SQL brut: ' . count($parentEtudiants));

            // Récupérer les modèles User complets si des étudiants ont été trouvés
            if (count($parentEtudiants) > 0) {
                $enfantIds = array_map(function($etudiant) {
                    return $etudiant->id;
                }, $parentEtudiants);

                $etudiants = User::whereIn('id', $enfantIds)
                    ->select('id', 'name', 'matricule', 'profile_photo_url')
                    ->get();

                Log::info('API - Après conversion en modèles: ' . $etudiants->count() . ' étudiants');
            } else {
                // Fallback à la méthode originale
                $etudiants = User::join('parents', 'users.id', '=', 'parents.etudiant_id')
                    ->where('parents.user_id', $parent->id)
                    ->where('users.role', 'etudiant')
                    ->select('users.id', 'users.name', 'users.matricule', 'users.profile_photo_url')
                    ->get();

                Log::info('API - Fallback: ' . $etudiants->count() . ' étudiants via jointure');
            }

            // Si nous n'avons pas d'étudiants, essayons de trouver une solution
            if ($etudiants->isEmpty()) {
                Log::info('Aucun étudiant trouvé pour le parent ' . $parent->id . ' via la relation. Tentatives alternatives...');

                try {
                    // 1. Essayer avec une requête SQL brute
                    Log::info('Tentative avec requête SQL brute');
                    $rawEtudiants = DB::select(
                        'SELECT u.id, u.name, u.matricule, u.profile_photo_url
                        FROM users u
                        JOIN parents p ON u.id = p.etudiant_id
                        WHERE p.user_id = ? AND u.role = ?',
                        [$parent->id, 'etudiant']
                    );

                    if (!empty($rawEtudiants)) {
                        $etudiants = collect($rawEtudiants);
                        Log::info('Étudiants trouvés avec requête brute: ' . count($rawEtudiants));
                    } else {
                        Log::warning('Aucun étudiant trouvé même avec la requête brute');

                        // 2. Pour les besoins du développement, montrons tous les étudiants
                        // À SUPPRIMER EN PRODUCTION
                        $allEtudiants = User::where('role', 'etudiant')
                            ->select('id', 'name', 'matricule', 'profile_photo_url')
                            ->limit(5) // Limiter pour ne pas exposer tous les étudiants
                            ->get();

                        if ($allEtudiants->isNotEmpty()) {
                            $etudiants = $allEtudiants;
                            Log::info('Utilisation des étudiants de secours pour le développement');
                        }
                    }
                } catch (\Exception $sqlEx) {
                    Log::error('Erreur lors de la requête SQL alternative: ' . $sqlEx->getMessage());
                    // Ne pas lancer d'exception, laisser $etudiants vide
                }
            }

            return response()->json([
                'success' => true,
                'etudiants' => $etudiants,
                'parent_id' => $parent->id
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur dans getAssignedStudents: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue: ' . $e->getMessage(),
                'error' => 'exception',
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    /**
     * Renvoie une couleur CSS en fonction du taux de présence
     *
     * @param float $rate Taux de présence en pourcentage (0-100)
     * @return string Code couleur au format CSS (ex: 'bg-red-500')
     */
    private function getColorForAttendanceRate($rate)
    {
        if ($rate >= 90) {
            return 'bg-green-500';
        } elseif ($rate >= 80) {
            return 'bg-green-400';
        } elseif ($rate >= 70) {
            return 'bg-yellow-400';
        } elseif ($rate >= 60) {
            return 'bg-yellow-500';
        } elseif ($rate >= 50) {
            return 'bg-orange-500';
        } else {
            return 'bg-red-500';
        }
    }
}
