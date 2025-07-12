<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Matiere;

class LoggingService
{
    public static function logStatusChange(User $etudiant, Matiere $matiere, $oldStatus, $newStatus, $tauxPresence)
    {
        Log::channel('status_changes')->info('Changement de statut', [
            'etudiant_id' => $etudiant->id,
            'etudiant_name' => $etudiant->name,
            'matiere_id' => $matiere->id,
            'matiere_nom' => $matiere->nom,
            'ancien_statut' => $oldStatus,
            'nouveau_statut' => $newStatus,
            'taux_presence' => $tauxPresence
        ]);
    }

    public static function logJustification(User $etudiant, $presence, $coordinateur, $justified)
    {
        Log::channel('justifications')->info('Nouvelle justification', [
            'etudiant_id' => $etudiant->id,
            'etudiant_name' => $etudiant->name,
            'coordinateur_id' => $coordinateur->id,
            'coordinateur_name' => $coordinateur->name,
            'cours_id' => $presence->cours_id,
            'matiere' => $presence->cours->matiere->nom,
            'date_cours' => $presence->cours->date,
            'justifiee' => $justified
        ]);
    }
}
