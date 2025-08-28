<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CompanyEmployee>
 */
class CompanyEmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_id' => \App\Models\Company::factory(),
            'user_id' => \App\Models\User::factory(),
            'job_title' => $this->faker->randomElement([
                'Software Engineer',
                'Project Manager',
                'Construction Supervisor',
                'Site Inspector',
                'Foreman',
                'Safety Coordinator',
                'Quality Controller',
                'Architect',
                'Civil Engineer',
                'Administrative Assistant'
            ]),
            'salary' => $this->faker->randomFloat(2, 30000, 120000),
            'hired_at' => $this->faker->dateTimeBetween('-3 years', 'now'),
        ];
    }
}
