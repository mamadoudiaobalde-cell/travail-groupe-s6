<?php

namespace Database\Factories;

use App\Models\Pv;
use App\Models\Soutenance;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Pv>
 */
class PvFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $note = fake()->randomFloat(2, 8, 19);

        return [
            'soutenance_id' => Soutenance::factory(),
            'note' => $note,
            'mention' => Pv::calculerMention($note),
            'observations' => fake()->sentence(),
            'status' => 'brouillon',
        ];
    }
}
