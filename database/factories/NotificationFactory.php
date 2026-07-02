<?php

namespace Database\Factories;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Notification>
 */
class NotificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'utilisateur_id' => User::factory(),
            'type' => fake()->randomElement(['convocation', 'invitation_jury', 'resultat']),
            'titre' => fake()->sentence(4),
            'message' => fake()->sentence(),
            'lu' => false,
        ];
    }
}
