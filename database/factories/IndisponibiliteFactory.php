<?php

namespace Database\Factories;

use App\Models\Indisponibilite;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Indisponibilite>
 */
class IndisponibiliteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $debut = fake()->dateTimeBetween('+1 week', '+1 month');

        return [
            'utilisateur_id' => User::factory()->state(['role' => 'enseignant']),
            'date_debut' => $debut->format('Y-m-d'),
            'date_fin' => (clone $debut)->modify('+3 days')->format('Y-m-d'),
            'motif' => fake()->sentence(3),
        ];
    }
}
