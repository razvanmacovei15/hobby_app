<?php

namespace App\Services\Implementations;

use App\Models\Company;
use App\Models\User;
use App\Models\Workspace;
use App\Services\IUserWorkspaceService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserWorkspaceService implements IUserWorkspaceService
{
    public function registerUserWithDefaultWorkspace(array $userData): User
    {
        return DB::transaction(function () use ($userData) {
            // 1. Create the user
            $user = User::create([
                'first_name' => $userData['first_name'],
                'last_name' => $userData['last_name'],
                'email' => $userData['email'],
                'password' => $userData['password'],
            ]);

            // 2. Create a personal company for the user
            $company = Company::create([
                'name' => "{$userData['first_name']} {$userData['last_name']}'s Company",
                'phone' => null, // Will be filled later by user
                'representative_id' =>  $user->id,
            ]);

            // 3. Create workspace owned by the company
            $workspace = Workspace::create([
                'name' => "{$userData['first_name']} {$userData['last_name']}'s Default Workspace",
                'owner_id' => $company->id,
            ]);

            // 4. Add user to workspace with default flag
            $user->workspaces()->attach($workspace->id, [
                'is_default' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 5. Assign owner role (will implement after Spatie is set up)
            // $user->assignRole('owner');

            return $user;
        });
    }
}
