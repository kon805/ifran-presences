<?php

namespace Database\Factories;

use App\Models\Matiere;
use Illuminate\Database\Eloquent\Factories\Factory;

class MatiereFactory extends Factory
{
    protected $model = Matiere::class;

    public function definition()
    {
        $matieres = [
            'Mathématiques', 'Physique', 'Chimie', 'Biologie',
            'Français', 'Anglais', 'Histoire', 'Géographie',
            'Informatique', 'Économie', 'Philosophie', 'Sport'
        ];

        return [
            'nom' => $this->faker->unique()->randomElement($matieres),
            'description' => $this->faker->sentence(),
            'coefficient' => $this->faker->randomElement([1, 2, 3, 4])
        ];
    }
}
