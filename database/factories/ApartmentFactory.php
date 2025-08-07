<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\Building;
use App\Models\Floor;
use App\Models\Staircase;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Apartment>
 */
class ApartmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'label' => $this->faker->randomNumber(nbDigits: 2),

            'address_id' => Address::factory(),
            'building_id' => Building::factory(),
            'staircase_id' => Staircase::factory(),
            'floor_id' => Floor::factory(),
        ];
    }
}
