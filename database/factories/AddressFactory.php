<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    protected $states = ['Cluj', 'Timisoara', 'Bucuresti'];
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
            'state' => $this->faker->randomElement($this->states),
            'country' => 'Romania',
        ];
    }
}
