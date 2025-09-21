<?php

namespace App\Services;

use App\Models\Company;
use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceExecutor;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

interface IExecutorService
{
    /**
     * Query builder scoped to the CURRENT Filament tenant (workspace).
     * Return a Builder so Filament can paginate/sort/filter.
     */
    public function queryForCurrentWorkspace(?bool $onlyActive = null): Builder;

    /**
     * Fetch one WorkspaceExecutor for viewing the row you clicked,
     * scoped to the current tenant and eager-loaded.
     */
    public function findForView(int $workspaceExecutorId): WorkspaceExecutor;

    public function mutateFormDataBeforeSave(array $data, ?Company $currentExecutor = null): array;

    /**
     * Assign an engineer (user) to a workspace executor.
     */
    public function assignEngineer(WorkspaceExecutor $workspaceExecutor, User $user): bool;

    /**
     * Remove an engineer (user) from a workspace executor.
     */
    public function unassignEngineer(WorkspaceExecutor $workspaceExecutor, User $user): bool;

    /**
     * Get available workspace users that can be assigned as engineers to an executor.
     */
    public function getAvailableEngineers(WorkspaceExecutor $workspaceExecutor): Collection;

    /**
     * Get users currently assigned as engineers to an executor.
     */
    public function getAssignedEngineers(WorkspaceExecutor $workspaceExecutor): Collection;
}
