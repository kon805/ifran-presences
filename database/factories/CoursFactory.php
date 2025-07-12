<?php

namespace Database\Factories;

use App\Models\Cours;
use App\Models\Classe;
use App\Models\Matiere;
use App\Models\TypeCours;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CoursFactory extends Factory
{
    protected $model = Cours::class;

    public function definition()
    {
        $date = $this->faker->dateTimeBetween('-2 months', '+2 months');
        $heure_debut = $this->faker->dateTimeBetween('08:00', '16:00')->format('H:i');
        $heure_fin = $this->faker->dateTimeBetween($heure_debut, '17:00')->format('H:i');

        return [
            'classe_id' => Classe::factory(),
            'professeur_id' => User::factory()->professeur(),
            'matiere_id' => Matiere::factory(),
            'date' => $date->format('Y-m-d'),
            'heure_debut' => $heure_debut,
            'heure_fin' => $heure_fin,
            'etat' => $this->faker->randomElement(['programmé', 'annulé', 'reporté'])
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Cours $cours) {
            // Associer un type de cours aléatoire
            $cours->types()->attach(
                TypeCours::inRandomOrder()->first()->id
            );
        });
    }
}
