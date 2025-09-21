<?php

namespace Database\Factories;

use App\Models\Contract;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ContractAnnex>
 */
class ContractAnnexFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $signDate = $this->faker->dateTimeBetween('-6 months', 'now');
        
        return [
            'contract_id' => Contract::factory(),
            'annex_number' => $this->faker->unique()->numberBetween(1, 50),
            'sign_date' => $signDate,
            'notes' => $this->faker->optional(0.7)->sentence(),
        ];
    }
}
