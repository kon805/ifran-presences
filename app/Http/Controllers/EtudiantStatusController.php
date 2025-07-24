<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Classe;
use App\Models\Matiere;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EtudiantStatusController extends Controller
{
    /**
     * Affiche la liste des matières où l'étudiant est marqué comme "dropped"
     */
    public function showDroppedStatus()
    {
        $etudiant = Auth::user();

        // Vérifier que l'utilisateur est bien un étudiant
        if ($etudiant->role !== 'etudiant') {
            return redirect()->route('dashboard')->with('error', 'Accès non autorisé.');
        }        // Récupérer les IDs des matières où l'étudiant est marqué comme "dropped"
        $matiereIds = DB::table('matiere_user')
            ->where('user_id', $etudiant->id)
            ->where('dropped', true)
            ->pluck('matiere_id');

        // Récupérer les matières avec leurs relations
        $matieresDropped = Matiere::whereIn('id', $matiereIds)
            ->with(['professeurs', 'cours' => function($query) use ($etudiant) {
                $query->with(['presences' => function($query) use ($etudiant) {
                    $query->where('etudiant_id', $etudiant->id);
                }]);
            }])
            ->get();

        // Pour l'instant, on ne récupère pas les classes où l'étudiant est dropped
        // car cette fonctionnalité n'est pas encore implémentée
        $classesDropped = collect();

        return view('etudiant.status.dropped', compact('matieresDropped', 'classesDropped', 'etudiant'));
    }

    /**
     * Affiche pour le coordinateur la liste des étudiants "dropped" dans sa classe
     */
    public function showClasseDroppedEtudiants($classeId)
    {
        $user = Auth::user();

        // Vérifier que l'utilisateur est bien un coordinateur
        if ($user->role !== 'coordinateur' && $user->role !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'Accès non autorisé.');
        }

        $classe = Classe::findOrFail($classeId);

        // Vérifier que le coordinateur est bien responsable de cette classe ou que c'est un admin
        if ($user->role === 'coordinateur' && $classe->coordinateur_id !== $user->id) {
            return redirect()->route('dashboard')->with('error', 'Vous n\'êtes pas autorisé à accéder à cette classe.');
        }

        // Récupérer les étudiants marqués comme "dropped" dans cette classe
        $etudiantsDropped = $classe->etudiants()
            ->wherePivot('dropped', true)
            ->get();

        return view('coordinateur.classes.dropped-etudiants', compact('classe', 'etudiantsDropped'));
    }

    /**
     * Recalcule manuellement le statut "dropped" pour un étudiant dans une matière spécifique
     */
    public function recalculateMatiere($etudiantId, $matiereId)
    {
        $user = Auth::user();

        // Vérifier que l'utilisateur est bien un coordinateur, professeur ou admin
        if (!in_array($user->role, ['coordinateur', 'professeur', 'admin'])) {
            return redirect()->route('dashboard')->with('error', 'Accès non autorisé.');
        }

        $etudiant = User::findOrFail($etudiantId);
        $matiere = Matiere::findOrFail($matiereId);

        // Calculer le nombre total de cours pour cet étudiant dans cette matière
        $totalCours = $matiere->cours()
            ->whereHas('classe.etudiants', function ($query) use ($etudiant) {
                $query->where('users.id', $etudiant->id);
            })
            ->count();

        // Calculer le nombre d'absences
        $totalAbsences = $matiere->cours()
            ->whereHas('presences', function ($query) use ($etudiant) {
                $query->where('etudiant_id', $etudiant->id)
                    ->where('statut', 'absent');
            })
            ->count();

        if ($totalCours > 0) {
            $tauxAbsence = ($totalAbsences / $totalCours) * 100;
            $dropped = $tauxAbsence >= 25;

            // Mettre à jour le statut dropped
            DB::table('matiere_user')
                ->updateOrInsert(
                    [
                        'user_id' => $etudiant->id,
                        'matiere_id' => $matiere->id,
                    ],
                    [
                        'dropped' => $dropped,
                        'updated_at' => now(),
                    ]
                );

            return redirect()->back()->with('success',
                'Le statut de l\'étudiant ' . $etudiant->name . ' a été recalculé pour la matière ' . $matiere->nom .
                '. Taux d\'absence: ' . number_format($tauxAbsence, 2) . '%. Statut: ' . ($dropped ? 'Dropped' : 'OK')
            );
        }

        return redirect()->back()->with('error', 'Aucun cours trouvé pour cet étudiant dans cette matière.');
    }
}
