<?php

namespace App\Services;

use App\Models\User;
use App\Models\Cours;
use App\Models\Matiere;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class PresenceService
{    public function recalculateDroppedStatus(Cours $cours)
    {
        Log::info('Début du recalcul du statut dropped', ['cours_id' => $cours->id]);

        $matiere = $cours->matiere;
        $classe = $cours->classe;

        if (!$matiere || !$classe) {
            Log::warning('Matière ou classe manquante', [
                'matiere_id' => $matiere ? $matiere->id : null,
                'classe_id' => $classe ? $classe->id : null
            ]);
            return;
        }

        // Récupérer tous les étudiants de la classe
        $etudiants = $classe->etudiants;

        foreach ($etudiants as $etudiant) {
            $this->updateEtudiantDroppedStatus($etudiant, $matiere);
        }
    }

    public function updateEtudiantDroppedStatus(User $etudiant, Matiere $matiere)
    {
        // Optimisation : Utiliser une seule requête pour obtenir les statistiques
        $stats = $matiere->cours()
            ->whereHas('presences', function ($query) use ($etudiant) {
                $query->where('etudiant_id', $etudiant->id);
            })
            ->withCount([
                'presences as total_presences' => function ($query) use ($etudiant) {
                    $query->where('etudiant_id', $etudiant->id);
                },
                'presences as total_absences' => function ($query) use ($etudiant) {
                    $query->where('etudiant_id', $etudiant->id)
                          ->where('statut', 'absent');
                }
            ])
            ->first();

        if (!$stats || $stats->total_presences === 0) {
            return;
        }        $tauxAbsence = ($stats->total_absences / $stats->total_presences) * 100;

        Log::info('Calcul du taux d\'absence', [
            'total_cours' => $stats->total_presences,
            'total_absences' => $stats->total_absences,
            'taux' => $tauxAbsence,
            'etudiant_id' => $etudiant->id,
            'matiere_id' => $matiere->id
        ]);

        // Si le taux d'absence dépasse 25%, marquer comme "dropped"
        $dropped = $tauxAbsence >= 25;

        try {
            // Mettre à jour ou créer l'enregistrement
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

                Log::info('Statut dropped mis à jour avec succès', [
                    'etudiant_id' => $etudiant->id,
                    'matiere_id' => $matiere->id,
                    'dropped' => $dropped
                ]);
            } catch (\Exception $e) {
                Log::error('Erreur lors de la mise à jour du statut dropped', [
                    'error' => $e->getMessage(),
                    'etudiant_id' => $etudiant->id,
                    'matiere_id' => $matiere->id
                ]);
            }
        }
    }

