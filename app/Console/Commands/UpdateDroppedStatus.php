<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Matiere;
use App\Models\Cours;
use App\Models\Presence;
use Illuminate\Support\Facades\Notification;
use App\Notifications\EtudiantDroppedMatiere;

class UpdateDroppedStatus extends Command
{
    protected $signature = 'matiere:update-dropped';
    protected $description = 'Met à jour le statut dropped des étudiants par matière et notifie coordinateur/professeur';

    public function handle()
    {
        $matieres = Matiere::all();
        foreach ($matieres as $matiere) {
            $etudiants = $matiere->etudiants;
            foreach ($etudiants as $etudiant) {
                $totalCours = Cours::where('matiere_id', $matiere->id)->count();
                $presences = Presence::where('etudiant_id', $etudiant->id)
                    ->whereHas('cours', fn($q) => $q->where('matiere_id', $matiere->id))
                    ->where('statut', 'present')
                    ->count();
                $taux = $totalCours > 0 ? ($presences / $totalCours) * 100 : 0;
                $dropped = $taux < 70;
                $etudiant->matieres()->updateExistingPivot($matiere->id, ['dropped' => $dropped]);
                if ($dropped) {
                    // Notifier coordinateur et professeur
                    $professeurs = $matiere->professeurs ?? collect();
                    $coordinateur = $matiere->classe->coordinateur ?? null;
                    Notification::send($professeurs, new EtudiantDroppedMatiere($etudiant, $matiere));
                    if ($coordinateur) {
                        $coordinateur->notify(new EtudiantDroppedMatiere($etudiant, $matiere));
                    }
                }
            }
        }
        $this->info('Statut dropped mis à jour et notifications envoyées.');
    }
}
