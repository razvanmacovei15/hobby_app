<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanyEmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing companies and users to create realistic relationships
        $companies = \App\Models\Company::all();
        $users = \App\Models\User::all();

        if ($companies->isEmpty() || $users->isEmpty()) {
            $this->command->warn('No companies or users found. Skipping CompanyEmployee seeding.');
            return;
        }

        // Create 20-30 employee relationships
        foreach ($companies as $company) {
            // Each company gets 2-5 employees
            $employeeCount = rand(2, 5);
            $companyUsers = $users->random(min($employeeCount, $users->count()));
            
            foreach ($companyUsers as $user) {
                // Check if this relationship already exists
                if (!\App\Models\CompanyEmployee::where('company_id', $company->id)
                    ->where('user_id', $user->id)->exists()) {
                    
                    \App\Models\CompanyEmployee::factory()->create([
                        'company_id' => $company->id,
                        'user_id' => $user->id,
                    ]);
                }
            }
        }
    }
}
