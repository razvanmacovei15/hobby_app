<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\WorkspaceExecutor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WorkspaceExecutorEngineer>
 */
class WorkspaceExecutorEngineerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'workspace_executor_id' => WorkspaceExecutor::factory(),
            'user_id' => User::factory(),
            'role' => $this->faker->randomElement(['primary', 'secondary', 'supervisor', 'engineer']),
            'assigned_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }

    /**
     * Indicate that the engineer has a primary role.
     */
    public function primary(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'primary',
        ]);
    }

    /**
     * Indicate that the engineer has a secondary role.
     */
    public function secondary(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'secondary',
        ]);
    }

    /**
     * Indicate that the engineer has a supervisor role.
     */
    public function supervisor(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'supervisor',
        ]);
    }
}
