<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Resident>
 */
class ResidentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'house_id' => fake()->numberBetween(1, 20),
            'full_name' => fake()->name(),
            'ktp' => fake()->fileExtension(),
            'status' => fake()->randomElement(['Tetap', 'Kontrak']),
            'phone_number' => fake()->phoneNumber(),
            'married' => fake()->randomElement(['Menikah', 'Belum Menikah']),
        ];
    }
}
