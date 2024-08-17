<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'resident_id' => fake()->numberBetween(1, 20),
            'house_id' => fake()->numberBetween(1, 20),
            'payment_date' => fake()->dateTimeBetween('-1 years', '+1 years'),
            'type' => fake()->randomElement(['Kebersihan', 'Satpam']),
            'amount' => fake()->numberBetween(15000, 1000000),
        ];
    }
}
