<?php

namespace Database\Factories;

use App\Models\Presence;
use App\Models\Cours;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PresenceFactory extends Factory
{
    protected $model = Presence::class;

    public function definition()
    {
        return [
            'cours_id' => Cours::factory(),
            'etudiant_id' => User::factory()->etudiant(),
            'statut' => $this->faker->randomElement(['présent', 'retard', 'absent'])
        ];
    }
}
