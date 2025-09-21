<?php

namespace Database\Factories;

use App\Enums\ExecutorType;
use App\Models\Company;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WorkspaceExecutor>
 */
class WorkspaceExecutorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'workspace_id' => Workspace::factory(),
            'executor_id' => Company::factory(),
            'responsible_engineer_id' => null, // Will be set by state methods or left null
            'is_active' => $this->faker->boolean(80), // 80% chance of being active
            'executor_type' => $this->faker->randomElement(ExecutorType::cases()),
            'has_contract' => $this->faker->boolean(60), // 60% chance of having contract
        ];
    }

    /**
     * State to assign a responsible engineer
     */
    public function withResponsibleEngineer(?User $user = null): static
    {
        return $this->state(fn (array $attributes) => [
            'responsible_engineer_id' => $user ? $user->id : User::factory(),
        ]);
    }

    /**
     * State for active executors
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * State for inactive executors
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * State for executors with contracts
     */
    public function withContract(): static
    {
        return $this->state(fn (array $attributes) => [
            'has_contract' => true,
        ]);
    }

    /**
     * Configure to add multiple engineers after creation
     */
    public function withMultipleEngineers(int $count = 3): static
    {
        return $this->afterCreating(function (WorkspaceExecutor $executor) use ($count) {
            // Create engineers for this executor
            $engineers = User::factory()->count($count)->create();

            $roles = ['primary', 'secondary', 'supervisor', 'engineer'];

            foreach ($engineers as $index => $engineer) {
                $role = $index === 0 ? 'primary' : $roles[array_rand($roles)];

                \App\Models\WorkspaceExecutorEngineer::create([
                    'workspace_executor_id' => $executor->id,
                    'user_id' => $engineer->id,
                    'role' => $role,
                    'assigned_at' => now()->subDays(rand(1, 30)),
                ]);
            }
        });
    }
}
