<?php

namespace Database\Factories;

use App\Models\Jury;
use App\Models\Soutenance;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Jury>
 */
class JuryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'soutenance_id' => Soutenance::factory(),
            'utilisateur_id' => User::factory()->state(['role' => 'enseignant']),
            'role' => fake()->randomElement(['president', 'directeur', 'rapporteur', 'membre']),
            'statut_confirmation' => 'en_attente',
        ];
    }
}
