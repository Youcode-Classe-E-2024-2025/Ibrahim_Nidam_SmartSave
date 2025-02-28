<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
            return [
                'user_id' => User::first()->id ?? User::factory()->create()->id,
                'name'    => $this->faker->unique()->word(),
                'color'   => $this->faker->safeHexColor(),
                'type'    => $this->faker->randomElement(['income', 'expense']),
                'created_at' => now(),
                'updated_at' => now(),
            ];
    }
}
