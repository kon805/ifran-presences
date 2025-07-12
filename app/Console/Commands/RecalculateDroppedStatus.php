<?php

namespace App\Console\Commands;

use App\Models\Matiere;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RecalculateDroppedStatus extends Command
{
    protected $signature = 'presences:recalculate-dropped';
    protected $description = 'Recalcule le statut dropped pour tous les étudiants en fonction de leur taux de présence';

    public function handle()
    {
        $this->info('Début du recalcul des statuts dropped...');

        // Récupérer toutes les matières avec leurs étudiants
        $matieres = Matiere::with(['cours.classe.etudiants'])->get();
        $bar = $this->output->createProgressBar($matieres->count());

        DB::transaction(function () use ($matieres, $bar) {
            foreach ($matieres as $matiere) {
                $etudiants = collect();

                // Récupérer tous les étudiants uniques de cette matière
                foreach ($matiere->cours as $cours) {
                    if ($cours->classe) {
                        $etudiants = $etudiants->concat($cours->classe->etudiants);
                    }
                }

                $etudiants = $etudiants->unique('id');

                foreach ($etudiants as $etudiant) {
                    $this->line("\nAnalyse de l'étudiant {$etudiant->name} pour la matière {$matiere->nom}");
                    // Calculer le nombre total de cours pour cet étudiant
                    $totalCours = $matiere->cours()
                        ->whereHas('classe.etudiants', function ($query) use ($etudiant) {
                            $query->where('users.id', $etudiant->id);
                        })
                        ->count();

                    $this->line("Total des cours: {$totalCours}");

                    // Calculer le nombre de présences
                    $presences = $matiere->cours()
                        ->whereHas('presences', function ($query) use ($etudiant) {
                            $query->where('etudiant_id', $etudiant->id)
                                ->where('statut', 'present');
                        })
                        ->count();

                    $this->line("Nombre de présences: {$presences}");

                    // Calculer et mettre à jour le statut dropped
                    if ($totalCours > 0) {
                        $tauxPresence = ($presences / $totalCours) * 100;

                        // Vérifier si l'entrée existe dans matiere_user
                        $exists = DB::table('matiere_user')
                            ->where('user_id', $etudiant->id)
                            ->where('matiere_id', $matiere->id)
                            ->exists();

                        if (!$exists) {
                            // Si l'entrée n'existe pas, la créer
                            DB::table('matiere_user')->insert([
                                'user_id' => $etudiant->id,
                                'matiere_id' => $matiere->id,
                                'dropped' => $tauxPresence < 70,
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);

                            $this->line("Nouvelle entrée créée pour l'étudiant {$etudiant->name} dans la matière {$matiere->nom}");
                        } else {
                            // Si elle existe, la mettre à jour
                            DB::table('matiere_user')
                                ->where('user_id', $etudiant->id)
                                ->where('matiere_id', $matiere->id)
                                ->update([
                                    'dropped' => $tauxPresence < 70,
                                    'updated_at' => now()
                                ]);
                        }

                        $this->line(
                            "Matière: {$matiere->nom} | " .
                            "Étudiant: {$etudiant->name} | " .
                            "Taux: " . number_format($tauxPresence, 2) . "% | " .
                            "Status: " . ($tauxPresence < 70 ? 'Dropped' : 'OK')
                        );
                    }
                }

                $bar->advance();
            }
        });

        $bar->finish();
        $this->newLine();
        $this->info('Recalcul des statuts dropped terminé !');
    }
}
