<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WorkReport>
 */
class WorkReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $months = [
            'ianuarie', 'februarie', 'martie', 'aprilie', 'mai', 'iunie',
            'iulie', 'august', 'septembrie', 'octombrie', 'noiembrie', 'decembrie'
        ];
        
        $reportYear = $this->faker->numberBetween(2024, 2025);
        $reportMonth = $this->faker->randomElement($months);
        
        return [
            'company_id' => Company::factory(),
            'written_by' => User::factory(),
            'report_month' => $reportMonth,
            'report_year' => $reportYear,
            'report_number' => $this->faker->numberBetween(1, 12),
            'observations' => $this->faker->optional(0.8)->paragraph(),
        ];
    }
}
