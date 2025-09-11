<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'street' => $this->faker->streetName,
            'street_number' => $this->faker->buildingNumber,
            'city' => $this->faker->city,
            'building' => $this->faker->name(),
            'apartment_number' => $this->faker->randomNumber(nbDigits: 2),
            'state' => $this->faker->randomElement(['Cluj', 'Timisoara', 'Bucuresti', 'Iasi', 'Constanta']),
            'country' => 'Romania',
        ];
    }
}
