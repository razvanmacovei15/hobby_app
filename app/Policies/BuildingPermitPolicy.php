<?php

namespace App\Policies;

use App\Enums\Permissions\BuildingPermitPermission;
use App\Models\BuildingPermit;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BuildingPermitPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->canInWorkspace(BuildingPermitPermission::VIEW->value);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, BuildingPermit $buildingPermit): bool
    {
        return $user->canInWorkspace(BuildingPermitPermission::VIEW->value);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->canInWorkspace(BuildingPermitPermission::CREATE->value);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, BuildingPermit $buildingPermit): bool
    {
        return $user->canInWorkspace(BuildingPermitPermission::EDIT->value);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, BuildingPermit $buildingPermit): bool
    {
        return $user->canInWorkspace(BuildingPermitPermission::DELETE->value);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, BuildingPermit $buildingPermit): bool
    {
        return $user->canInWorkspace(BuildingPermitPermission::EDIT->value);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, BuildingPermit $buildingPermit): bool
    {
        return $user->canInWorkspace(BuildingPermitPermission::DELETE->value);
    }
}