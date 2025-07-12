<?php

namespace Database\Factories;

use App\Models\TypeCours;
use Illuminate\Database\Eloquent\Factories\Factory;

class TypeCoursFactory extends Factory
{
    protected $model = TypeCours::class;

    public function definition()
    {
        $types = [
            'Présentiel' => 'presentiel',
            'E-learning' => 'e-learning',
            'Workshop' => 'workshop'
        ];
        $nom = $this->faker->randomElement(array_keys($types));

        return [
            'nom' => $nom,
            'code' => $types[$nom],
            'description' => $this->faker->sentence(),
        ];
    }
}
