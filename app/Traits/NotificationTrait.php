<?php

namespace App\Traits;

use App\Models\User;
use App\Notifications\AbsenceNotification;
use App\Notifications\EtudiantDroppedMatiere;

trait NotificationTrait
{
    protected function notifyAbsence($etudiant, $cours)
    {
        // Notifier le parent
        $parents = User::whereHas('enfants', function($query) use ($etudiant) {
            $query->where('etudiant_id', $etudiant->id);
        })->get();

        foreach ($parents as $parent) {
            if ($parent) {
                $parent->notify(new AbsenceNotification($etudiant, $cours));
            }
        }

        // Notifier le coordinateur
        $coordinateur = User::where('role', 'coordinateur')->first();
        if ($coordinateur) {
            $coordinateur->notify(new AbsenceNotification($etudiant, $cours));
        }
    }

    protected function notifyDropped($etudiant, $matiere)
    {
        // Notifier le parent
        $parents = User::whereHas('enfants', function($query) use ($etudiant) {
            $query->where('etudiant_id', $etudiant->id);
        })->get();

        foreach ($parents as $parent) {
            if ($parent) {
                $parent->notify(new EtudiantDroppedMatiere($etudiant, $matiere));
            }
        }

        // Notifier le coordinateur
        $coordinateur = User::where('role', 'coordinateur')->first();
        if ($coordinateur) {
            $coordinateur->notify(new EtudiantDroppedMatiere($etudiant, $matiere));
        }
    }
}
