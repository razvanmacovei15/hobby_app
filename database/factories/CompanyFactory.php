<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    protected $companyNames = [
        'Construct Pro SRL',
        'Building Solutions Ltd',
        'Urban Development Group',
        'Modern Construction Co',
        'Infrastructure Plus SRL'
    ];
    
    protected $registrationPlaces = [
        'Cluj-Napoca',
        'Bucuresti',
        'Timisoara',
        'Iasi',
        'Constanta'
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement($this->companyNames),
            'j' => 'J' . $this->faker->unique()->numberBetween(10000000, 99999999) . '/' . $this->faker->numberBetween(2000, 2024) . '/' . $this->faker->numberBetween(1, 999),
            'cui' => 'RO' . $this->faker->unique()->numberBetween(10000000, 99999999),
            'place_of_registration' => $this->faker->randomElement($this->registrationPlaces),
            'iban' => 'RO' . $this->faker->unique()->numberBetween(1000000000000000000000, 9999999999999999999999),
            'representative_id' => User::factory(),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->companyEmail(),
            'address_id' => Address::factory(),
        ];
    }
}
