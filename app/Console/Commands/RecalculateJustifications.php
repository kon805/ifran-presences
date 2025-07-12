<?php

namespace App\Console\Commands;

use App\Models\Presence;
use App\Models\Justification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RecalculateJustifications extends Command
{
    protected $signature = 'presences:recalculate-justifications';
    protected $description = 'Vérifie et crée les justifications pour toutes les absences';

    public function handle()
    {
        $this->info('Début du traitement des justifications...');

        // Récupérer toutes les absences qui n'ont pas de justification
        $absences = Presence::where('statut', 'absent')
            ->whereDoesntHave('justification')
            ->with(['etudiant', 'cours.matiere'])
            ->get();

        $bar = $this->output->createProgressBar($absences->count());
        $this->line("\nTraitement des absences sans justification...");

        DB::transaction(function () use ($absences, $bar) {
            foreach ($absences as $absence) {
                $this->line(
                    "\nTraitement de l'absence pour : " .
                    "Étudiant: {$absence->etudiant->name}, " .
                    "Matière: {$absence->cours->matiere->nom}, " .
                    "Date: {$absence->cours->date}"
                );

                // Créer une entrée de justification par défaut (non justifiée)
                $justification = Justification::create([
                    'presence_id' => $absence->id,
                    'coordinateur_id' => 1, // ID du coordinateur par défaut
                    'motif' => 'À justifier',
                    'justifiee' => false,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                $this->line("Justification créée avec le statut: Non justifiée");
                $bar->advance();
            }
        });

        $bar->finish();
        $this->newLine();
        $this->info('Traitement des justifications terminé !');

        // Afficher un résumé
        $this->line("\nRésumé des justifications :");
        $total = Presence::where('statut', 'absent')->count();
        $justified = Justification::where('justifiee', true)->count();
        $unjustified = Justification::where('justifiee', false)->count();

        $this->table(
            ['Total absences', 'Justifiées', 'Non justifiées'],
            [[$total, $justified, $unjustified]]
        );
    }
}
