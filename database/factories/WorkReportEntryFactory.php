<?php

namespace Database\Factories;

use App\Models\ContractService;
use App\Models\ContractExtraService;
use App\Models\WorkReport;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WorkReportEntry>
 */
class WorkReportEntryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Randomly choose between ContractService and ContractExtraService
        $serviceType = $this->faker->randomElement([
            ContractService::class,
            ContractExtraService::class
        ]);
        
        return [
            'work_report_id' => WorkReport::factory(),
            'order' => $this->faker->numberBetween(1, 10),
            'service_type' => $serviceType,
            'service_id' => $serviceType::factory(),
            'quantity' => $this->faker->randomFloat(2, 1, 500),
            'total' => $this->faker->randomFloat(2, 100, 100000),
        ];
    }
}
