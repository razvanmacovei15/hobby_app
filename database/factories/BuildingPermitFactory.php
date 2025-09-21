<?php

namespace Database\Factories;

use App\Enums\PermitType;
use App\Enums\PermitStatus;
use App\Models\Address;
use App\Models\Workspace;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BuildingPermit>
 */
class BuildingPermitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $issuanceYear = $this->faker->numberBetween(2020, 2025);
        $permitNumber = $this->faker->unique()->numerify('####');
        $workStartDate = $this->faker->dateTimeBetween('2024-01-01', '2025-12-31');
        $executionDays = $this->faker->numberBetween(30, 365);
        
        return [
            'permit_number' => $permitNumber,
            'permit_type' => $this->faker->randomElement(PermitType::cases()),
            'status' => $this->faker->randomElement(PermitStatus::cases()),
            'workspace_id' => Workspace::factory(),
            'name' => $this->faker->sentence(3),
            'height_regime' => $this->faker->randomElement(['P', 'P+1', 'P+2', 'P+3', 'P+4', 'P+5']),
            'land_book_number' => $this->faker->numerify('######'),
            'cadastral_number' => $this->faker->numerify('####/###/##'),
            'architect' => $this->faker->name(),
            'execution_duration_days' => $executionDays,
            'image_url' => $this->faker->optional()->imageUrl(800, 600, 'buildings'),
            'validity_term' => $this->faker->dateTimeBetween('2025-01-01', '2027-12-31'),
            'work_start_date' => $workStartDate,
            'work_end_date' => null, // Will be calculated automatically
            'address_id' => Address::factory(),
            'issuance_year' => $issuanceYear,
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => PermitStatus::PENDING,
        ]);
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => PermitStatus::APPROVED,
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => PermitStatus::REJECTED,
        ]);
    }
}
