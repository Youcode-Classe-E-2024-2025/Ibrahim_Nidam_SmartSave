<?php

namespace Database\Factories;

use App\Models\Profile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SavingGoal>
 */
class SavingGoalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'profile_id' => null,
            'title' => $this->faker->sentence(3),
            'target_amount' => $this->faker->randomFloat(2, 100, 10000),
            'current_amount' => $this->faker->randomFloat(2, 0, 5000),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
