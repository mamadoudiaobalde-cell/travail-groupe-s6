<?php

namespace Database\Factories;

use App\Models\Soutenance;
use App\Models\User;
use App\Models\Salle;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class SoutenanceFactory extends Factory
{
    protected $model = Soutenance::class;

    public function definition(): array
    {
        return [
            'etudiant_id' => User::where('role', 'etudiant')->inRandomOrder()->first()->id ?? 1,
            'directeur_id' => User::where('role', 'enseignant')->inRandomOrder()->first()->id ?? 1,
            'titre' => $this->faker->sentence(5),
            'filiere' => $this->faker->randomElement(['Informatique', 'Réseaux', 'Génie Civil', 'Gestion']),
            'type' => $this->faker->randomElement(['licence', 'master', 'doctorat']),
            'date' => $this->faker->dateTimeBetween('+1 week', '+1 month'),
            'heure' => $this->faker->time('H:i:s'),
            'salle_id' => Salle::inRandomOrder()->first()->id ?? null,
            'statut' => $this->faker->randomElement(['brouillon', 'planifiee', 'confirmee', 'realisee', 'annulee']),
        ];
    }
}