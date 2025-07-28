<?php

namespace App\Traits;

use App\Models\Notification;
use App\Models\User;

trait NotificationTrait
{
    protected function notifyAbsence($etudiant, $cours)
    {
        // Notifier le parent
        $parents = User::whereHas('enfants', function($query) use ($etudiant) {
            $query->where('etudiant_id', $etudiant->id);
        })->get();

        foreach ($parents as $parent) {
            Notification::create([
                'type' => 'absence',
                'etudiant_id' => $etudiant->id,
                'destinataire_id' => $parent->id,
                'message' => "{$etudiant->name} était absent au cours de {$cours->matiere->nom}",
                'details' => [
                    'cours_id' => $cours->id,
                    'date' => $cours->date,
                    'matiere' => $cours->matiere->nom,
                ]
            ]);
        }

        // Notifier le coordinateur
        $coordinateur = User::where('role', 'coordinateur')->first();
        if ($coordinateur) {
            Notification::create([
                'type' => 'absence',
                'etudiant_id' => $etudiant->id,
                'destinataire_id' => $coordinateur->id,
                'message' => "{$etudiant->name} était absent au cours de {$cours->matiere->nom}",
                'details' => [
                    'cours_id' => $cours->id,
                    'date' => $cours->date,
                    'matiere' => $cours->matiere->nom,
                ]
            ]);
        }
    }

    protected function notifyDropped($etudiant, $matiere)
    {
        // Notifier le parent
        $parents = User::whereHas('enfants', function($query) use ($etudiant) {
            $query->where('etudiant_id', $etudiant->id);
        })->get();

        foreach ($parents as $parent) {
            Notification::create([
                'type' => 'dropped',
                'etudiant_id' => $etudiant->id,
                'destinataire_id' => $parent->id,
                'message' => "{$etudiant->name} a été dropped du cours de {$matiere->nom}",
                'details' => [
                    'matiere_id' => $matiere->id,
                    'matiere_nom' => $matiere->nom,
                ]
            ]);
        }

        // Notifier le coordinateur
        $coordinateur = User::where('role', 'coordinateur')->first();
        if ($coordinateur) {
            Notification::create([
                'type' => 'dropped',
                'etudiant_id' => $etudiant->id,
                'destinataire_id' => $coordinateur->id,
                'message' => "{$etudiant->name} a été dropped du cours de {$matiere->nom}",
                'details' => [
                    'matiere_id' => $matiere->id,
                    'matiere_nom' => $matiere->nom,
                ]
            ]);
        }
    }
}
