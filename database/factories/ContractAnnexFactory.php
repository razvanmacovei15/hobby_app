<?php

namespace Database\Factories;

use App\Models\Contract;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ContractAnnex>
 */
class ContractAnnexFactory extends Factory
{
    protected $annexTypes = [
        'Modificare prețuri',
        'Prelungire termen',
        'Modificare specificații',
        'Adăugare servicii',
        'Modificare condiții'
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $signDate = $this->faker->dateTimeBetween('-6 months', 'now');
        $annexNumber = $this->faker->unique()->numberBetween(1, 50);
        
        return [
            'contract_id' => Contract::factory(),
            'annex_number' => 'Anexa ' . $annexNumber,
            'sign_date' => $signDate,
            'description' => $this->faker->randomElement($this->annexTypes) . ': ' . $this->faker->sentence(),
        ];
    }
}
