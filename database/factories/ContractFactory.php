<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contract>
 */
class ContractFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('-2 years', 'now');
        $endDate = $this->faker->dateTimeBetween($startDate, '+2 years');
        $signDate = $this->faker->dateTimeBetween('-1 year', 'now');
        $contractNumber = $this->faker->unique()->numberBetween(1, 999);
        
        return [
            'contract_number' => 'nr.' . $contractNumber . '/' . $signDate->format('d.m.Y'),
            'beneficiary_id' => Company::factory(),
            'executor_id' => Company::factory(),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'sign_date' => $signDate,
        ];
    }
}
