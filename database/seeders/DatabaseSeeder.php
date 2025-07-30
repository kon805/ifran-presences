<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Classe;
use App\Models\Matiere;
use App\Models\Cours;
use App\Models\TypeCours;
use App\Models\Presence;
use App\Models\AnneeAcademique;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Création des types de cours
        $typeCours = [
            'Présentiel' => TypeCours::create([
                'nom' => 'Présentiel',
                'code' => 'presentiel',
                'description' => 'Cours en présentiel'
            ]),
            'E-learning' => TypeCours::create([
                'nom' => 'E-learning',
                'code' => 'e-learning',
                'description' => 'Cours en ligne'
            ]),
            'Workshop' => TypeCours::create([
                'nom' => 'Workshop',
                'code' => 'workshop',
                'description' => 'Atelier pratique'
            ]),
        ];

        // Création des utilisateurs par rôle
        $admin = User::factory()->admin()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password')
        ]);

        // Création des 3 coordinateurs avec mot de passe explicite
        $coordinateurs = collect();
        for ($i = 1; $i <= 3; $i++) {
            $coordinateurs->push(
                User::factory()->coordinateur()->state([
                    'password' => Hash::make('password123'),
                    'email' => "coordinateur{$i}@example.com"
                ])->create()
            );
        }

        // Création des 5 professeurs avec mot de passe explicite
        $professeurs = collect();
        for ($i = 1; $i <= 5; $i++) {
            $professeurs->push(
                User::factory()->professeur()->state([
                    'password' => Hash::make('password123'),
                    'email' => "professeur{$i}@example.com"
                ])->create()
            );
        }

        // Création des années académiques
        $anneesAcademiques = collect();

        // Année académique en cours (2024-2025)
        $anneesAcademiques->push(
            AnneeAcademique::create([
                'annee' => '2024-2025',
                'date_debut' => Carbon::create(2024, 9, 1),
                'date_fin' => Carbon::create(2025, 7, 31),
                'statut' => 'en_cours'
            ])
        );

        // Année académique terminée (2023-2024)
        $anneesAcademiques->push(
            AnneeAcademique::create([
                'annee' => '2023-2024',
                'date_debut' => Carbon::create(2023, 9, 1),
                'date_fin' => Carbon::create(2024, 7, 31),
                'statut' => 'terminee'
            ])
        );

        // Création des classes avec coordinateurs
        $classes = collect();
        $filieres = ['Informatique', 'Commerce', 'Marketing'];
        $niveaux = ['1ère année', '2ème année', 'Master 1'];

        foreach ($coordinateurs as $coordinateur) {
            foreach ($anneesAcademiques as $anneeAcad) {
                // Créer une classe pour chaque semestre
                for ($semestre = 1; $semestre <= 2; $semestre++) {
                    $filiere = $filieres[array_rand($filieres)];
                    $niveau = $niveaux[array_rand($niveaux)];
                    $classes->push(
                        Classe::factory()
                            ->create([
                                'coordinateur_id' => $coordinateur->id,
                                'annee_academique_id' => $anneeAcad->id,
                                'semestre' => (string)$semestre,
                                'nom' => "{$niveau} {$filiere} S{$semestre}",
                                'statut' => $anneeAcad->statut === 'terminee' ? 'terminee' : 'en_cours'
                            ])
                    );
                }
            }
        }

        // Création des 20 étudiants et affectation aux classes de manière équilibrée
        $totalEtudiants = collect();
        for ($i = 1; $i <= 20; $i++) {
            $totalEtudiants->push(
                User::factory()->etudiant()->create([
                    'password' => Hash::make('password123'),
                    'email' => "etudiant{$i}@example.com"
                ])
            );
        }

        // Distribution des étudiants dans les classes en respectant la contrainte de semestre
        // Répartir les étudiants équitablement entre les années académiques
        $etudiantsParAnnee = $totalEtudiants->split(2); // Divise en 2 groupes de 10

        foreach ($anneesAcademiques as $index => $anneeAcad) {
            if (!isset($etudiantsParAnnee[$index])) continue;

            $etudiantsAnnee = $etudiantsParAnnee[$index];
            $classesAnnee = $classes->where('annee_academique_id', $anneeAcad->id);

            // Pour chaque semestre
            foreach (['1', '2'] as $semestre) {
                $classesSemestre = $classesAnnee->where('semestre', $semestre);
                $etudiantsDisponibles = $etudiantsAnnee->values(); // Reset les index

                foreach ($classesSemestre as $classe) {
                    // Prendre les 5 premiers étudiants disponibles pour cette classe
                    $etudiantsClasse = $etudiantsDisponibles->take(5);
                    if ($etudiantsClasse->isNotEmpty()) {
                        $classe->etudiants()->attach($etudiantsClasse->pluck('id'));
                        // Retirer ces étudiants de la liste des disponibles pour ce semestre
                        $etudiantsDisponibles = $etudiantsDisponibles->slice(5);
                    }
                }
            }
        }        // Création des parents (10 au total) et association avec les étudiants
        static $totalParents = 0;
        for ($i = 1; $i <= 10; $i++) {
            $etudiant = $totalEtudiants->get(($i - 1) * 2); // Prend un étudiant sur deux
            $parent = User::factory()->parent()->create([
                'password' => Hash::make('password123'),
                'email' => "parent{$i}@example.com"
            ]);
            \App\Models\Parents::create([
                'user_id' => $parent->id,
                'etudiant_id' => $etudiant->id
            ]);
        }

        // Création des matières et attribution aux professeurs
        $matieres = Matiere::factory(8)->create();
        foreach ($professeurs as $professeur) {
            $professeur->matieres()->attach(
                $matieres->random(rand(1, 3))->pluck('id')
            );
        }

        // Création des cours pour chaque classe
        foreach ($classes as $classe) {
            $matieresProfesseurs = collect();
            foreach ($matieres->random(5) as $matiere) {
                $professeur = $professeurs->random();
                $matieresProfesseurs->push([
                    'matiere' => $matiere,
                    'professeur' => $professeur
                ]);
            }

            // Générer des cours sur les 3 derniers mois
            $startDate = now()->subMonths(3)->startOfMonth();
            $endDate = now()->endOfMonth();
            $currentDate = clone $startDate;

            while ($currentDate <= $endDate) {
                if ($currentDate->isWeekday()) {
                    $matiereProfesseur = $matieresProfesseurs->random();

                    $cours = Cours::factory()->create([
                        'classe_id' => $classe->id,
                        'professeur_id' => $matiereProfesseur['professeur']->id,
                        'matiere_id' => $matiereProfesseur['matiere']->id,
                        'date' => $currentDate->format('Y-m-d'),
                        'heure_debut' => '09:00',
                        'heure_fin' => '12:00'
                    ]);

                    // Associer un type de cours
                    $cours->types()->attach($typeCours[array_rand($typeCours)]->id);

                    // Créer les présences pour ce cours
                    foreach ($classe->etudiants as $etudiant) {
                        // Créer une présence avec statut aléatoire
                        Presence::factory()->create([
                            'cours_id' => $cours->id,
                            'etudiant_id' => $etudiant->id,
                            'statut' => rand(0, 10) > 2 ? 'présent' : (rand(0, 1) ? 'retard' : 'absent')
                        ]);
                    }
                }
                $currentDate->addDay();
            }
        }
    }
}
