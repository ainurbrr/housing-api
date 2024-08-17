<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\House>
 */
class HouseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'residents_id' => fake()->numberBetween(1, 20),
            'address' => fake()->address(),
            'status' => fake()->randomElement(['Dihuni', 'Tidak Dihuni']),
        ];
    }
}
