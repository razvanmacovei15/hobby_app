<?php

namespace App\Policies;

use App\Enums\Permissions\EmployeePermission;
use App\Models\CompanyEmployee;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CompanyEmployeePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->canInWorkspace(EmployeePermission::VIEW->value);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, CompanyEmployee $companyEmployee): bool
    {
        return $user->canInWorkspace(EmployeePermission::VIEW->value);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->canInWorkspace(EmployeePermission::CREATE->value);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CompanyEmployee $companyEmployee): bool
    {
        return $user->canInWorkspace(EmployeePermission::EDIT->value);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CompanyEmployee $companyEmployee): bool
    {
        return $user->canInWorkspace(EmployeePermission::DELETE->value);
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->canInWorkspace(EmployeePermission::DELETE->value);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, CompanyEmployee $companyEmployee): bool
    {
        return $user->canInWorkspace(EmployeePermission::EDIT->value);
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->canInWorkspace(EmployeePermission::EDIT->value);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, CompanyEmployee $companyEmployee): bool
    {
        return $user->canInWorkspace(EmployeePermission::DELETE->value);
    }

    /**
     * Determine whether the user can force delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->canInWorkspace(EmployeePermission::DELETE->value);
    }
}
