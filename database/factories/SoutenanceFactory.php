<?php

namespace Database\Factories;

use App\Models\Salle;
use App\Models\Soutenance;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SoutenanceFactory extends Factory
{
    protected $model = Soutenance::class;

    public function definition(): array
    {
        return [
            'etudiant_id' => User::factory()->state(['role' => 'etudiant']),
            'directeur_id' => User::factory()->state(['role' => 'enseignant']),
            'titre' => $this->faker->sentence(5),
            'filiere' => $this->faker->randomElement(['Informatique', 'Réseaux', 'Génie Civil', 'Gestion']),
            'type' => $this->faker->randomElement(['licence', 'master', 'doctorat']),
            'date' => $this->faker->dateTimeBetween('+1 week', '+1 month'),
            'heure' => $this->faker->time('H:i:s'),
            'salle_id' => Salle::factory(),
            'statut' => 'brouillon',
        ];
    }
}
