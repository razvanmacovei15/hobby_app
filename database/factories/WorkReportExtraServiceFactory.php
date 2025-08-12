<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Contract;
use App\Models\WorkReport;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WorkReportExtraService>
 */
class WorkReportExtraServiceFactory extends Factory
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
            'work_report_id' => WorkReport::factory(),
            'contract_id' => Contract::factory(),
            'executor_company_id' => Company::factory(),
            'beneficiary_company_id' => Company::factory(),
            'name' => $this->faker->randomElement($services),
            'unit_of_measure' => $this->faker->randomElement($unitsOfMeasure),
            'price_per_unit_of_measure' => $this->faker->randomFloat(2, 10, 5000),
            'notes' => $this->faker->optional(0.7)->sentence(),
        ];
    }
}
