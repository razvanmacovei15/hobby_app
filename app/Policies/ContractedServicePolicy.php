<?php

namespace App\Policies;

use App\Enums\Permissions\ContractedServicePermission;
use App\Models\ContractedService;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ContractedServicePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->canInWorkspace(ContractedServicePermission::VIEW->value);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ContractedService $contractedService): bool
    {
        return $user->canInWorkspace(ContractedServicePermission::VIEW->value);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->canInWorkspace(ContractedServicePermission::CREATE->value);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ContractedService $contractedService): bool
    {
        return $user->canInWorkspace(ContractedServicePermission::EDIT->value);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ContractedService $contractedService): bool
    {
        return $user->canInWorkspace(ContractedServicePermission::DELETE->value);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ContractedService $contractedService): bool
    {
        return $user->canInWorkspace(ContractedServicePermission::EDIT->value);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ContractedService $contractedService): bool
    {
        return $user->canInWorkspace(ContractedServicePermission::DELETE->value);
    }
}
