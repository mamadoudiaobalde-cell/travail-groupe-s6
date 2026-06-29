<?php

namespace Database\Factories;

use App\Models\Document;
use App\Models\Soutenance;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Document>
 */
class DocumentFactory extends Factory
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
            'type' => fake()->randomElement(['pv', 'convocation', 'attestation']),
            'chemin_fichier' => 'documents/' . fake()->uuid() . '.pdf',
            'hash_fichier' => hash('sha256', fake()->uuid()),
        ];
    }
}
