<?php

namespace Database\Factories;

use App\Models\Salle;
use App\Models\Soutenance;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Soutenance>
 */
class SoutenanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'etudiant_id' => User::factory()->state(['role' => 'etudiant']),
            'directeur_id' => User::factory()->state(['role' => 'enseignant']),
            'titre' => 'Étude sur ' . fake()->words(3, true),
            'filiere' => fake()->randomElement(['Informatique', 'Génie Civil', 'Gestion', 'Réseaux']),
            'type' => fake()->randomElement(['licence', 'master', 'doctorat']),
            'date' => fake()->dateTimeBetween('+1 week', '+2 months')->format('Y-m-d'),
            'heure' => fake()->time('H:i'),
            'salle_id' => Salle::factory(),
            'statut' => 'brouillon',
        ];
    }
}
