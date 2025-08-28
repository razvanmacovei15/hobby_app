<?php

namespace App\Services\Implementations;

use App\Models\CompanyEmployee;
use App\Models\User;
use App\Services\ICompanyEmployeeService;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CompanyEmployeeService implements ICompanyEmployeeService
{
    private function createUserFromEmployeeData(array $userData): User
    {
        return User::create([
            'first_name' => trim($userData['first_name']),
            'last_name' => trim($userData['last_name']),
            'email' => $userData['email'],
            'password' => null, // User will set password via email invitation
        ]);
    }

    public function creteCompanyEmployee(array $data)
    {
        return DB::transaction(function () use ($data) {
            $workspace = Filament::getTenant();

            if (!$workspace) {
                throw new \Exception('No workspace found');
            }

            // Check if user already exists by email
            $user = User::query()->where('email', $data['user']['email'])->first();

            if (!$user) {
                // Create new user if doesn't exist
                $user = $this->createUserFromEmployeeData($data['user']);
            }

            // Check if this user is already employed by this company
            $existingEmployee = CompanyEmployee::where('company_id', $workspace->owner_id)
                ->where('user_id', $user->id)
                ->first();

            if ($existingEmployee) {
                throw new \Exception('This user is already employed by this company');
            }

            // Create the company employee record
            $companyEmployee = CompanyEmployee::create([
                'company_id' => $workspace->owner_id,
                'user_id' => $user->id,
                'job_title' => $data['job_title'],
                'salary' => $data['salary'],
                'hired_at' => $data['hired_at'],
            ]);

            // Add user to workspace if not already added
            if (!$user->workspaces()->where('workspace_id', $workspace->id)->exists()) {
                $user->workspaces()->attach($workspace->id);
            }

            return $companyEmployee->load(['user', 'company']);
        });
    }
}
