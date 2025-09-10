<?php

namespace Database\Factories;

use App\Enums\WorkReportStatus;
use App\Models\Contract;
use App\Models\Company;
use App\Models\User;
use App\Models\Workspace;
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
            'contract_id' => Contract::factory(),
            'workspace_id' => Workspace::factory(),
            'beneficiary_id' => Company::factory(),
            'executor_id' => Company::factory(),
            'written_by' => User::factory(),
            'report_month' => $reportMonth,
            'report_year' => $reportYear,
            'report_number' => $this->faker->numberBetween(1, 12),
            'notes' => $this->faker->optional(0.8)->paragraph(),
            'status' => $this->faker->randomElement(WorkReportStatus::cases()),
            'approved_at' => null,
            'approved_by' => null,
        ];
    }

    public function draft(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'status' => WorkReportStatus::DRAFT,
            'approved_at' => null,
            'approved_by' => null,
        ]);
    }

    public function approved(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'status' => WorkReportStatus::APPROVED,
            'approved_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'approved_by' => User::factory(),
        ]);
    }

    public function pendingApproval(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'status' => WorkReportStatus::PENDING_APPROVAL,
            'approved_at' => null,
            'approved_by' => null,
        ]);
    }
}
