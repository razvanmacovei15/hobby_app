<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WorkReport;
use Illuminate\Auth\Access\Response;

class WorkReportPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        $workspace = $user->currentWorkspace;

        if (!$workspace) {
            return false;
        }

        return $user->hasWorkspacePermission($workspace, 'work-reports.view');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, WorkReport $workReport): bool
    {
        $workspace = $user->getCurrentWorkspaceAttribute();

        if (!$workspace) {
            return false;
        }

        // Check if work report belongs to current workspace
        if ($workReport->workspace_id !== $workspace->id) {
            return false;
        }

        return $user->hasWorkspacePermission($workspace, 'work-reports.view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        $workspace = $user->getCurrentWorkspaceAttribute();

        if (!$workspace) {
            return false;
        }

        return $user->hasWorkspacePermission($workspace, 'work-reports.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, WorkReport $workReport): bool
    {
        $workspace = $user->getCurrentWorkspaceAttribute();

        if (!$workspace) {
            return false;
        }

        // Check if work report belongs to current workspace
        if ($workReport->workspace_id !== $workspace->id) {
            return false;
        }

        // Prevent editing approved work reports
        if ($workReport->status === \App\Enums\WorkReportStatus::APPROVED) {
            return false;
        }

        return $user->hasWorkspacePermission($workspace, 'work-reports.edit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, WorkReport $workReport): bool
    {
        $workspace = $user->getCurrentWorkspaceAttribute();

        if (!$workspace) {
            return false;
        }

        // Check if work report belongs to current workspace
        if ($workReport->workspace_id !== $workspace->id) {
            return false;
        }

        return $user->hasWorkspacePermission($workspace, 'work-reports.delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, WorkReport $workReport): bool
    {
        $workspace = $user->getCurrentWorkspaceAttribute();

        if (!$workspace) {
            return false;
        }

        // Check if work report belongs to current workspace
        if ($workReport->workspace_id !== $workspace->id) {
            return false;
        }

        return $user->hasWorkspacePermission($workspace, 'work-reports.delete');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, WorkReport $workReport): bool
    {
        $workspace = $user->getCurrentWorkspaceAttribute();

        if (!$workspace) {
            return false;
        }

        // Check if work report belongs to current workspace
        if ($workReport->workspace_id !== $workspace->id) {
            return false;
        }

        return $user->hasWorkspacePermission($workspace, 'work-reports.delete');
    }

    /**
     * Determine whether the user can approve the work report.
     */
    public function approve(User $user, WorkReport $workReport): bool
    {
        $workspace = $user->getCurrentWorkspaceAttribute();

        if (!$workspace) {
            return false;
        }

        // Check if work report belongs to current workspace
        if ($workReport->workspace_id !== $workspace->id) {
            return false;
        }

        return $user->hasWorkspacePermission($workspace, 'work-reports.approve');
    }
}
