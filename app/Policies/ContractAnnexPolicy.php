<?php

namespace App\Policies;

use App\Enums\Permissions\ContractAnnexPermission;
use App\Models\ContractAnnex;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ContractAnnexPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->canInWorkspace(ContractAnnexPermission::VIEW->value);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ContractAnnex $contractAnnex): bool
    {
        return $user->canInWorkspace(ContractAnnexPermission::VIEW->value);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->canInWorkspace(ContractAnnexPermission::CREATE->value);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ContractAnnex $contractAnnex): bool
    {
        return $user->canInWorkspace(ContractAnnexPermission::EDIT->value);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ContractAnnex $contractAnnex): bool
    {
        return $user->canInWorkspace(ContractAnnexPermission::DELETE->value);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ContractAnnex $contractAnnex): bool
    {
        return $user->canInWorkspace(ContractAnnexPermission::EDIT->value);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ContractAnnex $contractAnnex): bool
    {
        return $user->canInWorkspace(ContractAnnexPermission::DELETE->value);
    }
}