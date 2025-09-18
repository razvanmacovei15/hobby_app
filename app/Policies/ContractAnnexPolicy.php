<?php

namespace App\Policies;

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
        return $user->canInWorkspace('contract-annexes.view');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ContractAnnex $contractAnnex): bool
    {
        return $user->canInWorkspace('contract-annexes.view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->canInWorkspace('contract-annexes.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ContractAnnex $contractAnnex): bool
    {
        return $user->canInWorkspace('contract-annexes.edit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ContractAnnex $contractAnnex): bool
    {
        return $user->canInWorkspace('contract-annexes.delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ContractAnnex $contractAnnex): bool
    {
        return $user->canInWorkspace('contract-annexes.edit');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ContractAnnex $contractAnnex): bool
    {
        return $user->canInWorkspace('contract-annexes.delete');
    }
}