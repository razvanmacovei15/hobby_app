<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Contract;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ContractExtraService>
 */
class ContractExtraServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $unitsOfMeasure = ['buc', 'mp', 'mc', 'kg', 'l', 'km', 'ore'];
        $services = [
            'Instalare sistem de ventilatie',
            'Montare usi interioare',
            'Instalare sistem de incalzire',
            'Montare geamuri termopan',
            'Instalare sistem de apa',
            'Montare cabluri electrice',
            'Instalare sistem de canalizare',
            'Montare plinte',
            'Instalare sistem de incendiu',
            'Montare balustrade',
            'Instalare sistem de lift',
            'Montare garduri',
            'Instalare sistem de iluminat',
            'Montare scari exterioare',
            'Instalare sistem de securitate'
        ];

        return [
            'contract_id' => Contract::factory(),
            'company_id' => Company::factory(),
            'name' => fake()->randomElement($services),
            'unit_of_measure' => fake()->randomElement($unitsOfMeasure),
            'price_per_unit_of_measure' => fake()->randomFloat(2, 10, 5000),
            'quantity' => fake()->numberBetween(1, 100),
            'description' => fake()->optional(0.7)->sentence(),
            'provided_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
