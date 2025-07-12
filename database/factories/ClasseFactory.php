<?php

namespace Database\Factories;

use App\Models\Classe;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClasseFactory extends Factory
{
    protected $model = Classe::class;

    public function definition()
    {
        return [
            'nom' => 'Classe ' . $this->faker->unique()->word(),
            'coordinateur_id' => null, // Sera défini lors de la création
            'annee_academique' => '2024-2025',
            'semestre' => $this->faker->randomElement(['1', '2'])
        ];
    }
}
