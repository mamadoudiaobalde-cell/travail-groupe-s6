<?php

namespace Database\Factories;

use App\Models\Salle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Salle>
 */
class SalleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nom' => 'Salle ' . fake()->unique()->bothify('??-##'),
            'capacite' => fake()->numberBetween(10, 100),
            'localisation' => 'Bâtiment ' . fake()->randomLetter(),
            'equipements' => 'Vidéoprojecteur, tableau',
            'actif' => true,
        ];
    }
}
