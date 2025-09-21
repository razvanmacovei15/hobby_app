<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Workspace>
 */
class WorkspaceFactory extends Factory
{
    protected $workspaceNames = [
        'Proiect Rezidențial Central Park',
        'Complex Comercial Plaza Mall',
        'Bloc de Locuințe Green Valley',
        'Centru Medical Modern',
        'Hotel Business Center',
        'Complex Sportiv Olimpia',
        'Bloc de Birouri Sky Tower',
        'Rezidențial Premium Garden',
        'Centru Comercial Mega Mall',
        'Complex Rezidențial Lake View'
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement($this->workspaceNames),
            'owner_id' => Company::factory(),
        ];
    }
}
