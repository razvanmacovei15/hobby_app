<?php

namespace Database\Factories;

use App\Models\ContractAnnex;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ContractService>
 */
class ContractServiceFactory extends Factory
{
    protected $serviceNames = [
        'Excavare fundații',
        'Betonare fundații',
        'Zidărie pereți',
        'Tencuială exterioară',
        'Instalații sanitare',
        'Instalații electrice',
        'Tâmplărie exterioară',
        'Tâmplărie interioară',
        'Pardoseală',
        'Acoperiș',
        'Pavaj exterior',
        'Gradini'
    ];

    protected $unitsOfMeasure = [
        'm³',
        'm²',
        'm',
        'buc',
        'kg',
        'l'
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'contract_annex_id' => ContractAnnex::factory(),
            'order' => $this->faker->unique()->numberBetween(1, 100),
            'name' => $this->faker->randomElement($this->serviceNames),
            'unit_of_measure' => $this->faker->randomElement($this->unitsOfMeasure),
            'price_per_unit_of_measure' => $this->faker->randomFloat(2, 10, 500),
        ];
    }
}
