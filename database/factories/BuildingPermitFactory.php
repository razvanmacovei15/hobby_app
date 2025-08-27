<?php

namespace Database\Factories;

use App\Enums\PermitType;
use App\Enums\PermitStatus;
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
        return [
            'permit_number' => $this->faker->unique()->regexify('BP-[0-9]{4}-[0-9]{6}'),
            'permit_type' => $this->faker->randomElement(PermitType::cases()),
            'status' => $this->faker->randomElement(PermitStatus::cases()),
            'workspace_id' => Workspace::factory(),
        ];
    }
}
