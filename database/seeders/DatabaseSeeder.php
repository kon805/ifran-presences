<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Classe;
use App\Models\Matiere;
use App\Models\Cours;
use App\Models\TypeCours;
use App\Models\Presence;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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

        // Création des coordinateurs avec mot de passe explicite
        $coordinateurs = collect();
        for ($i = 1; $i <= 3; $i++) {
            $coordinateurs->push(
                User::factory()->coordinateur()->state([
                    'password' => Hash::make('password123'),
                    'email' => "coordinateur{$i}@example.com"
                ])->create()
            );
        }

        // Création des professeurs avec mot de passe explicite
        $professeurs = collect();
        for ($i = 1; $i <= 5; $i++) {
            $professeurs->push(
                User::factory()->professeur()->state([
                    'password' => Hash::make('password123'),
                    'email' => "professeur{$i}@example.com"
                ])->create()
            );
        }

        // Création des classes avec coordinateurs
        $classes = collect();
        $annees = ['2023-2024', '2024-2025'];
        foreach ($coordinateurs as $coordinateur) {
            foreach ($annees as $annee) {
                // Créer une classe pour chaque semestre
                for ($semestre = 1; $semestre <= 2; $semestre++) {
                    $classes->push(
                        Classe::factory()
                            ->create([
                                'coordinateur_id' => $coordinateur->id,
                                'annee_academique' => $annee,
                                'semestre' => (string)$semestre,
                                'nom' => "Classe {$annee} S{$semestre}"
                            ])
                    );
                }
            }
        }

        // Création des étudiants et affectation aux classes
        foreach ($classes as $classe) {
            $etudiants = collect();
            for ($i = 1; $i <= 15; $i++) {
                $etudiants->push(
                    User::factory()->etudiant()->create([
                        'password' => Hash::make('password123'),
                        'email' => "etudiant" . ((($classe->id - 1) * 15) + $i) . "@example.com"
                    ])
                );
            }
            $classe->etudiants()->attach($etudiants->pluck('id'));

            // Création de parents pour certains étudiants
            foreach ($etudiants as $etudiant) {
                if (rand(0, 1)) {
                    static $parentCount = 0;
                    $parentCount++;
                    $parent = User::factory()->parent()->create([
                        'password' => Hash::make('password123'),
                        'email' => "parent{$parentCount}@example.com"
                    ]);
                    \App\Models\Parents::create([
                        'user_id' => $parent->id,
                        'etudiant_id' => $etudiant->id
                    ]);
                }
            }
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
