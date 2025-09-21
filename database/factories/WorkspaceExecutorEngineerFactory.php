<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\WorkspaceExecutor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory for creating workspace executor engineer relationships.
 * This is a helper factory for the pivot table workspace_executor_engineers.
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
            'assigned_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }

    /**
     * Create relationship data for existing models.
     */
    public function forExecutorAndUser(WorkspaceExecutor $executor, User $user): array
    {
        return [
            'workspace_executor_id' => $executor->id,
            'user_id' => $user->id,
            'assigned_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
